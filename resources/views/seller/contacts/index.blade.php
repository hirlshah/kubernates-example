@extends('layouts.seller.index')
@section('content')
<div class="seller-contact-content" id="content">
    @include('seller.common._upgrade_warning')
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-user-plus"></i>
            <h5>{{__("Contacts")}}</h5>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip">
                    <span class="custom-badge">
                    <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753179621?h=1e6462bc4f" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                </div>
            @endif
        </div>

        <div class="content-header-right d-flex align-items-center ms-auto sort-filter" style="gap: 1rem;">
            <select class="mw-max-content ms-auto form-control align-items-center pe-4" name="sorting_data" id="sorting-contacts">
                <option value="">--{{ __('Select Sorting') }}--</option>
                <option value="asc">{{ __('A-Z') }}</option>
                <option value="desc">{{ __('Z-A') }}</option>
                <option value="creation-date-asc">{{ __('Creation date ASC') }}</option>
                <option value="creation-date-desc">{{ __('Creation date DESC') }}</option>
                <option value="followup-date-asc">{{ __('Follow Up Date ASC') }}</option>
                <option value="followup-date-desc">{{ __('Follow Up Date DESC') }}</option>
            </select>
            <input type="text" name="search" placeholder="{{__('Search')}}" id="searchContact" class="form-control">
        </div>
    </div>
    <div class="note mt-4 d-flex flex-wrap justify-content-between">
        <span>{{__('Note: To edit or add a message, double tap on the contact card.')}}</span>
        <div class="hstack gap-2">
            @if(auth()->user()->email == App\Models\User::SELLER_EMAIL)
                <button class="btn btn-outline-black mw-max-content contact-import-btn"><i class="feather-download fs-20 py-2 me-1"></i>{{__('Import')}}</button>
            @endif
            <button class="btn btn-blue mw-max-content collapse-contacts-btn collapse-minus mb-0" style="min-height: 58px;"><i class="feather-minus fs-26"></i></button>
        </div>
    </div>

    <div class="content-body p-2">
        <input type="hidden" name="board_id" id="board_id_hidden" value="{{$board->id}}">
        <div class="dummy-scroll-main mb-2 d-none d-lg-block">
            <div class="dummy-scrollbar"></div>
        </div>
        <div class="drag-drop-scroll-main">
            <div class="drag-drop-scroll-wrapper">
                <div id="ContactDataDiv">
                    @include('seller.contacts.contact_data')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /Add new contact modal -->
@include('seller.contacts._modal_add_contact')

<!-- /Contact detail modal -->
@include('seller.contacts._modal_contact_detail')

<!-- /Delete confirm modal -->
@include('seller.contacts._modal_contact_delete_confirm')

<!-- /Contact send message modal -->
@include('seller.contacts._modal_contact_send_message')

<!-- /Contact follow up modal -->
@include('seller.contacts._modal_contact_follow_up')

<!-- /Contact Label List modal -->
@include('seller.contacts._modal_task_label')

<!-- /Contact Label Add Update modal -->
@include('seller.contacts._modal_add_update_label')

<!-- /Contact Not Interested Modal -->
@include('seller.contacts._modal_not_interested')

<!-- /Contact Import Modal -->
@include('seller.contacts._modal_contact_import')

@include('seller.common._contact_upload_list')

@include('seller.contacts._modal_ai_writing')

@include('seller.modal._modal_delete_label')

@endsection
@section('scripts')
<script>
    let statusRoute = "{{route('seller.contacts.update-status')}}"
    let showRoute = "{{route('seller.contacts.show', '')}}"
    let updateRoute = "{{route('seller.contacts.update', '')}}"
    let deleteRoute = "{{route('seller.contacts.destroy', '')}}"
    let createRoute = "{{route('seller.contacts.store', '')}}"
    let userIcon = "{{asset('assets/images/user-icon2.png')}}";
    let memberUserId = null;
    let confirmText = "{{__('Are you sure you want to move this contact?')}}";
    let distributorID = "{{\App\Enums\ContactBoardStatus::NEW_DISTRIBUTOR}}";
    let clientID = "{{\App\Enums\ContactBoardStatus::NEW_CLIENT}}";
    let contactBoardFilterRoute = "{{route('seller.contacts.board.filter')}}";
    let getLabelRoute = "{{route('seller.label.list', ['', ''])}}";
    let contactLabelsUpdateRoute = "{{route('seller.contacts.labels.update', '')}}";
    let labelUpdateRoute = "{{ route('seller.label.update', '') }}";
    let getLabelDataRoute = "{{ route('seller.label.show', '') }}";
    let labelStoreRoute = "{{ route('seller.label.store') }}";
    let statusRangeKeysArr = "{{ $statusRangeKeys }}";
    let getBoardStatusData = "{{route('seller.contacts.board.status-data', '')}}";
    let getContactBoardFirstColumnData = "{{route('seller.contacts.board.get-contact-board-data')}}";
    var fileExtensionValidation = "{{__('File must be a file of type:xlsx,xls,ods')}}";
    var fullNameText="{{__('Full name')}}";
    var generateAiMessageRoute = "{{ route('generate-ai-message')}}";
    var copyLinkText = "{{__('Copy to clipboard')}}";
    var aiMessageText = "{{ __('Message copied to clipboard')}}";
    var getAiModelsRoute = "{{ route('openai.models')}}";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js" integrity="sha512-0bEtK0USNd96MnO4XhH8jhv3nyRF0eK87pJke6pkYf3cM0uDIhNJy9ltuzqgypoIFXw3JSuiy04tVk4AjpZdZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('/assets/js/contacts.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
