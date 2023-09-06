<!-- Send Message -->
<div class="modal fade" id="contactSendMessage" tabindex="-1" role="dialog" aria-labelledby="ContactSendMessageLabel" aria-hidden="true" data-id="" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <h6 class="fw-bold mb-3 text-center">{{__("You’re dragging this contact to “Message sent” collumn.")}}</h6>
                <p class="fs-14">{{__('You can share the message sent to this contact if you want to.')}}</p>
                <div class="row">
                    <div class="col-xl-6 col-12 m-auto">
                        <div class="card mt-2">
                            <div class="card-body d-flex align-items-center">
                                <img class="img-fluid message_contact_preview me-3 mw-100" src="{{ asset('assets/images/user-3.png') }}" alt="">
                                <h6 class="fw-bold ml-3 message_contact_name"></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <form id="contactMessageSendForm" class="row g-3" action="{{route('seller.contacts.send-message')}}" method="POST" enctype="multipart/form-data">
                        <div class="col-12">
                            <input type="hidden" class="form-control" id="contact_send_message_id" name="id" value="" >
                            <label for="contact_edit_message" class="form-label">{{__('Message')}}</label>
                            <textarea class="form-control" id="contact_edit_message" name="message" value="" rows="8"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-blue" id="contact_send_message">{{__('Add the message to this contact’s card')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
