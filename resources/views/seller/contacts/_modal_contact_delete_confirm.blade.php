<!-- Delete Card Modal -->
<div class="modal fade" id="deleteContactConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteContactConfirmLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <h6 class="fw-bold mb-3">{{__('Delete contact?')}}</h6>
                <p class="fs-14">{{__('Are you sure you want to delete this contact from your list?')}}</p>
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="card mt-3 mb-5">
                            <div class="card-body d-flex align-items-center">
                                <img class="img-fluid delete_contact_preview me-3 mw-45 rounded-circle" src="{{ asset('assets/images/user-3.png') }}" alt="">
                                <h6 class="fw-bold ml-3 delete_contact_name"></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row btns">
                    <div class="col-xl-6">
                        <button class="btn btn-blue w-100 mb-2 mb-xl-0" id="contact_delete_confirm" data-id=''>{{__('Yes, delete')}}</button>
                        <input type="hidden" class="contact_delete_id" value="">
                    </div>
                    <div class="col-xl-6">
                        <button class="btn btn-white-shadow w-100" id="contact_delete_cancel">{{__("Don’t delete it")}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
