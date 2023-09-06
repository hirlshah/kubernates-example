@extends('layouts.frontend.index')
@section('content')
    <section id="survey-list">
        <div class="container">
            <nav style="--bs-breadcrumb-divider: url({{ asset('/assets/images/breadcumb-devider.png') }});" aria-label="breadcrumb">
                <ol class="breadcrumb mb-5">
                    <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{__('Survey')}}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{Session::get('success')}}
                </div>
            @elseif($alreadyFilled)
                <div class="alert alert-success">
                    {{__('You have already submitted this survey. Thank you !')}}
                </div>
            @else
                <h4 class="mb-4">{{__('The importance of showing up and coming to events - Survey')}}</h4>
                <form action="{{ route('frontend.survey.store', ['slug'=>$slug]) }}" id="user-survey-form" method="post" autocomplete="off">
                    <input type="hidden" name="event_id" value="{{$eventId}}">
                    <input type="hidden" name="contact_id" value="{{$contact_id}}">
                    @csrf
                    <div class="row">
                        @if(isset($survey->surveyQuestions))
                            @foreach($survey->surveyQuestions as $key => $question)
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title mb-3">{{ $question->title }}</h6>
                                            @if($question->is_rating == 1)
                                                <div class="stars mt-5">
                                                    <div class="stars">
                                                        @foreach($survey->getAnswers($question->pivot->answers_ids) as $answer)
                                                            <a data-id="{{ $answer->id }}"><i class="feather-star"></i></a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <input type="hidden" name="answer_ids[{{$question->id}}][question]" value="{{$question->id}}">
                                                <input type="hidden" name="answer_ids[{{$question->id}}][answer]" id="rating">
                                            @else
                                                <input type="hidden" name="answer_ids[{{$question->id}}][question]" value="{{$question->id}}">
                                                @if(!$survey->getAnswers($question->pivot->answers_ids))
                                                    <input type="hidden" name="answer_ids[{{$question->id}}][only_comment]" value="1">
                                                @endif
                                                @foreach($survey->getAnswers($question->pivot->answers_ids) as $answer)
                                                    @if($answer->answer == 'text')
                                                        <textarea class="form-control mt-2"
                                                        name="answer_ids[{{$question->id}}][answers_text]" placeholder="{{__('Answer here')}}" rows="2"></textarea>
                                                        <input type="hidden" name="answer_ids[{{$question->id}}][question]" value="{{$question->id}}">
                                                        <input type="hidden" name="answer_ids[{{$question->id}}][answer]" value="{{$answer->id}}">
                                                        <span class="text-danger survey-error-msg print-error-msg-{{'answer_ids-'.$question->id.'-answers_text'}}"
                                                        style="display:none"></span>
                                                    @else
                                                        <label class="answer-btn">
                                                            <input hidden type="radio" name="answer_ids[{{$question->id}}][answer]" value="{{ $answer->id }}">
                                                            <span class="btn btn-outline-blue mb-3 p-3 me-2">{{ $answer->answer }}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <span class="text-danger survey-error-msg print-error-msg-{{'answer_ids-'.$question->id.'-answer'}}"
                                            style="display:none"></span>
                                            @if($question->pivot->with_comment == 1)
                                                <textarea class="form-control mt-2"
                                                name="answer_ids[{{ $question->id }}][comment]"
                                                placeholder="{{__('Comment here')}}" rows="2"></textarea>
                                                <span class="text-danger survey-error-msg print-error-msg-{{'answer_ids-'.$question->id.'-comment'}}" style="display:none"></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{ Form::hidden('event_id',$eventId) }}
                        @if(isset($survey))
                            {{ Form::hidden('survey_id',$survey->id) }}

                            <div class="col-12">
                                {{ Form::submit(__('Submit survey'),array('class'=>'btn btn-blue px-5')) }}
                            </div>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </section>
    <section id="home-3" class="d-none">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 img-col order-md-1 order-2">
                    <img class="img-fluid mt-md-n4" src="{{ asset('assets/images/girl-with-mobile.png') }}" alt="">
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center order-md-2 order-1">
                    <div class="content">
                        <h1>Lorem Ipsum dolor.</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent, lectus magna fringilla consectetur adipiscing elit ut aliqui, purus sit amet luctus venent, lectus magna.</p>
                        <button type="button" class="btn btn-white fw-bold px-5 mb-2 me-md-3">Lorem Ipsum</button>
                        <button type="button" class="btn btn-outline-white fw-bold px-5 mb-2">Lorem Ipsum</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <section id="similar-events">
        <div class="container">
            <h5 class="fs-18 mb-5">{{__('Similar events')}}</h5>
            <div class="row">
                @foreach($events as $event)
                    <div class="col-lg-4 col-sm-6 col-12 mb-3">
                        <div class="card border-0">
                            <div class="event-image" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}); min-height: 200px;"></div>
                            <div class="card-body ">
                                <h6 class="card-title mb-4">
                                    <a href="{{ route('frontend.event.details', $event->slug) }}">{{ $event->name }}</a>
                                </h6>
                                <p class="card-text mb-1">{{ $event->content }}</p>
                                <div class="tags">
                                    @foreach($event->tags as $tag)
                                        <span class="">{{ $tag->name }}</span>
                                    @endforeach
                                    @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                                        <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                                        <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s', 'H:i') }}h</span>
                                    @endif
                                </div>
                                <a type="button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('frontend.survey', $event->slug) }}">{{__('Submit survey')}}</a>
                                <a type="button" class="btn btn-outline-black px-3 py-3 fs-14 mb-2"><i class="feather-calendar me-1"></i>{{__('Add to calendar')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}
@endsection
@section('frontend-scripts')
    <script src="{{asset('/assets/js/front_survey.js?ver=')}}{{env('JS_VERSION')}}" ></script>
@endsection
