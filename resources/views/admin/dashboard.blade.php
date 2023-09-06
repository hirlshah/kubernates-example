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
                    <li class="breadcrumb-item active" aria-current="page">{{__('Dashboard')}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid">
            <h5 class="mb-4">{{__('People')}}</h5>
            <div class="card p-4 p-md-5">
                <div class="people-contact pb-md-3">
                    <div class="people-contact-col">
                        <h5 class="fs-18 mb-4">{{__('Active users')}}</h5>
                        <h6 class="grey-c0c0c0 fs-14">&nbsp;</h6>
                        <h1 class="blue mt-5 fs-60 fw-bold">{{ $activeUsers }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <h5 class="fs-18 mb-4">{{__('Canceled Users')}}</h5>
                        <h6 class="grey-c0c0c0 fs-14">&nbsp;</h6>
                        <h1 class="blue mt-5 fs-60 fw-bold">{{ $canceledUsers }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <h5 class="fs-18 mb-4">{{__('Weekly Users')}}</h5>
                        <h6 class="grey-c0c0c0 fs-14">&nbsp;</h6>
                        <h1 class="blue mt-5 fs-60 fw-bold">{{ $weeklyUsers }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3">
            <h5 class="mb-4">{{__('Plan Counts')}}</h5>
            <div class="card p-4 p-md-5">
                <div class="people-contact pb-md-3">        
                    @foreach($planCounts as $planCount)
                        <div class="people-contact-col">
                            <h5 class="fs-18 mb-4">{{ $planCount['name'] }}</h5>
                            <h6 class="grey-c0c0c0 fs-14">&nbsp;</h6>
                            <h1 class="blue mt-5 fs-60 fw-bold">{{ $planCount['count'] }}</h1>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection