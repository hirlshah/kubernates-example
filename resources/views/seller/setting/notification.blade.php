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
            <a class="notification d-none" href=""><i class="feather-bell blue"></i></a>
            @include('seller.common._language')
        </div>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success" id="successMessage">
            {{Session::get('success')}}
        </div>
    @endif
    <div class="content-body settings-body p-0">
        <div class="row h-100">
            @include('seller.setting.sidebar')
            <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-8">
                <div class="d-flex flex-column flex-shrink-0 shadow-custom rounded-3 h-100 p-md-5 py-4 px-3">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="v-pills-tab-1">
                        {{ Form::model($notification, ['route' => ['seller.setting.savenotification', $notification->id], 'method' => 'post']) }}
                            <div class="row">
                                <div class="col-xl-4 col-12 mb-4 d-flex align-items-center justify-content-between">
                                    <h6>{{__('Notifications')}}</h6>
                                    <label class="switch">
                                        {{ Form::checkbox('is_notification',$notification->is_notification,($notification->is_notification==1?'true':false), ['class'=>'checkbox is_notification']) }}
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="divider-ef mt-4 mb-4"></div>
                            <div class="row mb-5 notification">
                                <div class="col-12 mb-4">
                                    <h6 class="fs-16 fw-normal">{{__('Notifications me via:')}}</h6>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 115px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                <input type="hidden" name="is_email" value="0" class="checkvalue"/>
                                                {{ Form::checkbox('is_email',$notification->is_email,($notification->is_email==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue','id'=>'flexCheckDefault']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                        {{__('Email')}}
                                                    </label>
                                                    <p class="grey mb-0 fs-16 text-break">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 115px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                <input type="hidden" name="is_sms" value="0" class="checkvalue"/>
                                                {{ Form::checkbox('is_sms',$notification->is_sms,($notification->is_sms==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                        {{__('SMS')}}
                                                    </label>
                                                    <p class="grey mb-0 fs-16">{{__('Mobile')}} - {{ $user->phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 115px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                <input type="hidden" name="is_push_notifications" value="0" class="checkvalue"/>
                                                {{ Form::checkbox('is_push_notifications',$notification->is_push_notifications,($notification->is_push_notifications==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                        Push Up Notifications
                                                    </label>
                                                    <p class="grey mb-0 fs-16">Mobile - {{ $user->phone }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row mb-5 notification">
                                <div class="col-12 mb-4">
                                    <h6 class="fs-16 fw-normal">{{__('Notificate me when:')}}</h6>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 100px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                    <input type="hidden" name="is_message" value="0" class="checkvalue"/>
                                                    {{ Form::checkbox('is_message',$notification->is_message,($notification->is_message==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                        {{__('New Messages')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 100px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                    <input type="hidden" name="is_leads" value="0" class="checkvalue"/>
                                                    {{ Form::checkbox('is_leads',$notification->is_leads,($notification->is_leads==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                        {{__('New leads')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-6 col-md-12 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3 d-flex align-items-center" style="min-height: 100px;">
                                            <div class="form-check check-2 ps-4 ps-xxl-5">
                                                    <input type="hidden" name="is_event" value="0" class="checkvalue"/>
                                                    {{ Form::checkbox('is_event',$notification->is_event,($notification->is_event==1?'true':false), ['class'=>'form-check-input me-2 rounded-0 checkvalue']) }}
                                                <div class="d-inline-block">
                                                    <label class="form-check-label fs-16" for="">
                                                       {{__('New events')}}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button class="btn btn-blue fs-16 fw-bold" type="submit">{{__('Update')}}</button>
                                </div>
                            </div>
                        {{ Form::close() }}
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
    enabledisable('{{$notification->is_notification}}')
    $( document ).ready(function() {
        $( ".is_notification" ).click(function( event ) {
            let val = $(this).val();
            if(val==1){
                $(this).val('0')
                enabledisable(0)
            }else{
                $(this).val('1')
                enabledisable(1)
            }
        });
        $( ".checkvalue" ).click(function( event ) {
            let val = $(this).val();
            if(val==1){
                $(this).val('0')
            }else{
                $(this).val('1')
            }
        });
    });
    function enabledisable(val){
        if(val==1){
            $('.checkvalue').removeAttr("disabled")
            $(".notification").css("opacity", "1");
        }else{
            $('.checkvalue').attr('disabled', 'disabled');
            $(".notification").css("opacity", "0.5");
        }
    }
    if($('#successMessage').length) {
        window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000);    
    }
</script>

@endsection
