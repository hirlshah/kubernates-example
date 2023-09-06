<div class="modal fade" id="not-interested" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <h6 class="fw-bold mb-3 text-center">{{__("Has this person seen the presentation ?")}}</h6>
                <div class="row btns">
                    <div class="col-xl-6">
                        <a class="btn btn-blue w-100 mb-2 mb-xl-0 present-modal" id="in-present" data-present="1">{{__('Yes')}}</a>
                    </div>
                    <div class="col-xl-6">
                        <a class="btn btn-blue w-100 mb-2 mb-xl-0 present-modal" id="out-present" data-present="0">{{__('No')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
