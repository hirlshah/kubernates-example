@extends('layouts.seller.index')
@section('content')
<div id="content">
    @if(Session::has('success'))
        <div class="alert alert-success" id="successMessage">
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
            <i class="feather-clipboard me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Analytics')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <a class="notification" href=""><i class="feather-bell blue"></i></a>
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body p-0">
        <div class="card table-card px-5 py-3">
            <div class="container-fluid">
                <div class="card-header header-elements-inline px-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title fs-18 mt-auto mb-auto">{{__('Analytics')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="reload"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                        <div class="d-flex">
                            <input type="text" name="analytic_search" class="form-control analytic_search" placeholder="{{__('Search')}}" />
                            <input type="text" class="form-control datechange ms-2" id="datechange" name="daterange" value="" placeholder="{{__('Select Date')}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row" style="overflow: overlay;">
                <table class="table table-responsive datatable-basic" id="data-table">
                    <thead>
                        <tr>
                            <th width="5%">{{__('ID')}}</th>
                            <th width="10%">{{__('Contact Name')}}</th>
                            <th width="10%">{{__('Added On')}}</th>
                            <th width="10%">{{__('One on on call')}}</th>
                            <th width="10%">{{__('Added By')}}</th>
                            <th width="10%">{{__('Status')}}</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <div class="content-body">
        {{-- NEW CONTACTS CODE --}}
        <div class="row">
            <div class="col-12 mb-4 mt-4">
                <h5>{{__('New Contact')}}</h5>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New Contact').' '. __('per day')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="contact-per-day-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="contact-per-day-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="contact-per-day-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="contact-per-day" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New Contact').' '. __('per week')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="contact-per-week-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="contact-per-week-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="contact-per-week-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="contact-per-week" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New Contact').' '. __('per month')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="contact-per-month-previous" data-data="" data-function=""><i class="feather-chevron-left"></i></a>
                                <span id="contact-per-month-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="contact-per-month-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="contact-per-month" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- NEW CLIENTS CODE --}}
        <div class="row">
            <div class="col-12 mb-4 mt-4">
                <h5>{{__('New client')}}</h5>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New client').' '. __('per day')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="client-per-day-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="client-per-day-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"id="client-per-day-next" data-data="" data-function=""><i class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="client-per-day" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New client').' '. __('per week')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="client-per-week-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="client-per-week-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="client-per-week-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="client-per-week" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New client').' '. __('per month')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="client-per-month-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="client-per-month-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="client-per-month-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="client-per-month" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- NEW DISTRIBUTOR CODE --}}
        <div class="row">
            <div class="col-12 mb-4 mt-4">
                <h5>{{__('New distributor')}}</h5>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New distributor').' '. __('per day')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="distributor-per-day-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="distributor-per-day-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="distributor-per-day-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="distributor-per-day" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New distributor').' '. __('per week')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="distributor-per-week-previous" data-data="" data-function=""><i class="feather-chevron-left"></i></a>
                                <span id="distributor-per-week-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="distributor-per-week-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="distributor-per-week" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New distributor').' '. __('per month')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="distributor-per-month-previous" data-data="" data-function=""><i class="feather-chevron-left"></i></a>
                                <span id="distributor-per-month-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="distributor-per-month-next" data-data="" data-function=""><i class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="distributor-per-month" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- NEW DISTRIBUTOR CODE --}}
        <div class="row">
            <div class="col-12 mb-4 mt-4">
                <h5>{{__('New followup')}}</h5>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New followup').' '. __('per day')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="followup-per-day-previous" data-data="" data-function=""><i
                                class="feather-chevron-left"></i></a>
                                <span id="followup-per-day-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="followup-per-day-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="followup-per-day" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New followup').' '. __('per week')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="followup-per-week-previous" data-data="" data-function=""><i class="feather-chevron-left"></i></a>
                                <span id="followup-per-week-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="followup-per-week-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="followup-per-week" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                            <h6 class="fs-18">{{__('New followup').' '. __('per month')}}</h6>
                            <div class="month-navigation d-inline-flex align-items-center">
                                <a href="javascript:void(0);" class="blue fs-26 me-2 chartData"
                                id="followup-per-month-previous" data-data="" data-function=""><i class="feather-chevron-left"></i></a>
                                <span id="followup-per-month-name"></span>
                                <a href="javascript:void(0);" class="blue fs-26 ms-2 chartData"
                                id="followup-per-month-next" data-data="" data-function=""><i
                                class="feather-chevron-right"></i></a>
                            </div>
                        </div>
                        <canvas id="followup-per-month" style="width:60%;max-width:400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body p-0">
        <div class="card table-card px-5 py-3">
            <div class="container-fluid">
                <div class="card-header header-elements-inline px-0 py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title fs-18 mt-auto mb-auto">{{__('Zoom Meetings')}}</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="reload"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                        <div class="d-flex">
                            {{-- <input type="text" name="zoom_search" class="form-control zoom_search" placeholder="{{__('Search')}}" />
                            <input type="text" class="form-control datechange ms-2" id="zoom_datechange" name="zoom_datechange" value="" placeholder="{{__('Select Date')}}" /> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row" style="overflow: overlay;">
                <table class="table table-responsive datatable-basic" id="zoom-data-table">
                    <thead>
                        <tr>
                            <th width="10%">{{__('User')}}</th>
                            <th width="10%">{{__('Title')}}</th>
                            <th width="10%">{{__('Short Description')}}</th>
                            <th width="10%">{{__('Categories')}}</th>
                            <th width="10%">{{__('Date')}}</th>
                            <th width="10%">{{__('Action')}}</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <div class="content-body p-0">
        <div class="card table-card px-5 py-3">
            <div class="container-fluid">
                <div id="team_member_stats">
                </div>
                @include('seller.analytic._team_contact_stats')
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('/assets/js/stats.js')}}"></script>
<script src="{{ asset('/assets/js/statistics_graphs.js')}}"></script>
<script>
    let routes = [];
    routes['message-sent-graph'] = "{{route('seller.member.message-sent-stats')}}";
    routes['new-customer-graph'] = "{{route('seller.member.new-customer-stats')}}";
    routes['new-distributor-graph'] = "{{route('seller.member.new-distributor-stats')}}";
    let chartRoute = "{{route('chart.analytics.data')}}";
    let getChartRoute = "{{route('get.chart.analytics.data')}}";
    let getMemberStatRoute = "{{route('get.member.statistics')}}";
