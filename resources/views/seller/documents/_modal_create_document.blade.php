<div class="modal fade" id="add_document_modal" tabindex="-1" role="dialog" aria-labelledby="add_document_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4 p-sm-5 scroll-width">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-clipboard blue me-3"></i>
                    <h6 class="add_edit_document_modal_title">{{__('Add an Document')}}</h6>
                </div>
                <form id="add_document_form" action="{{route('documents.store')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Title')}}<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control ps-0" id="document_title" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-title" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <textarea class="form-control border br-10px" name="description" id="document_description" rows="3" placeholder="{{__('Description')}}"></textarea>
                                <span class="text-danger print-error-msg-description" style="display:none"></span>
                            </div>
                            <div class="form-group date mb-4">
                                <label for="" class="form-label">{{__('Tags')}}</label>
                                {{--{!! Form::select('tags[]', [], null, ['class'=>'form-control select2-dynamic', 'multiple'=>true,'id'=>'document_tags']) !!}--}}
                                <input type="text" name="tags[0]" class="form-control ps-0 d_tag" id="d_tag_0" placeholder="">
                                <input type="text" name="tags[1]" class="form-control ps-0 d_tag" id="d_tag_1" placeholder="" style="display: none">
                                <input type="text" name="tags[2]" class="form-control ps-0 d_tag" id="d_tag_2" placeholder="" style="display: none">
                                <span class="text-danger print-error-msg-tags" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <a class="btn btn-outline-white w-100 br-20px" href="javascript:;" id="new_tag_button">{{__('Add a Tag')}}</a>
                            </div>
                            <div class="form-group mb-2">
                                <label for="" class="form-label">{{__('Category')}}</label>
                                {!! Form::select('category_id', \App\Models\Category::getOptions('document'), NULL, ['id'=>'category', 'class'=>'form-control select2-dynamic', 'placeholder' => __('Uncategorized')]) !!}
                            </div>
                            <div class="form-group mb-2">
                                <label for="" class="form-label">{{__('SubCategory')}}</label>
                                {!! Form::select('sub_category_id',[],Request::old('sub_category_id'),array('class'=>'form-control select2-dynamic sub_category_id','id'=>'sub_category_id', 'placeholder' => __('Uncategorized'))); !!}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-2">
                                <label class="col-form-label col-lg-3">{{__('Document')}}<span class="text-danger">*</span></label>
                                <div class="drop-zone">
                                    <span class="drop-zone__prompt">{{__('Drag your document here')}}</span>
                                    <input type="file" name="document" class="drop-zone__input">
                                </div>
                                <span class="text-danger print-error-msg-document" style="display:none"></span>
                            </div>
                            <div class="form-group mb-2 mt-4">
                                <img id="new_document_image_preview" class="img-fluid mb-4 br-20px" src="{{ asset((config('app.rankup.company_default_image_file'))) }}" alt="" />
                                <a class="btn btn-outline-white w-100 br-20px mb-4" id="new_document_image_trigger" href="javascript:;">+ {{__('Image')}}</a>
                                <span class="text-danger print-error-msg-image" style="display:none"></span>
                                <input id='new_document_image' type='file' name="image" hidden/>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-0">
                                <button class="btn btn-blue add_document" style="display: none;" type="submit">+ {{__('Add Document')}}</button>
                                <button class="btn btn-blue update_document" style="display: none;" type="submit">+ {{__('Update Document')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


