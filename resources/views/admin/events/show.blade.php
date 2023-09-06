@extends('layouts.seller.index')
@section('content')
    <div id="content">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <a class="blue fs-24 me-3" href="{{ route('events.index') }}"><i
                    class=" feather-arrow-left"></i></a>
                    <h2> {{__('Show Events')}}</h2>
                </div>
            </div>
        </div>
        <div class="card container-fluid">
            <div class="row p-5">
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Name')}}:</strong>
                        {{ $event->name }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Start Date Time')}}:</strong>
                        {{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s', 'd/M/Y') }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('End Date Time:')}}</strong>
                        {{ convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s', 'H:i') }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Meeting Url')}}:</strong>
                        {{ $event->meeting_url }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Image')}}:</strong>
                        @if($event->image == null || \Illuminate\Support\Facades\Storage::disk('public')->missing($event->type))
                            <img src="{{ asset('uploads/static.png') }}" style="width:50px;height:50px;"/>
                        @else
                            <img src="{{ asset('storage/' . $event->image) }}" style="width:50px;height:50px;"/>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Tags')}}:</strong>
                        {{ implode(",",$event->tags()->get()->pluck('name')->toArray()) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
