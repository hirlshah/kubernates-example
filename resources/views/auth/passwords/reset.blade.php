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
                        <img class="img-fluid d-block mx-auto my-5" width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                        <h6>{{ __('Reset Password') }}</h6>
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="row mt-4">
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Email') }}">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                                </div>
                                <div class="col-12 mb-4 mb-md-5">
                                    <button type="submit" class="btn btn-blue-gradient fw-bold w-100">{{__('Reset Password')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection