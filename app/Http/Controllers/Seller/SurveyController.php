<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use App\Models\SurveyAnswerMaster;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionMaster;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class SurveyController extends Controller
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
     * Show the form for creating a new resource.
     */
    public function create() 
    {
        return View::make('seller.survey._modal_create_survey');
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param SurveyRequest $request
     *
     * @return jsonResponse
     */
    public function store(SurveyRequest $request) 
    { 
        $survey = Survey::create([]);
        
        $ratingQuestions = SurveyQuestionMaster::getRatingQuestionIds();
        $ratingAnswers = SurveyAnswerMaster::where('type', 'rating')->orderBy('answer', 'asc')->pluck('id')->toArray();
        $ratingAnswers = implode(',', $ratingAnswers);

        foreach($request->survey_questions as $sq) {
            $surveyQuestionMaster = new SurveyQuestionMaster();
            $surveyQuestionMaster->title = $sq['questions'];
            $surveyQuestionMaster->save();

            if(in_array($sq['questions'], $ratingQuestions)){
                $answer = $ratingAnswers;
            } else {
                $answer = !empty($sq['answers'])? implode(",", $sq['answers']) : NULL;
            }
            $surveyQuestion = new SurveyQuestion();
            $surveyQuestion->survey_id = $survey->id;
            $surveyQuestion->question_id = $surveyQuestionMaster->id;
            $surveyQuestion->answers_ids = $answer;
            $surveyQuestion->with_comment = isset($sq['with_comment']) ? 1 : 0;
            $surveyQuestion->answer_text = $sq['answers_text'];
            $surveyQuestion->save();
        }

        return response()->json([
            'success' => true,
            'data'=> $survey
        ], 200);
    }

    /**
     * Get survey list
     */
    public function getList() 
    {
        return Survey::getOptions();
    }

    /**
     * Update survey
     *
     * @param Request $request
     *
     * @return jsonResponse
     */
    public function update(Request $request) 
    {
        $ratingQuestions = SurveyQuestionMaster::getRatingQuestionIds();
        $ratingAnswers = SurveyAnswerMaster::where('type', 'rating')->orderBy('answer', 'asc')->pluck('id')->toArray();
        $ratingAnswers = implode(',', $ratingAnswers);
        foreach($request->survey_questions as $key => $surveyQuestionId) {
            if($key != '##') {
                if(in_array($surveyQuestionId['questions'], $ratingQuestions)){
                    $answer = $ratingAnswers;
                } else {
                    $answer = !empty($surveyQuestionId['answers'])? implode(",", $surveyQuestionId['answers']) : NULL;
                }
                if(isset($request->survey_id)) {
                    if(isset($surveyQuestionId['id']) && !empty($surveyQuestionId['id'])) {
                        $surveyQuestionMasters = SurveyQuestionMaster::where(['id' => $surveyQuestionId['id']])->get();
                        foreach($surveyQuestionMasters as $surveyQuestionMaster) {
                            $surveyQuestionMaster->title = $surveyQuestionId['questions'];
                            $surveyQuestionMaster->update();   
                            
                            $surveyQuestion = SurveyQuestion::where(['survey_id' => $request->survey_id,'question_id' => $surveyQuestionMaster->id ])->first();
                            $surveyQuestion->survey_id = $request->survey_id;
                            $surveyQuestion->question_id = $surveyQuestionMaster->id;
                            $surveyQuestion->answers_ids = $answer;
                            $surveyQuestion->answer_text = $surveyQuestionId['answer_text'];
                            $surveyQuestion->update();
                        }
                    } else {
                        $surveyQuestionMaster = new SurveyQuestionMaster();
                        $surveyQuestionMaster->title = $surveyQuestionId['questions'];
                        $surveyQuestionMaster->save();

                        $surveyQuestion = new SurveyQuestion();
                        $surveyQuestion->survey_id = $request->survey_id;
                        $surveyQuestion->question_id = $surveyQuestionMaster->id;
                        $surveyQuestion->answers_ids = $answer;
                        $surveyQuestion->answer_text = $surveyQuestionId['answers_text'];
                        $surveyQuestion->save();
                    }
                }
            }
        }
        return response()->json([
            'success' => true,
            'data'=> $request->survey_id
        ], 200);
    }
}