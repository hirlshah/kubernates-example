<div class="modal fade" id="add_survey_modal" tabindex="-1" role="dialog" aria-labelledby="add_survey_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-calendar blue me-3"></i>
                    <h6>{{__('Create a Survey')}}</h6>
                </div>
                <form id="add_survey_form" action="{{route('survey.store')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 col-12">
{{--                            <div class="form-group mb-4">--}}
{{--                                <label for="" class="form-label">{{__('Survey name')}}</label>--}}
{{--                                <input type="text" name="name" class="form-control ps-0"--}}
{{--                                       placeholder="{{__('type here')}}">--}}
{{--                                <span class="text-danger print-error-msg-name" style="display:none"></span>--}}
{{--                            </div>--}}
                            <div id="survey_copy_div" class="d-none">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        {{__('Question')}}
                                        {!! Form::text('survey_questions[##][questions]',null,['class'=>'form-control border br-10px']) !!}
                                        <span class="text-danger print-error-msg-survey_questions" style="display:none"></span>
                                    </div>
                                    <div class="col-md-4">
                                        {{__('Answer')}}
                                        {!! Form::textarea('survey_questions[##][answers_text]',null,['class'=>'form-control border br-10px', 'rows' => 2, 'cols' => 40, 'placeholder' => __('Answer here')]) !!}
                                        {!! Form::hidden('survey_questions[##][answers][]',  \App\Models\SurveyAnswerMaster::getTextAnswerId()) !!}
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-inline">
                                            {{__('With Comment ?')}}
                                            {!! Form::checkbox('survey_questions[##][with_comment]', '1', false, ['class' => 'd-block m-2']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:;" id="remove_question_row"><i class="feather-x red"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div id="survey_target_div" class="ps-3">
                                {{--<div class="row mb-3">
                                    <div class="col-md-4">
                                        {{__('Question')}}
                                    </div>
                                    <div class="col-md-4">
                                       {{__('Multiple Answers')}}
                                    </div>
                                    <div class="col-md-3">
                                        {{__('Can Comment')}}
                                    </div>
                                    <div class="col-md-1">
                                        {{__('Remove')}}
                                    </div>
                                </div>--}}
                            </div>

                            <div class="row ps-3">
                                <a href="javascript:;" id="add_question_row" class="col-md-4"><i class="feather-plus blue"></i> {{__('Add new')}}</a>
                            </div>

                            <div class="mb-3 mt-3">
                                <button type="submit" class="btn btn-blue-gradient" href="">{{__('Add a survey')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    let ratingQuestions = {!! json_encode(\App\Models\SurveyQuestionMaster::getRatingQuestionIds()) !!}
</script>