<div class="modal fade" id="sub-scription-plan-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body">
                <a href="#" class="my-modal-close mt-2" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <a href ="{{ route('seller.setting.my-subscription') }}" class="tab-link {{ (Request::is('seller/settings/my-subscription')  ? 'active' : '') }}" id="v-pills-settings-tab">
                        <i class="feather-credit-card"></i> 
                        <h6 class="add_edit_modal_title">{{__('Pick your plan')}}</h6>
                    </a>
                </div>
                <div class="text-danger mb-3 d-none error-subscription text-center"></div>
                <div class="row">
                    @if(!$userHasPlan)
                        <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-md-6 col-xl-4 col-sm-6 mb-4">
                            <div class="card h-100 plan-selection-card" data-id="{{$freePlan->id}}">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                        <h3 class="fw-normal blue">{{__('Free')}}</h3>
                                        <h3 class="fw-normal">{{ __('USD') }} $1/{{__($freePlan->interval)}}</h3>
                                    </div>
                                    <p class="fs-14 lh-base mb-4">{{__('free_plan_description')}}</p>
                                    @if(($myPlanSlug == 'free'))
                                        <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                    @endif
                                    @if($myPlanSlug == 'free')
                                        <button class="btn btn-blue-2 active mw-max-content fs-16 mt-auto cancel-active-plan" data-planid="{{$freePlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                    @elseif($myPlanSlug != 'free')
                                        @if($userHasPlan)
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$freePlan->id}}" data-id="{{$freePlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$freePlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @else
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto add-plan-details add-plan-details-{{$freePlan->id}}" data-id="{{$freePlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$freePlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span> </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(false && $standardPlan)
                        <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-md-6 col-xl-4 col-sm-6 mb-4">
                            <div class="card h-100 plan-selection-card" data-id="{{$standardPlan->id}}">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                        <h3 class="fw-normal blue">{{__('standard_plan_name')}}</h3>
                                        <h3 class="fw-normal">{{ __('USD') }} ${{round($standardPlan->price)}}/{{__('plan_interval_short_' . $standardPlan->interval)}}</h3>
                                    </div>
                                    <p class="fs-14 lh-base mb-4">{{__('standard_plan_description')}}</p>
                                    @if($myPlanSlug == 'standard' && $myPlan->stripe_status == 'incomplete')
                                        <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                    @endif
                                    @if($myPlanSlug == 'standard')
                                        <button class="btn btn-blue active mw-max-content fs-16 mt-auto cancel-active-plan" data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                    @elseif(!$incomplete)
                                        @if($userHasPlan)
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$standardPlan->id}}" data-id="{{$standardPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @else
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto add-plan-details add-plan-details-{{$standardPlan->id}}" data-id="{{$standardPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($standardMonthPlan)
                        <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-md-6 col-xl-4 col-sm-6 mb-4">
                            <div class="card h-100 plan-selection-card" data-id="{{$standardMonthPlan->id}}">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                        <h3 class="fw-normal blue">{{__('standard_month_plan_name')}}</h3>
                                        <h3 class="fw-normal">{{ __('USD') }} ${{round($standardMonthPlan->price)}}/{{__('plan_interval_short_' .
                                          $standardMonthPlan->interval)}}</h3>
                                    </div>
                                    <p class="fs-14 lh-base mb-4">{{__('standard_plan_description')}}</p>
                                    @if($myPlanSlug == 'pro_month' && $myPlan->stripe_status == 'incomplete')
                                        <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                    @endif
                                    @if($myPlanSlug == 'pro_month')
                                        <button class="btn btn-blue active mw-max-content fs-16 mt-auto cancel-active-plan" data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                    @elseif(!$incomplete)
                                        @if($userHasPlan)
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$standardMonthPlan->id}}" data-id="{{$standardMonthPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardMonthPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span> </button>
                                        @else
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto  add-plan-details add-plan-details-{{$standardMonthPlan->id}}" data-id="{{$standardMonthPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardMonthPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($proPlan)
                        <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-md-6 col-xl-4 col-sm-6 mb-4">
                            <div class="card h-100 plan-selection-card" data-id="{{$proPlan->id}}">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                        <h3 class="fw-normal blue">{{__('pro_year_plan_name')}}</h3>
                                        <h3 class="fw-normal">{{ __('USD') }} ${{round($proPlan->price)}}/{{__('plan_interval_short_' . $proPlan->interval)}}</h3>
                                    </div>
                                    <p class="fs-14 lh-base mb-4">{{__('pro_plan_description')}}</p>
                                    @if(($myPlanSlug == 'pro_year_199') && $myPlan->stripe_status == 'incomplete')
                                        <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                    @endif
                                    @if($myPlanSlug == 'pro_year_199')
                                        @if(false)
                                            <button class="btn btn-blue-2 active mw-max-content fs-16 mt-auto cancel-active-plan" data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                        @endif
                                    @elseif(!$incomplete && $myPlanSlug != 'pro_year')
                                        @if($userHasPlan)
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$proPlan->id}}" data-id="{{$proPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$proPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @else
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto  add-plan-details add-plan-details-{{$proPlan->id}}" data-id="{{$proPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$proPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($proBiYearPlan)
                        <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-md-6 col-xl-4 col-sm-6 mb-4">
                            <div class="card h-100 plan-selection-card" data-id="{{$proBiYearPlan->id}}">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                        <h3 class="fw-normal blue">{{__('standard_bi_year_plan_name')}}</h3>
                                        <h3 class="fw-normal">{{ __('USD') }} ${{round($proBiYearPlan->price)}}/2 {{__('plan_interval_short_bi_' .
                                          $proBiYearPlan->interval)}}</h3>
                                    </div>
                                    <p class="fs-14 lh-base mb-4">{{__('standard_plan_description')}}</p>
                                    @if($myPlanSlug == 'pro_bi_year' && $myPlan->stripe_status == 'incomplete')
                                        <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                    @endif
                                    @if($myPlanSlug == 'pro_bi_year')
                                        <button class="btn btn-blue active mw-max-content fs-16 mt-auto cancel-active-plan"
                                        data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                    @elseif(!$incomplete)
                                        @if($userHasPlan)
                                            <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$proBiYearPlan->id}}" data-id="{{$proBiYearPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$proBiYearPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @else
                                            <button class="btn btn-blue  mw-max-content fs-16 mt-auto add-plan-details add-plan-details-{{$proBiYearPlan->id}}" data-id="{{$proBiYearPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$proBiYearPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>