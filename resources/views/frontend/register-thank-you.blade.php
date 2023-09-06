<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <!-- favicon icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('app.rankup.company_favicon')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('app.rankup.company_favicon')) }}">
    <!-- Scripts -->
    <script src="{{ asset('assets/js/main/jquery.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/parsley.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/popper.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/main/jquery.validate.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone/min/dropzone.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/main/additional-methods.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment_locales.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment-timezone-with-data-10-year-range.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/plugins/js.cookie.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/frontend_custom.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <script src="{{ asset('assets/js/jquery.mask.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <!-- intlTelInput -->
    <script src="{{ asset('assets/js/intlTelInput-jquery.min.js?ver=')}}{{env('JS_VERSION') }}"></script>
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
    <link rel="stylesheet" href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css') }}">
    <!-- intlTelInput -->
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css')}}">
</head>

<body>
    <div class="vstack bg-dark-gray" style="min-height:100vh;">
        <section class="vstack" id="login-page" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-12 mx-auto">
                        <div class="row">
                            <div class="col-xl-9 col-lg-8 col-7 mx-auto">
                                <img class="img-fluid" src="{{ asset('assets/images/login.png') }}" alt="">
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="mt-4 fs-2">{{__('Thanks for registering')}}</p>
                            <p class="fs-6">{{__('Welcome to')}} Rankup</p>
                            <a href="{{ route('login')}}" class="btn btn-blue mt-2 rounded-pill">{{__('Let’s start!')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer id="footer" class="mt-auto">
            <div class="container">
                <div class="row">
                    <div class="text-center">
                        <img class="text-center" width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
