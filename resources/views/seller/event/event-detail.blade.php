@extends('layouts.seller.index')
@section('content')
<div id="content">
    @if(Session::has('success'))
        <div class="alert alert-success" id="successMessage">
            {{Session::get('success')}}
        </div>
    @endif
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-tv me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Events')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid mb-5">
            <div class="video">
                <div class="card p-0">
                    <div class="event-image event-detail-image" style="@if(isset($event->image) && Storage::disk('public')->exists($event->image)) background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}); @else background-image: url({{ asset(config('app.rankup.company_default_image_file')) }}); @endif min-height: 50vh;"></div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-8">
                    <h5 class="fs-22 mb-4">{{ $event->name }}</h5>
                    <p class="grey-666666">{{__('Organized by')}}: {{ $event->user->getFullName() }}</p>
                    <div class="tags mb-4">
                        @foreach($event->tags as $tag)
                            <span>{{ $tag->name }}</span>
                        @endforeach
                        @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                            <span>{{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','d M. Y','CRM-TO-FRONT') }}</span>
                            <span>{{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','H:i','CRM-TO-FRONT') }}h</span>
                        @endif
                        @if($event->user_id == auth()->user()->id)
                            @if($event->is_active == \App\Enums\EventActive::ACTIVE)
                                <a class="btn btn-blue event-active-btn" data-event-id="{{ $event->id }}" data-active="0">{{__('Make invisible')}}</a>
                            @else
                                <a class="btn btn-blue event-active-btn" data-event-id="{{ $event->id }}" data-active="1">{{__('Make visible')}}</a>
                            @endif
                        @endif
                    </div>
                    <p class="mb-4 fs-14 lh-lg">{{ $event->content }}</p>
                    <div class="row">
                        <div class="col-md-8 mb-2 mb-4">
                            @php
                                $url = route('frontend.event.details',$event->slug);
                                if($event->user_id != Auth::user()->id){
                                    $url .= '?referral='.Auth::user()->referral_code;
                                }
                            @endphp
                            <h5>{{__('Copy Event Meeting Link')}}</h5>
                            <div class="event-code">
                                <input value="{{$url}}" type="text" class="event-link">
                                <i class="feather-copy blue fs-20" id="copy_{{$url}}" data-href="{{$url}}?lang={{ app()->getLocale() }}"></i>
                                <div class="tooltiptext"><span>{{__('Copy to clipboard')}}</span></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-4">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div id="color-calendar" class="text-center"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            @if(!$isOwner)
                                @if(!$event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first())
                                    <button data-href="{{ route('seller.event.confirm-presence', ['event'=>$event->id]) }}" id="confirm-presence" class="btn btn-blue fs-12 mw-max-content">{{__('Confirm My Presence')}}</button>
                                    <span id="confirmed-presence" style="display: none">{{__('Your presence has been confirmed.')}}</span>
                                @elseif($event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first())
                                    <span>{{__('Your presence has been confirmed.')}}</span>
                                @elseif(checkIfEventIsPastCurrentTime($event))
                                <a type="button" id="zoom_meeting_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('seller.event.confirm-presence',$event->id) }}">{{__('Zoom Meeting')}}</a>
                                @endif
                            @endif
                            @if($contactConfirmedUserList->count() > 0 || $event->reps()->where(['status'=> \App\Enums\ContactBoardStatus::CONFIRMED_FOR_ZOOM])->count() > 0)
                                <h5 class="mb-4 mt-3">{{__('Confirmed users')}} ({{ $contactConfirmedUserList->count() + $event->reps()->where(['status'=> \App\Enums\ContactBoardStatus::CONFIRMED_FOR_ZOOM])->count() }})</h5>
                            @endif
                            @if($contactConfirmedUserList->count() > 0 || $event->reps()->where(['status'=> \App\Enums\ContactBoardStatus::CONFIRMED_FOR_ZOOM])->count() > 0)
                                <div class="row">
                                    @if($event->reps()->where(['status'=> \App\Enums\ContactBoardStatus::CONFIRMED_FOR_ZOOM])->count() > 0)
                                        @foreach($reps as $member)
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-4">
                                                    <div class="card-body d-flex align-items-center px-2" style="min-height:90px;">
                                                        <a href="{{ route('seller.member.profile', $member['id']) }}" class="people-list">
                                                        @if(isset($member['profile_image']) && !empty($member['profile_image']) && is_file(public_path("storage/".$member['profile_image'])))
                                                            <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($member['profile_image']) }});"></div>
                                                        @else
                                                            <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2" style="background-image: url({{ asset('assets/images/profile-1.png') }});"></div>
                                                        @endif
                                                            <h6 class="text-break" style="word-break: break-all;">{{$member['name']}}</h6>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    @if($contactConfirmedUserList->count() > 0)
                                        @foreach($contactConfirmedUserList as $member)
                                            <div class="col-sm-6 col-12">
                                                <div class="card mb-4">
                                                    <div class="card-body d-flex align-items-center px-2" style="min-height:90px;">
                                                        <a href="{{ route('seller.member.profile', $member['user_id']) }}" class="people-list">
                                                            <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2"
                                                                style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($member['profile_image']) }});">
                                                            </div>
                                                            <h6 class="min-content">{{$member['name']}}</h6>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            @if($contactConfirmedVisitorList->count() > 0)
                                <h5 class="mb-4 mt-3">{{__('confirmed people')}} ({{ $contactConfirmedVisitorList->count() }})</h5>
                                <div class="row">
                                    @foreach($contactConfirmedVisitorList as $member)
                                        <div class="col-sm-6 col-12">
                                            <div class="card mb-4">
                                                <div class="card-body d-flex align-items-center px-2" style="min-height:90px;">
                                                    @if(isset($member['profile_image']) && !empty($member['profile_image']) && is_file(public_path("storage/".$member['profile_image'])))
                                                        <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2"
                                                            style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($member['profile_image']) }});">
                                                        </div>
                                                    @else
                                                        <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2"
                                                            style="background-image: url({{ asset('assets/images/profile-1.png') }});">
                                                        </div>
                                                    @endif
                                                    <h6 class="text-break">{{$member['name']}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($event->documents) >= 1 || count($event->videos) >= 1)
                    <div class="col-xxl-5">
                        <div class="card pt-4">
                            <div class="message-poll-downloads">
                                <ul class="nav nav-tabs px-sm-5 px-4" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="downloads-tab" data-toggle="tab" href="#downloads" role="tab" aria-controls="downloads" aria-selected="false">
                                            <i class="feather-download"></i> <span class="d-sm-block d-none ms-2">{{__('Contact')}}</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane ps-sm-5 ps-4 fade" id="message" role="tabpanel"
                                        aria-labelledby="message-tab">
                                        <div class="chat">
                                            <img class=img-fluid src="{{ asset('assets/images/fake-chat.png') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="tab-pane ps-sm-5 ps-4 fade" id="poll" role="tabpanel"
                                        aria-labelledby="poll-tab">
                                        <div class="card mb-2 poll-card active">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href=""
                                                        class="btn btn-outline-blue fs-14 p-3 me-1 mb-2 active">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="fs-18 fw-400">{{__('Poll')}} #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="grey-666666">{{__('Lorem ipsum dolor sit aquon?')}}</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">{{__('Option')}}
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane ps-sm-5 ps-4 fade show active" id="downloads" role="tabpanel" aria-labelledby="downloads-tab">
                                        @foreach($event->documents as $document)
                                            <div class="card mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="fs-18 fw-400">{{$document->title}}</h6>
                                                            <a href="{{ url( '/seller/document/' . $document->id.'/download') }}"><i class="feather-download blue fs-22"></i></a>
                                                        </a>
                                                    </div>
                                                    <div class="download-btns">
                                                        <a href="javascript:;" class="btn btn-outline-black-2 fs-12 py-2 px-3 me-2 mb-2">{{__('Document')}}</a>
                                                        @foreach($document->tags as $tag)
                                                        <a href="javascript:;" class="btn btn-outline-black-2 fs-12 py-2 px-3 me-2 mb-2">{{$tag->name}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($event->videos as $video)
                                            <div class="card mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="fs-18 fw-400">{{$video->title}}</h6>
                                                        <a href="{{ url( '/seller/video/' . $video->id.'/download') }}"><i class="feather-download blue fs-22"></i></a>
                                                    </div>
                                                    <div class="download-btns">
                                                        <a href="javascript:;" class="btn btn-outline-black-2 fs-12 py-2 px-3 me-2 mb-2">{{__('Video')}}</a>
                                                        @foreach($video->tags as $tag)
                                                            <a href="javascript:;" class="btn btn-outline-black-2 fs-12 py-2 px-3 me-2 mb-2">{{$tag->name}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/event.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script>

$(document).ready(function() {
    $('#confirm-presence').click(function (){
        $.get($(this).data('href'), {}, function (response){
            console.log(response.success == true);
            if(response.success == true){
                $('#confirm-presence').hide();
                $('#confirmed-presence').show();
            }
        });
    });

    $('.event-active-btn').click(function (){
        $.post("/seller/events/status", {'id': $(this).data('event-id'), 'active': $(this).data('active')}, function (response){
            location.reload();
        });
    });



    @if(!empty($event->meeting_date) && !empty($event->meeting_time))
    const myEvents = [
      {
        start: "{{convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','Y-m-d H:i:s','CRM-TO-FRONT')}}",
        end: "{{convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s','Y-m-d H:i:s','CRM-TO-FRONT')}}",
        name: 'Event',
      }
    ]
    if(lang == "es") {
        customWeekdayValues= ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
        customMonthValues=["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    } else if(lang == "cs") {
        customWeekdayValues= ["Ne", "Po", "Út", "St", "Čt", "Pá", "So"];
        customMonthValues=["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];
    } else if(lang == "fr") {
        customWeekdayValues= ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"];
        customMonthValues=["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    } else {
        customWeekdayValues= ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        customMonthValues=["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    }
    new Calendar({
        id: '#color-calendar',
        eventsData: myEvents,
        customWeekdayValues: customWeekdayValues,
        customMonthValues: customMonthValues
    })
    @endif

    function c(passed_month, passed_year, calNum) {
        var calendar = calNum == 0 ? calendars.cal1 : calendars.cal2;
        makeWeek(calendar.weekline);
        calendar.datesBody.empty();
        var calMonthArray = makeMonthArray(passed_month, passed_year);
        var r = 0;
        var u = false;
        while (!u) {
            if (daysArray[r] == calMonthArray[0].weekday) {
                u = true
            } else {
                calendar.datesBody.append('<div class="blank"></div>');
                r++;
            }
        }
        for (var cell = 0; cell < 42 - r; cell++) { // 42 date-cells in calendar
            if (cell >= calMonthArray.length) {
                calendar.datesBody.append('<div class="blank"></div>');
            } else {
                var shownDate = calMonthArray[cell].day;
                var iter_date = new Date(passed_year, passed_month, shownDate);
                if (
                    (
                        (shownDate != today.getDate() && passed_month == today.getMonth()) || passed_month !=
                        today.getMonth()) && iter_date < today) {
                    var m = '<div class="past-date">';
                } else {
                    var m = checkToday(iter_date) ? '<div class="today">' : "<div>";
                }
                calendar.datesBody.append(m + shownDate + "</div>");
            }
        }

        var color = "#444444";
        calendar.calHeader.find("h2").text(i[passed_month] + " " + passed_year);
        calendar.weekline.find("div").css("color", color);
        calendar.datesBody.find(".today").css("color", "#87b633");

        // find elements (dates) to be clicked on each time
        // the calendar is generated
        var clicked = false;
        selectDates(selected);

        clickedElement = calendar.datesBody.find('div');
        clickedElement.on("click", function() {
            clicked = $(this);
            var whichCalendar = calendar.name;

            if (firstClick && secondClick) {
                thirdClicked = getClickedInfo(clicked, calendar);
                var firstClickDateObj = new Date(firstClicked.year,
                    firstClicked.month,
                    firstClicked.date);
                var secondClickDateObj = new Date(secondClicked.year,
                    secondClicked.month,
                    secondClicked.date);
                var thirdClickDateObj = new Date(thirdClicked.year,
                    thirdClicked.month,
                    thirdClicked.date);
                if (secondClickDateObj > thirdClickDateObj && thirdClickDateObj > firstClickDateObj) {
                    secondClicked = thirdClicked;
                    // then choose dates again from the start :)
                    bothCals.find(".calendar_content").find("div").each(function() {
                        $(this).removeClass("selected");
                    });
                    selected = {};
                    selected[firstClicked.year] = {};
                    selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
                    selected = addChosenDates(firstClicked, secondClicked, selected);
                } else { // reset clicks
                    selected = {};
                    firstClicked = [];
                    secondClicked = [];
                    firstClick = false;
                    secondClick = false;
                    bothCals.find(".calendar_content").find("div").each(function() {
                        $(this).removeClass("selected");
                    });
                }
            }
            if (!firstClick) {
                firstClick = true;
                firstClicked = getClickedInfo(clicked, calendar);
                selected[firstClicked.year] = {};
                selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
            } else {
                secondClick = true;
                secondClicked = getClickedInfo(clicked, calendar);

                // what if second clicked date is before the first clicked?
                var firstClickDateObj = new Date(firstClicked.year,
                    firstClicked.month,
                    firstClicked.date);
                var secondClickDateObj = new Date(secondClicked.year,
                    secondClicked.month,
                    secondClicked.date);

                if (firstClickDateObj > secondClickDateObj) {

                    var cachedClickedInfo = secondClicked;
                    secondClicked = firstClicked;
                    firstClicked = cachedClickedInfo;
                    selected = {};
                    selected[firstClicked.year] = {};
                    selected[firstClicked.year][firstClicked.month] = [firstClicked.date];

                } else if (firstClickDateObj.getTime() == secondClickDateObj.getTime()) {
                    selected = {};
                    firstClicked = [];
                    secondClicked = [];
                    firstClick = false;
                    secondClick = false;
                    $(this).removeClass("selected");
                }


                // add between dates to [selected]
                selected = addChosenDates(firstClicked, secondClicked, selected);
            }
            selectDates(selected);
        });

    }

    function selectDates(selected) {
        if (!$.isEmptyObject(selected)) {
            var dateElements1 = datesBody1.find('div');
            var dateElements2 = datesBody2.find('div');

            function highlightDates(passed_year, passed_month, dateElements) {
                if (passed_year in selected && passed_month in selected[passed_year]) {
                    var daysToCompare = selected[passed_year][passed_month];
                    for (var d in daysToCompare) {
                        dateElements.each(function(index) {
                            if (parseInt($(this).text()) == daysToCompare[d]) {
                                $(this).addClass('selected');
                            }
                        });
                    }

                }
            }

            highlightDates(year, month, dateElements1);
            highlightDates(nextYear, nextMonth, dateElements2);
        }
    }

    function makeMonthArray(passed_month, passed_year) { // creates Array specifying dates and weekdays
        var e = [];
        for (var r = 1; r < getDaysInMonth(passed_year, passed_month) + 1; r++) {
            e.push({
                day: r,
                // Later refactor -- weekday needed only for first week
                weekday: daysArray[getWeekdayNum(passed_year, passed_month, r)]
            });
        }
        return e;
    }

    function makeWeek(week) {
        week.empty();
        for (var e = 0; e < 7; e++) {
            week.append("<div>" + daysArray[e].substring(0, 3) + "</div>")
        }
    }

    function getDaysInMonth(currentYear, currentMon) {
        return (new Date(currentYear, currentMon + 1, 0)).getDate();
    }

    function getWeekdayNum(e, t, n) {
        return (new Date(e, t, n)).getDay();
    }

    function checkToday(e) {
        var todayDate = today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate();
        var checkingDate = e.getFullYear() + '/' + (e.getMonth() + 1) + '/' + e.getDate();
        return todayDate == checkingDate;
    }

    function getAdjacentMonth(curr_month, curr_year, direction) {
        var theNextMonth;
        var theNextYear;
        if (direction == "next") {
            theNextMonth = (curr_month + 1) % 12;
            theNextYear = (curr_month == 11) ? curr_year + 1 : curr_year;
        } else {
            theNextMonth = (curr_month == 0) ? 11 : curr_month - 1;
            theNextYear = (curr_month == 0) ? curr_year - 1 : curr_year;
        }
        return [theNextMonth, theNextYear];
    }

    function b() {
        today = new Date;
        year = today.getFullYear();
        month = today.getMonth();
        var nextDates = getAdjacentMonth(month, year, "next");
        nextMonth = nextDates[0];
        nextYear = nextDates[1];
    }

    var e = 480;

    var today;
    var year,
        month,
        nextMonth,
        nextYear;

    var r = [];
    var i = [
        "JANUARY",
        "FEBRUARY",
        "MARCH",
        "APRIL",
        "MAY",
        "JUNE",
        "JULY",
        "AUGUST",
        "SEPTEMBER",
        "OCTOBER",
        "NOVEMBER",
        "DECEMBER"
    ];
    var daysArray = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ];

    var cal1 = $("#calendar_first");
    var calHeader1 = cal1.find(".calendar_header");
    var weekline1 = cal1.find(".calendar_weekdays");
    var datesBody1 = cal1.find(".calendar_content");

    var cal2 = $("#calendar_second");
    var calHeader2 = cal2.find(".calendar_header");
    var weekline2 = cal2.find(".calendar_weekdays");
    var datesBody2 = cal2.find(".calendar_content");

    var bothCals = $(".calendar");

    var switchButton = bothCals.find(".calendar_header").find('.switch-month');

    var calendars = {
        "cal1": {
            "name": "first",
            "calHeader": calHeader1,
            "weekline": weekline1,
            "datesBody": datesBody1
        },
        "cal2": {
            "name": "second",
            "calHeader": calHeader2,
            "weekline": weekline2,
            "datesBody": datesBody2
        }
    }


    var clickedElement;
    var firstClicked,
        secondClicked,
        thirdClicked;
    var firstClick = false;
    var secondClick = false;
    var selected = {};

    b();
    c(month, year, 0);
    c(nextMonth, nextYear, 1);
    switchButton.on("click", function() {
        var clicked = $(this);
        var generateCalendars = function(e) {
            var nextDatesFirst = getAdjacentMonth(month, year, e);
            var nextDatesSecond = getAdjacentMonth(nextMonth, nextYear, e);
            month = nextDatesFirst[0];
            year = nextDatesFirst[1];
            nextMonth = nextDatesSecond[0];
            nextYear = nextDatesSecond[1];

            c(month, year, 0);
            c(nextMonth, nextYear, 1);
        };
        if (clicked.attr("class").indexOf("left") != -1) {
            generateCalendars("previous");
        } else {
            generateCalendars("next");
        }
        clickedElement = bothCals.find(".calendar_content").find("div");
    });


    //  Click picking stuff
    function getClickedInfo(element, calendar) {
        var clickedInfo = {};
        var clickedCalendar,
            clickedMonth,
            clickedYear;
        clickedCalendar = calendar.name;
        clickedMonth = clickedCalendar == "first" ? month : nextMonth;
        clickedYear = clickedCalendar == "first" ? year : nextYear;
        clickedInfo = {
            "calNum": clickedCalendar,
            "date": parseInt(element.text()),
            "month": clickedMonth,
            "year": clickedYear
        }
        return clickedInfo;
    }


    // Finding between dates MADNESS. Needs refactoring and smartening up :)
    function addChosenDates(firstClicked, secondClicked, selected) {
        if (secondClicked.date > firstClicked.date || secondClicked.month > firstClicked.month || secondClicked
            .year > firstClicked.year) {

            var added_year = secondClicked.year;
            var added_month = secondClicked.month;
            var added_date = secondClicked.date;

            if (added_year > firstClicked.year) {
                // first add all dates from all months of Second-Clicked-Year
                selected[added_year] = {};
                selected[added_year][added_month] = [];
                for (var i = 1; i <= secondClicked.date; i++) {
                    selected[added_year][added_month].push(i);
                }

                added_month = added_month - 1;
                while (added_month >= 0) {
                    selected[added_year][added_month] = [];
                    for (var i = 1; i <= getDaysInMonth(added_year, added_month); i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }

                added_year = added_year - 1;
                added_month = 11; // reset month to Dec because we decreased year
                added_date = getDaysInMonth(added_year, added_month); // reset date as well

                // Now add all dates from all months of inbetween years
                while (added_year > firstClicked.year) {
                    selected[added_year] = {};
                    for (var i = 0; i < 12; i++) {
                        selected[added_year][i] = [];
                        for (var d = 1; d <= getDaysInMonth(added_year, i); d++) {
                            selected[added_year][i].push(d);
                        }
                    }
                    added_year = added_year - 1;
                }
            }

            if (added_month > firstClicked.month) {
                if (firstClicked.year == secondClicked.year) {
                    selected[added_year][added_month] = [];
                    for (var i = 1; i <= secondClicked.date; i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }
                while (added_month > firstClicked.month) {
                    selected[added_year][added_month] = [];
                    for (var i = 1; i <= getDaysInMonth(added_year, added_month); i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }
                added_date = getDaysInMonth(added_year, added_month);
            }

            for (var i = firstClicked.date + 1; i <= added_date; i++) {
                selected[added_year][added_month].push(i);
            }
        }
        return selected;
    }
});
</script>
@endsection
