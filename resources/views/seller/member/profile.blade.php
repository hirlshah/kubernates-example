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
    </div>
    <div class="content-body p-0">
        <div>
            <img class="img-fluid" src="{{ asset('assets/images/profile-bg.png') }}" alt="" />
            <div class="profile-body px-lg-5 px-3 pb-lg-5 pb-3">
                <div class="row">
                    <div class="col-4">
                        <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3" style="@if(!empty($user->thumbnail_image) && Storage::disk('public')->exists($user->thumbnail_image))background-image: url({{App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}); @else background-image: url({{ asset('assets/images/profile-1.png') }}); @endif width:126px;height:126px;border-radius:100px;"></div>
                        <input type="file" class="form-control" id="image" onchange="readURL(this);" name="profile_image" style="display: none;"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-lg-5 px-3">
            <div class="pb-lg-5 pb-3" id="user">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h2 class="mb-2 profile-page-name">{{ $user->getFullName() }}</h2>
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
            <div class="pb-lg-5 pb-4">
                <div class="row gy-4">
                    <div class="col-xxl-5 col-12 col-xl-4 col-12">
                        @if($user->description) <p class="fs-14 lh-lg profile-page-description mb-4">{{ $user->description }}</p> @endif
                        <h5 class="fs-22 mb-2">{{__('URL')}}</h5>
                        <div class="event-code">
                            <input value="{{ route('home') }}/?ref={{ $user->referral_code }}" type="text" class="event-link">
                            <i class="feather-copy blue fs-20" id="copy_{{ route('home') }}/?ref={{ $user->referral_code }}" data-href="{{ route('home') }}/?ref={{ $user->referral_code }}"></i>
                            <div class="tooltiptext"><span>{{__('Copy to clipboard')}}</span></div>
                        </div>
                    </div>
                    <div class="col-xxl-7 col-12 col-xl-8 col-12">
                        <h5 class="fs-22 mb-3">{{__('Details')}}</h5>
                        <div class="d-lg-flex flex-column gap-lg-3">
                            <div class="mb-lg-0 mb-3">
                                <h6 class="fs-15 mb-0">{{__('Phone number')}}</h6>
                                <p class="fs-14 lh-lg mb-0">{{ $user->phone }}</p>
                            </div>
                            <div class="mb-lg-0 mb-3">
                                <h6 class="fs-15 mb-0">{{__('Email')}}</h6>
                                <p class="fs-14 lh-lg mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="mb-lg-0 mb-3">
                                <h6 class="fs-15 mb-0">{{__('City')}}</h6>
                                <p class="fs-14 lh-lg mb-0">{{ $user->city }}</p>
                            </div>
                            <div class="mb-lg-0 mb-3">
                                <h6 class="fs-15 mb-0">{{__('Country')}}</h6>
                                <p class="fs-14 lh-lg mb-0">{{ $user->country }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-lg-5 pb-4">
                <form class="update_profile_info" action="{{route('seller.update-profile-info')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        @if(!empty($user->info))
                            <div class="col-lg-11 col-12">
                                <h5 class="fs-22 mb-2">{{__('What is your')}} <span class="fw-bold">{{__('WHY')}}</span> ? </h5>
                                <div class="info-text profile-page-info ps-0 mt-3" style="min-height: 120px;">{{ $user->info }}</div>
                                <textarea name="info" class="form-control info-text-input profile-info-text profile-page-info ps-2 pt-2 pt-lg-3 mb-3 mt-2 ps-0" style="min-height: 120px;">{{ $user->info }}</textarea>
                                <span class="text-danger print-error-msg-info" style="display:none"></span>
                            </div>
                            @if($user->id == Auth::User()->id)
                                <div class="col-lg-1 col-12">
                                    <div class="d-flex align-items-center justify-content-md-end">
                                        <i class="feather-edit" id="info"></i>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                    @if($user->id == Auth::User()->id)
                        <div class="row mt-2">
                            <div class="col-lg-2 col-12">
                                <button class="btn btn-blue-gradient info-save-btn" type="submit">{{__('Save')}}</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
            <div class="pb-lg-5 pb-4">
                <div id="ajax-member-stats"></div>
            </div>
            <div class="pb-lg-5 pb-4">
                <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
                    <span class="minus"></span>
                    <span class="minus"></span>
                    <span class="minus"></span>
                </button>
                <div class="content-header d-flex align-items-center">
                    <div class="content-header-left d-flex align-items-center">
                        <i class="feather-user-plus"></i>
                        <h5>{{__('Contacts')}}</h5>
                    </div>
                </div>
                <div class="note my-3">
                    <span>{{__('Note: To edit or add a message, double tap on the contact card.')}}</span>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <div class="dummy-scroll-main mb-4">
                            <div class="dummy-scrollbar"></div>
                        </div>
                        <input type="hidden" name="board_id" id="board_id_hidden" value="{{$board->id}}">
                        <div class="drag-drop-scroll-main">
                            <div class="drag-drop-scroll-wrapper">
                                <div class="drag-drop-scroll mb-4 contact-board pb-5">
                                    <div id="contact_list" class="drag-drop-card" data-status-id="0">
                                        <h6 class="contact-status">{{__('Contacts')}}</h6>
                                        @if($user->id == Auth::User()->id)
                                            <div class="row contact-user-card-add">
                                                <div class="col-4">
                                                    <div class="contact-user-card-profile"></div>
                                                </div>
                                                <div class="col-8 m-auto">{{__('Add contact')}}</div>
                                            </div>
                                        @endif
                                        <div class="add_contact_list"></div>
                                        <div class="droppable-contact">
                                            @if(isset($contacts))
                                                @foreach($contacts as $contact)
                                                    <div class="flex-column contact-user-card draggable-user-card" data-id="{{$contact->id}}" data-status-id="0" data-board-id="{{$board->id}}"  data-follow-count="{{$contact->followUp?$contact->followUp->getDayCount():''}}"data-is-complete-profile="{{ $contact->email && $contact->phone }}">
                                                        <div class="contact-label">
                                                            @php
                                                                $labels = $contact->labels()->get();
                                                            @endphp
                                                            @if(isset($labels) && !empty($labels))
                                                                @foreach($labels as $label)
                                                                    <span class="label-title" style="background-color: {{ $label->color }};">
                                                                        {{ $label->name }}
                                                                    </span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="selected_image" style="background-image: url({{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }});">
                                                                </div>
                                                            </div>
                                                            <div class="col-8 m-auto card-contact-name">{{ $contact->name }}</div>
                                                            <a href="javascript:void(0)" data-id="{{ $contact->id }}" class="contact-delete-outer" data-name="{{ $contact->name }}" data-image="{{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}"><i class="feather-x contact-delete-direct"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @foreach($statusRange as $key => $status)
                                        <div class="drag-drop-card" data-status-id="{{$key}}">
                                            <h6 class="contact-status">{{__($status)}}</h6>
                                            <div class="droppable-contact">
                                                @if(isset($board_contacts[$key]))
                                                    @foreach($board_contacts[$key] as $contact)
                                                        <div class="flex-column contact-user-card draggable-user-card" data-id="{{$contact->id}}" data-status-id="{{$key}}" data-board-id="{{$board->id}}" data-is-complete-profile="{{ $contact->email && $contact->phone ? true : false }}">
                                                            <div class="contact-label">
                                                                @php
                                                                    $labels = $contact->labels()->get();
                                                                @endphp
                                                                @if(isset($labels) && !empty($labels))
                                                                    @foreach($labels as $label)
                                                                    <span class="label-title" style="background-color: {{ $label->color }};">
                                                                        {{ $label->name }}
                                                                    </span>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <img class="contact-user-card-profile-add" src="{{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}" />
                                                                </div>
                                                                <div class="col-8 m-auto card-contact-name">{{ $contact->name }}</div>
                                                                <a href="javascript:void(0)" data-id="{{ $contact->id }}" class="contact-delete-outer" data-name="{{ $contact->name }}" data-image="{{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}"><i class="feather-x contact-delete-direct"></i></a>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pb-5">
                <h5 class="fs-22 mb-4">{{__('Zoom Meetings')}}</h5>
                <div class="row gy-4">
                    @if(!empty($events))
                        @foreach($events as $event)
                            <div class="col-xxl-4 col-md-6">
                                <div class="card event border-0" style="min-height: 100%;">
                                    <div class="event-image" style="@if(isset($event->image) && Storage::disk('public')->exists($event->image)) background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }});@else background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }}); @endif min-height: 200px;"></div>
                                    
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

<!-- /Add new contact modal -->
@include('seller.contacts._modal_add_contact')

<!-- /Contact detail modal -->
@include('seller.analytic._modal_contact_detail')

<!-- /Delete confirm modal -->
@include('seller.contacts._modal_contact_delete_confirm')

<!-- /Contact send message modal -->
@include('seller.contacts._modal_contact_send_message')

<!-- /Contact follow up modal -->
@include('seller.contacts._modal_contact_follow_up')

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
                <button type="button" class="btn bg-danger modal-delete-edu-exp-confirm text-white">{{__('Delete')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- /delete modal -->

@endsection
@section('scripts')
    <script src="{{ asset('assets/js/dashboard_and_profile.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/profile.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script>
        let sellerDashboardStatsRoute = "{{route('dashboard-member-personal-stats')}}";
        let sellerDashboardStatsTeamRoutee = "{{route('dashboard-member-team-stats')}}";
        let overrideUserId = {{$user->id}};
        let showRoute = "{{route('seller.contacts.show', '')}}";
        let sellerTaskList = "{{route('seller.tasks.list')}}";
        let taskDataRoute = "{{ route('seller.tasks')}}";
        let sellerTaskUserTaskUpdate = "{{route('seller.tasks.user-task-update')}}";
        let taskTitleRequired = "{{__('Task title required')}}";
        var taskDayErrorMsg = "{{ __('You must choose a day for your goal')}}";
    </script>
    <script src="{{ asset('/assets/js/seller_dashboard_stats.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
