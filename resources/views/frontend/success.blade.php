@extends('layouts.frontend.index')
@section('content')
    <section id="success-page" class="d-flex flex-column" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-sm-8 col-10 mx-auto">
                    <div class="h-100 d-flex flex-column">
                        <div class="top text-center">
                            <img class="img-fluid mb-5" src="{{ asset('assets/images/modal-login.png') }}" alt="">
{{--                            <h4 class="mb-4">Thanks for registering</h4>--}}
{{--                            <p class="fs-16 mb-5">Welcome to Rankup</p>--}}
                            <h3>{{__('Your account has been created successfully. To access Rankup, please activate your account. Details will be emailed to you.')}}</h3>
                            <a href ="{{ route('login') }}" class="btn btn-blue-gradient mt-2">{{__('Login')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-auto">
            <div class="row">
                <div class="col-xl-4 col-md-6 col-sm-8 col-10 mx-auto">
                    <div class="bottom">
                        <img class="img-fluid d-block mx-auto mt-5" width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection