@extends('layouts.frontend.index')
@section('content')
    <section id="similar-events">
        <div class="container">
            <div class="row">
                @foreach($events as $event)
                    <div class="col-lg-4 col-sm-6 col-12 mb-3">
                        <div class="card border-0">
                            @if(isset($event->image))
                                <div class="event-image event-image" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}); min-height: 200px;"></div>
                            @else
                                <div class="event-image event-image" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }}); min-height: 200px;"></div>
                            @endif
                            <div class="card-body ">
                                <h6 class="card-title mb-4"><a href="{{ route('frontend.event.details', $event->slug) }}">{{ $event->name }}</a></h6>
                                <p class="card-text mb-1">{{ $event->content }}</p>
                                <div class="tags">
                                    @foreach($event->tags as $tag)
                                        <span class="">{{ $tag->name }}</span>
                                    @endforeach
                                    @if(!empty($event->meeting_date) && !empty($event->meeting_time))
                                        <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','d M. Y') }}</span>
                                        <span class="">{{ convertDateFormatWithTimezone($event->meeting_date ." ".$event->meeting_time,'Y-m-d H:i:s','H:i') }}h</span>
                                    @endif
                                </div>
                                <a type="button" class="btn btn-blue px-3 py-3 fs-14 fw-bold mr-3 mb-2 me-2" href="{{ route('frontend.survey', $event->slug) }}">{{__('Submit survey')}}</a>
                                <a type="button" class="btn btn-outline-black px-3 py-3 fs-14 mb-2"><i class="feather-calendar me-1"></i> {{__('Add to calendar')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
