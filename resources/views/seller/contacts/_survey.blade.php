@if(isset($survey))
    <div class="col-12 mt-3">
        <hr>
        <h5>{{__('Survey')}}</h5>
    </div>

    @foreach($survey->surveyQuestions as $key => $question)
        <div class="col-md-6 col-12 mb-4 mt-2">
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
                    @endif
                    @foreach($survey->getAnswers($question->pivot->answers_ids) as $answer)
                        @if($answer->answer == 'text')
                            <textarea class="form-control mt-2" name="answer_ids[{{$question->id}}][answers_text]"
                            placeholder="{{__('Answer here')}}" rows="2" readonly>{{ $contact->survey->where('question_id',$question->id)->where('contact_id',$contact->id)->first()->answer_text }}</textarea>
                        @else
                            <label class="answer-btn">
                                <input hidden type="radio" name="answer_ids[{{$question->id}}][answer]" value="{{ $answer->id }}">
                            <span class="btn btn-outline-blue mb-3 p-3 me-2"></span>
                            </label>
                        @endif
                    @endforeach
                    @if($question->pivot->with_comment == 1)
                        <textarea class="form-control mt-2" name="answer_ids[{{ $question->id }}][comment]" placeholder="{{__('Comment here')}}" rows="2" readonly>{{ $contact->survey->where('question_id',$question->id)->where('contact_id',$contact->id)->first()->answer_text }}</textarea>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif