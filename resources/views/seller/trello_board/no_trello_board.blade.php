@extends('layouts.seller.index')
@section('content')
    <div class="seller-contact-content" id="content">
        <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
            <span class="minus"></span>
            <span class="minus"></span>
            <span class="minus"></span>
        </button>
        <div class="content-header d-flex align-items-center">
            <div class="content-header-left d-flex align-items-center">
                <i class="feather-sidebar"></i>
                <h5>{{__("Task Board")}}</h5>
                <div class="custom-badge-tooltip">
                    <span class="custom-badge">   
                        <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753165753?h=c4e569d3ad" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                </div>
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-blue new_trello_board_button">+ {{__('New board')}}</button>
            </div>
        </div>
    </div>
    
    @include('seller.trello_board.modals._modal_add_trello_board')
@endsection
@section('scripts')
    <script>
      let getPeopleList = "{{ route('seller.people-list')}}";
    </script>
    <script src="{{ asset('/assets/js/trello_board.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
