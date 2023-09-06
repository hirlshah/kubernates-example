@extends('layouts.seller.index')
@section('content')
<div id="content">
	<button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-user me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <a class="notification" href=""><i class="feather-bell blue"></i></a>
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body settings-body p-0">
        <div class="row h-100">
            @if(Session::has('success'))
                <div class="alert alert-success mb-3">
                    {{Session::get('success')}}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger mb-3">
                    {{Session::get('error')}}
                </div>
            @endif
        <div class="alert alert-danger mb-3 print-error-msg-plan" style="display: none">
        </div>
        @include('seller.setting.sidebar')
	    <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-8">
            <div class="d-flex flex-column flex-shrink-0 shadow-custom rounded-3 h-100 p-md-5 py-4 px-3">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="v-pills-tab-1">
                        <div class="row mb-4">
                            @if(Session::has('success_top'))
                                <div class="alert alert-success mb-3">
                                    {{Session::get('success_top')}}
                                </div>
                            @endif
                            <div class="col mb-sm-0 mb-3">
                                <h6 class="mb-3 fs-19">{{__('Plans')}} Rank Up</h6>
                                <h6 class="fs-14 fw-normal">{{ __('Your') }} <b>{{ __('current plan') }}</b></h6>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-5 col-sm-4">
                                <label class="form-label mb-1">{{__('Discount coupon?')}}</label>
                                <div class="input-group custom-input-1 d-flex flex-nowrap align-items-center">
                                    <input type="text" class="form-control w-auto me-1" placeholder="{{__('type here')}}" id="plan_coupon">
                                    <a href="javascript:;" id="plan_coupon_button"><i class="feather-arrow-right blue"></i></a>
                                </div>
                                <div class="text-danger mb-3 print-error-msg-plan_coupon" style="display: none"></div>
                                <div id="coupon-success-amount-div" class="text-success mb-3" style="display: none"></div>
                            </div>
                        </div>
                        <div class="row">
                            @if(!$userHasPlan)
                                <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-xl-6 col-md-12 col-sm-6 mb-4">
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
                                                @if(false)
                                                    <button class="btn btn-blue-2 active mw-max-content fs-16 mt-auto cancel-active-plan" data-planid="{{$freePlan->id}}" data-target="#cancel_plan">{{__('Cancel')}}</button>
                                                @endif
                                            @elseif($myPlanSlug != 'free')
                                                @if($userHasPlan)
                                                    <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$freePlan->id}}" data-id="{{$freePlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$freePlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span> </button>
                                                @else
                                                    <button class="btn btn-blue mw-max-content fs-16 mt-auto add-plan-details add-plan-details-{{$freePlan->id}}" data-id="{{$freePlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$freePlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span> </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(false && $standardPlan)
                                <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-xl-6 col-md-12 col-sm-6 mb-4">
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
                                                <button class="btn btn-blue active mw-max-content fs-16 mt-auto cancel-active-plan text-danger" data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel subscription')}}</button>
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
                                <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-xl-6 col-md-12 col-sm-6 mb-4">
                                    <div class="card h-100 plan-selection-card" data-id="{{$standardMonthPlan->id}}">
                                        <div class="card-body d-flex flex-column h-100">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                                <h3 class="fw-normal blue">{{__('standard_month_plan_name')}}</h3>
                                                <h3 class="fw-normal">@if(app()->getLocale() == "cs") {{round($standardMonthPlan->price)}} {{ __('USD') }}/{{__('plan_interval_short_' .
                                                    $standardMonthPlan->interval)}} @else {{ __('USD') }} ${{round($standardMonthPlan->price)}}/{{__('plan_interval_short_' .
                                                    $standardMonthPlan->interval)}} @endif</h3>
                                            </div>
                                            <p class="fs-14 lh-base mb-4">{{__('standard_plan_description')}}</p>
                                            @if($myPlanSlug == 'pro_month' && $myPlan->stripe_status == 'incomplete')
                                            <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                            @endif
                                            @if($myPlanSlug == 'pro_month')
                                            <button class="btn active mw-max-content fs-16 mt-auto cancel-active-plan text-danger"
                                                data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel subscription')}}</button>
                                            @elseif(!$incomplete)
                                                @if($userHasPlan)
                                                    <button class="btn btn-blue mw-max-content fs-16 mt-auto plan-selection update-plan-details update-plan-details-{{$standardMonthPlan->id}}" data-id="{{$standardMonthPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardMonthPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                                @else
                                                    <button class="btn btn-blue mw-max-content fs-16 mt-auto add-plan-details add-plan-details-{{$standardMonthPlan->id}}" data-id="{{$standardMonthPlan->id}}">{{__('Subscribe to this plan')}} <span class="form-submit-wait form-submit-wait-{{$standardMonthPlan->id}} d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($proPlan)
                                <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-xl-6 col-md-12 col-sm-6 mb-4">
                                    <div class="card h-100 plan-selection-card" data-id="{{$proPlan->id}}">
                                        <div class="card-body d-flex flex-column h-100">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                                <h3 class="fw-normal blue">{{__('pro_year_plan_name')}}</h3>
                                                <h3 class="fw-normal">@if(app()->getLocale() == "cs") {{round($proPlan->price)}} {{ __('USD') }}/{{__('plan_interval_short_' . $proPlan->interval)}} @else {{ __('USD') }} ${{round($proPlan->price)}}/{{__('plan_interval_short_' . $proPlan->interval)}} @endif</h3>
                                            </div>
                                            <p class="fs-14 lh-base mb-4">{{__('pro_plan_description')}}</p>
                                            @if(($myPlanSlug == 'pro_year_199') && $myPlan->stripe_status == 'incomplete')
                                                <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                            @endif
                                            @if($myPlanSlug == 'pro_year_199')
                                                <button class="btn active mw-max-content fs-16 mt-auto cancel-active-plan text-danger" 
                                                    data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel subscription')}}</button>
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
                                <div class="{{ !$userHasPlan ? 'col-xxl-3' :  'col-xxl-4' }} col-xl-6 col-md-12 col-sm-6 mb-4">
                                    <div class="card h-100 plan-selection-card" data-id="{{$proBiYearPlan->id}}">
                                        <div class="card-body d-flex flex-column h-100">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
                                                <h3 class="fw-normal blue">{{__('standard_bi_year_plan_name')}}</h3>
                                                <h3 class="fw-normal">@if(app()->getLocale() == "cs") {{round($proBiYearPlan->price)}}/2 {{ __('USD') }}/{{__('plan_interval_short_bi_' .
                                                    $proBiYearPlan->interval)}} @else {{ __('USD') }} ${{round($proBiYearPlan->price)}}/2 {{__('plan_interval_short_bi_' .
                                                    $proBiYearPlan->interval)}} @endif</h3>
                                            </div>
                                            <p class="fs-14 lh-base mb-4">{{__('standard_plan_description')}}</p>
                                            @if($myPlanSlug == 'pro_bi_year' && $myPlan->stripe_status == 'incomplete')
                                                <button class="btn btn-danger mw-max-content fs-16 mt-auto" disabled>{{__('Awaiting Payment')}}</button>
                                            @endif
                                            @if($myPlanSlug == 'pro_bi_year')
                                            <button class="btn btn-blue active mw-max-content fs-16 mt-auto cancel-active-plan text-danger" data-planid="{{$myPlan->id}}" data-target="#cancel_plan">{{__('Cancel subscription')}}</button>
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

                        <div class="divider-ef"></div>
                        <div class="row mt-5">
                            <div id="coupon-success-amount-div" class="alert alert-success mb-3" style="display: none;" role="alert">
                                {{__('Coupon successfully applied. Now applicable amount is')}} <span id="coupon-success-amount"></span>
                            </div>
                            <div class="col-12">
                                @if($incomplete && $myPlanSlug != 'pro_year')
                                <h5 class="text-danger">{{__('Your invoice is unpaid. Please pay by today to keep subscription active.')}}</h5>
                                    @if($payUrl)
                                        <p class="14">
                                            <a class="btn btn-white-black me-3 my-2" href="{{$payUrl}}" target="__blank">{{__('Pay Now')}}</a>
                                        </p>
                                    @endif
                                @endif
                                <h6 class="mt-5 mb-4">{{__('Your card')}}</h6>
                                <div class="card-slider slider mb-5 mt-n5" id="add_card_div">
                                    @if($cards->count() == 0)
                                        <div>
                                            <div class="add-credit-card"  data-toggle="modal" data-target="#add-credit-card">
                                                <div class="add-card">
                                                    <span>+</span>
                                                    <h6>{{__('Add a new card')}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach($cards as $card)
                                        <div>
                                            <div class="credit-card roboto {{$card->is_active?"active":""}}" style="background-image: url({{ asset('assets/images/credit-card-corner.png') }})">
                                                <div class="credit-card-company mb-sm-5 mb-3"  style="background-image: url({{ asset('assets/images/visa.png') }})"></div>
                                                <div class="row">
                                                    <div class="col-sm-7 my-2">
                                                        <h6 class="fs-14 grey-b9b5b6 mb-2">{{__('Name')}}</h6>
                                                        <h5 class="fs-19">{{$card->card_holder_name}}</h5>
                                                    </div>
                                                    <div class="col-sm-5 my-2">
                                                        <h6 class="fs-14 grey-b9b5b6 mb-2">{{__('Status')}}</h6>
                                                        <div class="d-flex align-items-center">
                                                            @if($cards->count() > 1)
                                                            <label class="switch-2 me-2">
                                                                <input class="" name="card-activation" value="{{$card->id}}" type="checkbox" {{$card->is_active?"checked":""}} autocomplete="off">
                                                                <span class="slider round"></span>
                                                            </label>
                                                            @endif
                                                            <h5 class="fs-19">{{ $card->is_active ? __('Active') : __('Inactive')}}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-7 my-2">
                                                        <h6 class="fs-14 grey-b9b5b6 mb-2">{{__('Number')}}</h6>
                                                        <h5 class="fs-19">**** **** **** {{$card->card_last_four}}</h5>
                                                    </div>
                                                    <div class="col-sm-5 my-2">
                                                        <h6 class="fs-14 grey-b9b5b6 mb-2">{{__('Exp Date')}}</h6>
                                                        <h5 class="fs-19">{{$card->card_expiry_date}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- <button class="btn btn-blue fs-16 fw-bold update-plan-details">{{__('Update my plan details')}}</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
	</div>
</div>
@include('seller.setting.subscription._modal_add_card')
@include('seller.setting.subscription._modal_cancel_subscription')
@include('seller.setting.subscription._model_cancel_subscription_reason')
@include('seller.setting.subscription._model_cancel_subscription_free_month')
@include('seller.setting.subscription._modal_my-subscription-plan')
@endsection
@section('scripts')
    <script>
        let activateCardRoute = '{{route('seller.card.activate', ['card'=>'#id#'])}}';
        let updatePlanRoute = '{{route('seller.plan.update')}}';
        let createPlanRoute = '{{route('seller.plan.create')}}';
        let validateCouponRoute = '{{route('seller.coupon.validate')}}';
        let selectedPlan = null;
        let cardCount = '{{$cards->count()}}';
        let userHasPlan = "<?php echo $userHasPlan; ?>";
    </script>

    <script>
         $(document).ready(function() {
            $('.add-plan-details').click(function (e) {
                e.preventDefault();
                //if card count is 0 then first open add card modal
                if (cardCount == 0) {
                    $('.modal-backdrop').remove();
                    $('#sub-scription-plan-modal').hide();
                    $('#add-credit-card').modal('show');
                    return;
                }
                selectedPlan = $(this).data('id');
                $('.plan-selection-card').removeClass('bg-primary');
                $(`.plan-selection-card[data-id=${selectedPlan}]`).addClass('bg-primary');
                $('#plan-success-alert').hide();
                $('.plan-error').hide();
                let plan_coupon = $('#plan_coupon').val();

                $('.add-plan-details-'+selectedPlan).prop('disabled', true);
                $('.form-submit-wait-'+selectedPlan).removeClass('d-none');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: createPlanRoute,
                    data: { plan_coupon, selected_plan: selectedPlan},
                    type: "POST",
                    success: function(data) {
                        if ($.isEmptyObject(data.errors)) {
                            if(data.success == 'requires_action') { 
                                window.location.href = data.redirect_to_url;
                            } else if (data.success) {
                                window.location.reload();
                            } else  {
                                window.location.reload();
                            }
                        } else {
                            printErrorMsg(data.errors);
                            $('.add-plan-details-'+selectedPlan).prop('disabled', true);
                            $('.form-submit-wait-'+selectedPlan).removeClass('d-none');
                        }
                    },
                    error: function(data) {
                        $('.error-subscription').removeClass('d-none');
                        $('.error-subscription').text(data.responseJSON.errors.message);
                        printErrorMsg(data.responseJSON.errors);
                    },
                    complete: function () {
                        $('.add-plan-details-'+selectedPlan).prop('disabled', true);
                        $('.form-submit-wait-'+selectedPlan).removeClass('d-none');
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('assets/js/subscription.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script>
        $(".card-slider").slick({
            dots: false,
            arrows: false,
            infinite: false,
            slidesToScroll: 1,
            variableWidth: true
        });

        /**
         * Show Sub Scription Plan Modal If User No Have Any Plan
         */
        if(userHasPlan) {
            $('#sub-scription-plan-modal').modal('hide');
        } else {
            $('#sub-scription-plan-modal').modal('show');
        }
    </script>
@endsection
