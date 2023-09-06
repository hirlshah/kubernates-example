<div class="modal fade" id="add_prospection_video_modal" tabindex="-1" role="dialog" aria-labelledby="add_document_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-3 p-sm-4">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title"></h6>
                </div>
                <form class="mb-0" id="add_prospection_video_form" action="{{route('prospection.store')}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="prospection_video_method" name="_method" value="POST">
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('Video title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control ps-0" id="prospection_video_title" placeholder="{{__('Type here')}}">
                                <span class="text-danger print-error-msg-title" style="display:none"></span>
                            </div>
                            <div class="form-group mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label class="form-label mb-0">{{__('Custom title')}}<span class="text-danger">*</span></label>
                                    <span class="fs-10">{{__('(What title do you want others to see?)')}}</span>
                                </div>
                                <input type="text" name="custom_title" class="form-control ps-0" id="prospection_video_custom_title" placeholder="{{__('Type here')}}">
                                <span class="text-danger print-error-msg-custom_title" style="display:none"></span>
                            </div>
                            <div class="form-group mt-4">
                                <textarea class="form-control px-2 border br-10px" name="description" rows="4" id="prospection_video_description" placeholder="{{__('Description')}}"></textarea>
                                <span class="text-danger print-error-msg-description" style="display:none"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label d-block">{{__('Video')}}<span class="text-danger">*</span></label>
                                <img id="new_prospection_video" class="img-fluid br-20px" src="{{ asset('assets/images/rectangle.png') }}" alt="" />
                                <video controls id="default-video" width="300"><source src=""  id="new_prospection_video_preview">
                                    {{__('Your browser does not support vieo.')}}
                                </video>
                                <div>
                                    <video id="edit_prospection_video_show" controls width="100%" src="" class="modal_prospection_edit_video_show modal-video h-auto">
                                        <source src="">
                                        {{__('Your browser does not support vieo.')}}
                                    </video>
                                </div>
                                <span class="text-danger print-error-msg-video" style="display:none"></span>
                                <input id='video' type='file' name="video" hidden/>
                                <a class="btn btn-outline-blue w-100 mt-2" id="new_video_trigger" href="javascript:;">+ {{__('Browse video')}}</a>
                            </div>
                            <div class="form-group mt-3">
                                <img id="video_cover_image_preview" class="img-fluid mb-4 br-20px" src="{{ asset((config('app.rankup.company_thumbnail_path'))) }}" alt="" />
                                <a class="btn btn-outline-white w-100 br-20px mb-4" id="video_cover_image_trigger" href="javascript:;">+ {{__('Video photo')}}</a>
                                <span class="text-danger print-error-msg-video_cover_image" style="display:none"></span>
                                <input id='video_cover_image' type='file' name="video_cover_image" hidden/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{__('Category')}}</label>
                                {!! Form::select('category_id', \App\Models\Category::getOptions('formation'), NULL, ['id'=>'category', 'class'=>'form-control select2-dynamic ps-0', 'placeholder' => __('Category')]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label">{{__('Sub category')}}</label>
                                {!! Form::select('sub_category_id',[],Request::old('sub_category_id'),array('class'=>'form-control px-1 select2-dynamic sub_category_id','id'=>'sub_category_id', 'placeholder' => __('Sub category') )); !!}
                            </div>
                        </div>
                        <div class="col-12 form-group prospection_survey_div d-none">
                            <div class="form-group">
                                <label for="" class="form-label">{{__('Survey')}}</label>
                                {!! Form::select('survey_id', [], null, ['class'=>'form-control px-2', 'id'=>'survey_id']) !!}
                                <input type="hidden" name="hidden_survey_id" id="prospection_survey_id" value="">
                                <span class="form-control ps-0 py-3 border-dashed text-center br-10px" id="no-survey-span">
                                    {{__('No survey added.')}}
                                </span>
                                <span class="text-danger print-error-msg-survey_id" style="display:none"></span>
                            </div>
                        </div>
                        <div class="col-md-6 order-md-1 order-2">
                            <button class="btn btn-blue mt-auto w-100 br-20px prospection_video_btn add-prospection" type="submit" aria-valuenow="0">{{__('Add video')}} <span id="current-progress" aria-valuemin="0" aria-valuemax="100" style="width: 0%" class="current-progress spinner-prospection" role="status" aria-hidden="true"></span><span class="spinner"></span></button>
                            <button class="btn btn-blue mt-auto w-100 br-20px prospection_video_btn update-prospection d-none" type="submit">{{__('Update video')}} <span  id="current-progress" aria-valuemin="0" aria-valuemax="100" style="width: 0%" class="current-progress spinner-prospection" role="status" aria-hidden="true"></span><span class="spinner"></span></button>
                        </div>
                        <div class="col-md-6 order-md-2 order-1">
                            <a class="btn btn-outline-white w-100 br-20px add-survey-btn" href="javascript:;" id="prospection_survey_button">{{__('Add a survey')}}</a>
                            <a class="btn btn-outline-white w-100 br-20px d-none update-survey-btn" href="javascript:;" id="prospection_survey_button" data-survey-id="">{{__('Update survey')}}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
