@extends('layouts.seller.index')
@section('content')
<div id="content">
    @include('seller.common._upgrade_warning')
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="row content-header justify-content-between">
        <div class="col-sm-auto content-header-left hstack">
            <i class="feather-bar-chart me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">@if(app()->getLocale() == "es")
                        Cuadro de mandos
                        @else 
                        {{__('Dashboard')}}
                        @endif</li>
                </ol>
            </nav>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip" style="z-index: 1;">
                    <span class="custom-badge">   
                        <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753147198?h=0bfc84ff62" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                </div>
            @endif
        </div>
        @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
            <div class="col-sm-auto dashboard-header-right content-header-right">
                <div class="refrral-code w-sm-auto w-100">
                    <div class="hstack gap-2">
                        <label class="flex-none">{{ __('Reference address') }} :</label>
                        <input value="{{route('home')}}/?ref={{$member->referral_code}}" Type="text" />
                    </div>
                    <div class="copy-reg-link position-relative">
                        <i class="feather-copy blue fs-20" id="copy_{{$member->referral_code}}" data-href="{{route('home')}}/?ref={{$member->referral_code}}"></i>
                        <div class="tooltiptext"><span>{{__('Copy to clipboard')}}</span></div>
                    </div>
                    <a class="qr-code"> <i class="fa fa-qrcode text-primary" aria-hidden="true"></i></a>
                </div>
                @include('seller.common._language')
            </div>
        @endif
    </div>
    <div class="content-body">
        <div class="container-fluid" id="ajax-member-stats"></div>
        <div class="container-fluid mt-5">
            <div class="row gy-5">
                <div class="col-xxl-7">
                    <div class="content-header-left d-flex align-items-center mb-4">
                        <h5>{{__('Follow-up reminders')}} <span class="fs-14 grey-c0c0c0 ms-2">({{$followUpCount}})</span></h5>
                        <div class="custom-badge-tooltip">
                            <span class="custom-badge d-flex">   
                                <a href="#modal_play_vimeo" class="modal-popup-vimeo d-flex align-items-center" data-url="https://player.vimeo.com/video/753147198?h=0bfc84ff62" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                            </span>
                            <div class="tooltiptext" style="z-index:10;"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                        </div>
                    </div>
                    <div id="follow-ups-render" class="card p-md-4 p-0 mb-4">
                        <div class="follow-up-main px-sm-3 px-2">
                        </div>
                    </div>
                    <h5 class="mb-4">{{__('Recent Zooms')}} <span class="fs-14 grey-c0c0c0 ms-2">{{ ($todayEvents ? $todayEvents->count() : 0) + ($thisWeekEvents ? $thisWeekEvents->count() : 0) }}</span>
                    </h5>
                    <div class="card p-0">
                        <div class="recent-zooms px-3 pt-3">
                            <div class="line-title mb-4">
                                <h6>{{__('Today')}}</h6>
                            </div>
                                @foreach($todayEvents as $event)
                                    <div class="card mb-4 rounded-3">
                                        <div class="row g-0">
                                            <div class="col-sm-4 vertical-card-image" style=@if(isset($event->image) && Storage::disk('public')->exists($event->image)) "background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }});" @else "background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }});" @endif>
                                            </div>
                                           
                                            <div class="col-sm-8">
                                                <div class="card-body px-md-4">
                                                    <div class="meting-card-title mb-3">
                                                        <h6>{{ $event->name }}</h6>
                                                        <i class="feather-flag rounded-border fw-bold green"></i>
                                                    </div>
                                                    <p class="fs-14">{{ $event->content }}</p>
                                                    <div class="tags">
                                                        @foreach($event->tags as $tag)
                                                            <span class="py-3">{{ $tag->name }}</span>
                                                        @endforeach
                                                    </div>
                                                    <div class="tags">
                                                        <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date.' '.$event->meeting_time, 'Y-m-d H:i:s', 'd M. Y') }}</span>
                                                        <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date.' '.$event->meeting_time, 'Y-m-d H:i:s', 'H:i') }}h</span>
                                                    </div>
                                                    <a type="button" class="btn btn-blue py-2 lh-lg float-sm-end mb-sm-3 mt-sm-0 mt-3 w-sm-auto w-full d-sm-inline-block d-block"
                                                    href="{{ route('seller.zoom-status',$event->id) }}">{{__('See Stats')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            <div class="line-title mb-3">
                                <h6>{{__('This week')}}</h6>
                            </div>
                            <div>
                                @foreach($thisWeekEvents as $event)
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-sm-4 vertical-card-image"
                                            style=@if(isset($event->image) && Storage::disk('public')->exists($event->image)) "background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }});" @else "background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }});" @endif>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="card-body px-md-4">
                                                    <div class="meting-card-title mb-3">
                                                        <h6>{{ $event->name }}</h6>
                                                        <i class="feather-flag rounded-border fw-bold green"></i>
                                                    </div>
                                                    <p class="fs-14">{{ $event->content }}
                                                    </p>
                                                    <div class="tags">
                                                        @foreach($event->tags as $tag)
                                                            <span class="py-3">{{ $tag->name }}</span>
                                                        @endforeach
                                                        <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                                                        <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i') }}h</span>
                                                        <a type="button" class="btn btn-blue py-2 lh-lg float-sm-end mb-sm-3 mt-sm-0 mt-3 w-sm-auto w-full d-sm-inline-block d-block" href="{{ route('seller.zoom-status',$event->id) }}">{{__('See Stats')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5">
                    <h5 class="mb-4">{{__('Your team')}}</h5>
                    <div class="card py-4 px-4 mb-xxl-4" style="margin-bottom: 4rem;">
                        @if(!empty($memberImages))
                            <div class="y-contacts-row flex-xxl-wrap flex-sm-nowrap flex-wrap gap-3">
                                <div class="contact-multiple-images me-3 mb-0">
                                    @foreach($memberImages as $image)
                                        @continue(empty($image))
                                        @break($loop->iteration == 7)
                                        <div class="single-contact" style="background-image: url({{ asset("storage/".$image) }})"></div>
                                    @endforeach
                                </div>
                                <div class="right">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5>{{__('Team Stats')}}</h5>
                                        <a href="{{ route('analytics') }}"><i class="feather-arrow-right blue"></i></a>
                                    </div>
                                    <p class="mb-0">{{__('Check your team stats')}}</p>
                                </div>
                            </div>
                        @else
                            <p class="mb-0">{{__('You don\'t have any teammates yet')}}</p>
                        @endif
                    </div>
                    @if($nextEvents->count() > 0)
                        <h5 class="mb-4">{{__('Next zooms')}}</h5>
                        <div class="card py-0 mb-xxl-4" style="margin-bottom: 4rem;">
                            <div class="recent-zooms px-3 pt-3">
                                <div>
                                    @foreach($nextEvents as $event)
                                        <div class="card mb-3" id="event" data-slug="{{ $event->slug }}" data-url="{{ route('event-detail', $event->slug)  }}">
                                            <div class="row g-0 mb-3">
                                                <div class="col-sm-4 vertical-card-image"
                                                    @if(isset($event->image)) style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }});" @else style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }});" @endif>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="card-body px-md-4">
                                                        <h6>{{ $event->name }}</h6>
                                                        <p class="grey-666666 mt-2">{{__('Organized by')}}: @if(!empty($event->user)) {{ $event->user->getFullName() }} @endif</p>
                                                        <div class="tags">
                                                            <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                                                            <span class="py-3">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i') }}</span>
                                                            @if($event->user_id != Auth::id())
                                                                @if(!$event->reps->contains(Auth::user()->id))
                                                                    <button data-href="{{ route('seller.event.confirm-presence', ['event'=>$event->id]) }}" data-id="{{ $event->id }}" id="confirm-presence-{{ $event->id }}" class="btn btn-blue fs-12 mw-max-content  event-reps-add-btn">{{__('Confirm My Presence')}}</button>
                                                                    <span id="confirmed-presence-{{ $event->id }}" style="display: none">{{__('Your presence has been confirmed.')}}</span>
                                                                @elseif(!checkIfEventIsPastCurrentTime($event))
                                                                    <span>{{__('Your presence has been confirmed.')}}</span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@include('seller.modal._modal_qr')
@endsection
@section('scripts')
    <script>
        let followUpRenderRoute = "{{route('seller.dashboard.follow_ups')}}";
        let sellerDashboardStatsRoute = "{{route('dashboard-member-personal-stats')}}";
        let sellerDashboardStatsTeamRoutee = "{{route('dashboard-member-team-stats')}}";
        let sellerTaskUserTaskUpdate = "{{route('seller.tasks.user-task-update')}}";
        let sellerTaskList = "{{route('seller.tasks.list')}}";
        let taskTitleRequired = "{{__('Task title required')}}";
        let taskDataRoute = "{{ route('seller.tasks')}}";
        var personalGoalText = "{{ __('Adjust your personal goals') }}";
        var teamGoalText = "{{ __('Adjust your team goals') }}";
        var dailiesText = "{{ __('Dailies')}}";
        var taskDayErrorMsg = "{{ __('You must choose a day for your goal')}}";
    </script>
    <script src="{{ asset('assets/js/dashboard_and_profile.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('/assets/js/seller_dashboard.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('/assets/js/seller_dashboard_stats.js?ver=')}}{{env('JS_VERSION')}}"></script>

@endsection
