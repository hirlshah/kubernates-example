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
            @include('seller.setting.sidebar')
            <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-8">
                <div class="d-flex flex-column flex-shrink-0 shadow-custom rounded-3 h-100 p-md-5 py-4 px-3">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="v-pills-tab-1">
                            @if($page)
                                {!! $page->description !!}
                            @endif
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection