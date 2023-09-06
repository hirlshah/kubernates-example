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
                @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                    <div class="custom-badge-tooltip">
                        <span class="custom-badge">
                            <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753165753?h=c4e569d3ad" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                        </span>
                        <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                    </div>
                @endif
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-blue new_trello_board_button tour-btn">+ {{__('New board')}}</button>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-5">
            <div>
                <h2>{{__('All boards')}}</h2>
            </div>
            <div>
                <div class="form-search position-relative filter">
                    <input type="text" name="trello_board_search_text" class="border-0 form-control rounded-0 shadow-none px-0" placeholder="{{ __('Search') }}" id="trello_board_search_text">
                    <i class="feather-search position-absolute top-50 translate-middle end-0" style="z-index: 10"></i>
                </div>
            </div>
        </div>
        <div class="divider-ef mt-3"></div>
        <div class="d-sm-flex align-items-center justify-content-between mt-sm-5 mt-3">
            <div>
                <p class="mb-sm-0 mb-4" id="trello_board_count_text">{{__('You currently have')}} {{ $trelloBoardCount}}  {{ __('boards')}}</p>
            </div>
            <div>
                <select class="form-control border-0 shadow-none text-dark pe-5 filter" id="sorting_type">
                    <option value="creation-date-asc">{{ __('Creation date ASC') }}</option>
                    <option value="most_recents" selected>{{ __('Most recents') }}</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <div class="row gy-3" id="trello_board_data">
                @include('seller.trello_board.trello_data')
            </div>
        </div>
    </div>

    @include('seller.trello_board.modals._modal_add_trello_board')
    @include('seller.trello_board.modals._modal_delete_trello_board')
@endsection
@section('scripts')
    <script>
        let indexRoute = "{{ route('seller.trello-boards')}}";
        let deleteTrelloBoardRoute = "{{route('seller.trello-board.destroy', '')}}";
    </script>
    <script src="{{ asset('assets/js/trello_board.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
