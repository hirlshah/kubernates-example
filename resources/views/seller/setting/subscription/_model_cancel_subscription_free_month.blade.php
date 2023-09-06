<div class="modal fade" id="cancel_subscription_free_month_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5"  role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close cancel_subscription_back_btn" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="container-fluid mb-4">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <h5 class="modal-title mb-3">{{__('Cancel my subscription')}}</h5>
                            <h4 class="text-blue mb-0">{{__('You get 1 month free')}}</h4>
                            <p class="mb-0">{{__('We will give you 1 free month to continue using our platform.')}}</p>
                            <p class="mb-0">{{__('Would you like to use this month for free?')}}</p>
                        </div>
                    </div>
                </div>
                @if(isset($myPlan)) 
                    <button type="submit" class="btn btn-blue-gradient cancel_my_subscription_free_month_btn me-3" data-planid="{{ $myPlan->id }}" data-action="{{ route('seller.plan.free-month-plan')}}">{{__('Yes, I want a free month')}} <span class="form-submit d-none"><i class="fas fa-spin feather-loader"></i></button>
                @endif
                <button type="button" class="btn btn-blue-gradient me-auto cancel_subscription_free_month_back_btn" data-dismiss="modal">{{__('No, cancel my subscription.')}}</button>
                <div class="error_message text-danger mt-2"></div>
            </div> 
        </div>
    </div>
</div>