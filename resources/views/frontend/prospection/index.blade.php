@extends('layouts.prospection.master')
@section('content')
    <div class="video-bg">
        <div class="video-bg-img"></div>
        <div class="container">
            <div class="v-main text-center">
                <div class="white-border">
                    @if(isset($referralUser->thumbnail_image) && !empty($referralUser->thumbnail_image) && is_file(public_path("storage/".$referralUser->thumbnail_image)))
                        <img class="img-fluid rounded-circle w-100 h-100" src="{{ App\Classes\Helper\CommonUtil::getUrl($referralUser->thumbnail_image) }}" />
                    @else
                        <img class="img-fluid rounded-circle w-100 h-100" src="{{ asset('assets/images/profile-icon-large.png') }}" />
                    @endif
                </div>
                <p class="font-semibold mt-2">@if(isset($referralUser) && !empty($referralUser)) {{ $referralUser->name }} @endif</p>
                <h4><i>@if(!empty($prospectionVideo->custom_title))“{{ $prospectionVideo->custom_title }}”@endif</i></h4>
            </div>
        </div>
        <div class="container">
            <div class="video-sec">
                <div class="video-content">
                    <div class="video-visiter-screen">
                        <video width="100%" height="100%" controls="" data-status="false">
                            <source type="video/mp4" src="{{ asset('video/testVideo.mp4') }}">
                        </video>
                    </div>
                    <div class="overlay"></div>
                    <div class="form-content">
                        <form method="post" action="{{ route('frontend.video.visiter.form') }}" data-slug=" @if (isset($slug)) {{ $slug }} @endif" id="video-visiter-form">
                            @csrf
                            <input type="hidden" name="slug" class="slug" value="@if (isset($slug)) {{ $slug }} @endif">
                            <input type="hidden" name="referral" value="{{ request()->get('referral') }}">
                            <div class="form-heading">
                                <h2 class="text-center">{{ __('Before I begin, allow me')}}</h2>
                                <h2 class="text-center">{{ __('to know more about you')}}</h2>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-input">
                                        <label class="text-white">{{__('First name')}}</label>
                                        <input type="text" name="first_name" class="mt-2 video-visiter-input-name form-control bg-transparent border-top-0 border-end-0 border-start-0 rounded-0 shadow-none ps-0 text-white" id="name" placeholder="{{__('First name')}}">
                                        <span class="text-danger print-error-msg-first_name" style="display:none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-input">
                                        <label class="text-white">{{__('Last name')}}</label>
                                        <input type="text" name="last_name" class="mt-2 video-visiter-input-lastname form-control bg-transparent border-top-0 border-end-0 border-start-0 rounded-0 shadow-none ps-0 text-white" id="name" placeholder="{{__('Last name')}}">
                                        <span class="text-danger print-error-msg-last_name" style="display:none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-input">
                                        <label class="text-white">{{__('Email')}}</label>
                                        <input type="email" name="email" class="mt-2 video-visiter-input-email form-control bg-transparent border-top-0 border-end-0 border-start-0 rounded-0 shadow-none ps-0 text-white" id="email" placeholder="{{__('Email')}}">
                                        <span class="text-danger print-error-msg-email" style="display:none"></span>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-input">
                                        <label class="text-white">{{__('Phone number')}}</label>
                                        <input id="country_code" type="hidden" name="country_code">
                                        <input id="phone" type="text" name="phone" class="mt-2 video-visiter-input-phone form-control bg-transparent border-top-0 border-end-0 border-start-0 rounded-0 shadow-none text-white" maxlength="10" placeholder="{{__('Phone number')}}">
                                        <span class="text-danger print-error-msg-phone" style="display:none"></span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="video-btn text-center video-visiter-form-btn" type="submit">
                                        <div class="d-flex align-items-center gap-3">
                                            <svg width="24" height="28" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M24 14L3.56034e-07 27.8564L2.55621e-08 0.143594L24 14Z" fill="#56B2FF"/>
                                            </svg>
                                            <h2>{{__('Play video')}}</h2>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
