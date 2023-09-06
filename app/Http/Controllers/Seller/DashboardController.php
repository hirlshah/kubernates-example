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
use App\Models\FollowUp;
use App\Models\Task;
use App\Models\User;
use App\Models\UserPerformanceRadialSetting;
use App\Models\UserPlan;
use App\Models\UserTask;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use MetaTag;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:seller-dashboard', ['only' => ['index']]);
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
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Dashboard'));
        MetaTag::set('description', config('app.rankup.company_title').' Dashboard Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $member = Auth::user();
        $members = User::getMyMember(Auth::id());
        if (!empty($members)) {
            $members = CommonUtil::removeElementWithValue($members, 'id', Auth::id());
        }
        $memberIds = array_column($members, 'id');
        $todayDate = getCarbonTodayEndDateTimeForUser();

        //$downlineIds = Auth::user()->getDownlineIds();
        $downlineIds = array_diff($memberIds, [$member->id]);

        $user = Auth::user();
        array_push($downlineIds, $user->id);
        array_push($downlineIds, $user->root_id);

        $downlineIds = getDownlinesStr(implode(',', array_filter($downlineIds)));
        $todayEvents = Event::where('meeting_date', '>=', $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s'))->where('meeting_date', '<', $todayDate->clone()->format('Y-m-d H:i:s'))->whereRaw('user_id IN ('.$downlineIds.')')->get();
        $thisWeekEvents = Event::where('meeting_date', '>=', convertDateFormatWithTimezone($todayDate->clone()->startOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'))
            ->where('meeting_date', '<=', convertDateFormatWithTimezone($todayDate->clone()->endOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'))->whereRaw('user_id IN ('.$downlineIds.')')->get();
        $nextEvents = Event::where('meeting_date', '>', $todayDate->clone()->format('Y-m-d H:i:s'))->whereRaw('user_id IN ('.$downlineIds.')')->orderBy('meeting_date', 'asc')->get();
        $memberImages = array_column($members, 'thumbnail_image');
        $memberImages = array_slice($memberImages, 0, 5);
        $followUpCount = FollowUp::query()->whereRaw('user_id IN ('.$downlineIds.')')->where('follow_ups.follow_up_date', '>=', $todayDate)->count();

        return view('seller.dashboard.index', compact('todayEvents', 'thisWeekEvents', 'memberImages', 'followUpCount', 'member', 'nextEvents'));
    }

    /**
     * Get members stats
     *
     * @param Request $request
     *
     * @return View
     */
    public function getMemberStats(Request $request)
    {
        if(empty($request->user_id)) {
            $userId =  Auth()->id();
            $isObjectifsEdit = true;
        } else {
            $userId = $request->user_id;
            $isObjectifsEdit = false;
        }

        $isPageLoad = false;

        if(!isset($request->filter_type)) {
            $isPageLoad = true;
        }

        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');
        $todayEndDateForFilter = $todayDate->clone()->format('Y-m-d H:i:s');

        $personalStatsPeriod = $request->personal_stats_period ?? 'Today';
        $dateBeforeWeek = $todayDate->clone()->subDays(7)->format('Y-m-d H:i:s');
        $dateBeforeMonth = $todayDate->clone()->subDays(dateDiffInDays(date('Y-m-01'), $todayDate->format('Y-m-d')))->format('Y-m-d H:i:s');
        $startDate = $personalStatsPeriod == 'MONTHLY' ? $dateBeforeMonth : $dateBeforeWeek;

        $requestStartDate = $requestEndDate = '';
        if(!empty($request->start) && !empty($request->end)){
            $requestStartDate = Carbon::parse(convertDateFormatWithTimezone($request->start.' 00:00:00', 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'));
            $requestEndDate = Carbon::parse(convertDateFormatWithTimezone($request->end.' 00:00:00', 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'))->addDays(1);
        }

        $personalContactStats = $personalContactTotalStats = [];
        $totalContacts = 0;
        
        $contacts = Contact::where(['user_id' => $userId]);
        $board = Board::where(['user_id' => $userId])->first();
        if($board) {
            $stats = ContactLog::query()
                ->selectRaw('status, count(id) as status_count')
                ->where(['user_id' => $userId]);
            if($personalStatsPeriod == 'MONTHLY' || $personalStatsPeriod == 'WEEKLY') {
                $stats->where('created_at', '>=', $personalStatsPeriod == 'MONTHLY' ? $dateBeforeMonth : $dateBeforeWeek)->where('created_at', '<', $todayDate);
                $contacts->where('created_at', '>=', $startDate)->where('created_at', '<', $todayDate);
            } else if((!empty($requestStartDate) && !empty($requestEndDate))){
                $personalStatsPeriod = 'INTERVAL';
                $stats->where('created_at', '>=', $requestStartDate)->where('created_at', '<=', $requestEndDate);
                $contacts->where('created_at', '>=', $requestStartDate)->where('created_at', '<=', $requestEndDate);
            } else if($personalStatsPeriod == 'Total') {

            } else {
                $stats->where('created_at', '>=', $todayStartDateForFilter)->where('created_at', '<', $todayEndDateForFilter);

                $contacts = $contacts->where('created_at', '>=', $todayStartDateForFilter)->where('created_at', '<', $todayEndDateForFilter);
            }
            $stats = $stats->groupBy('status')->pluck('status_count', 'status');
        }

        $totalContacts = $contacts->count();
        foreach(ContactBoardStatus::asArray() as $key => $status) {
            if(empty($stats[$status])) {
                $personalContactStats[$key] = 0;
            } else {
                $personalContactStats[$key] = $stats[$status];
            }
        }
        $personalContactTotalStats = getContactStatsTotalCounts($personalContactStats);
        
        /* Get all tasks completed dates */
        $tasks = $completedTasks = [];
        $completedTaskDates = '';
        if($isPageLoad) {
            $todayDay = getTodayDayForUser();
            $tasks = Task::where(['user_id' => Auth::User()->id, 'repeat_'.$todayDay => 1])->get();
            
            $todayTask = UserTask::where(['user_id' => $userId])
                ->where('task_date', '>=', $todayStartDateForFilter)
                ->where('task_date', '<', $todayEndDateForFilter)->first();

            if(!empty($todayTask)) {
                $completedTasks = (array) json_decode($todayTask->tasks);
            }

            $todayStartDate = getCarbonTodayForUser();
            $start = convertDateFormatWithTimezone($todayStartDate->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
            $end = convertDateFormatWithTimezone($todayStartDate->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

            $completedTaskDatesData = UserTask::where(['user_id' => $userId, 'is_complete' => 1])
                        ->where('task_date', '>=', $start)
                        ->where('task_date', '<=', $end)->pluck('task_date', 'id');


            $completedTaskDates = array();
            if(!empty($completedTaskDatesData)) {
                foreach($completedTaskDatesData as $completedTaskDate) {
                    $completedTaskDates[] = convertDateFormatWithTimezone($completedTaskDate, 'Y-m-d H:i:s','d/m/Y','CRM-TO-FRONT').",,blue";
                }
            }
            $completedTaskDates = json_encode($completedTaskDates);
        }

        /* Get all tasks completed dates end */

        /* Performance radial start here */
        $userPerformanceRadialSetting = UserPerformanceRadialSetting::where(['user_id' => $userId, 'is_team' => 0])->first();
        $performanceRedial = $this->userPerformanceRadial($userPerformanceRadialSetting, $personalStatsPeriod);
        $totalMessageGoal = $performanceRedial['total_message_goal'];
        $userPerformanceRadialSettingArr = $performanceRedial['user_performance_radial_settings'];
        /* Performance radial end here */

        return view('seller.dashboard._stats',compact('totalContacts', 'personalContactStats', 'personalStatsPeriod', 'personalContactTotalStats', 'tasks', 'completedTasks', 'completedTaskDates', 'isObjectifsEdit', 'userPerformanceRadialSettingArr', 'totalMessageGoal', 'userId'));
    }

    /**
     * Get team member stats
     *
     * @param Request $request
     *
     * @return View
     */
    public function getMemberTeamStats(Request $request)
    {
        if(empty($request->user_id)) {
            $userId =  Auth()->id();
        } else {
            $userId =  $request->user_id;
        }

        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');
        $todayEndDateForFilter = $todayDate->clone()->format('Y-m-d H:i:s');

        $memberIds = $this->getMemberIds($userId);
        $memberIds = getDownlinesStr(implode(',', $memberIds));

        $teamStatsPeriod = $request->team_stats_period ?? 'Today';
        $dateBeforeWeek = $todayDate->clone()->subDays(7)->format('Y-m-d H:i:s');
        $dateBeforeMonth = $todayDate->clone()->subDays(dateDiffInDays(date('Y-m-01'), $todayDate->format('Y-m-d')))->format('Y-m-d H:i:s');

        $requestStartDate = $requestEndDate = '';
        if(!empty($request->start) && !empty($request->end)){
            $requestStartDate = Carbon::parse(convertDateFormatWithTimezone($request->start.' 00:00:00', 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'));
            $requestEndDate = Carbon::parse(convertDateFormatWithTimezone($request->end.' 00:00:00', 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'))->addDays(1);
        }

        $startDate = $teamStatsPeriod == 'MONTHLY' ? $dateBeforeMonth : $dateBeforeWeek;

        $teamContactStats = $teamContactTotalStats = [];
        $totalTeamContactsCount = 0;
        
        $statsQuery = ContactLog::query()
            ->selectRaw('status, count(contact_id) as status_count')
            ->whereRaw('user_id IN ('.$memberIds.')')
            ->groupBy('status');
        if($teamStatsPeriod == 'MONTHLY' || $teamStatsPeriod == 'WEEKLY') {
            $statsQuery->where('created_at', '>=', $startDate)
                ->where('created_at', '<', $todayDate);
        } else if((!empty($requestStartDate) && !empty($requestEndDate))){
            $statsQuery->where('created_at','>=', $requestStartDate)->where('created_at','<', $requestEndDate);
        } else if($teamStatsPeriod == 'Total') {

        } else {
            $statsQuery->where('created_at', '>=', $todayStartDateForFilter)->where('created_at', '<', $todayEndDateForFilter);
        }
        $stats = $statsQuery->pluck('status_count', 'status');

        foreach(ContactBoardStatus::asArray() as $key => $status) {
            if(empty($stats[$status])) {
                $teamContactStats[$key] = 0;
            } else {
                $teamContactStats[$key] = $stats[$status];
            }
        }

        $teamContactTotalStats = getContactStatsTotalCounts($teamContactStats);

        $teamContacts = Contact::whereRaw('user_id IN ('.$memberIds.')');

        if($teamStatsPeriod == 'MONTHLY' || $teamStatsPeriod == 'WEEKLY') {
            $teamContacts->where('created_at', '>=', $startDate)
                ->where('created_at', '<', $todayDate);
        } else if((!empty($requestStartDate) && !empty($requestEndDate))){
            $teamContacts->where('created_at', '>=', $requestStartDate)->where('created_at', '<', $requestEndDate);
        } else if($teamStatsPeriod == 'Total') {

        } else {
            $teamContacts->where('created_at', '>=', $todayStartDateForFilter)->where('created_at', '<', $todayEndDateForFilter);
        }
        $totalTeamContactsCount = $teamContacts->count();

        /* Performance radial start here */
        $userTeamPerformanceRadialSetting = UserPerformanceRadialSetting::where(['user_id' => $userId, 'is_team' => 1])->first();
        $performanceRedial = $this->userPerformanceRadial($userTeamPerformanceRadialSetting, $teamStatsPeriod);
        $totalTeamMessageGoal = $performanceRedial['total_message_goal'];
        $userTeamPerformanceRadialSettingArr = $performanceRedial['user_performance_radial_settings'];
        /* Performance radial end here */

        return view('seller.dashboard._stats_team',compact('teamContactStats', 'teamStatsPeriod', 'totalTeamContactsCount', 'teamContactTotalStats', 'totalTeamMessageGoal', 'userTeamPerformanceRadialSettingArr', 'userId'));
    }

    /**
     * Get member ids
     * 
     * @param int $userId
     * @return array
     */
    public function getMemberIds($userId) 
    {
        $members = User::getMyMember($userId);
        if(!empty($members)) {
            $members = CommonUtil::removeElementWithValue($members, 'id', $userId);
        }
        $memberIds = array_column($members, 'id');
        return $memberIds;
    }

    /**
     * Get user performance radial
     * 
     * @param array $userPerformanceRadialSetting
     * @param string $personalStatsPeriod
     * @param string $teamStatsPeriod
     * @return array
     */
    public function userPerformanceRadial($userPerformanceRadialSetting, $personalStatsPeriod = null, $teamStatsPeriod = null) 
    {
        $userPerformanceRadialSettingArr = ['no_of_clients' => 1, 'no_of_distributors' => 1];
        if($userPerformanceRadialSetting && $userPerformanceRadialSetting->count()) {
            $userPerformanceRadialSettingArr['no_of_clients'] = $userPerformanceRadialSetting->no_of_clients;
            $userPerformanceRadialSettingArr['no_of_distributors'] = $userPerformanceRadialSetting->no_of_distributors;
        }

        $totalMessageGoal = (($userPerformanceRadialSettingArr['no_of_distributors'] / 3) * 100);

        if($personalStatsPeriod == 'Today') {
            $totalMessageGoal = floor($totalMessageGoal / getTotalDaysOfCurrentMonth());
            $userPerformanceRadialSettingArr['no_of_clients'] = max(floor($userPerformanceRadialSettingArr['no_of_clients'] / getTotalDaysOfCurrentMonth()), 1);
            $userPerformanceRadialSettingArr['no_of_distributors'] = max(floor($userPerformanceRadialSettingArr['no_of_distributors'] / getTotalDaysOfCurrentMonth()), 1);
        } elseif($personalStatsPeriod == 'WEEKLY') {
            $totalMessageGoal = floor($totalMessageGoal / 4);
            $userPerformanceRadialSettingArr['no_of_clients'] = max(floor($userPerformanceRadialSettingArr['no_of_distributors'] / getTotalDaysOfCurrentMonth()), 1);

            if($teamStatsPeriod != null) {
                $userPerformanceRadialSettingArr['no_of_clients'] = max(floor($userPerformanceRadialSettingArr['no_of_clients'] / 4), 1);
            }
            
            $userPerformanceRadialSettingArr['no_of_distributors'] = max(floor($userPerformanceRadialSettingArr['no_of_distributors'] / 4), 1);
        }

        $data = [];
        $data = [
            'total_message_goal' => $totalMessageGoal,
            'user_performance_radial_settings' => $userPerformanceRadialSettingArr
        ];
        return $data;
    }

    /**
     * Display Follow Ups.
     *
     * @param Request $request
     *
     * @return View
     */
    public function getFollowUps(Request $request)
    {
        $filterType = $request->get('filter_type') ?? 'all';
        $pagePartial = $request->page_partial ?? 1;

        $startDate = getCarbonTodayEndDateTimeForUser();

        $downlineIds = Auth::user()->getDownlineIds();
        $user = Auth::user();
        array_push($downlineIds, $user->id);
        $downlineIds = getDownlinesStr(implode(',', $downlineIds));

        $followUpQuery = FollowUp::query()
            ->whereRaw('user_id IN ('.$downlineIds.')')
            ->orderBy('follow_ups.follow_up_date', 'ASC')
            ->orderBy('follow_ups.id', 'ASC');

        $members = array_column(User::getMyMember(Auth::id()), 'id');
        $teamIds = array_diff($members, [Auth::id()]);
        $teamIdsArr = getDownlinesStr(implode(',', $teamIds));
        $teams = User::whereRaw('id IN ('.$teamIdsArr.')')->get();
        if(is_numeric($filterType) && in_array($filterType, $teamIds)) {
            $filterType = intval($filterType);
            $followUpQuery->where('follow_ups.user_id', $filterType);
        } else {
            switch ($filterType) {
                case 'all':
                    $followUpQuery->whereRaw('follow_ups.user_id IN ('.getDownlinesStr(implode(',', $members)).')');
                    break;
                case 'yours':
                    $followUpQuery->whereIn('follow_ups.user_id', [Auth::id()]);
                    break;
                case 'team':
                    $followUpQuery->whereRaw('follow_ups.user_id IN ('.$teamIdsArr.')');
                    break;
            }
        }
        $date10DaysBefore = $startDate->clone()->subDays(10)->format('Y-m-d H:i:s');
        $followUpNormal = $followUpQuery->clone()
            ->where('follow_ups.follow_up_date', '>=', $date10DaysBefore)
            ->where('follow_ups.follow_up_date', '<', $startDate->format('Y-m-d H:i:s'))
            ->paginate(5, ['follow_ups.id', 'follow_ups.user_id', 'follow_ups.follow_up_date', 'follow_ups.contact_id'], 'page_partial');

        $followUpFuture = $followUpQuery->clone()
            ->where('follow_ups.follow_up_date', '>=', $startDate->format('Y-m-d H:i:s'))
            ->paginate(5, ['follow_ups.id', 'follow_ups.user_id', 'follow_ups.follow_up_date', 'follow_ups.contact_id'], 'page_partial');
        $followUpPast = $followUpQuery->clone()
            ->where('follow_ups.follow_up_date', '<', $startDate->format('Y-m-d H:i:s'))
            ->paginate(5, ['follow_ups.id', 'follow_ups.user_id', 'follow_ups.follow_up_date', 'follow_ups.contact_id'], 'page_partial');

        return view('seller.dashboard._follow_ups', compact('followUpNormal', 'filterType', 'teams', 'teamIds', 'pagePartial', 'followUpFuture', 'followUpPast'));
    }

    /**
     * Display Members Page.
     *
     * @return View
     */
    public function members()
    {
        MetaTag::set('title', config('app.rankup.company_title').' - Members');
        MetaTag::set('description', config('app.rankup.company_title').' Members Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $member = Auth::user();
        return view('seller.member.index', compact('member'));
    }

    /**
     * Display Members Tree Data.
     *
     * @return array
     */
    public function membersTreeData()
    {
        $isPerent = false;
        if(request()->has('id') ) {
            $id = request()->query('id');
            $isPerent = true;
        } else {
            $id = Auth::id();
        }

        if($id == Auth::id()) {
            $isPerent = false;
        }
        $memberTreeData = User::getMemberByLevel($id, $isPerent);
        foreach($memberTreeData as $key => $treeData) {
            $plan = UserPlan::getUserPlan($treeData->id);
            if(empty($plan)) {
                continue;
            }
            if($plan->status == 'active') {
                $treeData->opacity = 1;
            } else {
                $treeData->opacity = 0.3;
            }
        }

        return $memberTreeData;
    }

    /**
     * Display Zoom Status.
     *
     * @param int $id
     *
     * @return View
     */
    public function zoomStatus($id)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Stats'));
        MetaTag::set('description', config('app.rankup.company_title').' Stats Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $event = Event::findOrFail($id);
        $user = Auth::User();
        $eventRepoAll = $event->reps()->get();
        $statusArray = ContactBoardStatus::asArray();
        $totalContact = Contact::where('event_id', $id)->count();
        $confirmContact = BoardContact::query()
            ->join('contacts', 'contacts.id', '=', 'board_contact.contact_id')
            ->where(['contacts.event_id' => $id])
            ->where('board_contact.status', '>', '2')
            ->count();
        $showedContact = BoardContact::query()
            ->join('contacts', 'contacts.id', '=', 'board_contact.contact_id')
            ->where(['contacts.event_id' => $id])
            ->where('board_contact.status', '>', '3')
            ->count();
        $stats = BoardContact::query()
            ->select([DB::raw('count(board_contact.id) as total'), 'board_contact.status'])
            ->join('contacts', 'contacts.id', '=', 'board_contact.contact_id')
            ->where(['contacts.event_id' => $id])
            ->groupBy(['board_contact.status'])
            ->pluck('total', 'status');
        return view('seller.dashboard.zoom_status', compact('event', 'stats', 'statusArray', 'totalContact', 'confirmContact', 'showedContact', 'eventRepoAll'));
    }
}