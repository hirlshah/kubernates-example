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
            <i class="feather-bar-chart me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Statistics')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <a class="notification" href=""><i class="feather-bell blue"></i></a>
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-4 mt-4">
                    <h5>{{__('Contacts')}}</h5>
                </div>
                <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                                <h6 class="fs-18">{{__('Messages sent')}}</h6>
                                <div class="graph-date-range" data-chart-id="message-sent-graph">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <canvas id="message-sent-graph" style="width:60%;max-width:400px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                                <h6 class="fs-18">{{__('New Customers')}}</h6>
                                <div class="graph-date-range" data-chart-id="new-customer-graph">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <canvas id="new-customer-graph" style="width:60%;max-width:400px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                                <h6 class="fs-18">{{__('New distributors')}}</h6>
                                <div class="graph-date-range" data-chart-id="new-distributor-graph">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <canvas id="new-distributor-graph" style="width:60%;max-width:400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4 mb-4">
                    <h5 class="">{{__('Your team')}}</h5>
                </div>
                @foreach($members as $member)
                    <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6 col-12">
                        <div class="card mb-4">
                            <div class="card-body d-flex align-items-center px-2" style="min-height:90px;">
                                <a href="{{ route('seller.member.profile', $member->id) }}" class="people-list">
                                    <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($member->thumbnail_image) }})"></div>
                                    <h6 class="min-content">{{$member->name}}</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-xxl-4 col-12 mb-4">
                    <h5 class="mb-4">{{__('Log ins')}}</h5>
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fs-18 mb-3"><i class="feather-log-out blue me-2"></i>{{__('Log ins')}}</h5>
                            <h6 class="mb-3">{{__('All Users')}}</h6>
                            <ul class="list-name-value">
                                <li>
                                    <span class="left">{{__('Daily')}}</span>
                                    <span class="right">{{$loginCounts['allUserDaily']}}</span>
                                </li>
                                <li>
                                    <span class="left">{{__('Weekly')}}</span>
                                    <span class="right">{{$loginCounts['allUserWeekly']}}</span>
                                </li>
                                <li>
                                    <span class="left">{{__('Monthly')}}</span>
                                    <span class="right">{{$loginCounts['allUserMonthly']}}</span>
                                </li>
                            </ul>
                            <h6 class="mt-5 mb-3">{{__('Your stats')}}</h6>
                            <ul class="list-name-value">
                                <li>
                                    <span class="left">{{__('Daily')}}</span>
                                    <span class="right">{{$loginCounts['singleUserDaily']}}</span>
                                </li>
                                <li>
                                    <span class="left">{{__('Weekly')}}</span>
                                    <span class="right">{{$loginCounts['singleUserWeekly']}}</span>
                                </li>
                                <li>
                                    <span class="left">{{__('Monthly')}}</span>
                                    <span class="right">{{$loginCounts['singleUserMonthly']}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-12 mb-4">
                    <!--<h5 class="mb-4">People</h5>
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row mb-5">
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <h5 class="fs-18"><i class="feather-user blue me-2"></i>Users</h5>
                                    <h4 class="light-green">250</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-6">

                                            <h6 class="mb-3">Age</h6>
                                            <canvas id="age" style="width:60%;max-width:400px;"></canvas>
                                        </div>
                                        <div class="col-6">

                                            <h6 class="mb-3">Genre</h6>
                                            <canvas id="genre" style="width:60%;max-width:400px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="mb-5">Location</h6>
                                    <ul class="list-name-value dashed">
                                        <li>
                                            <span class="left">Québec</span>
                                            <span class="right">15</span>
                                        </li>
                                        <li>
                                            <span class="left">Shawinigan</span>
                                            <span class="right">13</span>
                                        </li>
                                        <li>
                                            <span class="left">Trois-Rivières</span>
                                            <span class="right">9</span>
                                        </li>
                                        <li>
                                            <span class="left">Saguenay</span>
                                            <span class="right">5</span>
                                        </li>
                                        <li>
                                            <span class="left">Victoriaville</span>
                                            <span class="right">2</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        let routes = [];
        routes['message-sent-graph'] = "{{route('seller.member.message-sent-stats')}}";
        routes['new-customer-graph'] = "{{route('seller.member.new-customer-stats')}}";
        routes['new-distributor-graph'] = "{{route('seller.member.new-distributor-stats')}}";
    </script>
    <script src="{{ asset('/assets/js/stats.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
