<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Board;
use App\Models\ProspectionVideo;
use App\Http\Requests\ProspectionVideoVisiterRequest;
use App\Models\ProspectionVideoSurvey;
use App\Models\UserEventSurvey;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\VideoVisiter;
use App\Classes\Helper\CommonUtil;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Enums\ContactBoardStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use MetaTag;
use App\Models\Survey;
use App\Models\VideoVisiterLog;
use App\Models\SurveyQuestionMaster;
use App\Models\BoardContact;

class ProspectionVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @param Request $request
     *
     * @return Application|Factory|View|Response
     */
    public function index($slug, Request $request)
    {
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);
        $referralUser = User::where(['referral_code' =>  $request->referral])->first();
        if(empty($prospectionVideo->title)) {
            return abort(404);
        }
        return view('frontend.prospection.index', compact('slug','prospectionVideo', 'referralUser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProspectionVideoVisiterRequest $request
     *
     * @return JsonResponse
     */
    public function store(ProspectionVideoVisiterRequest $request)
    {
        $data = $request->all();
        // TODO: IT ADD PLUS SIGN WITH COUNTRY CODE IN PHONE NUMBER.
        $data['phone'] = '+'.$data['country_code'].$data['phone']; 
        // TODO: IT JUST IF AUTH USER ARE NOT ENTER THEY NUMBER.
        if($data['phone'] == User::USER_PHONE_CHECK) {
            unset($data['phone']);
        }
        $visiterProspectionVideo = ProspectionVideo::where('slug', $request->slug)->first();
        $referralUser = User::select('id')->where(['referral_code' => $request->referral])->first();
        if (!empty($visiterProspectionVideo) && !empty($referralUser)) {
            $visiterUser = User::where('email', $request->email)->first();
            if(!empty($visiterUser)) {
                if($visiterUser->id !== $referralUser->id) {
                    $videoVisiter = VideoVisiter::updateOrCreate(
                        ['prospection_video_id' => $visiterProspectionVideo->id, 'user_id' => $visiterUser->id],
                        ['user_id' => $visiterUser->id, 'referral_user_id' => $referralUser->id, 'first_name' => $data['first_name'], 'phone' => $data['phone'], 'last_name' => $data['last_name'], 'email' => $data['email']]
                    );
                    VideoVisiterLog::Create(
                        [
                            'prospection_video_id' => $visiterProspectionVideo->id,
                            'user_id' => $visiterUser->id, 
                            'referral_user_id' => $referralUser->id, 
                            'first_name' => $data['first_name'],
                            'phone' => $data['phone'], 
                            'last_name' => $data['last_name'], 
                            'email' => $data['email']
                        ]
                    );

                }
            } else {
                $visitorContact = Contact::select('id')->where(['email' => $request->email, 'user_id' => $referralUser->id])->first();
                if(empty($visitorContact)) {
                    $visitorContact = Contact::create([
                        'user_id' => $referralUser->id,
                        'name' => $request->first_name,
                        'email' => $request->email,
                        'phone' => $request->phone
                    ]);

                    if (!$referralUser->board) {
                        Board::create([
                            'user_id' => $referralUser->id,
                            'is_current' => 1,
                        ]);
                        $referralUser->refresh();
                    }
                    $board = $referralUser->board;
                    $contactIds = Contact::where(['user_id' => $referralUser->id])->pluck('id')->toArray();

                    if (isset($contactIds)) {
                        $counter = 2;
                        foreach ($contactIds as $contactId) {
                            Contact::where(['id' => $contactId])->update(['order' => $counter]);
                            $counter++;
                        }
                    }

                    BoardContact::updateOrCreate([
                        'board_id' => $board->id,
                        'contact_id' => $visitorContact->id,
                    ], [
                        'status' => ContactBoardStatus::ATTENDED_THE_ZOOM,
                    ]);

                    for($i=0;$i<=ContactBoardStatus::ATTENDED_THE_ZOOM;$i++) {
                        ContactLog::createLog($visitorContact->id, $i, $visitorContact->user_id);
                    }
                }
                $videoVisiter = VideoVisiter::updateOrCreate(
                    ['prospection_video_id' => $visiterProspectionVideo->id, 'contact_id' => $visitorContact->id],
                    ['contact_id' => $visitorContact->id, 'referral_user_id' => $referralUser->id, 'first_name' => $data['first_name'], 'phone' => $data['phone'], 'last_name' => $data['last_name'], 'email' => $data['email']]
                );

                VideoVisiterLog::Create(
                    [
                        'prospection_video_id' => $visiterProspectionVideo->id,
                        'contact_id' => $visitorContact->id,
                        'referral_user_id' => $referralUser->id, 
                        'first_name' => $data['first_name'],
                        'phone' => $data['phone'], 
                        'last_name' => $data['last_name'], 
                        'email' => $data['email']
                    ]
                );
            }

            $videoVisitedId = '';
            if(!empty($videoVisiter)) {
                $videoVisitedId = $videoVisiter->id;
            }

            if (!empty($visiterProspectionVideo->video)) {
                $view = view('frontend.prospection.visiter_video_modal', compact('visiterProspectionVideo', 'videoVisitedId'))->render();
                $userData = [
                    'id' => !empty($visiterUser) ? $visiterUser->id : null,
                    'name' => !empty($visiterUser) ? $visiterUser->name : $data['first_name'].' '.$data['last_name'],
                    'email' => !empty($visiterUser) ? $visiterUser->email : $data['email'],
                    'phone' => !empty($visiterUser) ? $visiterUser->phone : $data['phone'],
                ];
                return response()->json([
                    'success' => true,
                    'content' => $view ,
                    'user_data' => $userData
                ]);
            } else {
                return response()->json([ 'success' => false ]);
            }
        } else {
            return response()->json([ 'success' => false]);
        }
    }

    /**
     * Video play end type
     *
     * @param Request $request
     */
    public function sendVideoVisiterMail(Request $request)
    {
        $referralUser = User::where(['referral_code' => $request->referral])->first();
        if($request->type == "play") {
            return $this->mailSend($request->type,$request->visited_video_id,$request->user_data, $referralUser, $request->current_date_time, $request->referral,$request->time);
        } else if($request->type == "pause") {
            return $this->mailSend($request->type,$request->visited_video_id,$request->user_data, $referralUser, $request->current_date_time, $request->referral,$request->time);
        } else {
            return $this->mailSend($request->type,$request->visited_video_id,$request->user_data, $referralUser, $request->current_date_time, $request->referral,$request->time);
        }
    }

    /**
     * Mail send prospection video
     *
     *
     * @param $type
     * @param $videoVisiterId
     * @param $user_data
     * @param $referralUser
    *  @param $referralCode
    *  @param $time
     *
     * @return JsonResponse
     */
    public function mailSend($type, $videoVisiterId, $user_data, $referralUser, $currentDateTime, $referralCode , $time)
    {
        $currentDateTime = convertDateFormatWithTimezone($currentDateTime, 'd/m/Y H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $videoVisiterTable = VideoVisiter::where('id', $videoVisiterId)->first();
        $videpProspection = $videoVisiterTable->video ?? NULL;
        $url = false;
        if(isset($videpProspection) && isset($videpProspection->user) && !empty($videpProspection->user)) {
            $userData = json_decode($user_data);
            $visiterUser = User::where('email', $userData->email)->first();
            $videoVisiterLogTable = VideoVisiterLog::where(['prospection_video_id'=> $videpProspection->id, 'referral_user_id' => $referralUser->id])->latest()->first();
            if(empty($visiterUser)) {
                $visiterUser = Contact::where(['email' => $userData->email, 'user_id' => $referralUser->id])->first();
            }

            $video_visiters = [];
            $video_visiters_datas = VideoVisiter::where(['prospection_video_id'=> $videpProspection->id, 'referral_user_id' => $referralUser->id])->orderBy('id','desc')->take(5)->get();
            $data = [
                'video_user' => $referralUser,
                'title' => $videpProspection->title ?? Null,
                'description' => $videpProspection->description ?? Null,
                'category' =>  $videpProspection->category->name ?? Null,
                'video' => CommonUtil::getUrl($videpProspection->video),
                'watching_user_name' => $userData->name ? $userData->name : (isset($visiterUser) && !empty($visiterUser) ? $visiterUser->name : ''),
                'watching_user_phone' => $userData->phone ? $userData->phone : (isset($visiterUser) && !empty($visiterUser) ? $visiterUser->phone : '') ,
                'watching_date' => isset($visiterUser) && !empty($visiterUser) ? (app()->getLocale() == "fr" ? convertDateFormatWithTimezone($visiterUser->created_at, 'Y-m-d H:i:s','l, dS F , H:i') : convertDateFormatWithTimezone($visiterUser->created_at, 'Y-m-d H:i:s','l, F dS , H:i')) : NULL,
                'video_cover_image'     => isset($videpProspection->video_cover_image) && !empty($videpProspection->video_cover_image) ? CommonUtil::getUrl($videpProspection->video_cover_image) : asset((config('app.rankup.company_thumbnail_path')))
            ];

            $isSendNoitifaction = true;
            if($type == "play") {
                $view = 'email.video_play_visiter';
                $subject = __('Your video was opened');
                if($videoVisiterTable->start_date) {
                    $isSendNoitifaction = false;
                } else {
                    $videoVisiterTable->start_date = $currentDateTime;
                    $videoVisiterLogTable->start_date = $currentDateTime;
                }
                $videoVisiterLogTable->time = $time;
                $videoVisiterTable->time = $time;
            } else if($type == "pause") {
                $videoVisiterTable->time = $time;
                $videoVisiterLogTable->time = $time;
            } else {
                $view = 'email.video_end_visiter';
                $subject = __('Your video was seen');
                if(!empty($videpProspection->survey_id)) {
                    $url = route('frontend.prospection.survey', [$videpProspection->slug, $videoVisiterTable->id, $referralCode]);
                }
                if($videoVisiterTable->end_date) {
                    $isSendNoitifaction = false;
                } else {
                    $videoVisiterTable->end_date = $currentDateTime;
                    $videoVisiterLogTable->end_date = $currentDateTime;
                }
                $videoVisiterTable->time = $time;
                $videoVisiterLogTable->time = $time;
            }
            $videoVisiterTable->save();
            $videoVisiterLogTable->save();

            if($isSendNoitifaction && $type != 'pause') {
                Mail::send($view, $data, function ($message) use ($referralUser, $subject) {
                    $message->to($referralUser->email);
                    $message->subject($subject);
                });
            }
        }

        return response()->json([
            'success' => true,
            'redirect_url' => $url
        ]);
    }

    /**
     * get a prospection video survey.
     *
     * @param string $slug
     *
     * @return Application|Factory|View|Response
     */
    public function survey(string $slug, $video_visiters_table_id, $referralCode)
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Prospection Survey'));
        MetaTag::set('description', config('app.rankup.company_title').' Prospection Survey Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);
		$survey = Survey::where(['id' => $prospectionVideo->survey_id])->first();
        return view('frontend.prospection.create_survey', compact('slug', 'survey','video_visiters_table_id', 'prospectionVideo','referralCode'));
    }

    /**
     * Add a prospection video survey.
     *
     * @param Request $request
     * @param string $slug
     *
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function saveProspectionSurvey(Request $request, $slug)
    {
        $prospectionVideo = ProspectionVideo::findBySlugOrFail($slug);
        $data = $request->all();
        $video_visiter_table =  VideoVisiter::find($request->video_visiters_table_id);
        $html = '';
        $i = 1;
        if(isset($data['answer_ids'])) {
            foreach($data['answer_ids'] as $key => $answer) {
                $prospectionVideoSurvey = ProspectionVideoSurvey::create([
                    'prospection_video_id' => $prospectionVideo->id,
                    'survey_id' => $prospectionVideo->survey_id,
                    'contact_id' => $video_visiter_table->contact_id ?? Null,
                    'user_id' => $video_visiter_table->user_id ?? NUll,
                    'question_id' => $answer['question'],
                    'answer_ids' => $answer['answer'] ?? NULL,
                    'answer_text' => $answer['answers_text'] ?? NULL,
                    'comment' => $answer['comment'] ?? NULL
                ]);

                // DON'T CHANGE ANYTHING CODE IN LINE 376 and 385.
                if(!empty($video_visiter_table->contact_id)) {
                    $question = SurveyQuestionMaster::find($answer['question']);
                    $html .= '
                    '.$i.') '.$question->title.'
                    '.$prospectionVideoSurvey->answer_text.'
                    '.$prospectionVideoSurvey->comment.'

                    ';
                }
                $i++;
            }
        }
        if(!empty($video_visiter_table->contact_id)) {
            $contactUpdate = Contact::find($video_visiter_table->contact_id);
            $contactUpdate->message = isset($html) && !empty($html) ? $html : null;
            $contactUpdate->update();
        }

        $savedSurvey = Session::get('saved_survey', []);
        $savedSurvey[] = $prospectionVideo->id;
        Session::put('saved_survey', $savedSurvey);
        return redirect (route('frontend.prospection.survey.thankyou', [ 'referral' => $request->referral, 'slug' => $slug ]));
    }

    /**
     * Prospection survey thank you
     *
     * @param Request $request
     *
     * @return Application|Factory|View|Response
     */
    public function prospectionSurveyThankyou(Request $request) 
    {
        $referralUser = User::where(['referral_code' =>  $request->referral])->first();
        return view('frontend.prospection.survey_thankyou',compact('referralUser'));
    }
}