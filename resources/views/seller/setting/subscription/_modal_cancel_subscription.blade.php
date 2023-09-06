<div class="modal fade" id="cancel_plan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close cancel_subscription_back_btn" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4">
                <h6 class="mb-4 fs-18">{{__('Cancel subscription')}}</h6>
                <div class="row">
                    <form id="cancel_subscription_form" action="{{route('seller.plan.cancel')}}" method="POST">
                        <input type="hidden" name="plan_id" id="cancel-plan-id">
                        <input type="hidden" name="user_cancel_plan_reason" id="user_cancel_plan_reason">
                        <div class="col-12 form-group mb-4">
                            <label class="mb-2" for="">{{__('Why do you want to cancel your subscription?')}}</label>
                            <textarea name="reason" class="form-control border br-10px px-3 placeholder-base" cols="5" id="cancel_reason" placeholder="{{ __('Type here')}}"></textarea>
                            <span class=" text-danger print-error-msg-reason reason_error_message" style="display:none"></span>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" id="cancel_plan_submit" href="javascript:;" class="btn btn-blue-gradient w-100">{{__('Confirm')}} <span class="form-submit d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                            </div>
                        </div>
                        <div class="cancel_plan_error_message text-danger mt-2"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>