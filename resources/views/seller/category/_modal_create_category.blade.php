<div class="modal fade" id="add_category_modal" tabindex="-1" role="dialog" aria-labelledby="add_category_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title">{{__('Add Category')}}</h6>
                </div>
                <form class="add_category_form" action="{{route('seller.category.store')}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="model_type" value="" id="model_type">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="form-group mb-4">
                                <label class="form-label">{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control ps-0" id="category_name" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-name" style="display:none"></span>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-blue" id="category_form_submit_btn" type="submit">+ {{__('Add Category')}}<span class="spinner"></span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
