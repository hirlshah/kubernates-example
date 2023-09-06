<div class="modal fade" id="cancel-subscription-reason-model" tabindex="-1" role="dialog" aria-labelledby="cancel-subscription-reason-model-label" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered pt-5"  role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close cancel_subscription_back_btn" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body px-3">
                <div class="row gy-3">
                    <div class="col-12">
                        <h5 class="modal-title" id="cancel-subscription-reason-model-label">{{__('Cancel my subscription')}}</h5>
                        <p class="me-3">{{__('Tell us why you want to unsubscribe')}}</p>
                        <select class="border-bottom-1" style="max-width: 330px;outline:none; background-position: center right 0px;" name="user_cancel_plan_reason" id="user_cancel_plan_reason_id">
                            <option value="">&nbsp; {{__('Select a reason')}}</option>
                            <option value='{{__("the price is high")}}'>&nbsp; {{__('The price is high')}}</option>
                            <option value='{{__("i don`t need this service anymore")}}'>&nbsp; {{__("I don`t need this service anymore")}}</option>
                            <option value='{{__("the platform didn`t meet my expectations")}}'>&nbsp; {{__("The platform didn`t meet my expectations")}}</option>
                            <option value='{{__("I don`t understand the feature")}}'>&nbsp; {{__("I don`t understand the feature")}}</option>
                            <option value='{{__("I don`t have enough contacts")}}'>&nbsp; {{__("I don`t have enough contacts")}}</option>
                        </select>
                        <div class="text-danger print-error-msg-user_cancel_plan_reason" style="display:none">{{__('Please select a reason for canceling your plan')}}</div>
                        @if(!empty($latestEvents) && $latestEvents->count() > 0 ) <h5 class="mb-0 font-semibold mt-4 ps-1">{{__('What you are losing')}}</h5> @endif
                    </div>
                    @if(isset($latestContacts) && count($latestContacts) > 0)
                        @foreach($latestContacts as $key => $contact)
                            <div class="col-12 col-sm-12 col-md-6">
                                <div class="card border-0 h-100">
                                    @if(isset($contact->profile_image) && !empty($contact->profile_image) &&  is_file(public_path("storage/".$contact->profile_image)))
                                        <div class="event-image" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) }}); min-height: 200px;"></div>
                                    @else
                                        <div class="event-image" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }}); min-height: 200px;"></div>
                                    @endif
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h6 class="card-title mb-4">
                                                    {{ $contact->name }}
                                                </h6>
                                            </div>
                                        </div>
                                        @if(isset($contact->phone))
                                            <p class="grey-666666">{{__('Phone number')}}: {{ $contact->phone }} </p>
                                        @endif
                                        @if(isset($contact->link))
                                            <p class="grey-666666">{{__('Link')}}: {{ $contact->link }} </p>
                                        @endif
                                        @if(isset($contact->message))
                                            <p class="card-text grey-666666 fs-14 mb-3">
                                            {{__('Message')}}: {{ $contact->message }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-12">
                        @if(!empty($myPlan) && $myPlan->is_extend_subscription_plan)
                            <button type="button" class="btn btn-blue-gradient me-3 cancel_subscription_free_month_back_btn mb-3 mb-sm-0" data-dismiss="modal">{{__('Cancel my subscription')}}</button>
                        @else
                            <button type="button" class="btn btn-blue-gradient cancel_my_subscription_btn">{{__('Cancel my subscription')}}</button>
                        @endif
                        <button type="button" class="btn btn-blue-gradient me-auto cancel_subscription_back_btn" data-dismiss="modal">{{__('Back')}}</button>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>