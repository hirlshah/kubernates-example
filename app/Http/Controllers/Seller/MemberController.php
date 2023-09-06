<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Classes\Helper\ReferralCode;
use App\Enums\ContactBoardStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Http\Requests\ExperienceRequest;
use App\Jobs\SendEmailJob;
use App\Mail\WelcomeMemberEmail;
use App\Models\Board;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Event;
use App\Models\Task;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserExperience;
use App\Models\UserFavourite;
use App\Models\UserTask;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use MetaTag;
use Session;
use Yadahan\AuthenticationLog\AuthenticationLog;

class MemberController extends Controller
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
     * Seller stats dashboard.
     */
    public function stats() 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Stats'));
        MetaTag::set('description', config('app.rankup.company_title').' Stats Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));

        $downlineIds = User::getDownlineIds();
        $downlineIds = getDownlinesStr(implode(',', $downlineIds));
        $members = User::getMyMember(Auth::id());
        unset($members[0]);
        $today = Carbon::today();
        $weekStart = Carbon::today()->subDays(7);
        $monthStart = Carbon::today()->subDays(30);

        $singleUserDaily = AuthenticationLog::where('authenticatable_id', Auth::id())->where('login_at', '>=', $today->format('Y-m-d H:i:s'))->count();
        $singleUserWeekly = AuthenticationLog::where('authenticatable_id', Auth::id())->where('login_at', '>=', $weekStart->format('Y-m-d H:i:s'))->count();
        $singleUserMonthly = AuthenticationLog::where('authenticatable_id', Auth::id())->where('login_at', '>=', $monthStart->format('Y-m-d H:i:s'))->count();

        $allUserDaily = AuthenticationLog::whereRaw('authenticatable_id IN ('.$downlineIds.')')->where('login_at', '>=', $today->format('Y-m-d H:i:s'))->count();
        $allUserWeekly = AuthenticationLog::whereRaw('authenticatable_id IN ('.$downlineIds.')')->where('login_at', '>=', $weekStart->format('Y-m-d H:i:s'))->count();
        $allUserMonthly = AuthenticationLog::whereRaw('authenticatable_id IN ('.$downlineIds.')')->where('login_at', '>=', $monthStart->format('Y-m-d H:i:s'))->count();

        $loginCounts = compact('singleUserDaily', 'singleUserWeekly', 'singleUserMonthly', 'allUserDaily', 'allUserWeekly', 'allUserMonthly');
        $myDownline = User::getDownlineCount();
        return view('seller.member.stats', compact('myDownline', 'members', 'loginCounts'));
    }

    /**
     * Get message sent stat
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getMessageSentStat(Request $request) 
    {
        return response()->json([
            'success' => true,
            'data' => $this->getContactLogStats($request, ContactBoardStatus::MESSAGE_SENT)
        ], 200);
    }

    /**
     * Get new customer stat
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getNewCustomerStat(Request $request) 
    {
        return response()->json([
            'success' => true,
            'data' => $this->getContactLogStats($request, ContactBoardStatus::NEW_CLIENT)
        ], 200);
    }

    /**
     * Get new distributor stat
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getNewDistributorStat(Request $request) 
    {
        return response()->json([
            'success' => true,
            'data' => $this->getContactLogStats($request, ContactBoardStatus::NEW_DISTRIBUTOR)
        ], 200);
    }

    /**
     * Get contact log stats
     *
     * @param Request $request
     */
    public function getContactLogStats($request, $status) 
    {
        $userStart = $request->start_date." 00:00:00";
        $userEnd = $request->end_date." 23:59:59";
        $start = carbonCreateFromFormatForUser('Y-m-d H:i:s', $userStart);
        $end = carbonCreateFromFormatForUser('Y-m-d H:i:s', $userEnd);

        $startDateTime = $start->format('Y-m-d H:i:s');
        $endDateTime = $end->format('Y-m-d H:i:s');

        $startDate = $start->format('Y-m-d');
        $endDate = $end->format('Y-m-d');
        $memberIds = User::getDownlineIds();
        $statsQuery = ContactLog::query()
           ->whereBetween('created_at', [$startDateTime, $endDateTime])
           ->where('status', $status)
           ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $memberIds)).')');
        if($startDate === $endDate){
            $statsQuery->select(DB::raw('count(id) as total'), DB::raw('HOUR(created_at) as time'))->groupByRaw('HOUR(created_at)');
        } else {
            $statsQuery->select(DB::raw('count(id) as total'), DB::raw('`date` as time'))->groupBy('date');
        }
        $stats = $statsQuery->pluck('total', 'time')->toArray();
        $data = [];

        $date = $start->clone();
        if($startDate === $endDate){
            do {
                $dateFormatted = $date->format('Y-m-d');
                $hourFormatted = $date->format('H');
                $key = ($hourFormatted == 0)? $dateFormatted : ($hourFormatted . ":00");
                $key = ($hourFormatted == 23)? $date->addDay()->format('Y-m-d') : $key;
                $data["'{$key}'"] = $stats[$hourFormatted]??0;
            } while ($date->addHour() <= $end);
        } else {
            do {
                $dateFormatted = $date->format('Y-m-d');
                $data[$dateFormatted] = $stats[$dateFormatted]??0;
            } while ($date->addDay() <= $end);
        }

        return $data;
    }

	/**
	 * Add Member
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws ValidationException
	 */
    public function addMember(Request $request) 
    {
        $message = array(
            'full_name.required' => __('Name is required.'),
            'profile_image.required' => __('Profile image is required.'),
            'tree_pos.required' => __('Please select position.')
        );
        Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'profile_image' => ['nullable', 'mimes:jpg,jpeg,png,svg', 'max:5000'],
            'tree_pos' => ['required', 'in:left,right'],
        ],$message)->validate();

        /**
         * Find Empty node
         */
        $emptyNodeId = User::findEmptyNode(Auth::id(), NULL, $request->tree_pos);
        $referralCode = new ReferralCode();

        $member = new User();
        $member->name = $request->full_name;
        $member->email = $request->email;
        $member->tree_pos = $request->tree_pos;
        $member->root_id = Auth::user()->root_id ?? Auth::id();
        $member->referral_code = $referralCode->createReferralCode();
        $member->parent_id = $emptyNodeId;
        $member->save();
        if($request->hasfile('profile_image')){
            $imageName = CommonUtil::uploadFileToFolder($request->file('profile_image'), 'users/image');
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('file'), 'users/thumbnails');
            $member->profile_image = $imageName;
            $member->thumbnail_image = $thumbanilImage;
            $member->save();
        }
        $email = new WelcomeMemberEmail($member);
        dispatch(new SendEmailJob($email));
        return response()->json([
            'success' => true,
            'redirect_url' => route('seller.members')
        ], 200);
    }

    /**
     * My Profile
     *
     * @return View
     */
    public function myProfile() 
    {
	    $user = Auth::user();
        MetaTag::set('title', config('app.rankup.company_title')." - ".$user->name);
        MetaTag::set('description', isset($user->description) ? config('app.rankup.company_title')." - ".$user->description : "Rank Up Profile Page");
        MetaTag::set('image', isset($user->profile_image) ? CommonUtil::getUrl($user->profile_image) : asset(config('app.rankup.company_logo_path')));
        $events = Event::where(['user_id' => Auth::id()])->orderBy('meeting_date','DESC')->limit(3)->get();

	    $todayDay = getTodayDayForUser();
	    $tasks = Task::where(['user_id' => Auth::User()->id, 'repeat_'.$todayDay => 1])->get();
	    $todayDate = getCarbonTodayEndDateTimeForUser();
	    $todayTask = UserTask::where(['user_id' => Auth()->id()])
            ->where('task_date', '>=', $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s'))
            ->where('task_date', '<', $todayDate->clone()->format('Y-m-d H:i:s'))->first();

	    $completedTasks = [];
	    if(!empty($todayTask)) {
		    $completedTasks = (array) json_decode($todayTask->tasks);
	    }

        /* Get all tasks completed dates */
        $todayStartDate = getCarbonTodayForUser();
        $start = convertDateFormatWithTimezone($todayStartDate->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $end = convertDateFormatWithTimezone($todayStartDate->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

        $completedTaskDatesData = UserTask::where(['user_id' => $user->id, 'is_complete' => 1])
            ->where('task_date', '>=', $start)
            ->where('task_date', '<', $end)->pluck('task_date', 'id');


        $completedTaskDates = [];
        if(!empty($completedTaskDatesData)) {
            foreach($completedTaskDatesData as $completedTaskDate) {
                $completedTaskDates[] = convertDateFormatWithTimezone($completedTaskDate, 'Y-m-d H:i:s','d/m/Y','CRM-TO-FRONT').",,blue";
            }
        }
        $completedTaskDates = json_encode($completedTaskDates);

        return view('seller.profile.index',compact('events', 'user', 'tasks', 'completedTasks', 'completedTaskDates'));
    }

	/**
	 * Member Profile
	 *
	 * @param int $id
	 *
	 * @return View
	 */
    public function memberProfile($id) 
    {
        $user = User::findOrFail($id);

        MetaTag::set('title', config('app.rankup.company_title')." - ".$user->name);
        MetaTag::set('description', isset($user->description) ? config('app.rankup.company_title')." - ".$user->description : config('app.rankup.company_title')." Member Profile Page");
        MetaTag::set('image', isset($user->profile_image) ? CommonUtil::getUrl($user->profile_image) : asset(config('app.rankup.company_logo_path')));
        $events = Event::where(['user_id' => Auth::id()])->orderBy('meeting_date','DESC')->limit(3)->get();
        if(!$user->board) {
            Board::create([
                'user_id' => $user->id,
                'is_current' => 1,
            ]);
            $user->refresh();
        }
        $statusRange = ContactBoardStatus::asSelectArray();
        $board = $user->board;
        $boardContacts = $board->contacts->pluck('pivot.contact_id', 'id')->toArray();
        $contacts = Contact::where(['user_id'=> $user->id])->whereNotIn('id', $boardContacts)->get();
        $board_contacts = [];
        foreach ($board->contacts as $board_contact) {
            $board_contacts[$board_contact->pivot->status][] = $board_contact;
        }
        return view('seller.member.profile',compact('events', 'user', 'contacts', 'board_contacts', 'statusRange', 'board'));
    }

	/**
	 * Update Parent
	 *
	 * @param Request $request
	 *
	 * @return array|bool[]
	 */
    public function updateParent(Request $request) 
    {
        if($request->selectionId && $request->targetId){
            $members  = User::getMyMember(Auth::id());
            $memberIds = array_column($members, 'id');
            if(in_array($request->selectionId, $memberIds) && in_array($request->targetId, $memberIds)){
                $targetChildren = User::where('parent_id', $request->targetId)->pluck('tree_pos', 'id')->toArray();
                $targetPosition = in_array('left', $targetChildren)?'right' : 'left';
                $selectedUser = User::findOrFail($request->selectionId);
                $selectedUser->parent_id = $request->targetId;
                $selectedUser->tree_pos = $targetPosition;
                $selectedUser->save();
                return ['status'=>true];
            }
        }
        return ['status'=>true];
    }

	/**
	 * Update Profile
	 *
	 * @param Request $request
	 *
	 * @return View
	 */
    public function profileUpdate(Request $request) 
    {
    	$data = $request->all();
	    $user = Auth::User();

        $message = array(
            'name.required' => __('First Name is required'),
            'last_name.required' => __('Last Name is required'),
        );

        $this->validate($request, [
            'name' => 'required',
            'last_name' => 'required',
        ],$message);

	    $user->update( $data );
	    return response()->json([
	        'success' => true,
	        'data' => [
	            'name' => $user->name,
                'last_name' => $user->last_name,
	            'description' => $user->description,
	         ]
	    ], 200);
    }

    /**
     * Update Profile Photo
     *
     * @param Request $request
     *
     * @return View
     */
    public function profilePhotoUpdate(Request $request) 
    {
        $user = Auth::User();
        
        $message = array(
            'profile_image.mimes' => __('Profile Image must be a file of type:jpeg,jpg'),
        );

        $this->validate($request, [
            'profile_image' => 'mimes:jpeg,jpg',
        ],$message);
        
        if ($request->file('profile_image')) { 
            if(isset($user->profile_image)) {
                CommonUtil::removeFile($user->profile_image);
            }
            $imageName = CommonUtil::uploadFileToFolder( $request->file('profile_image'), 'users/image' );
            $thumbanilImage = CommonUtil::generateThumbnails($request->file('profile_image'), 'users/thumbnails');
            $user->profile_image = $imageName;
            $user->thumbnail_image = $thumbanilImage;
        }

        $user->save();
        
        return response()->json([
            'success' => 'success',
        ]);
    }

    /**
     * Add Favourite
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addFavourite(Request $request) 
    {
        if($request->member_id){
            UserFavourite::updateOrCreate(['user_id' => Auth::id(), 'member_id'=>$request->member_id], []);
        }
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Remove Favourite
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeFavourite(Request $request) 
    {
        UserFavourite::query()->where(['user_id' => Auth::id(), 'member_id'=>$request->member_id])->delete();
        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Update Profile Info
     *
     * @param Request $request
     *
     * @return View
     */
    public function profileInfoUpdate(Request $request) 
    {
        $user = Auth::User();

        $message = array(
            'info.max' => __('Info should not be greater than 500 character'),
        );
        $this->validate($request, [
            'info' => 'max:500',
        ],$message);

        $user->info = $request->info;
        $user->save();

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $user->info
             ]
        ], 200);
    }

    /**
     * Get people list on search
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPeopleList(Request $request) 
    {   
        $members = User::getMyMember(Auth::id());
        $memberIds = array_column($members, 'id');

        $memberIds = getDownlinesStr(implode(',', array_filter($memberIds)));
        if(!empty($request->search_text)) {
            $searchKeyword = $request->search_text;
            $users = User::query()->whereRaw('id IN ('.$memberIds.')')->where('name', 'LIKE', "%{$searchKeyword}%")->orWhere('email', 'LIKE', "%{$searchKeyword}%");
            $users = $users->paginate(10);
        }

        if(!empty($users)) {
            $view = view('seller.member.component.people_list', compact('users'))->render();
             return response()->json([
                'success' => true,
                'html' => $view,
            ], 200);
        } else {
            return response()->json([
                'success' => false
            ], 200);
        }   
    }

    /**
     * Add Education - not used for now
     *
     * @param EducationRequest $request
     *
     * @return JsonResponse
     */
    public function addEducation(EducationRequest $request) 
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['start_date'] = CommonUtil::dateForDatabase($data['start_date']);
        if(isset($data['end_date'])){
            $data['end_date'] = CommonUtil::dateForDatabase($data['end_date']);
        }
        if($userEducation = UserEducation::create( $data )) {
            return response()->json( [
                'success' => true,
            ], 200 );
        }
    }

    /**
     * Delete Education - not used for now
     *
     * @param int $id
     *
     * @return redirect
     */
    public function deleteEducation($id) 
    {
        $userEducation = UserEducation::findOrFail( $id );
        $userEducation->delete();
        return response()->json( [
            'success' => true,
        ], 200 );
    }

    /**
     * Add Experience - not used for now
     *
     * @param ExperienceRequest $request
     *
     * @return JsonResponse
     */
    public function addExperience(ExperienceRequest $request) 
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['start_date'] = CommonUtil::dateForDatabase($data['start_date']);
        if(isset($data['end_date'])) {
            $data['end_date'] = CommonUtil::dateForDatabase( $data['end_date'] );
        }
        if( $request->hasFile('image') ) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('image'), 'experiences');
            $data['image'] = $imageName;
        }
        if($userExperience = UserExperience::create( $data )){
            return response()->json([
                'success' => true,
            ], 200);
        }
    }

    /**
     * Delete Experience - not used for now
     *
     * @param int $id
     *
     * @return redirect
     */
    public function deleteExperience($id) 
    {
        $userExperience = UserExperience::findOrFail( $id );
        $userExperience->delete();
        return response()->json( [
            'success' => true,
        ], 200 );
    }
}