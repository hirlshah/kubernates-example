<div class="modal fade" id="add_tag_modal" tabindex="-1" role="dialog" aria-labelledby="add_tag_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <h6>{{__('Add Tag')}}</h6>
                </div>
                <form id="add_tag_form" action="{{route('tag.store')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group mb-4">
                                <label for="" class="form-label">{{__('Tag name')}}</label>
                                <input type="text" name="name" class="form-control ps-0" id="name" placeholder="{{__('type here')}}">
                                <span class="text-danger print-error-msg-name" style="display:none"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <button class="btn btn-blue-gradient w-100 br-20px" type="submit">{{__('Add a Tag')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>