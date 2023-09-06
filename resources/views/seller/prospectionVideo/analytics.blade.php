@extends('layouts.seller.index')

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
        <div class="content-header-left d-sm-flex align-items-center mb-lg-5 mb-2">
            <div class="d-flex">
                <i class="feather-video me-3 text-primary"></i>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active d-inline-block" aria-current="page"> 
                            <a href="{{ route('prospection.index')}}"><span class="text-decoration-underline">{{ $prospectionVideo->title }}</span></a> 
                            <span class="d-inline-block">
                                <i class="fa fa-circle fs-8 mx-2" aria-hidden="true"></i> {{ __('Stats')}}
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="content-body mt-1">
        <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="prospection_slug" id="prospection_slug" value="{{ $prospectionVideo->slug }}">
        <div class="test-class"></div>

        <div class="row gy-lg-4 gy-2 mb-4">
            <div class="col-sm-5">
                <h6 class="fs-18">{{ __('Insights')}}</h6>
            </div>
            <div class="col-sm-7">
                @if(auth()->user()->id == $prospectionVideo->user_id)
                    <div class="d-flex align-items-center gap-lg-4 gap-2 justify-content-end">
                        <label>{{__('Data from')}}</label>
                        <select class="form-select border-0 custom-arrow refferal_user">
                            <option value="0" seleted>{{ __('All') }}</option>
                            @foreach($refferalUserName as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
        @include('seller.prospectionVideo.chart_data')
        <div class="card prospection-video-analytics">
            <div class="card-body p-xl-5 p-4">
                <div class="row gy-lg-5 gy-3 prospection-video-analytics-wrapper pb-4" id="analytic_count_section">
                </div>
            </div>
        </div>
        <h6 class="fs-18 mb-4 mt-5">{{ __('People')}}</h6>
        <h6 class="fs-18 mb-3">{{ __('All the people who watched your video')}}</h6>
        <div class="card">
            <div class="card-body p-0" id="analytic_people_section">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    let prospectionVideoId = "{{ $prospectionVideo->id }}";
    let prospectionFullViewRoute = "{{ route('prospection-full-view-graph')}}";
    let prospectionPartialViewRoute = "{{ route('prospection-partial-view-graph')}}";
    let prospectionNotPlayedRoute = "{{ route('prospection-not-played-graph')}}";
    let prospectionAnaylyticsPeopleViewRoute = "{{ route('prospection.analytics.people-view', ['',''])}}";
    let prospectionVisitorsCountSectionRoute = "{{ route('prospection.analytics.visitor-stats', ['',''])}}";
</script>
<script src="{{ asset('/assets/js/prospection_video_graph.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection

