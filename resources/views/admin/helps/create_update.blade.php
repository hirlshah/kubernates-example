@extends('layouts.seller.index')
@section('title', isset($help)? 'Update Help' :'Create Help')
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
                        <h6 class="card-title">@if(isset($help)) {{__('Update')}} @else {{__('Create')}} @endif {{__('Help')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($help))
                            {{ Form::model($help, ['route' => ['admin.helps.update', $help->id], 'method' => 'patch']) }}
                        @else
                            {{ Form::open(['route' => 'admin.helps.store','method' => 'post']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Title EN')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('title_en',Request::old('title_en'),array('class'=>"form-control")) }}
                                            @if ($errors->has('title_en'))
                                                <span class="text-danger">{{ $errors->first('title_en') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Title FR')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('title_fr',Request::old('title_fr'),array('class'=>"form-control")) }}
                                            @if ($errors->has('title_fr'))
                                                <span class="text-danger">{{ $errors->first('title_fr') }}</span>
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
                                        <label class="col-form-label col-lg-3">{{__('Url')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('url',Request::old('url'),array('class'=>"form-control")) }}
                                            @if ($errors->has('url'))
                                                <span class="text-danger">{{ $errors->first('url') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-2')) }}
                            <a href="{{ url('admin/helps') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
