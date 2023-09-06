@extends('layouts.seller.index')
@section('title', isset($user)? __('Update User') : __('Create User'))
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card table-card p-3">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">@if(isset($user)) {{ __('Update User')}} @else {{__('Create User')}} @endif</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        @if(isset($user))
                            {{ Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'users.store' , 'enctype'=>'multipart/form-data']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Name')}} <span class="text-danger">*</span></label>
                                        <div class="col-xl-9">
                                            {{ Form::text('name',Request::old('name'),array('class'=>"form-control")) }}
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Email')}}<span class="text-danger">*</span></label>
                                        <div class="col-xl-9">
                                            {{ Form::text('email',Request::old('email'),array('class'=>"form-control")) }}
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('User Name')}}<span class="text-danger">*</span></label>
                                        <div class="col-xl-9">
                                            {!! Form::text('user_name', Request::old('user_name'), array('class' => 'form-control')) !!}
                                            @if ($errors->has('user_name'))
                                                <span class="text-danger">{{ $errors->first('user_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Date Of Birth')}} <span class="text-danger">*</span></label>
                                        <div class="col-xl-9">
                                            {!! Form::date('date_of_birth', Request::old('date_of_birth'), array('class' => 'form-control')) !!}
                                            @if ($errors->has('date_of_birth'))
                                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        @if(!isset($user))
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="row">
                                            <label class="col-form-label col-xl-3">{{__('Password')}} <span class="text-danger">*</span></label>
                                            <div class="col-xl-9">
                                                {{ Form::password('password', array('class = "form-control"')) }}
                                                @if ($errors->has('password'))
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <div class="row">
                                            <label class="col-form-label col-xl-3">{{__('Confirm Password')}}<span class="text-danger">*</span></label>
                                            <div class="col-xl-9">
                                                {{ Form::password('confirm_password', array('class = "form-control"')) }}
                                                @if ($errors->has('confirm_password'))
                                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        @endif
                        <fieldset>
                            <div class="row">
                                @if(!isset($user))
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Roles')}} <span
                                        class="text-danger">*</span></label>
                                        <div class="col-xl-9 roles-select">
                                            {!! Form::select('roles[]', $roles,isset($user) ? $userRole : [], array('class' => 'form-control select2 bg-image-none','multiple')) !!}
                                            @if ($errors->has('roles'))
                                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Video')}}</label>
                                        <div class="col-xl-9">
                                            {!! Form::file('video',array('class' => 'form-control','multiple')) !!}
                                            @if(isset($user->video))
                                                <video width="320" height="240" controls>
                                                    <source src="{{ App\Classes\Helper\CommonUtil::getUrl($user->video) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @endif
                                            @if ($errors->has('video'))
                                                <span class="text-danger">{{ $errors->first('video') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Profile Image')}}</label>
                                        <div class="col-xl-9">
                                            @if(isset($user->profile_image))
                                                {{ Form::file('profile_image', array('class = "form-control mb-4"')) }}
                                                <img class="mb-2" src="{{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}" style="width:50px;height:50px;"/>
                                            @else
                                                {{ Form::file('profile_image', array('class = "form-control"')) }}
                                            @endif
                                            @if ($errors->has('profile_image'))
                                                <span class="text-danger">{{ $errors->first('profile_image') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-xl-3">{{__('Upline User')}}</label>
                                        <div class="col-xl-9 upline-user">
                                            <input type="hidden" name="parent_id" id="parent_id">
                                            <input name="parent_name" value="" readonly id="parent_name" class="form-control ps-0 mb-2">
                                            <a class="btn w-100 br-20px users_modal_btn btn btn-blue" href="javascript:;">+ {{__('Add Upline')}}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-12">{{__('Description')}} <span class="text-danger">*</span></label>
                                        <div class="col-12">
                                            {!! Form::textarea('description', Request::old('description'), array('class' => 'form-control')) !!}
                                            @if ($errors->has('description'))
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if(isset($user))
                                    <!-- <div class="col-md-6 col-12 mb-4 d-none">
                                        <div class="row">
                                            <label class="col-form-label col-lg-3">{{__('Expiration Plan Date')}}</label>
                                            <div class="col-lg-9">
                                                {!! Form::text('expiration', Request::old('expiration',isset($user->userPlan) && !empty($user->userPlan) ? convertDateFormatWithTimezone($user->userPlan->expiration, 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'CRM-TO-FRONT') : ''), array('class' => 'form-control')) !!}
                                                @if ($errors->has('expiration'))
                                                    <span class="text-danger">{{ $errors->first('expiration') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-6 col-12 mb-4 d-none">
                                        <div class="row">
                                            <label class="col-form-label col-lg-3">{{__('Status')}}</label>
                                            <div class="col-lg-9">
                                                {!! Form::select('status', $userPlanStatus, isset($user->userPlan) && !empty($user->userPlan) ? $user->userPlan->status : null, array('class' => 'form-control select2 bg-image-none','placeholder' => __('select plan status'))) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </fieldset>

                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-blue')) }}
                            <a href="{{ url('/admin/users') }}" class="btn btn-outline-black m-2">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('seller.modal.user_list_modal')
@endsection
@section('scripts')
<script type="text/javascript">
/**
 * Show user list modal
 */
$("body").on("click", ".users_modal_btn", function(e) {
  $('#user_list_modal').modal('show');
  $('.text-danger').hide();
});

$('#user_list_modal').on('shown.bs.modal', function () {
  currentUsersPage = 1;
  $('#users_search').val('');
  $('.users_list').html('');
  users_list(currentUsersPage);
});

var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 1 second for example

/**
 * Search user
 */
$('body').on('keyup', '#users_search', function (e) {
  $('.spinner').addClass('text-primary spinner-border spinner-border-lg w-4 h-4 mx-2');
  clearTimeout(typingTimer);
  $('.users_list').empty();
  currentModalPeoplePage = 1;
  if(event.keyCode === 13) {
    users_list(currentUsersPage);
  } else {
    typingTimer = setTimeout(users_list, doneTypingInterval);
  }
});

/**
 * Change value
 */
$("body").on("change", ".user_listing_check input[type='radio']", function() {
  if ($(this).is(":checked")) {
    var selectedValue = $(this).val();
    var selectedOptionText = $(this).siblings('label.people-check-label').find('span.fs-16').text();
    $("#parent_id").val(selectedValue);
    $("#parent_name").val(selectedOptionText);
    $('#user_list_modal').modal('hide');
  }
});

var currentUsersPage = 1;

/**
 * Get User List.
 */
function users_list(pageNumber) {
    var search_people_text = $('#users_search').val();
    $('.spinner').addClass('text-primary spinner-border spinner-border-lg w-4 h-4 mx-2');
    $.ajax({
        url: "{{ route('get-user-list')}}",
        type: 'GET',
        data: { 'page': pageNumber,  'search_text' : search_people_text},
        success: function(response) {
            $('.users_list').append(response.html);
            $('.spinner').removeClass('text-primary spinner-border spinner-border-sm w-4 h-4 mx-2');
        },
        error: function(error) {
            $('.users_list').append('');
            $('.spinner').removeClass('text-primary spinner-border spinner-border-sm w-4 h-4 mx-2');
        }
    });
}
</script>
@endsection