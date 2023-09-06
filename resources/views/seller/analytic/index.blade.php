@extends('layouts.seller.index')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/select2-checkbox.css') }}" />
@endsection

@section('content')
<div id="content">
    @include('seller.common._upgrade_warning')
    @if(Session::has('success'))
        <div class="alert alert-success" id="successMessage">
            {{Session::get('success')}}
        </div>
    @endif
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-activity me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Analytics')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            @include('seller.common._language')
        </div>
    </div>
    <div class="contact-body mt-4 p-0">
        <div class="row">
            <div class="col-12 mb-4">
                <ul class="nav nav-tabs stats-nav-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="columns-tab" data-bs-toggle="tab" data-bs-target="#columns" type="button" role="tab" aria-controls="columns" aria-selected="true">{{__('Presentation Statistics')}}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab" aria-controls="analytics" aria-selected="false">{{__('Analytics')}}</button>
                    </li>
                </ul>
            </div>
            <div class="col-xl-6 mb-lg-0 mb-4">
                <div class="row">
                    <div class="col-sm-6 filterDiv">
                        <div class="custom-control custom-switch">
                            <select class="form-control stat-dropdown" name="personalStat" id="personalStat">
                                <option value="team">{{__('Team Stats')}}</option>
                                <option value="personal">{{__('Your personal stats')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="eventFilterDiv" class="w-100 event-selection">
                            <select class="form-control eventselect" name="eventSelect2" id="eventSelect2" multiple="multiple">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 d-flex flex-row-reverse">
                <div class="dropdown-menu-analytic p-2 filterDiv">
                    <div class="calendar-top">
                        <div class="btn-group  flex-sm-nowrap flex-wrap" role="group" aria-label="Basic example">
                            <button type="button" class="btn calendar-btn px-sm-4" id="calendar-day" data-type="Day">{{__('Day')}}</button>
                            <button type="button" class="btn calendar-btn px-sm-4" id="calendar-week" data-type="Week">{{__('Last 7 Days')}}</button>
                            <input type="button" class="btn calendar-btn px-sm-4" id="custom-range" data-type="customRange" value="{{__('Range')}}">
                            <button type="button" class="btn calendar-btn active" id="calendar-month" data-type="Month">{{__('Month')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content mt-3">
            <div class="tab-pane active" id="columns" role="tabpanel" aria-labelledby="columns-tab">
                <div id="columnsData" class="mt-2">
                </div>
            </div>
            <div class="tab-pane" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                <div id="analyticsData" class="mt-2">
                @include('seller.analytic.analytics')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Contact detail modal -->
@include('seller.analytic._modal_contact_detail')

@endsection

@section('scripts')
<script>
    let analyticsRoute = "{{route('analytics.chart.data')}}";
    let analyticsPanelRoute = "{{route('analytics.panel.data')}}";
    let analyticsColumnRoute = "{{route('analytics.column.data')}}";
    let analyticsColumnContactRoute = "{{route('analytics.column.contacts')}}";
    let analyticsAjaxSearchRoute = "{{route('analytics.ajax.search')}}";
    let analyticsTeamStatRoute = "{{route('analytics.team.stats')}}";
    let analyticsPersonalStatRoute = "{{route('analytics.personal.stats')}}";
    let selectedPeriodText = "{{__('Selected period')}}";
    let previousPeriodText = "{{__('Previous period')}}";
    let personalStat = false;
    let eventID = null;
    let eventSelect2Title = "{{__('All Events')}}";
    let dateFilterType = "month";
    let start = null;
    let end = null;
    let showRoute = "{{route('seller.contacts.show', '')}}";
    let link = null;
    let userStatisticFlag = "{{Auth::user()->statistic_flag}}";
    let userStatisticFlagRoute = "{{route('update.statistics.flag')}}";
</script>
<script src="{{ asset('/assets/js/statistics_graphs.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
