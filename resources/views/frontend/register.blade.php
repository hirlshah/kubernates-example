@extends('layouts.frontend.index')

@section('style')
    <style>
        .parsley-errors-list li {
            color: #dc3545;
        }
    </style>
@endsection
@section('content')
    <section id="register-page" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
        <div class="container">
            <div class="row mb-5">
                <div class="col-sm-6 mb-5 mb-sm-0">
                    <div class="header-left h-100 d-sm-inline-flex text-center align-items-end">
                        <img class="img-fluid" width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="header-right text-sm-end text-center">
                        <img class="img-fluid" src="{{ asset('assets/images/modal-register.png') }}" alt="">
                    </div>
                </div>
            </div>
            <form method="post" action="{{ route('register') }}"  enctype="multipart/form-data" id="register-form">
                @csrf
                <div class="row">
                    <div class="col-12 mb-2">
                        @if($uplineEmail != '' && !empty(session('referral_code')))
                            <span class="text-success">{{__('You will be linked to your upline') . ' '. $uplineName}}</span>
                        @elseif($uplineEmail == '' && !empty(session('referral_code')))
                            <span class="text-warning">{{__('No upline has been found with this email, so you will be in a new team')}}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        @if (Session::has('error'))
                            <span class="text-danger">{!! session()->get('error')!!}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <h5>{{ __('Join') }}</h5>
                        <ul class="nav nav-pills mb-md-5 mb-4" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">1. {{ __('Personal information') }}</button>
                            </li>
                        </ul>
                        @if(Session::has('registeration_success'))
                            <h6 class="register-success text-success mb-2 mt-2">{{Session::get('registeration_success')}}</h6>
                        @endif
                        <div class="tab-content d-md-block d-none desktop-register-screen" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="row">
                                    <div class="col-md-6 col-12 pe-md-5">
                                        <div class="mb-3 py-2">
                                            <label for="name" class="form-label">{{__('First name')}}</label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('First Name is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="email" class="form-label">{{__('Email')}}</label>
                                            <input type="text" name="email" class="form-control email_field" id="email" value="{{ old('email') }}"
                                            data-parsley-required="true" data-parsley-required-message="{{__('Email is required')}}" data-parsley-group="block-home" data-type="email">
                                            <span class="text-danger" id="email_error"></span>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 mt-md-5 mt-0 py-2">
                                            <div class="profile-image d-flex align-items-center mt-3 mb-md-0 mb-3">
                                                <div class="me-4 platform-profile-image flex-none" id="selected_image" style="background-image:url('{{ asset('assets/images/profile-1.png') }}')">
                                                </div>
                                                <div class="profile-image-text">
                                                    <span class="fs-16 mb-0 font-weight-normal d-block"><b>{{__('Profile Image')}}</b></span>
                                                    <span class="fs-14 mb-0 font-weight-light d-block">{{__('Image cannot exceed 5 Mb')}}</span>
                                                    <input type='file' id="image" onchange="readURL(this);" name="profile_image" style="display:none" data-parsley-required="true"  data-parsley-required-message="{{__('Please select your image')}}" data-parsley-max-file-size="5" data-parsley-fileextension='[jpg,jpeg]' data-parsley-group="block-home" />
                                                    <span class="text-danger print-error-profile_image"></span>
                                                    <a class="btn btn-outline-white px-5 py-3 mt-4 br-20px imgBtn">+ {{__('Photo')}}</a>
                                                    @if ($errors->has('profile_image'))
                                                        <span class="text-danger d-block">{{ $errors->first('profile_image') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 mt-md-5 mt-0 py-2">
                                            <label for="city" class="form-label">{{__('City')}}</label>
                                            <input type="text" name="city" class="form-control" id="city" value="{{ old('city') }}" data-parsley-required="true"  data-parsley-required-message="{{__('City is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="country" class="form-label">{{__('Country')}}</label>
                                            <input type="text" name="country" class="form-control" id="country" value="{{ old('country') }}"data-parsley-required="true" data-parsley-required-message="{{__('Country is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('country'))
                                                <span class="text-danger">{{ $errors->first('country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 ps-md-5">
                                        <div class="mb-4 py-2">
                                            <label for="last_name" class="form-label">{{__('Name')}}</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('Last Name is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('last_name'))
                                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="date_of_birth" class="form-label">{{__('Date Of Birth')}}</label>
                                            <input type="text" class="form-control form-date" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                            name="date_of_birth" placeholder="{{__('yyyy-mm-dd')}}" data-parsley-required="true"  data-parsley-required-message="{{__('Date of birth is required')}}" data-parsley-group="block-home"
                                            readonly>
                                            @if ($errors->has('date_of_birth'))
                                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="password" class="form-label">{{__('Password')}}</label>
                                            <input type="password" name="password" class="form-control" id="password" data-bs-toggle="popover" data-bs-placement="left" data-bs-trigger="focus" data-bs-content="{{__('Password must contain one Capital , one Number and one Symbol.')}}" data-parsley-required="true"  data-parsley-required-message="{{__('Password is required')}}" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-parsley-pattern-message="{{__('Password must have at least one lowercase, uppercase, number and special character')}}" data-parsley-group="block-home">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                                <span class="text-danger print-error-msg-password" style="display:none"></span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="confirm_password" class="form-label">{{__('Confirm Password')}}</label>
                                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" data-parsley-equalto="#password" data-parsley-required="true"  data-parsley-required-message="{{__('Confirm password is required')}}" data-parsley-equalto-message="{{__('The confirm password and password must match.')}}"  data-parsley-group="block-home">
                                            @if ($errors->has('confirm_password'))
                                                <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="user_name" class="form-label">{{__('User name')}}</label>
                                            <input type="text" class="form-control user_name_field" name="user_name" id="user_name" value="{{ old('user_name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('Username is required')}}" data-parsley-group="block-home" data-type="user_name">
                                            <span class="text-danger" id="user_name_error"></span>
                                            @if ($errors->has('user_name'))
                                                <span class="text-danger">{{ $errors->first('user_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 mt-md-5 mt-0 py-2">
                                            <label for="upline_email" class="form-label">{{__('Your Upline Email')}}</label>
                                            <input type="text" name="upline_email" class="form-control" id="upline_email" value="@if($uplineEmail != '') {{ $uplineEmail }}  @else {{ old('upline_email') }} @endif" @if($uplineEmail !='') readonly @endif data-type="upline">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" value="1" id="no_upline" name="no_upline">
                                                <label class="form-check-label" for="exampleCheck1">{{__('I have no upline')}}</label>
                                            </div>
                                            <span class="" id="uplineValidate"></span>
                                            @if ($errors->has('upline_email'))
                                                <span class="text-danger">{{ $errors->first('upline_email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-12 my-2 d-flex align-items-center flex-wrap">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="1" id="agree" name="agree" data-parsley-required="true" data-parsley-required-message="{{__('Please accept terms and conditions')}}" data-parsley-group="block-home">
                                            <label class="form-check-label" for="exampleCheck1">{{__('I accept the Terms and Conditions')}}</label>
                                        </div>
                                        @if ($errors->has('agree'))
                                            <span class="text-danger d-block w-100">{{ $errors->first('agree') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4 col-12 my-2 text-md-end">
                                        <button type="submit" class="submit-btn btn btn-blue-gradient next-step">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content d-md-none mobile-register-screen" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab">
                                <div class="row">
                                    <div class="col-md-6 col-12 pe-md-5">
                                        <div class="mb-3 py-2">
                                            <label for="name" class="form-label">{{__('First name')}}</label>
                                            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('First Name is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="last_name" class="form-label">{{__('Name')}}</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('Last Name is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('last_name'))
                                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="email" class="form-label">{{__('Email')}}</label>
                                            <input type="text" name="email" class="form-control email_field" id="email" value="{{ old('email') }}"
                                            data-parsley-required="true" data-parsley-required-message="{{__('Email is required')}}" data-parsley-group="block-home" data-type="email">
                                            <span class="text-danger" id="email_error"></span>
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-4 py-2">
                                            <label for="date_of_birth" class="form-label">{{__('Date Of Birth')}}</label>
                                            <input type="text" class="form-control form-date" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                            name="date_of_birth" placeholder="{{__('yyyy-mm-dd')}}" data-parsley-required="true"  data-parsley-required-message="{{__('Date of birth is required')}}" data-parsley-group="block-home" readonly>
                                            @if ($errors->has('date_of_birth'))
                                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 mt-md-5 mt-0 py-2">
                                            <label for="city" class="form-label">{{__('City')}}</label>
                                            <input type="text" name="city" class="form-control" id="city" value="{{ old('city') }}" data-parsley-required="true"  data-parsley-required-message="{{__('City is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="country" class="form-label">{{__('Country')}}</label>
                                            <input type="text" name="country" class="form-control" id="country" value="{{ old('country') }}"
                                            data-parsley-required="true" data-parsley-required-message="{{__('Country is required')}}" data-parsley-group="block-home">
                                            @if ($errors->has('country'))
                                                <span class="text-danger">{{ $errors->first('country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 ps-md-5">
                                        <div class="mb-4 py-2">
                                            <label for="password" class="form-label">{{__('Password')}}</label>
                                            <input type="password" name="password" class="form-control" id="password" data-bs-toggle="popover" data-bs-placement="left" data-bs-trigger="focus"
                                            data-bs-content="{{__('Password must contain one Capital , one Number and one Symbol.')}}" data-parsley-required="true"  data-parsley-required-message="{{__('Password is required')}}" data-parsley-pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-parsley-pattern-message="{{__('Password must have at least one lowercase, uppercase, number and special character')}}" data-parsley-group="block-home">
                                            @if ($errors->has('password'))
                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="confirm_password" class="form-label">{{__('Confirm Password')}}</label>
                                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" data-parsley-equalto="#password" data-parsley-required="true"  data-parsley-required-message="{{__('Confirm password is required')}}" data-parsley-equalto-message="{{__('The confirm password and password must match.')}}" data-parsley-group="block-home">
                                            @if ($errors->has('confirm_password'))
                                                <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 py-2">
                                            <label for="user_name" class="form-label">{{__('User name')}}</label>
                                            <input type="text" class="form-control user_name_field" name="user_name" id="user_name" value="{{ old('user_name') }}" data-parsley-required="true"  data-parsley-required-message="{{__('Username is required')}}" data-parsley-group="block-home" data-type="user_name">
                                            <span class="text-danger" id="user_name_error"></span>
                                            @if ($errors->has('user_name'))
                                                <span class="text-danger">{{ $errors->first('user_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-3 mt-md-5 mt-0 py-2">
                                            <label for="upline_email" class="form-label">{{__('Your Upline Email')}}</label>
                                            <input type="text" name="upline_email" class="form-control" id="upline_email" value="@if($uplineEmail != '') {{ $uplineEmail }}  @else {{ old('upline_email') }} @endif" @if($uplineEmail !='') readonly @endif data-type="upline">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" value="1" id="no_upline" name="no_upline">
                                                <label class="form-check-label" for="exampleCheck1">{{__('I have no upline')}}</label>
                                            </div>
                                            <span class="" id="uplineValidate"></span>
                                            @if ($errors->has('upline_email'))
                                                <span class="text-danger">{{ $errors->first('upline_email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-12 my-2 d-flex align-items-center flex-wrap">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="1" id="agree" name="agree" data-parsley-required="true" data-parsley-required-message="{{__('Please accept terms and conditions')}}" data-parsley-group="block-home">
                                            <label class="form-check-label" for="exampleCheck1">{{__('I accept the Terms and Conditions')}}</label>
                                        </div>
                                        @if ($errors->has('agree'))
                                            <span class="text-danger d-block w-100">{{ $errors->first('agree') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3 mt-md-5 mt-0 py-2">
                                        <div class="profile-image d-flex align-items-center mt-3 mb-md-0 mb-3">
                                            <div class="me-4 platform-profile-image flex-none" id="selected_image" style="background-image:url('{{ asset('assets/images/profile-1.png') }}')">
                                            </div>
                                            <div class="profile-image-text">
                                                <span class="fs-16 mb-0 font-weight-normal d-block"><b>{{__('Profile Image')}}</b></span>
                                                <span class="fs-14 mb-0 font-weight-light d-block">{{__('Image cannot exceed 5 Mb')}}</span>
                                                <input type='file' id="image" onchange="readURL(this);" name="profile_image" style="display:none" data-parsley-required="true"  data-parsley-required-message="{{__('Please select your image')}}" data-parsley-max-file-size="5" data-parsley-fileextension='[jpg,jpeg]' data-parsley-group="block-home" />
                                                <span class="text-danger print-error-profile_image"></span>
                                                <a class="btn btn-outline-white px-5 py-3 mt-4 br-20px imgBtn">+
                                                {{__('Photo')}}</a>
                                                @if ($errors->has('profile_image'))
                                                    <span class="text-danger d-block">{{ $errors->first('profile_image') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12 my-2 text-md-end">
                                        <button type="submit" class="submit-btn btn btn-blue-gradient next-step">{{__('Next')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('frontend-scripts')
    <script>
      let imageSizeValidationText = '{{__("Image cannot exceed 5 Mb")}}';
      let imageTypeValidationText = '{{__("Image must be a file of type:jpg")}}';
      let validateRegisterFieldsRoute = "{{route('validate.register.fields')}}";
      var maxFileSize = "{{ env('IMAGE_UPLOAD_SIZE') }}"
      var maxfileSizeValidation = "{{__('File is too Big, please select a File less than ')}}" + "{{ env('IMAGE_UPLOAD_SIZE') }}" + "{{__(' MB')}}";
    </script>
    <script src="{{ asset('/assets/js/register-form.js')}}"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
    <script>
         $(document).ready(function() {
            $(":input").inputmask();
            if($(window).width() > 600) {
                $('.mobile-register-screen').remove();
            } else {
                $('.desktop-register-screen').remove();
            }
         });
    </script>
@endsection
