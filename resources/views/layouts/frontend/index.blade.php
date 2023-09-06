<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::openGraph() !!}
    {!! MetaTag::tag('description') !!}
    {!! MetaTag::tag('image', asset(config('app.rankup.company_logo_path'))) !!}

    <!-- favicon icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('app.rankup.company_favicon')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('app.rankup.company_favicon')) }}">

    <!-- Scripts -->
    <script src="{{ asset('assets/js/main/jquery.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/parsley.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/bootstrap/popper.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/plugins/dropzone/min/dropzone.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/main/jquery.validate.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/main/additional-methods.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment_locales.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment-timezone-with-data-10-year-range.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/js.cookie.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/frontend_custom.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/jquery.mask.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
    <script src="{{ asset('assets/js/inputmask.bundle.min.js?ver=')}}{{env('JS_VERSION') }}"></script>

    <!-- Appzi: Capture Insightful Feedback -->
    <script async src="https://w.appzi.io/w.js?token=xYokn"></script>
    <!-- End Appzi -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/icons/feather-icons-web/feather.css') }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset(config('app.rankup.company_css_file')) }}">
    <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css')}}" rel="stylesheet">
    @yield('style')
</head>

<body>
    <div id="home" class="inter d-flex flex-column" style="min-height: 100vh;">
        @include('layouts.frontend.header')
        <main>
            @yield('content')
        </main>
        @include('layouts.frontend.footer')
    </div>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('frontend-scripts')
</body>
</html>
