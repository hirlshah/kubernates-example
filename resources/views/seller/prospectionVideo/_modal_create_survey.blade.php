<div class="modal fade" id="prospection_add_survey_modal" tabindex="-1" role="dialog" aria-labelledby="prospection_add_survey_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="video-survey">
                    <div class="d-flex align-items-center mb-4">
                        <i class="feather-calendar blue me-3"></i>
                        <h6 class="survey-text">{{__('Create a Survey')}}</h6>
                    </div>
                    <form id="prospection_add_survey_form" action="{{route('survey.store')}}" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div id="survey_copy_div" class="d-none">
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
                                            <span class="text-danger print-error-msg-survey_questions" style="display:none"></span>
                                        </div>
                                        <div class="col-md-6">
                                            {{__('Answer')}}
                                            {!! Form::textarea('survey_questions[##][answers_text]',null,['class'=>'form-control border br-10px survey_answers_text', 'rows' => 2, 'cols' => 40, 'placeholder' => __('Answer here')]) !!}
                                            {!! Form::hidden('survey_questions[##][answers][]',  \App\Models\SurveyAnswerMaster::getTextAnswerId()) !!}
                                        </div>
                                    </div>
                                </div>

                                <div id="survey_target_div" class="ps-3"></div>
                                <!-- <span class="text-danger print-error-msg-survey_questions" style="display:none"></span> -->

                                <div class="row ps-3">
                                    <a href="javascript:;" id="prospection_add_question_row" class="col-md-4"><i class="feather-plus blue"></i> {{__('Add new')}}</a>
                                </div>

                                <div class="mb-3 mt-3">
                                    <button type="submit" class="btn btn-blue-gradient update-survey" href="">{{__('Add a survey')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let ratingQuestions = {!! json_encode(\App\Models\SurveyQuestionMaster::getRatingQuestionIds()) !!}
</script>