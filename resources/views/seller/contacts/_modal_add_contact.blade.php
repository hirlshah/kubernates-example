<!-- Modal -->
<div class="modal fade modal-width-70" id="addNewContact" tabindex="-1" role="dialog"
    aria-labelledby="addNewContactLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="profile-image d-flex align-items-center mt-3 mb-md-0 mb-3">
                        <div class="hw-126px bg-cover bg-repeat-n bg-center me-4" style="background-image: url({{ asset('assets/images/add-member-large.png') }})" id="contact_image_preview"></div>
                        <div class="profile-image-text">
                            <span class="fs-16 mb-0 font-weight-normal d-block"><b>{{__('New Contact')}}</b></span>
                            <a id="contact_image_button" class="contact_image_button">{{__('ADD PHOTO')}}</a>
                        </div>
                    </div>
                </div>
                <form id="create-contact-form" class="row g-3" action="{{route('seller.contacts.store')}}" method="POST"
                    enctype="multipart/form-data">
                    <input type='file' id="contact_image" name="contact_image" style="display:none" />
                    <span class="text-danger print-error-msg-contact_image" style="display:none"></span>
                    @if(isset($user))
                        <input type="hidden" value="{{$user->id}}" name="user_id">
                    @endif
                    <div class="col-md-3">
                        <label for="contact_full_name" class="form-label">{{__('Full Name')}}</label>
                        <input type="text" class="form-control" id="contact_full_name" name="name" value="">
                        <span class="text-danger print-error-msg-name" style="display:none"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="contact_email" class="form-label">{{__('Email')}}</label>
                        <input type="email" class="form-control" id="contact_email" name="email" value="">
                        <span class="text-danger print-error-msg-email" style="display:none"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="contact_phone" class="form-label">{{__('Phone number')}}</label>
                        <input type="text" class="form-control" id="contact_email" name="phone" value="">
                        <span class="text-danger print-error-msg-phone" style="display:none"></span>
                    </div>
                    <div class="col-md-3">
                        <label for="contacted_through" class="form-label">{{__('Contacted through')}}</label>
                        <input type="text" class="form-control" id="contacted_through" name="contacted_through" value="">
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="col-md-3" style="margin: inherit;">
                            <button type="submit" class="btn btn-blue">{{__('Create Contact')}}</button>
                        </div>
                        <div class="col-md-3" style="padding: 0 14px;">
                            <label for="" class="form-label">{{__('Follow Up Date')}}</label>
                            <input type="text" name="follow_up_date" class="form-control text-uppercase follow_up_date_picker" id="contacted_follow_up_date" placeholder="{{__('MM/DD/YYYY')}}" autocomplete="off">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
