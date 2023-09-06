<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use MetaTag;
use App\Models\Event;
use App\Models\Survey;
use App\Models\UserEventSurvey;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UserEventSurveyRequest;

class SurveyController extends Controller
{
	/**
	 * Display a survey of page.
	 *
	 * @param string $slug
	 *
	 * @return View
	 */
	public function survey(string $slug) 
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Survey'));
        MetaTag::set('description', config('app.rankup.company_title').' Survey Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
		$event = Event::findBySlugOrFail($slug);
		$survey = Survey::where(['id' => $event->survey_id])->first();
		$savedSurvey = Session::get('saved_survey', []);
		$alreadyFilled = in_array($event->id, $savedSurvey);
		$eventId = $event->id;
		$events = Event::where('slug','!=',$slug)->orderBy('meeting_date','DESC')->limit(3)->get();
        $contact_id = Cookie::get('contact_id') ?? null;
		return view('frontend.survey', compact('events', 'eventId', 'slug','survey', 'alreadyFilled','contact_id'));
	}

    /**
     * Store User survey
     * 
     * @param UserEventSurveyRequest $request
     * @param string $slug
     *
     * @return JsonResponse
     */
	public function saveSurvey(UserEventSurveyRequest $request, string $slug) 
    {
        $event = Event::findBySlugOrFail($slug);
        $data = $request->all();
		foreach($data['answer_ids'] as $answer){
            UserEventSurvey::create([
                'event_id' => $event->id,
                'survey_id' => $event->survey_id,
                'contact_id' => $request->contact_id,
                'user_id' => Auth::guest()? NULL : Auth::id(),
                'question_id' => $answer['question'],
                'answer_ids' => $answer['answer'] ?? NULL,
                'answer_text' => $answer['answers_text'] ?? NULL,
                'comment' => $answer['comment'] ?? NULL
            ]);
        }
        $savedSurvey = Session::get('saved_survey', []);
        $savedSurvey[] = $event->id;
        Session::put('saved_survey', $savedSurvey);
        Session::flash('success', __('Thank you for filling out survey!'));

        return response()->json([
            'success' => true,
            'redirect_url' => route('frontend.survey', ['slug' => $slug])
        ], 200);
	}
}