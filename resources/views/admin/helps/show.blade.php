@extends('layouts.seller.index')
@section('content')
    <div id="content">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <a class="blue fs-24 me-3" href="{{ route('admin.helps.index') }}"><i
                                class=" feather-arrow-left"></i></a>
                    <h2> {{__('Show Helps')}}</h2>
                </div>
            </div>
        </div>
        <div class="card container-fluid">
            <div class="row p-5">
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Title En')}}:</strong>
                        {{ $help->title_en }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Title Fr')}}:</strong>
                        {{ $help->title_fr }}
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 mb-3">
                    <div class="form-group">
                        <strong>{{__('Url:')}}</strong>
                        {{ $help->url }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
