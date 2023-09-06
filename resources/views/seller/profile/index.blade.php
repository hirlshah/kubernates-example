@extends('layouts.seller.index')
@section('content')
<div id="content" class="profile-page">
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-user me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{ (auth()->user()->id != $user->id) ? __('Profile') : __('My Profile') }}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body p-0">
        <div class="container-fluid p-0">
            <img class="img-fluid" src="{{ asset('assets/images/profile-bg.png') }}" alt="">
            <div class="profile-body px-lg-5 px-3 pb-lg-5 pb-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">
                            <div class="profile-avtar" id="imageButton">
                                <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 selected_image"
                                style="@if(!empty($user->thumbnail_image) && Storage::disk('public')->exists($user->thumbnail_image))background-image: url({{App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}); @else background-image: url({{ asset('assets/images/profile-1.png') }}); @endif width:126px;height:126px;border-radius:100px;">
                                </div>
                                <div class="avtar-overlay">
                                    <i class="feather-edit"></i>
                                </div>
                            </div>
                            <input type="file" class="form-control image_update" id="image" onchange="readURL(this);" name="profile_image" style="display: none;"/>
                            <span class="text-danger print-error-msg-profile_image" style="display:none"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid px-lg-5 px-3 pb-lg-5" id="user">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <h2 class="mb-2 profile-page-name">{{ $user->getFullName() }}</h2>
                            <div>
                                <i class="feather-edit cursor-pointer fs-5" id="update_profile_button"></i>
                            </div>
                        </div>
                        <p class="mb-2">
                            @if(isset($user->userExperience))
                                @php
                                    $latestJob = $user->userExperience->where('end_date',NULL)->first();
                                @endphp
                                @if($latestJob)
                                    {{ $latestJob->title }}
                                @endif
                            @endif
                        </p>
                        <div class="location">
                            <i class="feather-map-pin blue"></i>
                            <span>{{ config('app.rankup.location') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid px-lg-5 px-3 pb-lg-5">
                @include('seller.dashboard.dailies._dailies_tasks', ['tasks' => $tasks, 'completedTasks' => $completedTasks, 'isEdit' => false, 'completedTaskDates' => $completedTaskDates])
                <input type="hidden" name="profile_page" id="page_name">
            </div>
            <div class="container-fluid px-lg-5 px-3">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        @if($user->description) <p class="fs-14 lh-lg profile-page-description">{{ $user->description }} </p> @endif
                    </div>
                    <div class="col-lg-6 col-12 ms-auto">
                    </div>
                </div>
            </div>
            <form class="update_profile_info" action="{{route('seller.update-profile-info')}}" method="POST" enctype="multipart/form-data">
                <div class="container-fluid px-lg-5 px-3 pb-lg-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="fs-22 mb-0">{{__('What is your')}} <span class="fw-bold">{{__('WHY')}}</span> ? </h5>
                                <div class="d-flex">
                                    <i class="feather-edit cursor-pointer fs-5" id="info"></i>
                                </div>
                            </div>
                            <div class="info-text profile-page-info ps-0 mt-3" style="min-height: 120px;">{{ $user->info }}</div>
                            <textarea name="info" class="form-control info-text-input profile-info-text profile-page-info ps-2 pt-2 pt-lg-3 mb-3 mt-2">{{ $user->info }}</textarea>
                            <span class="text-danger print-error-msg-info" style="display:none"></span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-2 col-12">
                            <button class="btn btn-blue-gradient info-save-btn" type="submit">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="container-fluid mt-5 px-lg-5 px-3 pb-lg-5 pb-3">
                <h5 class="fs-22 mb-4">{{__('Zoom Meetings')}}</h5>
                <div class="row">
                    @if(!empty($events))
                        @foreach($events as $event)
                            <div class="col-xxl-4 col-md-6 col-sm-12 mb-4">
                                <div class="card event border-0" style="min-height: 100%;">
                                    @if(isset($event->image))
                                        <div class="event-image" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}); min-height: 200px;"></div>
                                    @else
                                        <div class="event-image" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }}); min-height: 200px;"></div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title mb-3 fs-16">{{ $event->name }}</h6>
                                        <p class="card-text mb-3 fs-14 grey-666666">{{ $event->content }}</p>
                                        <div class="tags mb-3">
                                            @foreach($event->tags as $tag)
                                                <span class="">{{ $tag->name }}</span>
                                            @endforeach
                                            @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                                                <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                                                <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i') }}h</span>
                                            @endif
                                        </div>
                                        <a type="button" class="btn btn-blue px-4 py-2 fs-14 mr-3 mb-2 me-2 fw-400" href="{{ route('event-detail', $event->slug) }}">{{__('See Details')}}</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-black px-4 py-2 fs-14 mb-2 fw-400 download-event-ics" data-id="{{ $event->id }}" data-url="{{ route('seller.event.download-ics', $event->id) }}" name="ics">
                                            <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>{{__('No meetings scheduled!')}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete modal -->
<div id="modal_delete_edu_exp_warning" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content overflow-visible">
            <div class="modal-header bg-danger">
                <h6 class="modal-title text-white fs-24">{{__('Warning!!')}}</h6>
                <a class="close modal-close-btn text-white fs-24 cursor-pointer" data-dismiss="modal">&times;</a>
            </div>

            <div class="modal-body p-3">
                <h6 class="font-weight-semibold">{{__('Are you sure you want to delete this record ?')}}</h6>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-black modal-close-btn" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn bg-danger modal-delete-edu-exp-confirm text-white">{{__('Delete')}}/button>
            </div>
        </div>
    </div>
</div>
<!-- /delete modal -->

<style type="text/css">
    .edit_detail {
        cursor : pointer;
    }
</style>
@include('seller.profile._modal_add_education')
@include('seller.profile._modal_add_experience')
@include('seller.profile._modal_update_profile')
@endsection
@section('scripts')
    <script>
        let sellerTaskUserTaskUpdate = "{{route('seller.tasks.user-task-update')}}";
        let sellerTaskList = "{{route('seller.tasks.list')}}";
        let taskTitleRequired = "{{__('Task title required')}}";
        let userId = "{{ Auth::User()->id }}";
        let taskDataRoute = "{{ route('seller.tasks')}}";
        let profilePicUploadRoute = "{{ route('seller.update-profile-photo') }}";
        var taskDayErrorMsg = "{{ __('You must choose a day for your goal')}}";
    </script>
    <script src="{{ asset('assets/js/dashboard_and_profile.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/profile.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script>
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
              $('.selected_image').css('background-image', 'url("' + e.target.result + '")');
            };
            reader.readAsDataURL(input.files[0]);
          }
        }

    </script>
@endsection
