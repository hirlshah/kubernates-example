@extends('layouts.frontend.index')
@section('content')
    <section id="countdown-timer" class="countdown-timer">
        <div class="container mb-4">
            <nav style="--bs-breadcrumb-divider: url({{ asset('/assets/images/breadcumb-devider.png') }});" aria-label="breadcrumb">
                <div class="row">
                    {{-- <div class="col-md-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $event->name }}</li>
                        </ol>
                    </div> --}}
                    <div class="col-md-12 text-center">
                        <img width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                    </div>
                </div>
            </nav>
        </div>
        <div class="container position-relative mb-md-n5">
            <div class="countdown" style="background-image: url({{ asset('assets/images/timer-bg.png') }})">
                <div>
                    @if(empty($event->meeting_date) || empty($event->meeting_time))
                        <p class="countdown-sm-title mb-4">{{__('You have been invited on a call by')}}</p>
                        <h1 class="countdown-lg-title mb-md-5 mb-4 event-start">{{$event->user->getFullName()}}</h1>
                    @else
                        <p class="countdown-sm-title mb-4">{{__('Hello, welcome')}}</p>
                        <h1 class="countdown-lg-title mb-md-5 mb-4">{{ $event->name }}</h1>
                        <br>
                        <ul class="p-0" id="time">
                            <li>
                                <div class="number" id="days"></div>
                                <span class="dhms">{{__('Days')}}</span>
                            </li>
                            <li>
                                <div class="number" id="hours"></div>
                                <span class="dhms">{{__('Hours')}}</span>
                            </li>
                            <li>
                                <div class="number" id="minutes"></div>
                                <span class="dhms">{{__('Minutes')}}</span>
                            </li>
                            <li>
                                <div class="number" id="seconds"></div>
                                <span class="dhms">{{__('Seconds')}}</span>
                            </li>
                        </ul>
                    @endif
                    @if(!Auth::user())
                        <div id="user-info-form-call" style="display: none;">
                            <form class="row g-3" action="{{route('frontend.store.contacts')}}" method="POST">
                                <input type="hidden" name="event_id" value="{{$event->id}}">
                                <input type="hidden" name="referral_id" value="{{app('request')->input('referral')}}">
                                <div class="col-md-12">
                                    <label for="contact_full_name" class="form-label">{{__('Full Name')}}</label>
                                    <input type="text" name="name" class="form-control" id="contact_name" required>
                                    <span class="text-warning print-error-msg-name" style="display:none"></span>
                                </div>
                                <div class="col-md-12">
                                    <label for="event_email" class="form-label">{{__('Email')}}</label>
                                    <input type="email" name="email" class="form-control" id="event_email" required>
                                    <span class="text-warning print-error-msg-email" style="display:none"></span>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-bluebtn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2">{{__('Enter the call')}}</button>
                                </div>
                            </form>
                        </div>
                    @elseif(Auth::user() && (empty($event->meeting_date) || empty($event->meeting_time)))
                        <div>
                            <a type="button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ $event->meeting_url }}" target="_blank">{{__('Enter the call')}}</a>
                        </div>
                    @endif
                    <div>
                        <a type="button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2 userdetailbutton" id="userdetailbutton">{{__('Confirm My Presence')}}</a>
                        <span class="px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" id="presence_confirmed" style="display: none">{{__('Your presence has been confirmed.')}}</span>
                        <br>
                        <a type="button" id="meeting_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ $event->meeting_url }}" target="_blank" style="display: none">{{__('Join Zoom')}}</a>
                    </div>
                    <div>
                        @if (Auth::user() && !$event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' => Auth::id()])->first())
                            <a type="button" id="zoom_meeting_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('register.auth.user',$event->id) }}" style="display: none">{{__('Confirm My Presence')}}</a>
                        @elseif (Auth::user() && $event->reps()->where(['status'=> ContactBoardStatus::CONFIRMED_FOR_ZOOM, 'member_id' =>
                        Auth::id()])->first())
                            <a type="button" id="zoom_meeting_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ $event->meeting_url }}" target="_blank" style="display: none">{{__('Join Zoom')}}</a>
                        @endif
                        <a type="button" id="event_survey_button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('frontend.survey', $event->slug) }}" style="display: none">{{__('Survey')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="chat">
        <div class="container mb-5">
            <h4>{{ $event->name }}</h4>
        </div>
        <div class="container">
            <div class="row">
                {{-- <div class="col-md-6 col-12">
                    <div class="btns">
                        @foreach($event->tags as $tag)
                        <button type="button" class="btn btn-outline-white fw-bold p-2 mb-3 fs-10">{{ $tag->name}}</button>
                        @endforeach
                        @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                        <button type="button" class="btn btn-outline-white fw-bold p-2 mb-3 fs-10">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y','CRM-TO-FRONT') }}</button>
                        <button type="button" class="btn btn-outline-white fw-bold p-2 mb-3 fs-10">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i','CRM-TO-FRONT') }}h</button>
                        @endif
                    </div>
                    <p>{{ $event->content}}</p>
                </div> --}}
                @if(count($event->documents) >= 1 || count($event->videos) >= 1)
                    <div class="col-md-6 col-12">
                        <div class="card-dark pt-4">
                            <div class="message-poll-downloads-dark">
                                <ul class="nav nav-tabs px-sm-5 px-4" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="downloads-tab" data-toggle="tab" href="#downloads" role="tab" aria-controls="downloads" aria-selected="false">
                                            <i class="feather-download"></i> <span class="d-sm-block d-none ms-2">Contact</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane ps-sm-5 ps-4 fade" id="message" role="tabpanel"
                                        aria-labelledby="message-tab">
                                        <div class="chat">
                                            <img class=img-fluid src="{{ asset('assets/images/fake-chat-dark.png') }}" alt="">
                                        </div>
                                    </div>
                                    <div class="tab-pane ps-sm-5 ps-4 fade" id="poll" role="tabpanel"
                                        aria-labelledby="poll-tab">
                                        <div class="card-dark-2 mb-2 poll-card active">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href=""
                                                        class="btn btn-outline-blue fs-14 p-3 me-1 mb-2 active">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-dark-2 mb-2 poll-card">
                                            <div class="card-body p-4">
                                                <div
                                                    class="poll-header d-flex align-items-center justify-content-between mb-3">
                                                    <h6 class="text-white fs-18 fw-400">Poll #01</h6>
                                                    <i class="feather-check blue fs-22 green-2"></i>
                                                </div>
                                                <p class="text-white">Lorem ipsum dolor sit aquon?</p>
                                                <div class="poll-btns">
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                    <a href="" class="btn btn-outline-blue fs-14 p-3 me-1 mb-2">Option
                                                        01</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane ps-sm-5 ps-4 fade show active" id="downloads" role="tabpanel"
                                        aria-labelledby="downloads-tab">

                                        @foreach($event->documents as $document)
                                            <div class="card-dark-2 mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="text-white fs-18 fw-400">{{$document->title}}</h6>
                                                        @if(\Storage::disk('public')->missing($document->document))
                                                            <a href="#"><i class="feather-download blue fs-22"></i></a>
                                                        @else
                                                            <a href="{{ url( '/document/' . $document->id.'/download') }}"><i
                                                            class="feather-download blue fs-22"></i></a>
                                                        @endif
                                                    </div>
                                                    <div class="download-btns">
                                                        <a href="javascript:;" class="btn btn-outline-grey fs-12 py-2 px-3 me-2 mb-2">{{__('Document')}}</a>
                                                        @foreach($document->tags as $tag)
                                                            <a href="javascript:;" class="btn btn-outline-grey fs-12 py-2 px-3 me-2 mb-2">{{$tag->name}}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($event->videos as $video)
                                            <div class="card-dark-2 mb-3">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <h6 class="text-white fs-18 fw-400">{{$video->title}}</h6>
                                                        @if(\Storage::disk('public')->missing($video->video))
                                                            <a href="#"><i class="feather-download blue fs-22"></i></a>
                                                        @else
                                                            <a href="{{ url( '/video/' . $video->id.'/download') }}"><i
                                                            class="feather-download blue fs-22"></i></a>
                                                        @endif
                                                    </div>
                                                    <div class="download-btns">
                                                        <a href="javascript:;"
                                                           class="btn btn-outline-grey fs-12 py-2 px-3 me-2 mb-2">{{__('Video')}}</a>
                                                        @foreach($video->tags as $tag)
                                                            <a href="javascript:;" class="btn btn-outline-grey fs-12 py-2 px-3 me-2 mb-2">{{$tag->name}}</a>
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
    </section>
    {{-- <section id="home-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 img-col order-md-1 order-2">
                    <img class="img-fluid mt-md-n4" src="{{ asset('assets/images/girl-with-mobile.png') }}" alt="">
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center order-md-2 order-1">
                    <div class="content">
                        <h1>Lorem Ipsum dolor.</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus
                            venent, lectus magna fringilla consectetur adipiscing elit ut aliqui, purus sit amet luctus
                            venent, lectus magna.</p>
                        <button type="button" class="btn btn-white fw-bold px-5 mb-2 me-md-3">Lorem Ipsum</button>
                        <button type="button" class="btn btn-outline-white fw-bold px-5 mb-2">Lorem Ipsum</button>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
{{--    <section id="similar-events">--}}
{{--        <div class="container">--}}
{{--            <h5 class="fs-18 mb-5">{{__('Similar events')}}</h5>--}}

{{--            <div class="row">--}}
{{--                @foreach($similarEvents as $similarEvent)--}}
{{--                    <div class="col-lg-4 col-sm-6 col-12 mb-3">--}}
{{--                        <div class="card border-0">--}}
{{--                            @if(isset($similarEvent->image))--}}
{{--                                <div class="event-image"--}}
{{--                                     style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($similarEvent->image) }}); min-height: 200px;"></div>--}}
{{--                            @else--}}
{{--                                <div class="event-image" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }}); min-height: 200px;"></div>--}}
{{--                            @endif--}}

{{--                            <div class="card-body ">--}}
{{--                                <h6 class="card-title mb-4"><a href="{{ route('frontend.event.details', $similarEvent->slug) }}">{{ $similarEvent->name }}</a></h6>--}}
{{--                                <p class="card-text mb-1">{{ $similarEvent->content }}</p>--}}
{{--                                <div class="tags">--}}
{{--                                    @foreach($similarEvent->tags as $tag)--}}
{{--                                        <span class="">{{ $tag->name }}</span>--}}
{{--                                    @endforeach--}}
{{--                                    @if(!empty($event->meeting_date) && !empty($event->meeting_time))--}}
{{--                                    <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y','CRM-TO-FRONT') }}</span>--}}
{{--                                    <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i','CRM-TO-FRONT') }}h</span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <a type="button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('frontend.survey', $event->slug) }}">{{__('Submit survey')}}</a>--}}
{{--                                @if(!empty($event->meeting_date) && !empty($event->meeting_time))--}}
{{--                                <a type="button" class="btn btn-outline-black px-3 py-3 fs-14 mb-2"><i class="feather-calendar me-1"></i>{{__(' Add to calendar')}}</a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <!-- /Add new contact modal -->
    @include('frontend.event.event_contact_modal')
@endsection

@section('frontend-scripts')
<script>
    @if(!empty($event->meeting_date) && !empty($event->meeting_time))
    let eventMeetingDate =  "{{ convertDateFormatWithTimezone($event->meeting_date.' '.$event->meeting_time, 'Y-m-d H:i:s', 'Y-m-d H:i:s','CRM-TO-FRONT') }}";
    @else
    let eventMeetingDate = null;
    let eventMeetingTime = null;
    @endif
    @if($cookieCustomCheck) 
        let referralCookie =  '{{ $referralCode ? 1 : 0 }}';
        let eventCookie =  '{{ $event->id }}';
    @else 
        let referralCookie =  '{{ Cookie::get("referralcode_$referralCode")}}';
        let eventCookie =  '{{ Cookie::get("event_$event->id")}}';
    @endif
    
    let event =  '{{ $event->id }}';
    let eventMeetingURL = '{{$event->meeting_url}}';
    let attendedZoom = '{{\App\Enums\ContactBoardStatus::ATTENDED_THE_ZOOM}}';
    let confirmedZoom = '{{\App\Enums\ContactBoardStatus::CONFIRMED_FOR_ZOOM}}';
    let getEventDateRoute = '{{ route("get.event.date",$event->id) }}';
    let authUser = '{{Auth::user() ? "true" : "false"}}';
    let zoomMeetingText = '{{__("Zoom Meeting")}}';
</script>
<script src="{{ asset('/assets/js/front_event.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
