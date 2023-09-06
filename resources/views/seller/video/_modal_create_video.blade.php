<div class="modal fade" id="add_video_modal" tabindex="-1" role="dialog" aria-labelledby="add_video_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4 p-sm-5 scroll-width">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-youtube blue me-3"></i>
                    <h6 class="add_edit_modal_title">{{__('Add Video')}}</h6>
                </div>
                <form id="add_video_form" action="{{route('videos.store')}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="video_method" name="_method" value="POST">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Video title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control ps-0" id="video_title" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-title" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <textarea class="form-control border br-10px" name="description" rows="3" id="video_description" placeholder="{{__('Description')}}"></textarea>
                                <span class="text-danger print-error-msg-description" style="display:none"></span>
                            </div>
                            <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Tags')}}</label>
                                <input type="text" name="tags[0]" class="form-control ps-0 d_tag" id="d_tag_0" placeholder="">
                                <input type="text" name="tags[1]" class="form-control ps-0 d_tag" id="d_tag_1" placeholder="" style="display: none">
                                <input type="text" name="tags[2]" class="form-control ps-0 d_tag" id="d_tag_2" placeholder="" style="display: none">
                                <span class="text-danger print-error-msg-tags" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;" id="new_tag_button">{{__('Add a Tag')}}</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="form-group mb-4">
                                 <label for="" class="form-label">{{__('Video Link')}} <span class="text-danger">*</span> </label>
                                 <input type="text" name="video_link" class="form-control ps-0" id="video_link" placeholder="{{__('YouTube link')}}">
                                    <span class="text-danger print-error-msg-video_link" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Category')}}</label>
                                {!! Form::select('category_id', \App\Models\Category::getOptions('formation'), NULL, ['id'=>'category', 'class'=>'form-control select2-dynamic', 'placeholder' => __('Uncategorized')]) !!}
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('SubCategory')}}</label>
                                {!! Form::select('sub_category_id',[],Request::old('sub_category_id'),array('class'=>'form-control select2-dynamic sub_category_id','id'=>'sub_category_id', 'placeholder' => __('Uncategorized') )); !!}
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <div class="mb-0">
                                <button class="btn btn-blue" id="video_form_submit_btn" type="submit">+ {{__('Add a video')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
