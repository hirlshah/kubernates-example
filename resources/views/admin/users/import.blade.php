@extends('layouts.seller.index')
@section('title', 'Users')
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
                        <h6 class="card-title">{{ __('Import')}}</h6>
                    </div>
                    <div class="card-body">
                        {{ Form::open(['route' => 'admin.users.import' , 'enctype'=>'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-md-6 col-12 mb-4">
                                    <div class="row">
                                        <input type="file" name="file">
                                        @if ($errors->has('file'))
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                {{ Form::submit(__('Submit'),array('class'=>'btn btn-blue')) }}
                                <a href="{{ url('/admin/users') }}" class="btn btn-outline-black m-2">{{__('Cancel')}}</a>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection