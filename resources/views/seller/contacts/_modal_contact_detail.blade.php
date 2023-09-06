<!-- Modal -->
<div class="modal fade modal-width-70 pt-5" id="contactDetail" tabindex="-1" role="dialog"
    aria-labelledby="ContactDetailLabel" aria-hidden="true" data-id="" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="label-title d-flex align-items-center label-modal-color">
                    <p class="label-modal-text d-flex flex-wrap gap-2 contact-modal-label"></p>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8 col-sm-9">
                        <div class="profile-image d-flex align-items-center mt-3 mb-md-0 mb-3">
                            <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="contact_edit_image_preview" style="background-image: url({{ asset('assets/images/add-member-large.png') }}); max-width:126px;border-radius:100px;max-height:126px;height:126px;width: 126px;">
                            </div>
                            <div class="profile-image-text">
                                <span class="fs-16 mb-0 font-weight-normal d-block contact_name"><b></b></span>
                                <a id="contact_edit_image_button" class="contact_image_button">{{__('Edit photo')}}</a>
                            </div>
                        </div>
                        <span class="text-danger print-error-msg-contact_image" style="display:none"></span>
                    </div>
                    <div class="col-md-4 col-sm-3 d-flex align-items-center justify-content-end">
                        @if(env('APP_ENV') != 'production')
                            <div class="d-flex justify-content-end" style="margin-right: 10px;">
                                <div class="btn btn-blue ai-writing-btn" data-id="">{{ __('AI Writing') }}</div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-end" style="margin-right: 10px;">
                            <div class="btn btn-blue add-contact-label-modal" data-id="">{{ __('Add a tag') }}</div>
                        </div>
                        <i class="feather-edit-3 contact-edit cursor-pointer fs-22 me-3 d-none"></i>
                        <i class="feather-trash contact-delete cursor-pointer fs-22"></i>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <form id="update-contact-form" action="{{route('seller.contacts.update', '')}}" method="POST"
                        enctype="multipart/form-data">
                        <div class="col-12">
                            <div class="row">
                                <input type='file' id="contact_edit_image" name="contact_image" style="display:none" />
                                <div class="col-lg-4 col-sm-6 my-3">
                                    <label for="contact_full_name" class="form-label">{{__('Full Name')}}</label>
                                    <input type="text" class="form-control" id="contact_edit_full_name" name="name" value="" readonly="true">
                                    <span class="text-danger print-error-msg-name" style="display:none"></span>
                                </div>
                                <div class="col-lg-4 col-sm-6 my-3">
                                    <label for="contact_email" class="form-label">{{__('Email')}}</label>
                                    <input type="email" class="form-control" id="contact_edit_email" name="email" value="" readonly="true">
                                    <span class="text-danger print-error-msg-email" style="display:none"></span>
                                </div>
                                <div class="col-lg-4 col-sm-6 my-3">
                                    <label for="contact_phone" class="form-label">{{__('Phone number')}}</label>
                                    <input type="text" class="form-control" id="contact_edit_phone" name="phone" value="" readonly="true">
                                    <span class="text-danger print-error-msg-phone" style="display:none"></span>
                                </div>
                                <div class="col-lg-6 col-sm-6 my-3 d-none">
                                    <label for="contacted_through" class="form-label">{{__('Contacted though')}}</label>
                                    <input type="text" class="form-control" id="contact_edit_contacted_through" name="contacted_through" value="" readonly="true">
                                </div>
                                <div class="col-lg-6 col-sm-6 my-3">
                                    <label for="created_at" class="form-label">{{__('Entry date')}}</label>
                                    <input type="text" class="form-control" id="contact_edit_created_at" name="created_at" value="" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 my-3">
                                    <label for="contact_full_name" class="form-label">{{__('Message')}}</label>
                                    <textarea class="form-control ps-3 py-2" id="contact_edit_message" style="height:90px;" name="message" value="" readonly="true"></textarea>
                                </div>
                                <div class="col-lg-3 col-sm-6 my-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="" class="form-label">{{__('Link')}}</label>
                                        <a href="javascript:void(0)" class="edit_link"><i class="feather-edit"></i></a>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 align-items-end" style="height:90px;">
                                        <input type="text" class="form-control" id="contact_link"
                                        name="link" value="" readonly="true" style="height:90px;">
                                        <a href="javascript:void(0)" target="_blank" class="view_link" style="word-break: break-all;"> </a>
                                    </div>
                                    <span class="text-danger print-error-msg-link" style="display:none"></span>
                                </div>
                                <div class="col-lg-3 col-md-6 my-3">
                                    <div class="d-flex justify-content-between mb-2 align-items-center">
                                        <label for="" class="form-label mb-0">{{__('Follow Up Date')}}</label>
                                        <a href="javascript:void(0)" class="clear_follow_up_date_picker"><i class="feather-x"></i></a>
                                    </div>
                                    <input type="text" name="follow_up_date" class="form-control text-uppercase follow_up_date_picker" style="height:90px;"
                                    id="contacted_follow_up_date" disabled="disabled" placeholder="{{__('MM/DD/YYYY')}}" autocomplete="off">
                                    <span class="text-danger print-error-msg-follow_up_date" style="display:none"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-blue">{{__('Update')}}</button>
                        </div>
                    </form>
                </div>
                <div class="row" id="survey-details">
                </div>
            </div>
        </div>
    </div>
</div>
