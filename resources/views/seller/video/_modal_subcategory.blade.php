<div class="modal fade" id="add_videosubcategory_modal" tabindex="-1" role="dialog" aria-labelledby="add_videosubcategory_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4 p-sm-5">
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title">{{__('Add Sub Category')}}</h6>
                </div>
                <form class="sub_category_form" action="{{route('seller.category.subCategoryStore')}}" method="POST" enctype="multipart/form-data">   
                    <div class="row">
                        <input type="hidden" name="model_type" value="formation">
                        <div class="col-md-12 col-12">
                            <div class="form-group mb-4">
                                <label class="form-label">{{__('Add Category')}}<span class="text-danger">*</span></label>
                                <select class="form-control" name="parent_id" id="perent_id">
                                    <option value="">--{{ __('Select Category') }}--</option>
                                    @foreach($parentCategories as $category)
                                        <option value="{{ $category->id}}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger print-error-msg-parent_id" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="form-label">{{__('Add Name')}}<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control ps-0" id="sub_category_name" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-name" style="display:none"></span>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-blue" id="subcategory_form_submit_btn" type="submit">+ {{__('Add Sub Category')}}<span class="spinner"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>