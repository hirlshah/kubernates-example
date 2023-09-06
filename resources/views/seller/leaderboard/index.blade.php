@extends('layouts.seller.index')
@section('content')
<div id="content">
    @include('seller.common._upgrade_warning')
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-calendar me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">
                        {{__('Leaderboards').' ' .getCarbonNowForUser()->translatedFormat('F Y')}}
                    </li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
        
        </div>
    </div>
    <div class="content-body">
        <div class="px-3">
            <div class="row gy-4">
                <div class="col-xl-6" id="page-title">
                </div>
                <div class="col-xl-6 text-xl-end">
                    <div class="dropdown-menu-analytic">
                        <div class="calendar-top">
                            <div class="btn-group flex-sm-nowrap flex-wrap" role="group" aria-label="Basic example">
                                <button type="button" class="btn calendar-btn" id="calendar-day" data-type="Day">{{__('Day')}}</button>
                                <button type="button" class="btn calendar-btn" id="calendar-week" data-type="Week">{{__('Week')}}</button>
                                <input type="button" class="btn calendar-btn" id="custom-range" data-type="customRange" value="{{__('Range')}}">
                                <button type="button" class="btn calendar-btn active" id="calendar-month" data-type="Month">{{__('Month')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider-ef my-4"></div>
            <div id="leaderboard_stats" class="position-relative">
                <div class="row gy-5">
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-copy icon"></i>
                            <h5 class="col-title">{{__('Top presentations given')}}</h5>
                        </div>
                        <div class="leader-main" id="presentation_given">
                        </div>
                    </div>
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-user-check icon"></i>
                            <h5 class="col-title">{{__('Top customer acquisition')}}</h5>
                        </div>
                        <div class="leader-main" id="customer_acquisition">
                        </div>
                    </div>
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-wind icon"></i>
                            <h5 class="col-title">{{__('Top distributor acquisition')}}</h5>
                        </div>
                        <div class="leader-main" id="distributor_acquisition">
                        </div>
                    </div>
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-copy icon"></i>
                            <h5 class="col-title">{{__('Most popular presentation')}}</h5>
                        </div>
                        <div class="leader-main" id="presentations">
                        </div>
                    </div>
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-copy icon"></i>
                            <h5 class="col-title">{{__('Top message sent')}}</h5>
                        </div>
                        <div class="leader-main" id="message_sent">
                        </div>
                    </div>
                    <div class="col-xxxl-4 col-md-6">
                        <div class="leaderboard-col-head-main">
                            <i class="feather-copy icon"></i>
                            <h5 class="col-title">{{__('Top present')}}</h5>
                        </div>
                        <div class="leader-main" id="present_at_zoom">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let presentationGivenRoute = "{{route('selle.leaderboard-stats.presentation-given')}}";
    let customerAcquisitionRoute = "{{route('seller.leaderboard-stats.customer-acquisition')}}";
    let distributorAcquisitionRoute = "{{route('seller.leaderboard-stats.distributor_acquisition')}}";
    let presentationsRoute = "{{route('seller.leaderboard-stats.presentations')}}";
    let messageSentRoute = "{{route('seller.leaderboard-stats.message_sent')}}";
    let presentAtZoomRoute = "{{route('seller.leaderboard-stats.present_at_zoom')}}";
    let dateFilterType = 'Month';
    let start = null;
    let end = null;
</script>
<script src="{{ asset('/assets/js/seller_leaderboard.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