</script>
<script src="{{ asset('/assets/js/analytic_contact_stats.js')}}"></script>
<script src="{{ asset('/assets/js/analytic_member_stats.js')}}"></script>
<script>
    //Documents list and category filter
        $(function (){
            var $input = $('.analytic_search');
            var startDate = '';
            var endDate = '';

            var table =$('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: false,
                searching: false, 
                bInfo: false,
                order: [[ 0, "desc" ]],
                language: {
                    "sProcessing": "{{__('Processing')}}",
                    "paginate": {
                    "previous": "«",
                    "next": "»",
                    }
                },
               
                ajax:{ 
                        url:'{!! route("analytics.data") !!}',
                        data: function (d) {
                            d.search = $input.val(),
                            d.startDate = startDate;
                            d.endDate = endDate;
                        }
                    },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'one_on_on_call', name: 'one_on_on_call' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'status', name: 'status' },
                    
                ]
			});

            //setup before functions
            var typingTimer;                //timer identifier
            var doneTypingInterval = 2000;  //time in ms, 5 second for example

            //on keyup, start the countdown
            $input.on('keyup', function (event) {
                clearTimeout(typingTimer);
                if (event.keyCode === 13) {
                    doneTyping ();
                }else{
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }             
            });

            //on keydown, clear the countdown 
            $input.on('keydown', function () {
            clearTimeout(typingTimer);
            });
            
            //user is "finished typing," do something
            function doneTyping () {
                table.draw();
            }

            // Date range filter
            $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // Date filter
            var $datechange = $('.datechange');
            
            $datechange.on('apply.daterangepicker', function(ev, picker) {
                 startDate = picker.startDate.format('YYYY-MM-DD');
                 endDate = picker.endDate.format('YYYY-MM-DD');
                 table.draw();
            });
            $datechange.on('cancel.daterangepicker', function(ev, picker) {
                startDate = '';
                endDate = '';
                table.draw();
            });

            window.dataGridTable = jQuery('#zoom-data-table').DataTable({
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
                ajax: '{!! route('get.zooming.meetings') !!}',
                columns: [
                    {data: 'user_name', name: 'user_name'},
                    {data: 'name', name: 'name'},
                    {data: 'content', name: 'content'},
                    {data: 'categories', name: 'categories'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

        });
</script>
@endsection