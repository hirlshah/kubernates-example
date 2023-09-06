<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\Helper\CommonUtil;
use App\Enums\ContactBoardStatus;
use App\Enums\EventActive;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\ConfirmingPresenceEmail;
use App\Models\Board;
use App\Models\BoardContact;
use App\Models\Contact;
use App\Models\ContactEvents;
use App\Models\ContactLog;
use App\Models\Document;
use App\Models\Event;
use App\Models\EventReps;
use App\Models\User;
use App\Models\Video;
use Cookie;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use MetaTag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Events'));
        MetaTag::set('description', config('app.rankup.company_title').' Events Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $events = Event::orderBy('meeting_date', 'DESC')->where('is_active', EventActive::ACTIVE)->get();
        return view('frontend.event.index', compact('events'));
    }

    /**
     * Display a Event Details of page.
     *
     * @param Request $request
     * @param string $slug
     *
     * @return View
     */
    public function eventDetails(Request $request, string $slug)
    {
        $event = Event::findBySlugOrFail($slug);
        if (!$event) {
            return redirect()->to('http://www.rankup.io');
        }

        if (isset($request->referral)) {
            $referralCode = $request->referral;
        } else {
            $referralCode = $event->user->referral_code;
        }

        $cookieCustomCheck = false;

        if ($request->confirmed && $request->contact && !empty($request->contact)) {
            $contactId = Crypt::decryptString($request->contact);
            Cookie::queue('referralcode_' . $referralCode, true);
            Cookie::queue('event_' . $event->id, $event->id);
            Cookie::queue('contact_id', $contactId);
            $cookieCustomCheck = true;
        }

        MetaTag::set('title', $event->name);
        MetaTag::set('description', config('app.rankup.company_title')." - " . $event->content);
        MetaTag::set('image', isset($event->image) ? CommonUtil::getUrl($event->image) : asset(config('app.rankup.company_logo_path')));

        $similarEvents = Event::where('slug', '!=', $slug)->orderBy('meeting_date', 'DESC')->where('is_active', EventActive::ACTIVE)->limit(3)->get();
        $lang = app()->getLocale(); 

        return view('frontend.event.event-details', compact('event', 'similarEvents', 'referralCode', 'cookieCustomCheck','lang'));
    }

    /**
     * Download document
     *
     * @param Document $document
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function downloadDocument(Document $document)
    {
        if (!$document->document) {
            return Redirect::back()->with('success', __('Invalid Document Name'));
        }
        return Storage::disk('public')->download($document->document);
    }

    /**
     * Download Video
     *
     * @param Video $video
     *
     * @return RedirectResponse|StreamedResponse
     */
    public function downloadVideo(Video $video)
    {
        if (!$video->video) {
            return Redirect::back()->with('success', __('Invalid Video Name'));
        }
        return Storage::disk('public')->download($video->video);
    }

    /**
     * Store Contacts
     *
     * @param Request $request
     *
     * @return false|string
     *
     * @throws ValidationException
     */
    public function storeContacts(Request $request)
    {
        if (is_null($request->referral_id)) {
            $user_id = Event::find($request->event_id)->user_id;
        } else {
            $user = User::where('referral_code', $request->referral_id)->first();
            $user_id = $user ? $user->id : Event::find($request->event_id)->user_id;
        }

        $message = array(
            'first_name.required' => __('First Name is required'),
            'last_name.required' => __('Last Name is required'),
            'email.required' => __('Email is required'),
            'phone.required' => __('Phone number is required'),
        );

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ], $message);

        $name = $request->first_name . ' ' . $request->last_name;

        $user = User::find($user_id);
        $event = Event::find($request->event_id);

        if (!$event) {
            return redirect()->to('http://www.rankup.io');
        }

        if ($request->status == 5) {
            $statusArray = [1, 2, 3, 4];
        } else {
            $statusArray = [1, 2, 3];
        }

        $isMember = User::query()->where(['email' => $request->email])->first();
        if ($isMember) {
            if (!$event->reps()->where(['status' => $request->board_status, 'member_id' => $isMember->id])->first()) {
                EventReps::create([
                    'status' => $request->board_status,
                    'member_id' => $isMember->id,
                    'event_id' => $event->id,
                ]);
                Cookie::queue('referralcode_' . $user->referral_code, true);
                Cookie::queue('event_' . $request->event_id, $request->event_id);
                Cookie::queue('contact_id', $isMember->id);

                $this->sendMeetingPresenceConfirmationEmail(['name' => $name, 'email' => $request->email, 'contact_id' => $isMember->id], $event);
            }
            return json_encode(['success' => true, 'message' => 'Data stored successfully.']);
        }

        $find = Contact::where([
            'event_id' => $request->event_id,
            'user_id' => $user_id,
            'email' => $request->email])
            ->first();

        if ($find) {
            BoardContact::where(['board_id' => $user->board->id, 'contact_id' => $find->id])->update(['status' => $request->board_status]);
            if (is_null($event->meeting_date) && is_null($event->meeting_time)) {
                return json_encode(['success' => true, 'url' => $event->meeting_url]);
            }
            $findContactEventData = ContactEvents::where(['contact_id' => $find->id, 'event_id' => $request->event_id])->first();
            if (!$findContactEventData) {
                ContactEvents::create(['contact_id' => $find->id, 'event_id' => $request->event_id]);
            }
            ContactLog::createLog($find->id, $request->board_status, $event->user_id);
            foreach ($statusArray as $SA) {
                ContactLog::createLog($find->id, $SA, $event->user_id);
            }
            Cookie::queue('referralcode_' . $user->referral_code, true);
            Cookie::queue('event_' . $request->event_id, $request->event_id);
            Cookie::queue('contact_id', $find->id);
            return json_encode(['success' => true, 'message' => 'Data stored successfully.']);
        }

        $findContactEvent = Contact::where(['user_id' => $user_id, 'email' => $request->email])->first();
        if ($findContactEvent) {
            $findContactEventData = ContactEvents::where(['contact_id' => $findContactEvent->id, 'event_id' => $request->event_id])->first();
            if (!$findContactEventData) {
                ContactEvents::create(['contact_id' => $findContactEvent->id, 'event_id' => $request->event_id]);
            }
            ContactLog::createLog($findContactEvent->id, $request->board_status, $findContactEvent->user_id);
            foreach ($statusArray as $SA) {
                ContactLog::createLog($findContactEvent->id, $SA, $findContactEvent->user_id);
            }

            $this->sendMeetingPresenceConfirmationEmail(['name' => $name, 'email' => $request->email, 'contact_id' => $findContactEvent->id], $event);

            if (is_null($event->meeting_date) && is_null($event->meeting_time)) {
                return json_encode(['success' => true, 'url' => $event->meeting_url]);
            }
            Cookie::queue('referralcode_' . $user->referral_code, true);
            Cookie::queue('event_' . $request->event_id, $request->event_id);
            Cookie::queue('contact_id', $findContactEvent->id);
            return json_encode(['success' => true, 'message' => 'Data stored successfully.']);
        }

        $data = [];
        $data = $request->except(['_token', 'referral_id', 'first_name', 'last_name']);
        $data['name'] = $request->first_name . ' ' . $request->last_name;
        $data['user_id'] = $user_id;

        $create = Contact::create($data);
        ContactEvents::create(['contact_id' => $create->id, 'event_id' => $request->event_id]);
        ContactLog::createLog($create->id, $request->board_status, $create->user_id);
        foreach ($statusArray as $SA) {
            ContactLog::createLog($create->id, $SA, $create->user_id);
        }

        if ($create) {
            if (!$user->board) {
                $board = Board::create([
                    'user_id' => $user_id,
                    'is_current' => 1,
                ]);
                $board_id = $board->id;
            } else {
                $board_id = $user->board->id;
            }

            BoardContact::updateOrCreate([
                'board_id' => $board_id,
                'contact_id' => $create->id,
            ], [
                'status' => $request->board_status,
            ]);
            ContactLog::createLog($create->id, $request->board_status, $event->user_id);
            foreach ($statusArray as $SA) {
                ContactLog::createLog($create->id, $SA, $event->user_id);
            }

            $this->sendMeetingPresenceConfirmationEmail(['name' => $name, 'email' => $request->email, 'contact_id' => $create->id], $event);

            if (is_null($event->meeting_date) && is_null($event->meeting_time)) {
                return json_encode(['success' => true, 'url' => $event->meeting_url]);
            }
            Cookie::queue('referralcode_' . $user->referral_code, true);
            Cookie::queue('event_' . $request->event_id, $request->event_id);
            Cookie::queue('contact_id', $create->id);
            return json_encode(['success' => true, 'message' => __('Data stored successfully.')]);
        } else {
            return json_encode(['success' => false, 'message' => __('There\'s some problem.')]);
        }
    }

    /**
     * Get Event Date
     *
     * @param Request $request
     * @param int $id
     *
     * @return string
     *
     * @throws ValidationException
     */
    public function getEventDate(Request $request, $id)
    {
        if (!empty($request->timezoneOffset)) {
            $_SESSION['timezone_offset'] = $request->timezoneOffset;
        }
        if (!empty($request->timezoneName)) {
            $_SESSION['timezone_name'] = $request->timezoneName;
        }
        $event = Event::findOrFail($id);
        $date = convertDateFormatWithTimezone($event->meeting_date . ' ' . $event->meeting_time, 'Y-m-d H:i:s', 'Y-m-d H:i:s','CRM-TO-FRONT');
        if ($event) {
            return json_encode(['success' => true, 'date' => $date]);
        } else {
            return json_encode(['success' => false, 'message' => __('There\'s some problem in fetching event date-time.')]);
        }
    }

    /*
     * update status for auth user for the event
     * If event is not started then user will be added as Present for Zoom and if event is started then user will be added as attanded the zoom
     */
    public function registerAuthUser($id)
    {
        $event = Event::find($id);

        if (Auth::user()) {
            if (checkIfEventIsPastCurrentTime($event)) {
                if (!$event->reps()->where(['status' => ContactBoardStatus::ATTENDED_THE_ZOOM, 'member_id' => Auth::id()])->first()) {
                    EventReps::create([
                        'status' => ContactBoardStatus::ATTENDED_THE_ZOOM,
                        'member_id' => Auth::id(),
                        'event_id' => $event->id,
                    ]);
                }
                return redirect()->to($event->meeting_url);
            } else {
                if (!$event->reps()->where(['status' => ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first()) {
                    EventReps::create([
                        'status' => ContactBoardStatus::CONFIRMED_FOR_ZOOM,
                        'member_id' => Auth::id(),
                        'event_id' => $event->id,
                    ]);
                }
                return redirect()->back();
            }
            return redirect()->back();
        }
    }

    /*
     * Send meeting presence confirmation email.
     */
    private function sendMeetingPresenceConfirmationEmail($data, $event)
    {
        try {
            $email = new ConfirmingPresenceEmail($event, $data['email'], $data['name'], Crypt::encryptString($data['contact_id']));
            dispatch(new SendEmailJob($email));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        return true;
    }
}