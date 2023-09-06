<?php

namespace App\Http\Controllers\Seller;

use DB;
use MetaTag;
use DateTime;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Event;
use App\Models\Video;
use App\Models\Document;
use App\Models\EventReps;
use http\Client\Response;
use App\Enums\EventActive;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\ContactBoardStatus;
use App\Classes\Helper\CommonUtil;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\IcalendarGenerator\Components\Calendar;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Events'));
        MetaTag::set('description', config('app.rankup.company_title').' Events Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $categories = Tag::query()->pluck('name', 'id');
        $categories->prepend('All Contents', 0);

        if ($request->ajax()) {
            $searchText = $request->search ?? '';
            $sorting = 'meeting_date_desc';
            $filteredCategory = $request->category_filter ?? Session::get('event.index.category_filter', 0);
            $eventType = $request->type ?? 'current';
        } else {
            $sorting = 'meeting_date_asc';
            $filteredCategory = 0;
            $eventType = 'current';
        }
        $direction = $sorting == 'meeting_date_asc' ? 'asc' : 'desc';
        $now = getCarbonNowForUser();
        $eventQuery = Event::query()->where(function ($query) use ($now, $eventType) {
            if ($eventType == 'past') {
                $query->where(DB::raw("CONCAT(meeting_date,' ',meeting_time)"), '<=', $now);
            } else {
                $query->where(DB::raw("CONCAT(meeting_date,' ',meeting_time)"), '>=', $now);
            }
            $query->orWhereRaw('meeting_date IS NULL');
        })->orderBy('meeting_date', $direction)->orderBy('meeting_time', $direction)
            ->whereNotNull('meeting_date')->whereNotNull('meeting_time');

        if (isset($searchText) && !empty($searchText)) {
            $eventQuery->where(function ($query) use ($searchText) {
                $query->where('name', 'LIKE', '%' . $searchText . '%')
                    ->orWhereHas('tags', function ($q) use ($searchText) {
                        $q->where('name', 'like', '%' . $searchText . '%');
                    })
                    ->orWhereHas('user', function ($q) use ($searchText) {
                        $q->where('name', 'like', '%' . $searchText . '%');
                    });
            });
        }

        if (intval($filteredCategory)) {
            $eventQuery->whereHas('tags', function ($q) use ($filteredCategory) {
                $q->where('tag_id', $filteredCategory);
            });
        }

        $downlineIds = Auth::user()->getDownlineIds();
        $user = Auth::user();
        array_push($downlineIds, $user->id);
        array_push($downlineIds, $user->root_id);
        $downlineIds = array_filter(array_unique(array_merge($downlineIds, User::getUplineArray($user))));
        $eventQuery->where(function ($query) use ($downlineIds) {
            $query->where(function ($query) use ($downlineIds) {
                $query->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')->where('is_active', EventActive::ACTIVE);
            })
            ->orWhere('user_id', Auth::user()->id);
        });

        $events = $eventQuery->paginate(11);

        $videoQuery = Video::orderBy('id', 'asc');
        $isRootMember = Auth::user()->isRootMember();
        $documentQuery = Document::orderBy('id', 'asc');

        if ($isRootMember) {
            $videoQuery->where('user_id', Auth::id());
            $documentQuery->where('user_id', Auth::id());
        } else {
            $videoQuery->where('user_id', Auth::user()->getRootMemberId());
            $documentQuery->where('user_id', Auth::user()->getRootMemberId());
        }

        $videos = $videoQuery->get();
        $documents = $documentQuery->get();

        Session::put('event.index.sort', $sorting);
        Session::put('event.index.category_filter', $filteredCategory);
        $params = compact('events', 'categories', 'sorting', 'filteredCategory', 'videos', 'documents');
        if ($request->ajax()) {
            return view('seller.event._event_pagination', $params);
        }
        return view('seller.event.index', $params);
    }

    /**
     * Display a event detail
     *
     * @param string $slug
     *
     * @return View
     */
    public function eventDetail(string $slug)
    {
        $event = Event::findBySlugOrFail($slug);
        MetaTag::set('title', config('app.rankup.company_title')." - " . $event->name);
        MetaTag::set('description', config('app.rankup.company_title')." - ".$event->content);
        MetaTag::set('image', isset($event->image) ? CommonUtil::getUrl($event->image) : asset(config('app.rankup.company_logo_path')));

        $reps = $event->reps()->where('status', ContactBoardStatus::CONFIRMED_FOR_ZOOM)->get()->toArray();
        $repIds = array_column($reps, 'id');
        $isOwner = $event->user_id === Auth::id();

        $contactConfirmed = $event->contacts()->join('board_contact', 'board_contact.contact_id', '=', 'contacts.id')->select('contacts.*', 'board_contact.status')->where('status', ContactBoardStatus::ATTENDED_THE_ZOOM)->count();

        $eventContacts = $event->contacts()->join('contact_logs', 'contact_logs.contact_id', '=', 'contacts.id')->select('contacts.*', 'contact_logs.status');
        $contactConfirmedUserList = $eventContacts->where('status', ContactBoardStatus::CONFIRMED_FOR_ZOOM)->join('users', 'users.email', '=', 'contacts.email')->get();
        $contactConfirmedUserId = $contactConfirmedUserList->pluck('id')->toArray();

        $contactConfirmedVisitorList = $event->contacts()->join('contact_logs', 'contact_logs.contact_id', '=', 'contacts.id')->select('contacts.*', 'contact_logs.status')->where('status', ContactBoardStatus::CONFIRMED_FOR_ZOOM)->whereNotIn('contacts.id', $contactConfirmedUserId)->distinct()->get();

        return view('seller.event.event-detail', compact('event', 'reps', 'isOwner', 'contactConfirmed', 'contactConfirmedUserList', 'contactConfirmedVisitorList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventRequest $request
     *
     * @return Response
     */
    public function store(EventRequest $request)
    {
        $now = getCarbonNowForUser();
        $todayDate = $now->format('d/m/Y');
        request()->validate(
            array_merge(
                array(
                    'meeting_date' => 'date_format:d/m/Y|after_or_equal:' . $todayDate,
                )
            )
        );

        $data = $request->all();

        $timezone = null;
        if(!empty($data['timezone'])) {
            $timezone = $data['timezone'];
        }

        $data['meeting_date'] = convertDateFormatWithTimezone($request->meeting_date . " " . $request->meeting_time, 'd/m/Y H:i', 'Y-m-d', 'FRONT-TO-CRM', $timezone);
        $data['meeting_time'] = convertDateFormatWithTimezone($request->meeting_date . " " . $request->meeting_time, 'd/m/Y H:i', 'H:i', 'FRONT-TO-CRM', $timezone);
       
        $data['content'] = $request->content_message;
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->name);

        if (!empty($request->hidden_survey_id)) {
            $data['survey_id'] = $request->hidden_survey_id;
        }

        if ($request->hasFile('image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'events');
            $data['image'] = $imageName;
        }

        $event = Event::create($data);
        if($event) {
            if ($request->event_documents) {
                $documents = explode(",", $request->event_documents);
                $event->documents()->sync($documents);
            }
            if ($request->event_videos) {
                $videos = explode(",", $request->event_videos);
                $event->videos()->sync($videos);
            }

            if ($request->tags) {
                $tags = Tag::findOrCreate(array_filter($request->tags));
                $event->tags()->sync($tags);
            }
        }
    
        return response()->json([
            'success' => true,
            'redirect_url' => route('event-detail', $event->slug),
        ], 200);
    }

    /**
     * ICS file download.
     *
     * @param int $id
     * @return Response
     */
    public function icsDownload($id)
    {
        $event = Event::findOrFail($id);
        $from = convertDateFormatWithTimezone($event->meeting_date . " " . $event->meeting_time, 'Y-m-d H:i:s', 'Y-m-d H:i','CRM-TO-FRONT');
        $addHour = date('Y-m-d H:i:s', strtotime($event->meeting_date . ' ' . $event->meeting_time . "+1 hour"));
        $to = convertDateFormatWithTimezone($addHour, 'Y-m-d H:i:s', 'Y-m-d H:i','CRM-TO-FRONT');
        $calendar = Calendar::create('Rankup Calendar')
            ->event(\Spatie\IcalendarGenerator\Components\Event::create($event->name)
                ->startsAt(DateTime::createFromFormat('Y-m-d H:i', $from))
                ->endsAt(DateTime::createFromFormat('Y-m-d H:i', $to))
            )
            ->get();
        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->slug . ".ics",
        ]);
    }

    /**
     * one On One call make link.
     *
     * @return Response
     */
    public function oneOnOneCall()
    {
        $user = Auth::User();
        if (!empty($user->permanent_zoom_link)) {
            $findEvent = Event::where('user_id', Auth::id())->WhereNull(['meeting_date', 'meeting_time'])->first();
            if ($findEvent) {
                $event = $findEvent;
                $id = isset($event) && !empty($event->id) ? $event->id + 1 : 1;
                $name = 'One on one Event ' . $id . rand(100, 999);
            } else {
                $event = Event::orderBy('id', 'desc')->first();
                $id = isset($event) && !empty($event->id) ? $event->id + 1 : 1;
                $name = 'One on one Event ' . $id . rand(100, 999);
                $event = new Event();
                $event->name = $name;
                $event->user_id = Auth::id();
            }
            $event->meeting_url = $user->permanent_zoom_link;
            $event->slug = Str::slug($name);
            $event->save();
            $url = route('frontend.event.details', $event->slug);
            if ($event->user_id != Auth::id()) {
                $url .= '?referral=' . Auth::user()->referral_code;
            }
            return json_encode([
                'success' => true,
                'url' => $url,
            ]);
        } else {
            Session::flash('error', __('Please add permanente zoom link'));
            return json_encode([
                'success' => false,
                'redirect_url' => route('seller.setting.account'),
            ]);
        }
    }

    /**
     * Confirm presence.
     *
     * @param Request $request
     * @param Event $event
     *
     * @return Response
     */
    public function confirmPresence(Request $request, Event $event)
    {
        if (checkIfEventIsPastCurrentTime($event)) {
            if (!$event->reps()->where(['status'=> ContactBoardStatus::ATTENDED_THE_ZOOM, 'member_id' => Auth::id()])->first()) {
                EventReps::create([
                    'status' => ContactBoardStatus::ATTENDED_THE_ZOOM,
                    'member_id' => Auth::id(),
                    'event_id' => $event->id,
                ]);
            }
            return redirect()->to($event->meeting_url);
        } else {
            if (!$event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first()) {
                EventReps::create([
                    'status' => ContactBoardStatus::CONFIRMED_FOR_ZOOM,
                    'member_id' => Auth::id(),
                    'event_id' => $event->id,
                ]);
            }

            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    /**
     * Event status change
     *
     * @param Request $request
     *
     * @return void
     */
    public function statusChanges(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $event->is_active = $request->active;
        $event->save();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        if (isset($event->meeting_date) && isset($event->meeting_time)) {
            $time = convertDateFormatWithTimezone($event->meeting_date . " " . $event->meeting_time, 'Y-m-d H:i:s', 'H:i','CRM-TO-FRONT');
            $date = convertDateFormatWithTimezone($event->meeting_date . " " . $event->meeting_time, 'Y-m-d H:i:s', 'd/m/Y','CRM-TO-FRONT');
        }
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $event->name,
                'meeting_date' => $date ?? '',
                'meeting_time' => $time ?? '',
                'content' => $event->content,
                'image' => $event->image ?? '',
                'meeting_url' => $event->meeting_url,
                'presenter' => $event->presentator_id,
                'presentator_name' => !empty($event->presentator) ? $event->presentator->name : ''
            ],
        ], 200);
    }

    /**
     * Update event
     *
     * @param EventRequest $request
     * @param int $id
     *
     * @return Response
     */
    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $now = getCarbonNowForUser();
        $todayDate = $now->format('d/m/Y');
        request()->validate(
            array_merge(
                array(
                    'meeting_date' => 'date_format:d/m/Y|after_or_equal:' . $todayDate,
                )
            )
        );

        $data = $request->all();
        $data['meeting_date'] = convertDateFormatWithTimezone($request->meeting_date . " " . $request->meeting_time, 'd/m/Y H:i', 'Y-m-d', 'FRONT-TO-CRM');
        $data['meeting_time'] = convertDateFormatWithTimezone($request->meeting_date . " " . $request->meeting_time, 'd/m/Y H:i', 'H:i', 'FRONT-TO-CRM');
        $data['content'] = $request->content_message;
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->name);

        if (!empty($request->hidden_survey_id)) {
            $data['survey_id'] = $request->hidden_survey_id;
        }

        if ($request->hasFile('image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'events');
            if (!empty($event->image)) {
                CommonUtil::removeFile($event->image);
            }
            $data['image'] = $imageName;
        }
        $event->update($data);

        if ($request->event_documents) {
            $documents = explode(",", $request->event_documents);
            $event->documents()->syncWithoutDetaching($documents);
        }
        if ($request->event_videos) {
            $videos = explode(",", $request->event_videos);
            $event->videos()->syncWithoutDetaching($videos);
        }

        if ($request->tags) {
            $tags = Tag::findOrCreate(array_filter($request->tags));
            $event->tags()->syncWithoutDetaching($tags);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('event-detail', $event->slug),
        ], 200);
    }

    /**
     * Delete event
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->contacts()->delete();
        $event->tags()->detach();
        $event->reps()->detach();
        $event->videos()->detach();
        $event->documents()->detach();
        if(!empty($event->image)) {
            CommonUtil::removeFile($event->image);
        }
        $event->delete();
        return response()->json([
            'data' => 'event',
        ], 200);
    }
}