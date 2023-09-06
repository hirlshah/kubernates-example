@extends('layouts.seller.index')
@section('title', isset($coupon)? 'Update Coupon' :'Create Coupon')
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{Session::get('error')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card table-card p-3">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">@if(isset($coupon)) {{__('Update')}} @else {{__('Create')}} @endif {{__('Coupon')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($coupon))
                            {{ Form::model($coupon, ['route' => ['coupons.update', $coupon->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'coupons.store' , 'enctype'=>'multipart/form-data']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Code <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('code',Request::old('code'),array('class'=>"form-control")) }}
                                            @if ($errors->has('code'))
                                                <span class="text-danger">{{ $errors->first('code') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3"> {{__('Is Active')}}</label>
                                        <div class="col-lg-9">
                                            @if(!isset($coupon))
                                                {{ Form::checkbox('is_active','0',false, ['class'=>'form-check-input custom-checkbox-3']) }}
                                            @else
                                                @if($coupon->is_active == true)
                                                    {{ Form::checkbox('is_active','1',true, ['class'=>'form-check-input custom-checkbox-3']) }}
                                                @else
                                                    {{ Form::checkbox('is_active','1',false, ['class'=>'form-check-input custom-checkbox-3']) }}
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="row">
                                            <label class="col-form-label col-lg-3"> {{__('Expiration')}} <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                {{ Form::text('expiration',Request::old('expiration'),array('class'=>"form-control datetimepicker", 'id'=>'expiration')) }}

                                                @if ($errors->has('expiration'))
                                                    <span class="text-danger">{{ $errors->first('expiration') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="row">
                                            <label class="col-form-label col-lg-3"> {{__('Min Downline')}} </label>
                                            <div class="col-lg-9">
                                                {{ Form::text('min_downline',Request::old('min_downline'),array('class'=>"form-control")) }}
                                                @if ($errors->has('min_downline'))
                                                    <span class="text-danger">{{ $errors->first('min_downline') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <fieldset>
                            <div class="row">
                                {{-- Not needed right now
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Min Usd Amount</label>
                                        <div class="col-lg-9">
                                           {{ Form::number('min_usd_amount',Request::old('min_usd_amount'),array('class'=>"form-control", 'id'=>'min_usd_amount'))}}
                                            @if ($errors->has('min_usd_amount'))
                                                <span class="text-danger">{{ $errors->first('min_usd_amount') }}</span>
                                            @endif 
                                        </div>
                                    </div>
                                </div>--}}
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3"> {{__('Discount Percentage')}} <span class="text-danger">*</span> </label>
                                        <div class="col-lg-9">
                                            {{ Form::text('discount_percentage',Request::old('discount_percentage'),array('class'=>"form-control")) }}
                                            @if ($errors->has('discount_percentage'))
                                                <span class="text-danger">{{ $errors->first('discount_percentage') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3"> {{__('Discount Amount')}} <span class="text-danger">*</span> </label>
                                        <div class="col-lg-9">
                                            {{ Form::text('discount_amount',Request::old('discount_amount'),array('class'=>"form-control")) }}
                                            @if ($errors->has('discount_amount'))
                                                <span class="text-danger">{{ $errors->first('discount_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">

                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Description')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::textarea('description',Request::old('description'),array('class'=>"form-control", 'id'=>'description', 'rows'=>3))}}
                                            @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-2')) }}
                            <a href="{{ url('admin/coupons') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
