@extends('layouts.frontend.index')
@section('content')
    <section id="survey-list">
        <div class="container" style="margin-bottom:5rem;">
            <nav style="--bs-breadcrumb-divider: url({{ asset('/assets/images/breadcumb-devider.png') }});" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{__('Home')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><span class="circle-icon">•</span> {{__('Survey')}}</li>
                </ol>
            </nav>
        </div>
        <div class="container">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {{Session::get('success')}}
                </div>
            @else
                <h4 class="mb-4">{{__('Survey')}}</h4>
                <form action="{{ route('frontend.prospection.survey.store', ['slug' => $slug]) }}" id="user-survey-form" method="post" autocomplete="off">
                    @csrf
                    <input type="hidden" name="video_visiters_table_id" value="{{ $video_visiters_table_id }}">
                    <input type="hidden" name="referral" value="{{ $referralCode }}">
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
                                                        <span class="text-danger survey-error-msg print-error-msg-{{'answer_ids-'.$question->id.'-answers_text'}}" style="display:none"></span>
                                                    @else
                                                        <label class="answer-btn">
                                                            <input hidden type="radio" name="answer_ids[{{$question->id}}][answer]"
                                                            value="{{ $answer->id }}">
                                                            <span class="btn btn-outline-blue mb-3 p-3 me-2">{{ $answer->answer }}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <span class="text-danger survey-error-msg print-error-msg-{{'answer_ids-'.$question->id.'-answer'}}" style="display:none"></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if(isset($survey))
                            {{ Form::hidden('survey_id', $survey->id) }}
                            <div class="col-12">
                                {{ Form::submit(__('Submit survey'),array('class'=>'btn btn-blue px-5')) }}
                            </div>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </section>
@endsection
