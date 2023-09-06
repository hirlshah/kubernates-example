@extends('layouts.frontend.index')
@section('content')
    <section id="login-page" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-10 mx-auto">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8 col-7 mx-auto">
                            <img class="img-fluid" src="{{ asset('assets/images/login.png') }}" alt="">
                        </div>
                    </div>
                    <div class="text-center">
                        <img class="img-fluid d-block mx-auto my-5" width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                        <form method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="row">
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input type="email" class="form-control" name="email" id="login-email"  value="{{ old('email') }}"  placeholder="{{__('Email')}}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                    @if (session('error'))
                                        <span class="text-danger admin-login-alert" role="alert">
                                            <strong>{{ session('error') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input type="password" class="form-control" name="password" id="login-password" placeholder="{{__('Password')}}">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="col-12 mb-4">
                                    <button type="submit" class="btn btn-blue-gradient fw-bold w-100">{{__('Login')}}</button>
                                </div>
                                <!-- <div class="d-flex align-items-center gap-3 mb-4">
                                    <span class="flex-fill border-white"></span>
                                    <p class="text-blue font-7 mb-0">
                                        <span>or</span>
                                    </p>
                                    <span class="flex-fill border-white"></span>
                                </div>
                                <div class="col-12 mb-4 mb-md-5">
                                    <div class="d-flex align-items-center gap-4 justify-content-center">
                                        <a class="signin-register" href="{{ url('/login/google') }}">
                                            <img class="img-fluid" src="{{ asset('assets/images/icons/google.svg') }}" alt="google">
                                        </a>
                                        <a class="signin-register" href="{{ url('/login/facebook') }}">
                                            <img class="img-fluid" src="{{ asset('assets/images/icons/facebook.svg') }}" alt="facebook">
                                        </a>
                                    </div>
                                </div> -->
                                <div class="col-12 mb-4 mb-md-3">
                                    <a class="signin-forgot" href="{{ route('password.request') }}">{{__('Forgot my password')}}</a>
                                </div>
                                <div class="col-12">
                                    <a class="signin-register" href="{{ route('register') }}">{{__('Register')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
