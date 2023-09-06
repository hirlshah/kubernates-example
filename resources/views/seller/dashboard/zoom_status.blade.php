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
            <i class="feather-bar-chart me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('seller-dashboard') }}">{{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('Recent Zooms')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <a class="notification" href=""><i class="feather-bell blue"></i></a>
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid mb-4">
            <div class="zoom-header d-flex align-items-center justify-content-between">
                <div class="left d-flex align-items-center">
                    <a href="{{ route('seller-dashboard') }}"><i class="feather-arrow-left blue fs-24 me-3"></i></a>
                    <h5>{{ $event->name }}</h5>
                </div>
                <div class="right">
                    <i class="feather-flag rounded-border green"></i>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="zoom-header d-flex align-items-center justify-content-between">
                <div class="left d-flex align-items-center">
                    <p class="grey-666666">{{__('Organized by')}}: {{ $event->user->name }} </p>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-3">
            <div class="tags mb-4">
                @foreach($event->tags as $tag)
                    <span>{{ $tag->name }}</span>
                @endforeach
                <span>{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                <span>{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i') }}h</span>
            </div>
            <div class="row">
                <div class="col-xl-6 col-12 mb-4">
                    <img class="img-fluid" src="@if(isset($event->image) && Storage::disk('public')->exists($event->image)){{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}@else {{ asset((config('app.rankup.company_default_image_file'))) }} @endif" alt="">
                   
                    <div class="col-xl-6 col-12 mb-6">
                        <p class="lh-lg fs-14">{{ $event->content }}</p>
                    </div>
                </div>
                <div class="col-xl-6 col-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="analytics-col">
                                <div class="analytics-top">
                                    <h5 class="fs-18" style="min-height: 44px;">{{__('Representatives present')}}</h5>
                                </div>
                                <div class="tags mb-4">
                                    @foreach($eventRepoAll  as $eventRep)
                                        <span class="fs-14"><a href="{{ route('seller.member.profile', $eventRep->id) }}">{{ $eventRep->name }}</a></span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="line-title my-4">
                <h6>{{__('General Analytics')}}</h6>
            </div>
            <div class="row">
                <div class="col-xl-4 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fs-18 mb-3">{{__('General')}}</h6>
                                <span class="fs-14 grey-c0c0c0">{{$confirmContact}} {{__('confirmed people')}}</span>
                            </div>
                            <div class="graph">
                                <canvas id="general" style="width:70%;max-width:400px;height:137px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('confirmed people')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{$totalContact?round($confirmContact * 100 / $totalContact):0}}% {{__('at Zoom')}})</h6>
                                                <span class="circle blue-a"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$confirmContact}}</h1>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('People who showed up')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{$totalContact?round($showedContact * 100 / $totalContact):0}}% {{__('at Zoom')}})</h6>
                                                <span class="circle blue-b"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$showedContact}}</h1>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('Follow up percentage')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{!empty($stats[$statusArray['FOLLOWUP']]) && $confirmContact?(round($stats[$statusArray['FOLLOWUP']] * 100 / $confirmContact)):0}}% {{__('follow up')}})</h6>
                                                <span class="circle blue-c"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$stats[$statusArray['FOLLOWUP']]??0}}</h1>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('Not interested')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{!empty($stats[$statusArray['NOT_INTERESTED']]) && $confirmContact?(round($stats[$statusArray['NOT_INTERESTED']] * 100 / $confirmContact)):0}}% {{__('of total')}})</h6>
                                                <span class="circle blue-d"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$stats[$statusArray['NOT_INTERESTED']]??0}}</h1>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('New client')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{!empty($stats[$statusArray['NEW_CLIENT']]) && $confirmContact?(round($stats[$statusArray['NEW_CLIENT']] * 100 / $confirmContact)):0}}% {{__('client')}})</h6>
                                                <span class="circle blue-b"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$stats[$statusArray['NEW_CLIENT']]??0}}</h1>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-sm-6 col-12 analytics">
                                    <div class="analytics-col">
                                        <div class="analytics-top">
                                            <h5 class="fs-18 mb-4" style="min-height: 44px;">{{__('New distributor')}}</h5>
                                            <div class="analytics-percentage">
                                                <h6 class="grey-c0c0c0 fs-14 d-inline-block me-2">({{!empty($stats[$statusArray['NEW_DISTRIBUTOR']]) && $confirmContact?(round($stats[$statusArray['NEW_DISTRIBUTOR']] * 100 / $confirmContact)):0}}% {{__('distributor')}})</h6>
                                                <span class="circle blue-b"></span>
                                            </div>
                                        </div>
                                        <h1 class="blue mt-4 fs-60 fw-bold">{{$stats[$statusArray['NEW_DISTRIBUTOR']]??0}}</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--
            <div class="line-title my-4">
                <h6>{{__('Conversion Stats')}}</h6>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">{{__('Nº of representants')}}</h5>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">1.000</h1>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">{{__('Nº Of guests')}}</h5>
                                    <div class="stats-percentage">
                                        <h6 class="grey-c0c0c0 fs-14">5% ({{('of total')}})</h6>
                                    </div>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">95%</h1>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">% {{('(Of closed reps')}}</h5>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">{{!empty($stats[$statusArray['NEW_DISTRIBUTOR']])?(round($stats[$statusArray['NEW_DISTRIBUTOR']] * 100 / $totalContact)):0}}%</h1>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">% {{('(Of closed customers')}}</h5>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">{{!empty($stats[$statusArray['NEW_CLIENT']])?(round($stats[$statusArray['NEW_CLIENT']] * 100 / $totalContact)):0}}%</h1>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">% {{__('Follow up')}}</h5>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">{{!empty($stats[$statusArray['FOLLOWUP']])?(round($stats[$statusArray['FOLLOWUP']] * 100 / $totalContact)):0}}%</h1>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-xl-4 col-sm-6 col-12 stats">
                            <div class="stats-col">
                                <div class="stats-top">
                                    <h5 class="fs-18 mb-4">% {{__('Not interested')}}</h5>
                                </div>
                                <h1 class="blue mt-4 fs-60 fw-bold">{{!empty($stats[$statusArray['NOT_INTERESTED']])?(round($stats[$statusArray['NOT_INTERESTED']] * 100 / $totalContact)):0}}%</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            --}}

        </div>
    </div>
</div>

<script>
var ctx = document.getElementById('general').getContext('2d');
var statChartData = [{{$confirmContact}},{{$showedContact}},{{$stats[$statusArray['FOLLOWUP']]??0}},{{$stats[$statusArray['NOT_INTERESTED']]??0}}];
var general = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Confirmées', 'Présentes', 'Suivis', 'Non intéressées'],
        datasets: [{
            label: '#',
            data: [{{$confirmContact}},{{$showedContact}},{{$stats[$statusArray['FOLLOWUP']]??0}},{{$stats[$statusArray['NOT_INTERESTED']]??0}}],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        legend: {
            display: false
        },
         scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    max: Math.max(...statChartData) + 5,
                    stepSize: 1
                }
            }]
        }
    }
});
</script>

@endsection
