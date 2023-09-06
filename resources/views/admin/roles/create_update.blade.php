@extends('layouts.seller.index')
@section('title', isset($role)? 'Update Role' :'Create Role')
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
                        <h6 class="card-title">@if(isset($role)) {{__('Update')}} @else {{__('Create')}} @endif {{__('Role')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        @if(isset($role))
                            {{ Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'patch' , 'enctype'=>'multipart/form-data']) }}
                        @else
                            {{ Form::open(['route' => 'roles.store' , 'enctype'=>'multipart/form-data']) }}
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
                                        <label class="col-form-label col-lg-3">{{__('Permission')}} <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            @foreach($permission as $value)
                                                <label>{{ Form::checkbox('permission[]', $value->id, isset($role) ? in_array($value->id, $rolePermissions) ? true : false : false, array('class' => 'name')) }}
                                                    {{ $value->name }}</label>
                                                <br/>
                                            @endforeach
                                            @if ($errors->has('permission'))
                                                <span class="text-danger">{{ $errors->first('permission') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="text-end">
                            {{ Form::submit(__('Submit'),array('class'=>'btn btn-outline-black m-2')) }}
                            <a href="{{ url('admin/roles') }}" class="btn btn-blue">{{__('Cancel')}}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
