@extends('layouts.seller.index')
@section('title', isset($page)? 'Update Page' :'Create Page')
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{Session::get('error')}}
            </div>
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="card table-card p-3">
                    <div class="card-header header-elements-inline">
                        <h6 class="card-title">@if(isset($page)) {{__('Update')}} @else {{__('Create')}} @endif
                            {{__('Page')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($page))
                            {{ Form::model($page, ['route' => ['pages.update', $page->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'pages.store' , 'enctype'=>'multipart/form-data']) }}
                        @endif
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Page Id')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @if(isset($page->page_id))
                                                {{ Form::text('page_id',Request::old('page_id'),array('class'=>"form-control" ,'readonly'=>"readonly")) }}
                                            @else
                                                {{ Form::text('page_id',Request::old('page_id'),array('class'=>"form-control")) }}
                                            @endif
                                            @if ($errors->has('page_id'))
                                                <span class="text-danger">{{ $errors->first('page_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">{{__('Title')}} <span
                                        class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            {{ Form::text('title',Request::old('title'),array('class'=>"form-control")) }}
                                            @if ($errors->has('title'))
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <label class="col-form-label">{{__('Description')}}<span
                                class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    {{ Form::textarea('description',Request::old('description'),array('class'=>"form-control tinymce-editor", 'id'=>'description'))}}
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                        </fieldset>

                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-2')) }}
                            <a href="{{ url('admin/pages') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>   
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea.tinymce-editor', // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists',
        toolbar: 'undo redo | formatselect| bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
   });
</script>
@endsection
