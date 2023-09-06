@extends('layouts.seller.index')
@section('title', 'Coupons')
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
                        <li class="breadcrumb-item active" aria-current="page">{{__('Coupons')}}</li>
                    </ol>
                </nav>
            </div>
            <div class="content-header-right d-flex align-items-center ms-auto">
                <a href="{{ route('coupons.create') }}" type="button" class="btn btn-blue">+ {{__('New Coupon')}}</a>
            </div>
        </div>
        <div class="content-body p-0">
            <div class="card table-card px-5 py-3">
                <div class="card-header header-elements-inline px-0 py-3">
                    <h6 class="card-title fs-14">{{__('All Coupons')}} <span>({{ $couponCount }})</span></h6>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive datatable-basic coupons-table" id="data-table">
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Code')}}</th>
                        <th>{{__('Description')}}</th>
                        <th>{{__('Is Active')}}</th>
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
                ajax: '{!! route('coupons.data') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'code', name: 'code'},
                    {data: 'description', name: 'description'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@endsection
