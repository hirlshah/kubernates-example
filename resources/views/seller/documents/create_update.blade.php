@extends('layouts.seller.index')
@section('title', isset($document)? 'Update Document' :'Create Document')
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
                        <h6 class="card-title">@if(isset($document)) {{__('Update')}} @else {{__('Create')}} @endif {{__('Document')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        @if(isset($document))
                            {{ Form::model($document, ['route' => ['documents.update', $document->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'documents.store' , 'enctype'=>'multipart/form-data']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Title')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('title',Request::old('title'),array('class'=>"form-control")) }}
                                            @if ($errors->has('title'))
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Date')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @if(isset($document))
                                                {{ Form::date('date', Carbon\Carbon::parse($document->date)->format('Y-m-d'),array('class'=>"form-control")) }}
                                            @else
                                                {{ Form::date('date',Request::old('date'), array('class'=>"form-control")) }}
                                            @endif
                                            @if ($errors->has('date'))
                                                <span class="text-danger">{{ $errors->first('date') }}</span>
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
                                        <label class="col-form-label col-lg-3">{{__('Tags')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                        {{ Form::select('tags[]', $tags, isset($tagIds) ? $tagIds : null, array('class'=>"form-control select2 bg-image-none", 'multiple'=>true))}}
                                        @if ($errors->has('tags'))
                                            <span class="text-danger">{{ $errors->first('tags') }}</span>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Document')}}<span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::file('document', array('class = "form-control"')) }}
                                            @if ($errors->has('document'))
                                                <span class="text-danger">{{ $errors->first('document') }}</span>
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
                                         <label class="col-form-label col-lg-3">{{__('Description')}}<span class="text-danger">*</span></label>
                                         <div class="col-lg-9">
                                             {{ Form::textarea('description',Request::old('description'),array('class'=>"form-control", 'id'=>'description'))}}
                                             @if ($errors->has('description'))
                                                 <span class="text-danger">{{ $errors->first('description') }}</span>
                                             @endif
                                         </div>
                                     </div>
                                 </div>
                            </div>
                        </fieldset>
                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-')) }}
                            <a href="{{ url('admin/documents') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
