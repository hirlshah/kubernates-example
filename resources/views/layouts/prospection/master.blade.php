<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($prospectionVideo) ? $prospectionVideo->title : __("contact them!") }}</title>
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
  <div class="vstack vh-100 bg-dark-gray">
      @include('layouts.prospection.header')
      <main>
          @yield('content')
      </main>
      @include('layouts.prospection.footer')
  </div>
</body>

<script type="text/javascript">
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  if(!$('.thankyou-bg').length) {
    $('#video-visiter-form input').on('input',function(e){
    $('.video-visiter-form-btn').removeClass('active');
    var idActiveClass = false;
    var nameInput = $('.video-visiter-input-name');
    var lastNameInput = $('.video-visiter-input-lastname');
    var emailInput = $('.video-visiter-input-email');
    var phoneInput = $('.video-visiter-input-phone');
    if(nameInput.val() != ''  && lastNameInput != '' && emailInput.val() != '' && phoneInput.val() != '') {
      idActiveClass = true;
    } else {
      idActiveClass = false;
    }
    if(idActiveClass) {
      $('.video-visiter-form-btn').addClass('active');
    } else {
      $('.video-visiter-form-btn').removeClass('active');
    }
  });
  var isAuth = "{{ auth()->check() ? 'true' : 'false' }}";

  $.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
      return null;
    }
    return decodeURI(results[1]) || 0;
  }

  if(isAuth === 'true') {
    let url = "{{ route('frontend.video.visiter.form') }}";
    let userEmail = "{{ Auth::User() ? Auth::User()->email : null }}";
    let userName = "{{ Auth::User() ? Auth::User()->name : null }}";
    let userPhone = "{{ Auth::User() && !empty(Auth::User()->phone) ? Auth::User()->phone : app\Models\User::USER_PHONE_CHECK }}";
    let slug = "{{ isset($slug) ? $slug : "" }}";
    formData = new FormData();
    formData.append("name", userName);
    formData.append("email", userEmail);
    formData.append("phone", userPhone);
    formData.append("slug", slug);
    formData.append("referral", $.urlParam('referral'));
    videoVisitor(formData, url);
  } else {
    $('#staticVideoModal').modal({backdrop: 'static',keyboard: false});
    $('#staticVideoModal').modal('show');
  }

  $("#phone").intlTelInput({
    initialCountry: "ca",
    separateDialCode: true
  });
  var instance = $("[name=phone]")
  $("[name=phone]").on("blur", function() {
  var code = instance.intlTelInput('getSelectedCountryData').dialCode;
  $('#country_code').val(code); //get counrty code
  });

  localStorage.removeItem('user_data');
  localStorage.removeItem('video_visiter_id');

  $("#video-visiter-form").submit(function(event) {
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    formData.append("referral", $.urlParam('referral'));
    videoVisitor(formData, url);
  });

  function videoVisitor(formData, url) {
    $.ajax({
      type: 'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function(data) {
        if ($.isEmptyObject(data.errors)) {
          if (data.success) {
            $('.video-visiter-screen').html(data.content);
            $(".video-container").removeClass("d-none");
            $('#staticVideoModal').hide();
            $('#staticVideoModal').removeClass('show');
            $('.modal-backdrop').remove();
            $('.v-text').hide();
            $('.video-content').addClass('active');
            $('.video-btn').addClass('active');
            localStorage.setItem("user_data", JSON.stringify(data.user_data));
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error: function(data) {
        printErrorMsg(data.responseJSON.errors);
      }
    });
  }
  }
  
</script>

</html>
