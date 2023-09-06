<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Enums\ContactBoardStatus;
use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BoardContact;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Event;
use App\Models\EventReps;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use MetaTag;

class AnalyticController extends Controller
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
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Analytics'));
        MetaTag::set('description', config('app.rankup.company_title').' Analytics Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('seller.analytic.index');
    }

    /**
     * Show the columns data Ajax.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function columnsData(Request $request)
    {
        $downlineIds = User::getDownlineIds();
        $downlineIds = getDownlinesStr(implode(',', $downlineIds));
        if (isset($request->eventID)) {
            $eventID = $request->eventID;
        } else {
            $eventID = Event::whereRaw('user_id IN ('.$downlineIds.')')->pluck('id')->toArray();
        }
        $status = [ContactBoardStatus::ATTENDED_THE_ZOOM, ContactBoardStatus::NEW_DISTRIBUTOR, ContactBoardStatus::NEW_CLIENT, ContactBoardStatus::FOLLOWUP, ContactBoardStatus::NOT_INTERESTED];

        $distributorId = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('users', 'users.email', '=', 'contacts.email')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->where('contact_logs.status', ContactBoardStatus::ATTENDED_THE_ZOOM)
            ->pluck('contacts.id')->toArray();

        $contacts = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->when(count($distributorId) > 0, function ($q) use ($distributorId) {
                return $q->whereNotIn('contacts.id', $distributorId);
            })
            ->whereIn('contact_logs.status', $status)
            ->select('contacts.event_id as event_id', DB::raw('MAX(contact_logs.created_at) as created_at'))
            ->groupBy('contacts.event_id')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
            
        $board_contacts = [];
        foreach ($contacts as $board_contact) {
            $statusData = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_events.event_id', $board_contact->event_id)
                ->when(count($distributorId) > 0, function ($q) use ($distributorId) {
                    return $q->whereNotIn('contacts.id', $distributorId);
                })
                ->whereIn('contact_logs.status', $status)
                ->select('contact_logs.status as status', DB::raw('count(contact_logs.status) as count'))
                ->groupBy('contact_logs.status')
                ->get();

            $confirm = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_events.event_id', $board_contact->event_id)
                ->where('contact_logs.status', ContactBoardStatus::CONFIRMED_FOR_ZOOM)
                ->select('contact_logs.status as status', DB::raw('count(contact_logs.status) as count'))
                ->groupBy('contact_logs.status')
                ->first();

            $totalGuestCount = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_events.event_id', $board_contact->event_id)
                ->where('contact_logs.status', '!=',ContactBoardStatus::NEW_CLIENT)
                ->select(DB::raw('COUNT(DISTINCT contact_logs.contact_id) as total'))
                ->first()
                ->total;

            $totalDistributorCount = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_events.event_id', $board_contact->event_id)
                ->where('contact_logs.status', ContactBoardStatus::NEW_DISTRIBUTOR)
                ->select(DB::raw('COUNT(DISTINCT contact_logs.contact_id) as total'))
                ->first()
                ->total;

            $board_contacts[] = array(
                'event_id' => $board_contact->event_id,
                'event_name' => Event::find($board_contact->event_id)->name ?? 'Event',
                'status' => $statusData,
                'confirm' => $confirm->count ?? 0,
                'total_guests' => $totalGuestCount ?? 0,
                'total_distributor' => $totalDistributorCount ?? 0
            );
        }

        if ($request->personalStatFlag == 'true') {
            $title = __('Personal team stats');
        } else {
            $title = __('Total team stats');
        }

        return view('seller.analytic.contact', compact('board_contacts', 'title', 'contacts'));
    }

    /**
     * Get column contact data
     *
     * @param Request $request
     *
     * @return Response
     */
    public function columnContactsData(Request $request)
    {
        $user = Auth::user();
        $downlineIds = User::getDownlineIds();
        $downlineIds = getDownlinesStr(implode(',', $downlineIds));
        $downlines = User::whereRaw('id IN ('.$downlineIds.')')->get();
        $boardID = Board::whereRaw('user_id IN ('.$downlineIds.')')->pluck('id')->toArray();

        if (isset($request->eventID)) {
            $eventID = $request->eventID;
        } else {
            $eventID = [];
        }
        $userIds = Event::whereIn('id', $eventID)->pluck('user_id')->toArray();

        $status = [ContactBoardStatus::ATTENDED_THE_ZOOM, ContactBoardStatus::NEW_DISTRIBUTOR, ContactBoardStatus::NEW_CLIENT, ContactBoardStatus::FOLLOWUP, ContactBoardStatus::NOT_INTERESTED];

        $distributorId = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('users', 'users.email', '=', 'contacts.email')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->where('contact_logs.status', ContactBoardStatus::ATTENDED_THE_ZOOM)
            ->pluck('contacts.id')->toArray();

        $contacts = ContactLog::selectRaw('contact_logs.user_id, contact_logs.contact_id,
                MAX(contact_logs.status) as status, ANY_VALUE(contacts.id), MAX(contact_logs.created_at) as latest_created_at')
            ->join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->when(count($distributorId) > 0, function ($q) use ($distributorId) {
                return $q->whereNotIn('contacts.id', $distributorId);
            })
            ->whereIn('contact_logs.status', $status)
            ->groupBy('contact_logs.user_id', 'contact_logs.contact_id')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        $board_contacts = [];
        foreach ($contacts as $board_contact) {
            $board_contacts[$board_contact->status][] = $board_contact;
        }
        $exclude = [1, 2, 3, 4];
        $statusRange = ContactBoardStatus::asSelectArray();
        $statusRange = array_diff_key($statusRange, array_flip($exclude));

        $notPresent = ContactLog::selectRaw('contact_logs.user_id,contact_logs.contact_id, MAX(contact_logs.status) as status,ANY_VALUE(contact_logs.created_at) as log_created_time,ANY_VALUE(contacts.id)')
            ->join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->groupBy('contact_logs.user_id', 'contact_logs.contact_id')
            ->having('status', '=', ContactBoardStatus::CONFIRMED_FOR_ZOOM)
            ->get();

        array_push($statusRange, __('Not Present'));
        foreach ($notPresent as $board_contact) {
            $board_contacts[array_key_last($statusRange)][] = $board_contact;
        }

        $distributorUserContacts = ContactLog::selectRaw('contact_logs.user_id,contact_logs.contact_id, MAX(contact_logs.status) as status,ANY_VALUE(contact_logs.created_at) as log_created_time,ANY_VALUE(contacts.id)')
            ->join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->join('contact_events', 'contact_events.contact_id', '=', 'contact_logs.contact_id')
            ->join('users', 'users.email', '=', 'contacts.email')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('contact_events.event_id', $eventID);
            })
            ->where('contact_logs.status', ContactBoardStatus::ATTENDED_THE_ZOOM)
            ->groupBy('contact_logs.user_id', 'contact_logs.contact_id')
            ->get();

        $distributorEventUsers = EventReps::
            when(count($eventID) > 0, function ($q) use ($eventID) {
            return $q->whereIn('event_id', $eventID);
        })->where('status', ContactBoardStatus::ATTENDED_THE_ZOOM)->get();

        array_push($statusRange, __('Distributors present'));
        foreach ($distributorUserContacts as $board_contact) {
            $board_contacts[array_key_last($statusRange)][] = $board_contact;
        }
        return view('seller.analytic.contactData', compact('contacts', 'board_contacts', 'statusRange', 'downlines', 'distributorEventUsers'));
    }

    /**
     * Update statistics flag for user
     *
     * @return Response
     */
    public function updateStatisticsFlag()
    {
        $user = Auth::user();
        $user->statistic_flag = 1;
        $user->save();
        return response()->json(['success' => true]);
    }

    /**
     * Show the analytics data
     *
     * @param Request $request
     * @return Response
     */
    public function analyticsData(Request $request)
    {
        $switchType = 'month';

        if (isset($request->dateFilterType)) {
            if ($request->dateFilterType == 'Week') {
                $switchType = 'week';
            }
            if ($request->dateFilterType == 'Month') {
                $switchType = 'month';
            }
            if ($request->dateFilterType == 'Day') {
                $switchType = 'day';
            }
            if ($request->dateFilterType == 'customRange') {
                $switchType = 'customRange';
            }
        }
        $user = Auth::user();

        if ($request->personalStatFlag === 'true') {
            $downlineIds = [$user->id];
        } else {
            $downlineIds = User::getDownlineIds();
            array_push($downlineIds, $user->id);
        }

        $downlineIds = getDownlinesStr(implode(',', $downlineIds));
        $eventID = Event::whereRaw('user_id IN ('.$downlineIds.')')->pluck('id')->toArray();
        $status = $request->status;

        if ($switchType == 'month') {
            $data = $this->monthAnalytics($downlineIds, $status, $eventID);
        } else if ($switchType == 'week') {
            $data = $this->weekAnalytics($downlineIds, $status, $eventID);
        } else if ($switchType == 'day') {
            $data = $this->dayAnalytics($downlineIds, $status, $eventID);
        } else if ($switchType == 'customRange') {
            $data = $this->customRangeAnalytics($downlineIds, $status, $eventID, $request->start, $request->end);
        }
        return $data;
    }

    /**
     * Month analytics.
     *
     * @param array $downlineIds
     * @param int $status
     * @param int $eventID
     * @return Response
     */
    public function monthAnalytics($downlineIds, $status, $eventID)
    {
        $now = Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'));
        $currentMonth = getDatesFromRange($now->copy()->firstOfMonth()->translatedFormat('Y-m-d'), $now->copy()->translatedFormat('Y-m-d'));
        $previousMonth = getDatesFromRange($now->copy()->subMonth()->firstOfMonth()->translatedFormat('Y-m-d'), $now->copy()->subMonth()->translatedFormat('Y-m-d'));

        for ($i = 1; $i <= count($currentMonth); $i++) {
            $label[] = $i;
        }

        $data = ['new_count' => 0, 'old_count' => 0];
        
        foreach ($currentMonth as $k => $v) {
            $new2 = $old2 = 0;
            $newDateRange = getStartEndDate($v);
            if (isset($previousMonth[$k])) {
                $oldDateRange = getStartEndDate($previousMonth[$k]);
                $old = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                    ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                    ->where('contact_logs.status', $status)
                    ->where('contact_logs.created_at', '>=', $oldDateRange['start'])
                    ->where('contact_logs.created_at', '<', $oldDateRange['end'])
                    ->selectRaw('count(*) as count')
                    ->first()->toArray();
                if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                    $old2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                        return $q->whereIn('event_id', $eventID);
                    })
                        ->where('status', $status)
                        ->where('created_at', '>=', $oldDateRange['start'])
                        ->where('created_at', '<', $oldDateRange['end'])
                        ->selectRaw('count(*) as count')
                        ->first()->count;
                }
                $temp['old'][$previousMonth[$k]] = $old['count'] + $old2;

            } else {
                $temp['old'][$k] = 0;
            }

            $new = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_logs.status', $status)
                ->where('contact_logs.created_at', '>=', $newDateRange['start'])
                ->where('contact_logs.created_at', '<', $newDateRange['end'])
                ->selectRaw('count(*) as count')
                ->first()->toArray();
            if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                $new2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                    return $q->whereIn('event_id', $eventID);
                })
                    ->where('status', $status)
                    ->where('created_at', '>=', $newDateRange['start'])
                    ->where('created_at', '<', $newDateRange['end'])
                    ->selectRaw('count(*) as count')
                    ->first()->count;
            }
            $temp['new'][$v] = $new['count'] + $new2;
        }
        $data['status' . $status]['new']['title'] = array_keys($temp['new']);
        $data['status' . $status]['new']['count'] = array_values($temp['new']);
        $data['status' . $status]['old']['title'] = array_keys($temp['old']);
        $data['status' . $status]['old']['count'] = array_values($temp['old']);
        $data['label'] = $label;

        $data['status' . $status]['new_count'] = array_sum($data['status' . $status]['new']['count']);
        $data['status' . $status]['old_count'] = array_sum($data['status' . $status]['old']['count']);

        if ($data['status' . $status]['new_count'] > $data['status' . $status]['old_count']) {
            $data['status' . $status]['status'] = 'more';
        } else {
            $data['status' . $status]['status'] = 'less';
        }
        if ($data['status' . $status]['old_count'] > 0 && $data['status' . $status]['new_count'] > 0) {
            $data['status' . $status]['percentage'] = round(($data['status' . $status]['new_count'] - $data['status' . $status]['old_count']) * 100 / $data['status' . $status]['old_count'], 0);
        } else if ($data['status' . $status]['old_count'] == 0) {
            $data['status' . $status]['percentage'] = $data['status' . $status]['new_count'] * 100;
        } else if ($data['status' . $status]['new_count'] == 0) {
            $data['status' . $status]['percentage'] = '-' . $data['status' . $status]['old_count'] * 100;
        }

        return $data;
    }

    /**
     * Week analytics.
     *
     * @param array $downlineIds
     * @param int $status
     * @param int $eventID
     * @return Response
     */
    public function weekAnalytics($downlineIds, $status, $eventID)
    {
        $userTodayEndDate = Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'));

        $currentWeek = getDatesFromRangeForWeek($userTodayEndDate->copy()->translatedFormat('Y-m-d'), $userTodayEndDate->copy()->subDays(6)->translatedFormat('Y-m-d'));
        $previousWeek = getDatesFromRangeForWeek($userTodayEndDate->copy()->subDays(7)->translatedFormat('Y-m-d'), $userTodayEndDate->copy()->subDays(13)->translatedFormat('Y-m-d'));
        $label = ['Monday', 'Tuesday', 'WednesDay', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $data = ['new_count' => 0, 'old_count' => 0];
        
        foreach ($currentWeek as $k => $v) {
            $new2 = $old2 = 0;
            $newDateRange = getStartEndDate($v);
            if (isset($previousMonth[$k])) {
                $oldDateRange = getStartEndDate($previousWeek[$k]);
                $old = ContactLog::selectRaw('count(*) as count')
                    ->join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                    ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                    ->where('contact_logs.status', $status)
                    ->where('contact_logs.created_at', '>=', $oldDateRange['start'])
                    ->where('contact_logs.created_at', '<', $oldDateRange['end'])
                    ->first()->toArray();

                if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                    $old2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                            return $q->whereIn('event_id', $eventID);
                        })
                        ->where('status', $status)
                        ->where('created_at', '>=', $oldDateRange['start'])
                        ->where('created_at', '<', $oldDateRange['end'])
                        ->selectRaw('count(*) as count')
                        ->first()->count;
                }
                $temp['old'][$previousWeek[$k]] = $old['count'] + $old2;
            } else {
                $temp['old'][$k] = 0;
            }
            
            $new = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_logs.status', $status)
                ->where('contact_logs.created_at', '>=', $newDateRange['start'])
                ->where('contact_logs.created_at', '<', $newDateRange['end'])
                ->selectRaw('count(*) as count')
                ->first()->toArray();

            if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                $new2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                        return $q->whereIn('event_id', $eventID);
                    })
                    ->where('status', $status)
                    ->where('created_at', '>=', $newDateRange['start'])
                    ->where('created_at', '<', $newDateRange['end'])
                    ->selectRaw('count(*) as count')
                    ->first()->count;
            }

            $temp['new'][$v] = $new['count'] + $new2;
            
        }
        $data['status' . $status]['new']['title'] = array_keys($temp['new']);
        $data['status' . $status]['new']['count'] = array_values($temp['new']);
        $data['status' . $status]['old']['title'] = array_keys($temp['old']);
        $data['status' . $status]['old']['count'] = array_values($temp['old']);
        $data['label'] = $label;

        $data['status' . $status]['new_count'] = array_sum($data['status' . $status]['new']['count']);
        $data['status' . $status]['old_count'] = array_sum($data['status' . $status]['old']['count']);

        if ($data['status' . $status]['new_count'] > $data['status' . $status]['old_count']) {
            $data['status' . $status]['status'] = 'more';
        } else {
            $data['status' . $status]['status'] = 'less';
        }
        if ($data['status' . $status]['old_count'] > 0 && $data['status' . $status]['new_count'] > 0) {
            $data['status' . $status]['percentage'] = round(($data['status' . $status]['new_count'] - $data['status' . $status]['old_count']) * 100 / $data['status' . $status]['old_count'], 0);
        } else if ($data['status' . $status]['old_count'] == 0) {
            $data['status' . $status]['percentage'] = $data['status' . $status]['new_count'] * 100;
        } else if ($data['status' . $status]['new_count'] == 0) {
            $data['status' . $status]['percentage'] = '-' . $data['status' . $status]['old_count'] * 100;
        }

        return $data;
    }

    /**
     * Day analytics.
     *
     * @param array $downlineIds
     * @param int $status
     * @param int $eventID
     * @return Response
     */
    public function dayAnalytics($downlineIds, $status, $eventID)
    {
        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');
        $todayEndDateForFilter = $todayDate->clone()->format('Y-m-d H:i:s');
        $previousDayStartDateForFilter = $todayDate->clone()->subDays(2)->format('Y-m-d H:i:s');

        $label = ['Today', 'Yesterday'];

        $data = ['new_count' => 0, 'old_count' => 0];
        $old2 = $new2 = 0;
        $old = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->where('contact_logs.status', $status)
            ->where('contact_logs.created_at', '>=', $previousDayStartDateForFilter)
            ->where('contact_logs.created_at', '<', $todayStartDateForFilter)
            ->selectRaw('count(*) as count')
            ->first()->toArray();

        $new = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
            ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
            ->where('contact_logs.status', $status)
            ->where('contact_logs.created_at', '>=', $todayStartDateForFilter)
            ->where('contact_logs.created_at', '<', $todayEndDateForFilter)
            ->selectRaw('count(*) as count')
            ->first()->toArray();

        if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
            $new2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('event_id', $eventID);
            })
                ->where('status', $status)
                ->where('created_at', '>=', $todayStartDateForFilter)
                ->where('created_at', '<', $todayEndDateForFilter)
                ->selectRaw('count(*) as count')
                ->first()->count;

            $old2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereIn('event_id', $eventID);
            })
                ->where('status', $status)
                ->where('created_at', '>=', $previousDayStartDateForFilter)
                ->where('created_at', '<', $todayStartDateForFilter)
                ->selectRaw('count(*) as count')
                ->first()->count;
        }

        $temp['new'][Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'))->translatedFormat('Y-m-d')] = $new['count'] + $new2;
        $temp['old'][Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'))->subDays(1)->translatedFormat('Y-m-d')] = $old['count'] + $old2;

        $data['status' . $status]['new']['title'] = array_keys($temp['new']);
        $data['status' . $status]['new']['count'] = array_values($temp['new']);
        $data['status' . $status]['old']['title'] = array_keys($temp['old']);
        $data['status' . $status]['old']['count'] = array_values($temp['old']);
        $data['status' . $status]['new_count'] = $new['count'] + $new2;
        $data['status' . $status]['old_count'] = $old['count'] + $old2;
        $data['label'] = $label;
        if ($data['status' . $status]['new_count'] > $data['status' . $status]['old_count']) {
            $data['status' . $status]['status'] = 'more';
        } else {
            $data['status' . $status]['status'] = 'less';
        }
        if ($data['status' . $status]['old_count'] > 0 && $data['status' . $status]['new_count'] > 0) {
            $data['status' . $status]['percentage'] = round(($data['status' . $status]['new_count'] - $data['status' . $status]['old_count']) * 100 / $data['status' . $status]['old_count'], 0);
        } else if ($data['status' . $status]['old_count'] == 0) {
            $data['status' . $status]['percentage'] = $data['status' . $status]['new_count'] * 100;
        } else if ($data['status' . $status]['new_count'] == 0) {
            $data['status' . $status]['percentage'] = '-' . $data['status' . $status]['old_count'] * 100;
        }
        return $data;
    }

    /**
     * Custom range analytics.
     *
     * @param array $downlineIds
     * @param int $status
     * @param int $eventID
     * @param date $start
     * @param date $end
     * @return Response
     */
    public function customRangeAnalytics($downlineIds, $status, $eventID, $start, $end)
    {
        $start = Carbon::parse(convertDateFormatWithTimezone($start, 'Y-m-d', 'Y-m-d', 'FRONT-TO-CRM'));
        $end = Carbon::parse(convertDateFormatWithTimezone($end, 'Y-m-d', 'Y-m-d', 'FRONT-TO-CRM'));

        $currentRange = getDatesFromRange($start->copy(), $end->copy());
        $diff = Carbon::parse($start)->diffInDays(Carbon::parse($end)->addHours(24), false);
        $previousRange = getDatesFromRange($start->copy()->subDays($diff), $start->copy()->subDays(1));

        for ($i = 1; $i <= count($currentRange); $i++) {
            $label[] = $i;
        }

        $data = ['new_count' => 0, 'old_count' => 0];
        
        foreach ($currentRange as $k => $v) {
            $new2 = $old2 = 0;
            $newDateRange = getStartEndDate($v);
            $oldDateRange = getStartEndDate($previousRange[$k]);
            if (isset($previousRange[$k])) {
                $old = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                    ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                    ->where('contact_logs.status', $status)
                    ->where('contact_logs.created_at', '>=', $oldDateRange['start'])
                    ->where('contact_logs.created_at', '<', $oldDateRange['end'])
                    ->selectRaw('count(*) as count')
                    ->first()->toArray();
                if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                    $old2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                        return $q->whereIn('event_id', $eventID);
                    })
                        ->where('status', $status)
                        ->where('created_at', '>=', $oldDateRange['start'])
                        ->where('created_at', '<', $oldDateRange['end'])
                        ->selectRaw('count(*) as count')
                        ->first()->count;
                }
                $temp['old'][$previousRange[$k]] = $old['count'] + $old2;
            } else {
                $temp['old'][$k] = 0;
            }

            $new = ContactLog::join('contacts', 'contacts.id', '=', 'contact_logs.contact_id')
                ->whereRaw('contacts.user_id IN ('.$downlineIds.')')
                ->where('contact_logs.status', $status)
                ->where('contact_logs.created_at', '>', $newDateRange['start'])
                ->where('contact_logs.created_at', '<=', $newDateRange['end'])
                ->selectRaw('count(*) as count')
                ->first()->toArray();
            if (in_array($status, [ContactBoardStatus::CONFIRMED_FOR_ZOOM, ContactBoardStatus::ATTENDED_THE_ZOOM])) {
                $new2 = EventReps::when(count($eventID) > 0, function ($q) use ($eventID) {
                    return $q->whereIn('event_id', $eventID);
                })
                    ->where('status', $status)
                    ->where('created_at', '>', $newDateRange['start'])
                    ->where('created_at', '<=', $newDateRange['end'])
                    ->selectRaw('count(*) as count')
                    ->first()->count;
            }
            $temp['new'][$v] = $new['count'] + $new2;
        }
        $data['status' . $status]['new']['title'] = array_keys($temp['new']);
        $data['status' . $status]['new']['count'] = array_values($temp['new']);
        $data['status' . $status]['old']['title'] = array_keys($temp['old']);
        $data['status' . $status]['old']['count'] = array_values($temp['old']);
        $data['label'] = $label;

        $data['status' . $status]['new_count'] = array_sum($data['status' . $status]['new']['count']);
        $data['status' . $status]['old_count'] = array_sum($data['status' . $status]['old']['count']);

        if ($data['status' . $status]['new_count'] > $data['status' . $status]['old_count']) {
            $data['status' . $status]['status'] = 'more';
        } else {
            $data['status' . $status]['status'] = 'less';
        }
        if ($data['status' . $status]['old_count'] > 0 && $data['status' . $status]['new_count'] > 0) {
            $data['status' . $status]['percentage'] = round(($data['status' . $status]['new_count'] - $data['status' . $status]['old_count']) * 100 / $data['status' . $status]['old_count'], 0);
        } else if ($data['status' . $status]['old_count'] == 0) {
            $data['status' . $status]['percentage'] = $data['status' . $status]['new_count'] * 100;
        } else if ($data['status' . $status]['new_count'] == 0) {
            $data['status' . $status]['percentage'] = '-' . $data['status' . $status]['old_count'] * 100;
        }

        return $data;
    }

    /**
     * Show the event  data Ajax.
     *
     * @param Request $request
     * @return Response
     */
    public function eventDataAjax(Request $request)
    {
        $todayUserEndDateTime = Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'));

        $switchType = 'Month';
        $start = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $end = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

        $month = $todayUserEndDateTime->translatedFormat('m');

        if ($request->dateFilterType == 'Week') {
            $switchType = 'Week';
            $start = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->startOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
            $end = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->endOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        }
        if ($request->dateFilterType == 'Month') {
            $switchType = 'Month';
            $start = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
            $end = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        }
        if ($request->dateFilterType == 'customRange') {
            $switchType = 'customRange'; //because condition applies same as of month
            $start = convertDateFormatWithTimezone(Carbon::parse($request->start)->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
            $end = convertDateFormatWithTimezone(Carbon::parse($request->end)->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        }
        if ($request->dateFilterType == 'Day') {
            $switchType = 'Day';
            $start = $todayUserEndDateTime->clone()->subDays(1)->format('Y-m-d H:i:s');
            $end = $todayUserEndDateTime->clone()->format('Y-m-d H:i:s');
        }
        $user = Auth::user();
        $downlineIds = User::getDownlineIds();
        $downlines = User::whereRaw('id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')->get();
        array_push($downlineIds, $user->id);
        $downlineIds = getDownlinesStr(implode(',', array_filter($downlineIds))); 
        $data = [];
        $term = $request->term ?? null;
        $data = Event::select("id", "name")->whereRaw('user_id IN ('.$downlineIds.')')
            ->when(!is_null($term), function ($q) use ($term) {
                return $q->where('name', 'LIKE', "%$term%");
            })
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start, $end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day' || $switchType == 'customRange', function ($q) use ($start, $end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->get();
        return response()->json($data);
    }

    /**
     * Personal contact stats
     *
     * @param Request $request
     * @return Response
     */
    public function personalContactStats(Request $request)
    {
        $switchType = 'month';
        $userTodayEndDate = Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'));

        $todayDate = $userTodayEndDate->format('Y-m-d');

        if (isset($request->dateFilterType)) {
            if ($request->dateFilterType == 'Week') {
                $switchType = 'WEEKLY'; //week
            }
            if ($request->dateFilterType == 'Month') {
                $switchType = 'MONTHLY'; //month
            }
            if ($request->dateFilterType == 'Day') {
                $switchType = 'Today'; //day
            }
        }

        $userId = Auth()->id();
        $memberIds = [Auth()->id()];

        if (isset($request->eventID)) {
            $eventID = $request->eventID;
        } else {
            $eventID = array();
        }

        $contacts = Contact::where(['user_id' => $userId]);
        $cbStatus = ContactBoardStatus::asArray();
        $personalStatsPeriod = $switchType ?? 'Today';
        $dateBeforeWeek = $userTodayEndDate->clone()->subDays(7)->format('Y-m-d');
        $dateBeforeMonth = $userTodayEndDate->clone()->subDays(dateDiffInDays(date('Y-m-01'), $todayDate->format('Y-m-d')))->format('Y-m-d');

        $startDate = $personalStatsPeriod == 'MONTHLY' ? $dateBeforeMonth : $dateBeforeWeek;
        $board = Board::where(['user_id' => $userId])->first();
        $stats = [];
        if ($board) {
            $statsQuery = $board->boardContacts()->selectRaw('status, count(contact_id) as status_count')
                ->groupBy('status');
            if ($personalStatsPeriod !== 'Today') {
                $statsQuery->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $todayDate);
            } else {
                $statsQuery->whereDate('created_at', $todayDate);
            }
            $statsQuery->when(count($eventID) > 0, function ($q) use ($eventID) {
                return $q->whereHas('contacts', function ($q) use ($eventID) {
                    $q->whereIn('contacts.event_id', $eventID);
                });
            });
            $stats = $statsQuery->pluck('status_count', 'status');
        }

        $personalContactStats = [];
        foreach (ContactBoardStatus::asArray() as $key => $status) {
            if (empty($stats[$status])) {
                $personalContactStats[$key] = 0;
            } else {
                $personalContactStats[$key] = $stats[$status];
            }
        }

        $personalContactTotalStats = getContactStatsTotalCounts($personalContactStats);

        $personalBoardContactIds = BoardContact::query()
            ->join('contacts', 'contacts.id', '=', 'board_contact.contact_id')
            ->where(['contacts.user_id' => $userId])->pluck('board_contact.contact_id')->toArray();

        return view('seller.analytic._personal_stats',
            compact('personalContactStats', 'personalContactTotalStats'));
    }

    /**
     * Team contact stats
     *
     * @param Request $request
     * @return Response
     */
    public function teamContactStats(Request $request)
    {
        $switchType = 'month';
        $userTodayEndDate = Carbon::parse(convertDateFormatWithTimezone(getCarbonNowForUser(), 'Y-m-d H:i:s', 'Y-m-d H:i:s'));

        $todayDate = $userTodayEndDate->format('Y-m-d');

        if (isset($request->dateFilterType)) {
            if ($request->dateFilterType == 'Week') {
                $switchType = 'WEEKLY'; //week
            }
            if ($request->dateFilterType == 'Month') {
                $switchType = 'MONTHLY'; //month
            }
            if ($request->dateFilterType == 'Day') {
                $switchType = 'Today'; //day
            }
        }

        $userId = Auth()->id();
        $members = User::getMyMember($userId);
        $todayDate = $userTodayEndDate->format('Y-m-d');
        if (!empty($members)) {
            $members = CommonUtil::removeElementWithValue($members, 'id', $userId);
        }
        $memberIds = array_column($members, 'id');

        if (isset($request->eventID)) {
            $eventID = $request->eventID;
        } else {
            $eventID = array();
        }

        $contacts = Contact::where(['user_id' => $userId]);
        $totalContacts = $contacts->count();
        $cbStatus = ContactBoardStatus::asArray();
        $teamStatsPeriod = $request->team_stats_period ?? 'Today';
        $dateBeforeWeek = $userTodayEndDate->clone()->subDays(7)->format('Y-m-d');
        $dateBeforeMonth = $userTodayEndDate->clone()->subDays(dateDiffInDays(date('Y-m-01'), $todayDate->format('Y-m-d')))->format('Y-m-d');

        $startDate = $teamStatsPeriod == 'MONTHLY' ? $dateBeforeMonth : $dateBeforeWeek;
        $memberIds = getDownlinesStr(implode(',', $memberIds));
        $boardIds = Board::query()->whereRaw('user_id IN ('.$memberIds.')')->pluck('id')->toArray();
        $statsQuery = BoardContact::query()
            ->selectRaw('status, count(contact_id) as status_count')
            ->whereIn('board_id', $boardIds)
            ->groupBy('status');
        if ($teamStatsPeriod !== 'Today') {
            $statsQuery->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $todayDate);
        } else {
            $statsQuery->whereDate('created_at', $todayDate);
        }
        $stats = $statsQuery->pluck('status_count', 'status');
        $teamContactStats = [];
        foreach (ContactBoardStatus::asArray() as $key => $status) {
            if (empty($stats[$status])) {
                $teamContactStats[$key] = 0;
            } else {
                $teamContactStats[$key] = $stats[$status];
            }
        }

        $teamContactTotalStats = getContactStatsTotalCounts($teamContactStats);
        $teamBoardContactIds = BoardContact::query()
            ->join('contacts', 'contacts.id', '=', 'board_contact.contact_id')
            ->whereRaw('contacts.user_id IN ('.$memberIds.')')->pluck('board_contact.contact_id')->toArray();
        $totalTeamContactsCount = Contact::whereRaw('user_id IN ('.$memberIds.')')->count();

        return view('seller.analytic._team_stats',
            compact('totalContacts', 'teamContactStats', 'cbStatus', 'teamStatsPeriod', 'totalTeamContactsCount', 'teamContactTotalStats'));
    }
}
