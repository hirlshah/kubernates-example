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
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-help-circle me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Help')}}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="content-body p-0">
        <div class="card table-card px-lg-5 px-2 py-3">
            <div id="video-ajax-list">
                @include('seller.helps._help_pagination')
            </div>
        </div>
    </div>
</div>
@endsection


