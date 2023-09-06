<!-- Modal -->
<div class="modal fade modal-width-70" id="myEventModal" tabindex="-1" role="dialog"
     aria-labelledby="addNewEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <h5 class="m-3">{{__('Add your Details')}}</h5>
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <span class="text-warning font-weight-bold print-common-error-msg" style="display:none"></span>
                <span class="text-success font-weight-bold print-common-success-msg" style="display:none"></span>
                <form id="user-info-form" class="row g-3" action="{{route('frontend.store.contacts')}}" method="POST">
                    <input type="hidden" name="event_id" value="{{$event->id}}">
                    <input type="hidden" name="referral_id" value="{{app('request')->input('referral')}}">
                    <div class="col-md-6">
                        <label for="contact_full_name" class="form-label">{{__('First Name')}}</label>
                        <input type="text" name="first_name" class="form-control" id="first_name" required>
                        <span class="text-warning print-error-msg-first-name" style="display:none"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_full_name" class="form-label">{{__('Last Name')}}</label>
                        <input type="text" name="last_name" class="form-control" id="last_name" required>
                        <span class="text-warning print-error-msg-last-name" style="display:none"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_full_name" class="form-label">{{__('Phone number')}}</label>
                        <input type="text" name="phone" class="form-control" id="phone" required>
                        <span class="text-warning print-error-msg-phone" style="display:none"></span>
                    </div>
                    <div class="col-md-6">
                        <label for="event_email" class="form-label">{{__('Email')}}</label>
                        <input type="email" name="email" class="form-control" id="event_email" required>
                        <span class="text-warning print-error-msg-email" style="display:none"></span>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-blue">{{__('REGISTER')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
