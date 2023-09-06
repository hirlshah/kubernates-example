<!-- Send Message -->
<div class="modal fade" id="contactFollowUp" tabindex="-1" role="dialog"
    aria-labelledby="contactFollowUpLabel" aria-hidden="true" data-id="" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <h6 class="fw-bold mb-3 text-center">{{__("You’re dragging this contact to “Follow Up” column.")}}</h6>
                <div class="row mb-3">
                    <form id="contactFollowUpForm" class="row g-3" action="{{route('seller.contacts.follow-up')}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" id="follow_up_contact_id" name="contact_id" value="" >
                        <div class="col-12">
                            <label for="contact_follow_up_date" class="form-label">{{__('Follow up Date')}}</label>
                            <input type="text" class="form-control" name="follow_up_date" id="contact_follow_up_date" value="" autocomplete="off">
                            <span class="text-danger follow-up-error print-error-msg-follow_up_date" style="display:none"></span>
                        </div>
                        <div class="col-12">
                            <label for="contact_follow_up_reson" class="form-label">{{__('Reason')}}</label>
                            <textarea class="form-control" id="contact_follow_up_reson" name="reason" rows="4"></textarea>
                            <span class="text-danger follow-up-error print-error-msg-reason" style="display:none"></span>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-blue" id="contact_send_message">{{__('Create Follow up')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
