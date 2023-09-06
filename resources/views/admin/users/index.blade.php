@extends('layouts.seller.index')
@section('title', 'Users')
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        <!-- Basic datatable -->
        <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
            <span class="minus"></span>
            <span class="minus"></span>
            <span class="minus"></span>
        </button>
        <div class="content-header d-flex align-items-center gap-3">
            <div class="content-header-left d-flex align-items-center">
                <i class="feather-user me-3"></i>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">{{__('Users')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="content-header-right d-flex align-items-center ms-auto">
                <a href="{{ route('import-form') }}" type="button" class="btn btn-blue user_import_btn">+ {{__('Import')}}</a>
            </div>
            <div class="content-header-right d-flex align-items-center">
                <a href="{{ route('users.create') }}" type="button" class="btn btn-blue">+ {{__('New User')}}</a>
            </div>
        </div>
        <div class="content-body p-0">
            <div class="card table-card">
                <div class="card-header header-elements-inline px-4 py-3">
                    <h6 class="card-title fs-14">{{__('All Users')}} <span>({{ $userCount }})</span></h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive px-4 py-3">
                    <table class="table datatable-basic users-table" id="data-table">
                        <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('User Name')}}</th>
                            <th>{{__('Email')}}</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        jQuery(function () {
            window.dataGridTable = jQuery('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "order": [[1, "asc"]],
                language: {
                    "paginate": {
                        "previous": "{{__('Previous')}}",
                        "next": "{{__('Next')}}"
                    },
                    "sSearch": "{{__('Search')}}",
                    "sProcessing": "{{__('Processing')}}",
                    "sLengthMenu": "{{__('Show')}} _MENU_ {{__('entries')}}",
                    "info": "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
                },
                ajax: '{!! route('users.data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'email', name: 'email'},
                    {data: 'profile_image', name: 'profile_image'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
