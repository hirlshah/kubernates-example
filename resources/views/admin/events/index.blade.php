@extends('layouts.seller.index')
@section('title', 'Events')
@section('content')
    <div id="content">
        @if(Session::has('success'))
            <div class="alert alert-success">
                {{Session::get('success')}}
            </div>
        @endif
        <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
            <span class="minus"></span>
            <span class="minus"></span>
            <span class="minus"></span>
        </button>
        <div class="content-header d-flex align-items-center">
            <div class="content-header-left d-flex align-items-center">
                <i class="feather-calendar me-3"></i>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">{{__('Events')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="content-header-right d-flex align-items-center ms-auto">
                <a class="notification" href=""><i class="feather-bell blue"></i></a>
                <a href="{{ route('events.create') }}" type="button" class="btn btn-blue">+ {{__('New Event')}}</a>
            </div>
        </div>
        <div class="content-body p-0">
            <div class="card table-card px-5 py-3">
                <div class="card-header header-elements-inline px-0 py-3">
                    <h6 class="card-title fs-14">{{__('All Events')}} <span>({{ $eventCount }})</span></h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive datatable-basic events-table" id="data-table">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Image')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(function () {
            window.dataGridTable = $('#data-table').DataTable({
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
                ajax: '{!! route('events.data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'image', name: 'image'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
