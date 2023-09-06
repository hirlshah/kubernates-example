<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProspectionVideo;
use App\Models\User;
use App\Models\Category;
use App\Models\Survey;
use App\Models\Document;
use App\Models\VideoVisiter;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionMaster;
use App\Models\ModuleConfig;
use App\Models\VideoVisiterLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProspectionVideoRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Classes\Helper\CommonUtil;
use Illuminate\Support\Facades\DB;
use MetaTag;
use Session;
use Carbon\Carbon;
use App\Enums\ContactBoardStatus;
use App\Models\ContactLog;

class ProspectionVideoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);

        $recordExist = ModuleConfig::checkForModuleNotExist('Prospection');

        if($recordExist) {
            abort(404);
        }
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
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Prospecting video'));
        MetaTag::set('description', config('app.rankup.company_title').' Prospection Video Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $user = Auth::User();
        $prospectionVideos = ProspectionVideo::orderBy('id', 'desc');
        $memberIds = User::getDownlineIds($user->id);
        array_unshift($memberIds, $user->root_id);
        array_unshift($memberIds, $user->id);

        $memberIds = array_filter(array_unique(array_merge($memberIds, User::getUplineArray($user))));
        $prospectionVideosCategoryIds = $prospectionVideos->pluck('category_id')->toArray();
        $categories = Category::query()->whereIn('id', $prospectionVideosCategoryIds)->where(['model_type' => 'formation', 'parent_id' => '0'])->pluck('name','id');

        $categories->prepend(__('All contents'), 0);
        $authCategoryIds = DB::table('user_category')->where(['user_id' => Auth::User()->id])->pluck('category_id')->toArray();
        $surveyOptions = Survey::get();

        $filteredCategory = $filteredSubCategory = 0;
        if($request->ajax()){
            $searchText = $request->search ?? '';
            $filteredCategory = $request->category_filter??Session::get('prospectionVideo.index.category_filter', 0);
            $filteredSubCategory = $request->sub_category_filter??Session::get('prospectionVideo.index.sub_category_filter', 0);
        }

        $parentCategoryFilter = '';
        if(isset($request->category_filter) && ($request->category_filter != 0) ){
            $parentCategoryFilter = $request->category_filter;
        }

        if(!empty($filteredSubCategory)) {
            $subCategory = Category::where(['id' => $filteredSubCategory])->first();
            if(!empty($subCategory)){
                $parentCategoryFilter = $subCategory->parent_id;
            }
        }

        $subNewCategories = [];
        if(!empty($parentCategoryFilter)){
            $subNewCategories = Category::where(['model_type'=> 'formation', 'parent_id' => $parentCategoryFilter])->get();
        }

        if (intval($filteredCategory)) {
            $prospectionVideos->whereHas('category', function ($q) use ($filteredCategory) {
                $q->where('category_id', $filteredCategory);
            });
        }

        if(intval($filteredSubCategory)){
            $prospectionVideos->whereHas('category', function ($q) use ($filteredSubCategory){
                $q->where('sub_category_id', $filteredSubCategory);
            });
        }

        if (isset($searchText) && !empty($searchText)) {
            $prospectionVideos->where(function ($query) use ($searchText) {
                $query->where('title', 'LIKE', '%' . $searchText . '%')
                    ->orWhereHas('category', function ($q) use ($searchText) {
                        $q->where('name', 'like', '%' . $searchText . '%');
                    });
            });
        }
        $prospectionVideos->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $memberIds)).')');
        $prospectionVideosCount = $prospectionVideos->count();
        $prospectionVideos = $prospectionVideos->paginate(11);

        Session::put('prospectionVideo.index.category_filter', $filteredCategory);

        $parentCategories = Category::where('model_type','=','formation')->where('parent_id','=',0)->select('id','name')->get();
        $params = compact('prospectionVideos','categories','filteredCategory','surveyOptions','filteredSubCategory','subNewCategories','authCategoryIds','parentCategories', 'prospectionVideosCount');

        if ($request->ajax()) {
            return view('seller.prospectionVideo._prospection_video_pagination', $params);
        }

        return view('seller.prospectionVideo.index',$params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProspectionVideoRequest $request
     *
     * @return JsonResponse
     */
    public function store(ProspectionVideoRequest $request)
    {
        $data = $request->all();
        if (!empty($request->hidden_survey_id)) {
            $data['survey_id'] = $request->hidden_survey_id;
        }

        if($request->hasFile('video_cover_image')) {
            $imageName = CommonUtil::uploadFileToFolder($request->file('video_cover_image'), 'prospection/thumbnail');
            $data['video_cover_image'] = $imageName;
        }

        if($request->hasFile('video')) {
            $data['video'] = CommonUtil::uploadFileToFolder($request->file('video'), 'prospection/videos');
        }
        
        $data['user_id'] = Auth::id();

        $prospectionVideo = ProspectionVideo::create($data);

        if($prospectionVideo) {
            return response()->json([
                'data' => $data,
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param ProspectionVideo $prospection
     *
     * @return JsonResponse
     */
    public function show(ProspectionVideo $prospection)
    {
       return response()->json([
            'success' => true,
            'data' => [
                'title' => $prospection->title,
                'custom_title' => $prospection->custom_title,
                'description' => $prospection->description,
                'video' => CommonUtil::getUrl($prospection->video),
                'category' => $prospection->category_id,
                'survey_id' => $prospection->survey_id,
                'video_cover_image' => isset($prospection->video_cover_image) && !empty($prospection->video_cover_image) ? CommonUtil::getUrl($prospection->video_cover_image) : asset('images/prospection-default-image.png'),
                'sub_category_id' => $prospection->sub_category_id
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProspectionVideoRequest $request
     * @param ProspectionVideo $prospection
     *
     * @return JsonResponse
     */
    public function update(ProspectionVideoRequest $request, ProspectionVideo $prospection)
    {
        $data = $request->all();
        if(!empty($request->hidden_survey_id)) {
            $data['survey_id'] =  $request->hidden_survey_id;
        }

        if($request->hasFile('video')) {
            $data['video'] = CommonUtil::uploadFileToFolder($request->file('video'), 'prospection/videos');
            if(!empty($prospection->video)) {
                CommonUtil::removeFile($prospection->video);
            }
        }

        if($request->hasFile('video_cover_image')) {
            $data['video_cover_image'] = CommonUtil::uploadFileToFolder($request->file('video_cover_image'), 'prospection/thumbnail');
            if(!empty($prospection->video_cover_image)) {
                CommonUtil::removeFile($prospection->video_cover_image);
            }
        }

        if($prospection->update($data)) {
            return response()->json([
                'data' => $prospection,
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'status' => true,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $prospectionVideo = ProspectionVideo::find($id);
		if ($prospectionVideo) {
		    if (isset($prospectionVideo->video)) {
                CommonUtil::removeFile($prospectionVideo->video);
            }
            if (isset($prospectionVideo->video_cover_image)) {
                CommonUtil::removeFile($prospectionVideo->video_cover_image);
            }
			$prospectionVideo->delete();
		}

		return response()->json([
			'success' => true,
		], 200);
    }
    
    /**
     * Insights page
     *
     * @param string $slug
     *
     * @return Response
     */
    public function analyticsData(string $slug) 
    {
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);

        $videoVisitors = [];
        $videoVisitorsData = VideoVisiter::where(['prospection_video_id'=> $prospectionVideo->id, 'referral_user_id' => Auth::User()->id])->orderBy('id','asc')->paginate(25);

        $videoReffralData = VideoVisiter::where(['prospection_video_id'=> $prospectionVideo->id])->pluck('referral_user_id')->toArray();

        $refferalUserName = User::whereIn('id', $videoReffralData)->pluck('name','id');

        foreach($videoVisitorsData as $videoVisitor) {
            $name = $email = $phone = null;
            if(isset($videoVisitor->user) && !empty($videoVisitor->user)) {
                $name = $videoVisitor->user->name;
                $email = $videoVisitor->user->email;
                $phone = $videoVisitor->user->phone;
            } else if(isset($videoVisitor->contact) && !empty($videoVisitor->contact)) {
                $name = $videoVisitor->contact->name;
                $email = $videoVisitor->contact->email;
                $phone = $videoVisitor->contact->phone;
            }
            if(isset($videoVisitor->first_name) && isset($videoVisitor->last_name) && !empty($videoVisitor->first_name) && !empty($videoVisitor->last_name)) {
                $name = $videoVisitor->first_name.' '.$videoVisitor->last_name;
                $email = $videoVisitor->email;
                $phone = $videoVisitor->phone;
            }
            $videoVisitors[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'end_date' => $videoVisitor->end_date,
                'start_date' => $videoVisitor->start_date,
                'time' => $videoVisitor->time,
            ];
        }

        return view('seller.prospectionVideo.analytics', compact('prospectionVideo', 'videoVisitors', 'videoVisitorsData','refferalUserName'));
    }

    /**
     * Prospection full view graph data
     *
     * @param Request $request
     */
    public function prospectionFullViewGraphData(Request $request) 
    {
        $prospectionVideo = ProspectionVideo::find($request->prospectionVideoId);
        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');

        $dateBeforeWeek = $todayDate->clone()->subDays(7)->format('Y-m-d H:i:s');

        $weekLabels = [];
        
        $fullViewDataTitle = [__('Full views')];
    
        $fullViewData = ['visitor_count' => 0];

        $weekStartDate = Carbon::parse($dateBeforeWeek);

        for($i=0;$i<=6;$i++) {
            $startDate = $weekStartDate->clone()->addDays($i)->format('Y-m-d H:i:s');
            $endDate = $weekStartDate->clone()->addDays($i + 1)->format('Y-m-d H:i:s');
            
            $videoVisitorFullViewQuery = VideoVisiter::selectRaw('count(*) as count')
                ->where('prospection_video_id', $request->prospectionVideoId)
                ->where('created_at', '>=', $startDate)->where('created_at', '<', $endDate)
                ->where('start_date', '!=', NULL)
                ->where('end_date', '!=', NULL);

            if($request->refferal_user_id != 0) {
                $videoVisitorFullViewQuery->where('referral_user_id', $request->refferal_user_id);
            } else {
                if(Auth::user()->id != $prospectionVideo->user_id) {
                    $videoVisitorFullViewQuery->where('referral_user_id', Auth::User()->id);
                }
            }

            $videoVisitorFullView = $videoVisitorFullViewQuery->first()->toArray();

            $temp['full_data_new'][$startDate] = $videoVisitorFullView['count'];

            $day = ucFirst( convertDateFormatWithTimezone($startDate,'Y-m-d H:i:s','D') );

            array_push($weekLabels,$day);
        }

        $fullViewData['full_view_data']['title'] = array_keys($temp['full_data_new']);
        $fullViewData['full_view_data']['count'] = array_values($temp['full_data_new']);
        $fullViewData['full_view_data']['label'] = $weekLabels;
        $fullViewData['full_view_data']['data_title'] = $fullViewDataTitle;

        return $fullViewData;
    }

    /**
     * Prospection partial view graph data
     *
     * @param Request $request
     */
    public function prospectionPartialViewGraphData(Request $request) 
    {
        $prospectionVideo = ProspectionVideo::find($request->prospectionVideoId);
        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');

        $dateBeforeWeek = $todayDate->clone()->subDays(7)->format('Y-m-d H:i:s');

        $weekLabels = [];

        $partialDataTitle = [__('Partial views')];

        $partialViewData = ['visitor_count' => 0];

        $weekStartDate = Carbon::parse($dateBeforeWeek);

        for($i=0;$i<=6;$i++) {
            $startDate = $weekStartDate->clone()->addDays($i)->format('Y-m-d H:i:s');
            $endDate = $weekStartDate->clone()->addDays($i + 1)->format('Y-m-d H:i:s');
            
            $videoVisitorPartialViewQuery = VideoVisiter::selectRaw('count(*) as count')
                ->where('prospection_video_id', $request->prospectionVideoId)
                ->where('created_at', '>=', $startDate)->where('created_at', '<', $endDate)
                ->where('start_date', '!=', NULL)
                ->where('end_date', '=', NULL);

            if($request->refferal_user_id != 0) {
                $videoVisitorPartialViewQuery->where('referral_user_id', $request->refferal_user_id);
            } else {
                if(Auth::user()->id != $prospectionVideo->user_id) {
                    $videoVisitorPartialViewQuery->where('referral_user_id', Auth::User()->id);
                }
            }

            $videoVisitorPartialView = $videoVisitorPartialViewQuery->first()->toArray();

            $temp['partial_data_new'][$startDate] = $videoVisitorPartialView['count'];

            $day = ucFirst( convertDateFormatWithTimezone($startDate,'Y-m-d H:i:s','D') );

            array_push($weekLabels,$day);
        }

        $partialViewData['partial_view_data']['title'] = array_keys($temp['partial_data_new']);
        $partialViewData['partial_view_data']['count'] = array_values($temp['partial_data_new']);
        $partialViewData['partial_view_data']['label'] = $weekLabels;
        $partialViewData['partial_view_data']['data_title'] = $partialDataTitle;

        return $partialViewData;
    }

    /**
     * Prospection not played graph data
     *
     * @param Request $request
     */
    public function prospectionNotPlayedGraphData(Request $request) 
    {
        $prospectionVideo = ProspectionVideo::find($request->prospectionVideoId);
        $todayDate = getCarbonTodayEndDateTimeForUser();
        $todayStartDateForFilter = $todayDate->clone()->subDays(1)->format('Y-m-d H:i:s');

        $dateBeforeWeek = $todayDate->clone()->subDays(7)->format('Y-m-d H:i:s');

        $weekLabels = [];

        $notPlayedDataTitle = [__('Not played')];
        
        $notPlayedData = ['visitor_count' => 0];

        $weekStartDate = Carbon::parse($dateBeforeWeek);

        for($i=0;$i<=6;$i++) {
            $startDate = $weekStartDate->clone()->addDays($i)->format('Y-m-d H:i:s');

            $endDate = $weekStartDate->clone()->addDays($i + 1)->format('Y-m-d H:i:s');
           
            $videoVisitorNotPlayedQuery = VideoVisiter::selectRaw('count(*) as count')
                ->where('prospection_video_id', $request->prospectionVideoId)
                ->where('created_at', '>=', $startDate)->where('created_at', '<', $endDate)
                ->where('start_date', '=', NULL)
                ->where('end_date', '=', NULL);

            if($request->refferal_user_id != 0) {
                $videoVisitorNotPlayedQuery->where('referral_user_id', $request->refferal_user_id);
            } else {
                if(Auth::user()->id != $prospectionVideo->user_id) {
                    $videoVisitorNotPlayedQuery->where('referral_user_id', Auth::User()->id);
                }
            }

            $videoVisitorNotPlayed = $videoVisitorNotPlayedQuery->first()->toArray();

            $temp['not_played_data_new'][$startDate] = $videoVisitorNotPlayed['count'];

            $day = ucFirst( convertDateFormatWithTimezone($startDate,'Y-m-d H:i:s','D') );

            array_push($weekLabels,$day);
        }

        $notPlayedData['not_played_data']['title'] = array_keys($temp['not_played_data_new']);
        $notPlayedData['not_played_data']['count'] = array_values($temp['not_played_data_new']);
        $notPlayedData['not_played_data']['label'] = $weekLabels;
        $notPlayedData['not_played_data']['data_title'] = $notPlayedDataTitle;

        return $notPlayedData;
    }

    /**
     * Analytics data view
     *
     * @param int $user_id
     * @param string $slug
     *
     * @return Response
     */
    public function analyticsProspectionVisitors($slug, $user_id)
    {
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);

        $videoVisitorsDataQuery = VideoVisiter::where('prospection_video_id', $prospectionVideo->id)->where('start_date', '!=', NULL)->orderBy('id', 'asc');

        if ($user_id == 0) {
            if (Auth::user()->id != $prospectionVideo->user_id) {
                $videoVisitorsDataQuery->where('referral_user_id', Auth::user()->id);
            }
        } else {
            $videoVisitorsDataQuery->where('referral_user_id', $user_id);
        }

        $videoVisitorsData = $videoVisitorsDataQuery->paginate(25);

        $videoReffralData = VideoVisiter::where(['prospection_video_id'=> $prospectionVideo->id])->pluck('referral_user_id')->toArray();

        $refferalUserName = User::whereIn('id', $videoReffralData)->pluck('name','id');

        $videoVisitors = [];

        foreach($videoVisitorsData as $videoVisitor) {
            $name = $email = $phone = null;
            if(isset($videoVisitor->user) && !empty($videoVisitor->user)) {
                $name = $videoVisitor->user->name;
                $email = $videoVisitor->user->email;
                $phone = $videoVisitor->user->phone;
            } else if(isset($videoVisitor->contact) && !empty($videoVisitor->contact)) {
                $name = $videoVisitor->contact->name;
                $email = $videoVisitor->contact->email;
                $phone = $videoVisitor->contact->phone;
            }

            if(isset($videoVisitor->first_name) && isset($videoVisitor->last_name) && !empty($videoVisitor->first_name) && !empty($videoVisitor->last_name)) {
                $name = $videoVisitor->first_name.' '.$videoVisitor->last_name;
                $email = $videoVisitor->email;
                $phone = $videoVisitor->phone;
            }
            $videoVisitors[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'end_date' => $videoVisitor->end_date,
                'start_date' => $videoVisitor->start_date,
                'time' => $videoVisitor->time,
            ];
        }

        $params = compact('prospectionVideo', 'videoVisitors', 'videoVisitorsData','refferalUserName');
        $view = view('seller.prospectionVideo.analytics_people_section', $params)->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ], 200);
    }

    /**
     * Get survey data
     *
     * @param int $id
     *
     * @return Response
     */
    public function getSurveyData($id) 
    {
        $surveyQuestionMaster = SurveyQuestion::where('survey_id', $id)->get();
        $questionAnswers = [];
        foreach ($surveyQuestionMaster as $surveyQuestion) {
            $questionMaster = SurveyQuestionMaster::where('id', $surveyQuestion->question_id)->first();
            $questionAnswers[] = [
                'survey_id' => $id,
                'title' => $questionMaster->title,
                'survey_question_master_id' => $questionMaster->id,
                'answer_text' => $surveyQuestion->answer_text,
            ];
        }
        $html = view('seller.common._update_survey', compact('questionAnswers','id'))->render();
        return response()->json([
            'success' => true,
            'data' => $html
        ], 200);
    }

    /**
     * Video visitors user stastics
     *
     * @param string $slug
     * @param int $userId
     *
     * @return Array
     */
    public function videoVisitorsUserStatistics($slug, $userId)
    {
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);
        $videoVisitorsQuery = VideoVisiter::select('user_id','contact_id')->where(['prospection_video_id' => $prospectionVideo->id]);
        $videoVisitorsLogQuery = VideoVisiterLog::select('user_id','contact_id')->where(['prospection_video_id' => $prospectionVideo->id]);
        $videoVisitorsContactjoin = VideoVisiter::join('contact_logs','contact_logs.contact_id', '=', 'video_visiters.contact_id')
            ->where('video_visiters.start_date', '!=', NULL)
            ->where('video_visiters.contact_id', '!=', NULL)
            ->where(['video_visiters.prospection_video_id' => $prospectionVideo->id]);
        $videoVisitorsContactDistributorjoin = VideoVisiter::join('contact_logs','contact_logs.contact_id', '=', 'video_visiters.contact_id')
            ->where('video_visiters.start_date', '!=', NULL)
            ->where('video_visiters.contact_id', '!=', NULL)
            ->where(['video_visiters.prospection_video_id' => $prospectionVideo->id]);
            
        if($userId == 0) {
            if(Auth::user()->id != $prospectionVideo->user_id) {
                $refferalUserId = Auth::user()->id;
            }
        } else {
            $refferalUserId = $userId;
        }

        if(isset($refferalUserId)) {
            $videoVisitorsQuery->where('referral_user_id', $refferalUserId);
            $videoVisitorsLogQuery->where('referral_user_id', $refferalUserId);
            $videoVisitorsContactjoin->where('video_visiters.referral_user_id', $refferalUserId);
            $videoVisitorsContactDistributorjoin->where('video_visiters.referral_user_id', $refferalUserId);
        }

        $videoVisitorsMainUserCount = $videoVisitorsQuery->count();
        $videoVisitorsLogMainUserCount = $videoVisitorsLogQuery->count();

        $videoVisitorsUserPlayPercent = $videoVisitorsUserPlayFullyPercent = $videoVisitorsUserConvertionRatePercentForNewClient = $videoVisitorsUserConvertionRatePercentForDistributor = 0;

        $videoVisitorsUserPlayCount = $videoVisitorsQuery->where('start_date', '!=', NULL)->count();
        if($videoVisitorsUserPlayCount > 0 && $videoVisitorsMainUserCount > 0) {
            $videoVisitorsUserPlayPercent = round(100 * $videoVisitorsUserPlayCount / $videoVisitorsMainUserCount);
        }

        $videoVisitorsUserPlayFullyCount = $videoVisitorsQuery->where('start_date', '!=', NULL)->where('end_date', '!=', NULL)->count();
        if($videoVisitorsUserPlayFullyCount > 0 && $videoVisitorsMainUserCount > 0) {
            $videoVisitorsUserPlayFullyPercent = round(100 * $videoVisitorsUserPlayFullyCount / $videoVisitorsMainUserCount);
        }

        $videoVisitorsUserConvertionRateCountForNewClient = $videoVisitorsContactjoin
            ->whereIn('contact_logs.status', [ContactBoardStatus::NEW_CLIENT])
            ->select('video_visiters.contact_id', 'contact_logs.status')
            ->groupBy('video_visiters.contact_id')
            ->count();

        if($videoVisitorsUserConvertionRateCountForNewClient > 0 && $videoVisitorsMainUserCount > 0 )  {
            $videoVisitorsUserConvertionRatePercentForNewClient = round(100 * $videoVisitorsUserConvertionRateCountForNewClient / $videoVisitorsMainUserCount, 2);
        }

        $videoVisitorsUserConvertionRateCountForDistributor = $videoVisitorsContactDistributorjoin
            ->whereIn('contact_logs.status', [ContactBoardStatus::NEW_DISTRIBUTOR])
            ->select('video_visiters.contact_id', 'contact_logs.status')
            ->groupBy('video_visiters.contact_id')
            ->count();

        if($videoVisitorsUserConvertionRateCountForDistributor > 0 && $videoVisitorsMainUserCount > 0 )  {
            $videoVisitorsUserConvertionRatePercentForDistributor = round(100 * $videoVisitorsUserConvertionRateCountForDistributor / $videoVisitorsMainUserCount, 2);
        }
        
        $videoVisitorsUserStatistics = [
            'NumberofPlays' => $videoVisitorsUserPlayCount,
            'PercentageofPlayRate' => $videoVisitorsUserPlayPercent,
            'PercentageofEngagement' => $videoVisitorsUserPlayFullyPercent,
            'NumberofTimePlayed' => $videoVisitorsLogMainUserCount,
            'NumberofUniqueVisitors' => $videoVisitorsMainUserCount,
            'NumberofConvertionRateForNewClient' => $videoVisitorsUserConvertionRatePercentForNewClient,
            'NumberofConvertionRateForDistributor' => $videoVisitorsUserConvertionRatePercentForDistributor
        ];

        $params = compact('videoVisitorsUserStatistics');
        $view = view('seller.prospectionVideo.analytics_count_section', $params)->render();
        return response()->json([
            'success' => true,
            'html' => $view
        ], 200);
    }
}