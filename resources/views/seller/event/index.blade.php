@extends('layouts.seller.index')
@section('content')
<div id="content">
    @include('seller.common._upgrade_warning')
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-tv me-3"></i>
            <nav aria-label="breadcrumb" style="margin-top: 3px;">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Events')}}</li>
                </ol>
            </nav>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip mt-1">
                    <span class="custom-badge">   
                     <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753141506?h=29b35b4e7c" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                </div>
            @endif
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <div class="copy-event-one-on-one-link" id="copy-event-one-on-one-link">
                <button type="button" class="btn btn-blue me-2" id="one-on-one-call">{{__('One on one call')}}</button>
                <div class="tooltiptext"><span>{{__('The individual call link has been successfully copied to the clipboard.')}}</span></div>
            </div>
            <button id="new_event_button" type="button" class="btn btn-blue">+ {{__('New Event')}}</button>
            @include('seller.common._language')
        </div>
    </div>
        @if(Session::has('success'))
            <div class="alert alert-success mt-4" id="successMessage">
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger mt-4">
                {{Session::get('error')}}
            </div>
        @endif
    <div class="content-body">
        <div class="container-fluid mb-4">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h6 class="fs-18 mb-2 mb-sm-0">{{__('Upcoming Events')}}</h6>
                <div class="btn-group" role="group" id="event-sort-ajax">
                    {{Form::select('personal_stats_period', ['current'=>__('Events'), 'past'=>__('Past events')],'', ['class'=>'form-control pe-4', 'id'=>'event_type', 'autocomplete'=>'off'])}}
                    <button id="event-sorting" type="button" class="btn dropdown-toggle no-legitripple py-0 px-0 mx-sm-3 mx-2 shadow-none" aria-haspopup="true"  data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="feather-sliders fs-22"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="event-sorting">
                        <a class="dropdown-item {{$sorting == 'meeting_date_asc'? 'active' : ''}}" data-sort="meeting_date_asc" href="#">{{__('Date ascending')}}</a>
                        <a class="dropdown-item {{$sorting == 'meeting_date_desc'? 'active' : ''}}" data-sort="meeting_date_desc" href="#">{{__('Date descending')}}</a>
                    </div>
                    <div>
                        <input type="text" name="event_search" class="form-control event_search" placeholder="{{__('Search')}}" />
                    </div>
                </div>
            </div>
        </div>
        <div id="event-ajax-list">
            @include('seller.event._event_pagination')
        </div>
    </div>
</div>
@include('seller.modal._modal_create_event')
@include('seller.modal._modal_create_survey')
@include('seller.modal._modal_add_doc_video')
@include('seller.modal.user_list_modal')
@endsection
@section('scripts')
<script>
let oneOnOneCall = '{{route("oneOnOneCall")}}';
let updateEventRoute = "{{route('seller.event.update', '')}}"
let showEventRoute = "{{route('seller.event.show', '')}}"
let storeEventRoute = "{{route('seller.event.store')}}"
let imageUrl = "{{ asset('') }}"
let copiedText = "{{ __('Copy to clipboard') }}";
let eventEditText = "{{ __('Edit an event') }}";
let eventEditButtonText = "{{ __('Save changes') }}";
let surveyText = "{{ __('Select the survey') }}";
let createEventText = "{{ __('Create an Event')}}";
var companyDefaultImage = "{{ asset(config('app.rankup.company_default_image_file')) }}";
var currentUsersPage = 1;
let defaultSurveyOption = "{{ __('Personalized survey')}}";

/**
 * Get presentator list
 */
function users_list(pageNumber) {
    var search_people_text = $('#users_search').val();
    $('.spinner').addClass('text-primary spinner-border spinner-border-lg w-4 h-4 mx-2');
    $.ajax({
        url: "{{ route('get-user-list')}}",
        type: 'GET',
        data: { 'page': pageNumber,  'search_text' : search_people_text},
        success: function(response) {
            $('.users_list').append(response.html);
            $('.spinner').removeClass('text-primary spinner-border spinner-border-sm w-4 h-4 mx-2');
        },
        error: function(error) {
            $('.users_list').append('');
            $('.spinner').removeClass('text-primary spinner-border spinner-border-sm w-4 h-4 mx-2');
        }
    });
}
</script>
<script src="{{ asset('assets/js/event.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script>
    $(function (){
        let currentLink = window.location.href;
        $('.select2-category-filter').select2({
            responsive: true,
        });
        function loadData(link, params){
            if(!params) params = {};
            $.get(link, params, function (response){
                $('#event-ajax-list').html(response);
                $('.select2-category-filter').select2({
                    responsive: true,
                    heights: 10
                });
            });
        }
        $(document).on( 'click', '.a-pagination-links .page-link',function (e){
            e.preventDefault();
            let link = $(this).attr('href');
            let type = $('#event_type').val();
            loadData(link,{'type': type});
        });

        $(document).on('click', '#event-sort-ajax .dropdown-item:not(.active)', function (){
            loadData(currentLink, {'sort': $(this).data('sort')});
        });

        $(document).on('change', '#event-category-filter', function (){
            let filter = $(this).val();
            loadData(currentLink, {'category_filter': filter});
        });

        $(document).on('change', '#event_type', function (){
            let type = $(this).val();
            loadData(currentLink, {'type': type});
        });

        //setup before functions
        var typingTimer;                //timer identifier
        var doneTypingInterval = 2000;  //time in ms, 5 second for example
        var $input = $('.event_search');

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
            loadData(currentLink, {'search': $input.val()});
        }

        /*$(document).on('click', '#event-category-filter > label:not(.active)', function (){
            let filter = $(this).find('.category-filter').val();
            loadData(currentLink, {'category_filter': filter});
        });*/

        $('.one-on-one-call').tooltip({trigger: 'manual'});
        $(".one-on-one-call").on({
            "click": function() {
                $('[data-toggle="tooltip"], .tooltip').tooltip("show");
            },
            "mouseout": function() {
                $('[data-toggle="tooltip"], .tooltip').tooltip("hide");
            }
        });
    });
</script>
@endsection
