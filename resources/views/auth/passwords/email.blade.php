@extends('layouts.frontend.index')
@section('content')
    <section id="login-page" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-10 mx-auto">
                    <div class="text-center">
                        <img class="img-fluid" src="{{ asset('assets/images/modal-login.png') }}" alt="">
                        <img class="img-fluid d-block mx-auto my-5" width="200" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                        <h6>{{ __('Reset Password') }}</h6>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ __(session('status')) }}
                            </div>
                        @endif
                        <form method="post" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row mt-4">
                                <div class="col-10 mx-auto mb-4 mb-md-5">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email') }}">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ __($message) }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-4 mb-md-5">
                                    <button type="submit" class="btn btn-blue-gradient fw-bold w-100">{{__('Send Password Reset Link')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
