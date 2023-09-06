@extends('layouts.seller.index')
@section('content')
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-settings me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Settings')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            @include('seller.common._language')
        </div>
    </div>
    @if(Session::has('success'))
        <div class="alert alert-success" id="successMessage">
            {{Session::get('success')}}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
            {{Session::get('error')}}
        </div>
    @endif
    <div class="content-body settings-body p-0">
        <div class="row h-100">
            @include('seller.setting.sidebar')
            <div class="col-xxl-9 col-xl-8 col-lg-7 col-md-8">
                <div class="d-flex flex-column flex-shrink-0 shadow-custom rounded-3 h-100 p-md-5 py-4 px-3">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="v-pills-tab-1">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <h6>{{__('Photo')}}</h6>
                                </div>
                            </div>
                            {{ Form::model($user, ['route' => ['seller.setting.account-update', $user->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center">
                                            @if(!empty($user->thumbnail_image) && Storage::disk('public')->exists($user->thumbnail_image))
                                                <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3" id="selected_image"
                                                style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}); height:98px; width:98px;">
                                                </div>
                                            @else
                                                <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3" id="selected_image"
                                                style="background-image: url({{ asset('assets/images/profile-1.png') }}); height:98px; width:98px;">
                                                </div>
                                            @endif

                                            <div class="name">
                                                <h5>{{ $user->getFullName() }}</h5>
                                                <div class="icons mt-2">
                                                    <input type="file" class="form-control" id="image" onchange="readURL(this);" name="profile_image" style="display: none;"/>
                                                    <div class="edit_detail">
                                                        <a><i class="feather-upload blue fs-20 me-2" id="imageButton"></i></a>
                                                        <a class="delete-account-media" data-url="{{route('seller.setting.delete-photo')}}"><i class="feather-trash-2 red fs-20"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($errors->has('profile_image'))
                                            <span class="text-danger">{{ $errors->first('profile_image') }}</span>
                                        @endif
                                        <div class="divider-ef mt-4 mb-4"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <h6>{{__('Personal Data')}}</h6>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('First Name')}}</label>
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Name')}}</label>
                                            <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                                            @if ($errors->has('last_name'))
                                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Email')}}</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" >
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Phone No')}}</label>
                                            <input type="text" class="form-control" value="{{ $user->phone }}"
                                                   name="phone">
                                            @if ($errors->has('phone'))
                                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Permanente Zoom Link')}}</label>
                                            <input type="text" class="form-control" value="{{ $user->permanent_zoom_link }}" name="permanent_zoom_link">
                                            @if ($errors->has('permanent_zoom_link'))
                                                <span class="text-danger">{{ $errors->first('permanent_zoom_link') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="city" class="form-label">{{__('City')}}</label>
                                            <input type="text" class="form-control" value="{{ $user->city }}"
                                            name="city">
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="country" class="form-label">{{__('Country')}}</label>
                                            <input type="text" class="form-control" value="{{ $user->country }}" name="country">
                                            @if ($errors->has('country'))
                                                <span class="text-danger">{{ $errors->first('country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="age" class="form-label">{{__('Age')}}</label>
                                            <input type="number" class="form-control" value="{{ $user->age }}" name="age" min="18">
                                            @if ($errors->has('age'))
                                            <span class="text-danger">{{ $errors->first('age') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6 col-12 mb-4">
                                        <div class="form-group custom-input-2">
                                            <label for="gender" class="form-label">{{__('Gender')}}</label><br>
                                            <input type="radio" value="Male" name="gender" @if($user->gender == 'Male') checked @endif> {{__('Male')}}
                                            <input type="radio" value="Female" name="gender" @if($user->gender == 'Female') checked @endif> {{__('Female')}}
                                            @if ($errors->has('gender'))
                                            <span class="text-danger">{{ $errors->first('gender') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-xxl-3 col-12 mb-4 d-flex align-items-center justify-content-between">
                                    <h6>{{__('Password')}}</h6>
                                    <div class="edit_detail passwordButton">
                                        <a class="fs-14 d-flex align-items-center">{{__('Edit')}} <i class="feather-edit ms-2 fs-20 blue"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-sm-6 col-12 mb-4 password">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Current')}}</label>
                                            <input type="password" name="current_password" class="form-control password-enable" placeholder="{{__('type here')}}" disabled="disabled">
                                            @if ($errors->has('current_password'))
                                                <span class="text-danger">{{ $errors->first('current_password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-4 password">
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('New password')}}</label>
                                            <input type="password"  name="new_password" class="form-control password-enable" placeholder="{{__('type here')}}" disabled="disabled" >
                                            @if ($errors->has('new_password'))
                                                <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-4 password" >
                                        <div class="form-group custom-input-2">
                                            <label for="" class="form-label">{{__('Confirm Password')}}</label>
                                            <input type="password" name="confirm_password" class="form-control password-enable" placeholder="{{__('type here')}}" disabled="disabled">
                                            @if ($errors->has('confirm_password'))
                                                <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-blue fs-16 fw-bold">{{__('Update')}}</button>
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .password {
        opacity : 0.25;
    }
    .edit_detail {
        cursor : pointer;
    }
</style>
@if($errors->has('current_password') || $errors->has('new_password') || $errors->has('confirm_password'))
    <script>
        $('.password-enable').attr('disabled', false);
        $(".password").css("opacity", "1");
    </script>
@endif

<script>
    $('body').on('click','.delete-account-media',function (e) {
        e.preventDefault();
        var delurl = $(this).attr('data-url');
        $.ajax({
            url: delurl,
            type: 'get',
            context: this,
            success: function(result) {
                window.location.href = "{{ route('seller.setting.account') }}";
            }
        });
    });

    /*
     * Image Button
     */
    $('#imageButton').click(function(){
      $('#image').trigger('click');
    });

    /*
     * Change Image based on selection
     */
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#selected_image').css('background-image', 'url("' + e.target.result + '")');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    /*
     * Password Button
     */
    $('.passwordButton').click(function(){
        $('.password-enable').attr('disabled', false);
        $(".password").css("opacity", "1");
    });

    if($('#successMessage').length) {
        window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000);
    }

</script>
@endsection
