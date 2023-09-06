<div class="d-flex align-items-center mb-4">
    <i class="feather-calendar blue me-3"></i>
    <h6 class="survey-text">{{__('Update survey')}}</h6>
</div>
<form id="prospection_add_survey_form" action="{{route('prospection.survey.update')}}" method="post" enctype="multipart/form-data" class="update-question-form">
    @csrf
    <input type="hidden" name="survey_id" value="{{ $id }}">
    <div class="row">
        <div class="col-md-12 col-12">
            <div id="survey_copy_div">
                @foreach($questionAnswers as $key => $questionAnswer)
                    <div class="row mb-3 gy-2">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{__('Question')}}
                                </div>
                                <div>
                                    <a href="javascript:;" class="prospection_remove_question_row"><i class="feather-x red"></i></a>
                                </div>
                            </div>
                            <input type="hidden" name="survey_questions[{{$key}}][id]" value="{{ $questionAnswer['survey_question_master_id'] }}">
                            <input type="text" placeholder = "{{__('Question')}}" class="form-control border br-10px" name="survey_questions[{{$key}}][questions]" value="{{ $questionAnswer['title'] }}">
                        </div>
                        <div class="col-md-6">
                            {{__('Answer')}}
                            <textarea class="form-control border br-10px" name="survey_questions[{{$key}}][answer_text]" placeholder="{{__('Answer here')}}" rows ="2" cols = "40">{{ $questionAnswer['answer_text'] }}</textarea>
                            <input type="hidden" placeholder = "{{__('Answer here')}}" class="form-control border br-10px" name="survey_questions[{{$key}}][answers][]" value="{{ \App\Models\SurveyAnswerMaster::getTextAnswerId() }}">
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="survey_clone_div" class="d-none">
                <div class="row mb-3 gy-2">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between">
                            <div>
                                {{__('Question')}}
                            </div>
                            <div>
                                <a href="javascript:;" class="prospection_remove_question_row"><i class="feather-x red"></i></a>
                            </div>
                        </div>
                            {!! Form::text('survey_questions[##][questions]',null,['class'=>'form-control border br-10px survey_questions','placeholder' => __('Question')]) !!}
                    </div>
                    <div class="col-md-6">
                        {{__('Answer')}}
                        {!! Form::textarea('survey_questions[##][answers_text]',null,['class'=>'form-control border br-10px survey_answers_text', 'rows' => 2, 'cols' => 40, 'placeholder' => __('Answer here')]) !!}
                        {!! Form::hidden('survey_questions[##][answers][]',  \App\Models\SurveyAnswerMaster::getTextAnswerId()) !!}
                    </div>
                </div>
            </div>
            <div id="survey_target_div" class="ps-3"></div>
            <span class="text-danger print-error-msg-survey_questions" style="display:none"></span>

            <div class="row ps-3">
                <a href="javascript:;" id="prospection_update_question_row" class="col-md-4"><i class="feather-plus blue"></i> {{__('Add new')}}</a>
            </div>

            <div class="mb-3 mt-3">
                <button type="submit" class="btn btn-blue-gradient update-survey" href="" data-survey-id="">{{__('Update survey')}}</button>
            </div>
        </div>
    </div>
</form>
