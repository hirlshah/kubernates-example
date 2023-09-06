@extends('layouts.prospection.master')
@section('content')
    <div class="thankyou-bg">
        <div class="thankyou-bg-img"></div>
        <div class="container">
            <div class="content text-center">
                <div class="white-border">
                    @if(isset($referralUser->thumbnail_image) && !empty($referralUser->thumbnail_image) && is_file(public_path("storage/".$referralUser->thumbnail_image)))
                        <img class="img-fluid rounded-circle w-100 h-100" src="{{ App\Classes\Helper\CommonUtil::getUrl($referralUser->thumbnail_image) }}" />
                    @else
                        <img class="img-fluid rounded-circle w-100 h-100" src="{{ asset('assets/images/profile-1.png') }}" />
                    @endif
                </div>
                <p class="font-semibold mt-2 mb-5"> @if(isset($referralUser) && !empty($referralUser)) {{ $referralUser->name }} @endif </p>
                <h1 class="mb-4">{{__('Thank you for completing the survey')}}</h1>
                <h6>{{__('The answers have been sent to')}} @if(isset($referralUser) && !empty($referralUser)) {{ $referralUser->name }} @endif {{__('who will contact you shortly')}}</h6>
            </div>
        </div>
    </div>
@endsection
