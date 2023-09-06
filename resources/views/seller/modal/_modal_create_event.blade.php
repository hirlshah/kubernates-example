<div class="modal fade" id="add_event_modal" tabindex="-1" role="dialog" aria-labelledby="add_event_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4 p-sm-5 scroll-width">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-calendar blue me-3"></i>
                    <h6 class="event_modal_title">{{__('Create an Event')}}</h6>
                </div>
                <form id="add_event_form" action="{{route('seller.event.store')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" id="event_method" name="_method" value="POST">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <img id="new_event_image_preview" class="img-fluid mb-4 br-20px" src="{{ asset((config('app.rankup.company_thumbnail_path'))) }}" alt="" />
                            <a class="btn btn-outline-white w-100 br-20px mb-4" id="new_event_image_trigger" href="javascript:;">+ {{__('Event Photo')}}</a>
                            <span class="text-danger print-error-msg-image" style="display:none"></span>
                            <input id='new_event_image' type='file' name="image" hidden/>
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Event name')}}</label>
                                <input type="text" name="name" class="form-control ps-0" id="event_name" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-name" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <textarea class="form-control border br-10px" name="content_message" rows="3" id="event_content" placeholder="{{__('Description')}}"></textarea>
                                <span class="text-danger print-error-msg-content_message" style="display:none"></span>
                            </div>
                            <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Subject')}}</label>
                                {{--{!! Form::select('tags[]', \App\Models\Tag::getOptions(), NULL, ['class'=>'form-control select2-dynamic', 'multiple'=>true]) !!}--}}
                                <input type="text" name="tags[0]" class="form-control ps-0 d_tag" id="d_tag_0"
                                placeholder="">
                                <input type="text" name="tags[1]" class="form-control ps-0 d_tag" id="d_tag_1"
                                placeholder="" style="display: none">
                                <input type="text" name="tags[2]" class="form-control ps-0 d_tag" id="d_tag_2"
                                placeholder="" style="display: none">
                                <span class="text-danger print-error-msg-tags" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;" id="new_tag_button">{{__('Add a Tag')}}</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <h6 class="mb-3">{{__('Meeting proposal')}}</h6>
                            <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Date')}}</label>
                                <input type="text" name="meeting_date" class="form-control text-uppercase ps-0" id="event_meeting_date" placeholder="{{__('MM/DD/YYYY')}}" autocomplete="off">
                                <span class="text-danger print-error-msg-meeting_date" style="display:none"></span>
                            </div>
                            {{--<div class="mb-4">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;">{{__('See your calendar')}}</a>
                            </div>--}}
                            <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Hour')}}</label>
                                <input type="text" name="meeting_time" class="form-control text-uppercase ps-0" placeholder="{{__('HH:MM')}}" id="event_meeting_time" autocomplete="off" readonly>
                                <span class="text-danger print-error-msg-meeting_time" style="display:none"></span>
                            </div>
                            <div class="form-group date mb-4">
                                {!! Form::select('timezone', getTimeZoneiList(), null, ['class'=>'form-control', 'placeholder' => __('Select timezone')]) !!}
                            </div>
                            <h6 class="mb-4">{{__('Meeting room')}}</h6>
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Meeting link')}}</label>
                                <input type="text" name="meeting_url" class="form-control ps-0" id="meeting_url" placeholder="{{__('https://www.yourlink.com')}}"
                                value="">
                                <span class="text-danger print-error-msg-meeting_url" style="display:none"></span>
                            </div>
                             <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Presenter')}}</label>
                                <input type="hidden" name="presentator_id" id="presentator_id">
                                <input name="presentator_name" value="" readonly id="presentator_name" class="form-control ps-0 mb-2">
                                <a class="btn btn-outline-white w-100 br-20px users_modal_btn" href="javascript:;">+ {{__('Add presentator')}}</a>
                            </div>
                            <div class="form-group mb-3">
                                <label for="" class="form-label">{{__('Survey')}}</label>
                                {!! Form::select('survey_id', [], null, ['class'=>'form-control', 'id'=>'event_survey_list']) !!}
                                <input type="hidden" name="hidden_survey_id" id="event_survey_id" value="">
                                <span class="form-control ps-0 py-3 border-dashed text-center br-10px" id="no-survey-span">
                                    {{__('No survey added.')}}
                                </span>
                                <span class="text-danger print-error-msg-survey_id" style="display:none"></span>
                            </div>
                            <div class="mb-3">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;" id="new_survey_button">{{__('Add a survey')}}</a>
                            </div>
                            <div class="mb-3">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;" id="add_media_button">{{__('Add media')}} <span id="media_count"></span></a>
                                <input type="hidden" name="event_documents" id="event_documents" value="">
                                <input type="hidden" name="event_videos" id="event_videos" value="">
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-blue-gradient w-100 br-20px" type="submit" id="launchEventBtn">{{__('Save changes')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
