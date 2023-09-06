@extends('layouts.frontend.index')
@section('content')
<div class="container verify-email">
    <div class="row my-auto justify-content-center">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    <div class="body-text fs-18 mb-3">
                        {{ __('Avant de proceder, veuillez cliquez sur le lien dans vos courriels')}},
                    </div>
                        <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-blue align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
