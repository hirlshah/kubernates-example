@extends('layouts.seller.index')
@section('title', isset($event)? 'Update Event' :'Create Event')
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card table-card p-3">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">@if(isset($event)) {{__('Update')}} @else {{__('Create')}} @endif {{__('Event')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($event))
                            {{ Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'events.store' , 'enctype'=>'multipart/form-data']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Name')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('name',Request::old('name'),array('class'=>"form-control")) }}
                                            @if ($errors->has('name'))
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Meeting Url')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('meeting_url',Request::old('meeting_url'),array('class'=>"form-control")) }}
                                            @if ($errors->has('meeting_url'))
                                                <span class="text-danger">{{ $errors->first('meeting_url') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3"> {{__('Meeting Date')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @if(isset($event))
                                                {{ Form::date('meeting_date', convertDateFormatWithTimezone($event->meeting_date." ".$event->meeting_time, 'Y-m-d H:i:s', 'Y-m-d'),array('class'=>"form-control")) }}
                                            @else
                                                {{ Form::date('meeting_date',Request::old('meeting_date'), array('class'=>"form-control")) }}
                                            @endif
                                            @if ($errors->has('meeting_date'))
                                                <span class="text-danger">{{ $errors->first('meeting_date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Meeting Time')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::time('meeting_time',null, ['class' => 'form-control']) }}
                                            @if ($errors->has('meeting_time'))
                                                <span class="text-danger">{{ $errors->first('meeting_time') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Tags')}}<span
                                        class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::select('tags[]', $tags, isset($tagIds) ? $tagIds : null, array('class'=>"form-control select2", 'multiple'=>true))}}
                                            @if ($errors->has('tags'))
                                                <span class="text-danger">{{ $errors->first('tags') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Image')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @if(isset($event))
                                                {{ Form::file('image', array('class = "form-control mb-4"')) }}
                                                <img src="{{ App\Classes\Helper\CommonUtil::getUrl($event->image) }}" style="width:50px;height:50px;"/>
                                            @else
                                                {{ Form::file('image', array('class = "form-control"')) }}
                                            @endif
                                            @if ($errors->has('image'))
                                                <span class="text-danger">{{ $errors->first('image') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Content')}}<span
                                        class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @if(isset($event->content))
                                                {{ Form::textarea('content_message',$event->content,array('class'=>"form-control", 'id'=>'content_message'))}}
                                            @else
                                                {{ Form::textarea('content_message',Request::old('content'),array('class'=>"form-control", 'id'=>'content_message'))}}
                                            @endif
                                            @if ($errors->has('content_message'))
                                                <span class="text-danger">{{ $errors->first('content_message') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-2')) }}
                            <a href="{{ url('admin/events') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
