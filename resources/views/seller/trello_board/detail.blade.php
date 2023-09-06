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
                <a href="{{ route('seller-user-task-board-stats', getEncrypted($board_id))}}" class="btn btn-blue mw-max-content text-start">{{__('Board Stats')}}</a>
                <button type="button" class="btn btn-blue new_trello_board_button tour-btn">+ {{__('New board')}}</button>
            </div>
        </div>
        <div class="note mt-4">
            <span></span>
        </div>
        <div class="row gy-3 bg-F5F5F5 rounded-3">
            <div class="col-md-auto trello_board_dropdown_section">
                <select class="form-control border-0 shadow-none font-600 text-dark bg-transparent pe-5" name="trello_board" id="trello_board">
                    @foreach($trelloBoards as $key => $board)
                        @php
                            $boardId = getEncrypted($key);
                        @endphp
                        <option value="{{ route('seller-task-board', $boardId)}}" @if($key == $trelloBoard->id) selected @endif>{{ $board }}</option>
                    @endforeach
                    <option value="{{ route('seller.trello-boards')}}">{{ __('All boards')}}</option>
                </select>
                @if(!empty($trelloBoard) && $trelloBoard->user_id == Auth::id())
                    <a class="btn btn-edit p-lg-3 p-2" id="update_trollo_board_title"><i class="feather-edit fs-20"></i></a>
                @endif
            </div>
            <div class="col-md-4 update_trello_board_section" style="display:none">
                <input class="form-control shadow-none" type="text" name="title" value="{{ $trelloBoard->title}}" id="trello_board_title_input">
                <span class="text-danger print-error-msg-title" style="display:none"></span>
                <input class="form-control shadow-none" type="hidden" name="trello_board_id" value="{{ $trelloBoard->id }}" id="trello_board_id">
                <button class="btn btn-blue update_trello_board">{{ __('Save')}}</button>
            </div>
            <div class="col-md-auto ms-auto">
                <div class="d-sm-flex align-items-center gap-2 justify-content-end mb-sm-0 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-md-0 mb-3">
                        <div class="avatar-group header-avatar ms-auto">
                            @if(!empty($trelloBoard->users))
                                @foreach($trelloBoard->users as $user)
                                    <a href="#" class="avatar avatar-xs">
                                        <img src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }} @else {{ asset('assets/images/people.png') }} @endif" alt="">
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <p class="text-sm fw-normal mb-0">{{ $trelloBoardPeopleCount }} {{ __('Members') }}</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <div class="dropdown-menu-end">
                            <a class="btn btn-outline-geyser share-btn shadow-none py-2 px-2" href="#" id="add-people-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                <i class="feather-users pe-2"></i>  {{__('Add people')}}
                            </a>
                            <div class="dropdown-menu py-0 shadow-custom-1 border-1" aria-labelledby="add-people-dropdown" style="border-radius: 16px;">
                                <div class="d-flex px-4 pt-4 align-items-center">
                                    <div>
                                        <h6 class="fs-18 px-4">{{__('Add people')}}</h6>
                                    </div>
                                    <div class="ms-auto">
                                        <button class="btn btn-blue cursor-pointer assign_people_to_trello_board">+{{ __('Ok') }}</button>
                                    </div>
                                </div>
                                <div class="people-list mt-4">
                                    <div class="people-search pt-4 px-4">
                                        <input type="text" class="fs-16 mb-0 font-weight-normal form-control ps-0 mb-3 border-top-0 border-start-0 border-end-0 shadow-none rounded-0 search_people" placeholder="{{ __('Search people')}}" id="search_people">
                                    </div>
                                    <div class="people_list px-4" onscroll="getPeopleList()" id="main-page-people-list"></div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-menu-end">
                            <a class="btn btn-outline-geyser share-btn shadow-none py-2 px-2" href="#" id="add-share-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                <i class="feather-share pe-2"></i>  {{__('Share')}}
                            </a>
                            <div class="dropdown-menu py-0 shadow-custom-1 border-0 px-4 py-4" aria-labelledby="add-share-dropdown">
                                <div class="people-list mt-4">
                                    <h6 class="fw-400 fs-18 mb-2">{{__('Share board')}}</h6>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    @php
                                        $trelloBoardId = getEncrypted($trelloBoard->id);
                                    @endphp
                                    <div>
                                        <input value="{{ route('seller-task-board', $trelloBoardId) }}" type="text" style="display:none;" id="copy_trello_board_link_input">
                                        <i class="feather-link text-primary feather-copy blue fs-20 copy_trello_board_link cursor-pointer d-block" id="copy_{{ route('seller-task-board', $trelloBoardId) }}" data-href="{{ route('seller-task-board', $trelloBoardId) }}"></i>
                                        <div class="tooltiptext" style="display: none"><span></span></div>
                                    </div>
                                    <a class="email_btn cursor-pointer lh-1" data-url="{{ route('seller-task-board', $trelloBoardId) }}">
                                        <i class="feather-mail text-primary fs-20"></i>
                                    </a>
                                    <a href="" class="d-none" id="my_email_anchor"></a>
                                    <a data-href="https://web.whatsapp.com/send?text={{ config('app.rankup.company_title') }} - Trello board: " data-url="{{ route('seller-task-board', $trelloBoardId)}}" class="whatsapp_btn cursor-pointer lh-1">
                                        <i class="fa fa-whatsapp text-primary fs-20" aria-hidden="true"></i>
                                    </a>
                                    <!-- <a href="#"><img src="{{ asset('assets/images/facebook.svg') }}" alt=""></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider-ef mt-0"></div>
            <div class="p-0">
                <!-- <div class="dummy-scroll-main mb-2">
                    <div class="dummy-scrollbar"></div>
                </div> -->
                <div class="drag-drop-scroll-main pt-3">
                    <div class="drag-drop-scroll-wrapper" id="trello-task-main-div">
                        @include('seller.trello_board.status_data')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Delete confirm modal -->
    @include('seller.trello_board.modals._modal_task_delete_confirm')

    <!-- /Task detail modal -->
    @include('seller.trello_board.modals._modal_task_detail')

    <!-- /Task Status detail modal -->
    @include('seller.trello_board.modals._modal_task_status_detail')

    <!-- /Delete Task status confirm modal -->
    @include('seller.trello_board.modals._modal_task_status_delete_confirm')

    @include('seller.trello_board.modals.add_category_to_trello_task')

    @include('seller.trello_board.modals.create_trello_board_category')

    @include('seller.trello_board.modals._modal_add_people_to_trello_task')

    @include('seller.trello_board.modals._modal_add_trello_board')
@endsection
@section('scripts')
    <script>
        let screenWidth = "";
        let copiedText = "{{ __('Copy to clipboard') }}";
        let statusRoute = "{{route('seller.task.update.event')}}";
        let createRoute = "{{route('seller.trello-task-store', '')}}";
        let trelloTaskUpdateRoute = "{{ route('seller.trello-task-update', '') }}";
        let deleteRoute = "{{route('seller.task.destroy', '')}}";
        let createStatusRoute = "{{route('seller.add-trello-status')}}";
        let editStatusRoute = "{{route('seller.edit-trello-status', '')}}";
        let updateStatusRoute = "{{ route('seller.trello-status-update', '') }}";
        let deleteStatusRoute = "{{route('seller.destroy-trello-status', '')}}";
        let taskStatusRoute = "{{route('seller.task.status.update.event')}}";
        let TitleAlertText = "{{__('The title must not be greater than 191 characters')}}";
        let trelloBoardId = "{{ $trelloBoard->id }}";
        let createTrelloBoardCategory = "{{ route('seller.add-trello-board-category')}}";
        let addTrelloTaskCommentRoute = "{{ route('seller.add-trello-task-comment')}}";
        let assignPeopleToTrelloBoard = "{{ route('seller.add-people-to-trello-board') }}";
        let getTrelloTaskDetails = "{{ route('seller.get-trello-task-details')}}";
        let getTrelloTaskComments = "{{ route('seller.get-trello-task-comments')}}";
        let deleteTrelloTaskAttachment = "{{ route('seller.trello-board.task.delete-attachment')}}";
        let getTrelloBoardCategories = "{{ route('seller.trello-board-categories')}}";
        let getPeopleListRoute = "{{ route('seller.people-list')}}";
        let updateTrelloBoard = "{{ route('seller.update-trello-board')}}";
        let welcomeText = "{{__('Welcome to Rank up') }}";
        let subjectText = "{{__('Share trello board')}}";
        let footerText = "{{__('Thank you for your collaboration!') }}";
        let editButtonText = "{{ __('Edit')}}";
        let deleteButtonText = "{{ __('Delete')}}";
        let statusPlaceholderText = "{{ __('Column title')}}";
        let taskPlaceholderText = "{{ __('Task title')}}";
        var close = "{{ __('Close') }}";
        var Previous = "{{ __('Previous') }}";
        var Next = "{{ __('Next') }}";
        var step_1_title = "{{ __('Task Board') }}";
        var step_2_title = "{{ __('New Board') }}";
        var step_3_title = "{{ __('Card') }}";
        var step_4_title = "{{ __('Add new card') }}";
        var step_5_title = "{{ __('New column') }}";
        var step_1_description = "{{ __('By clicking here, you will be redirected to your dashboard.') }}";
        var step_2_description = "{{ __('By clicking in this button you will create a new board.') }}";
        var step_3_description = "{{ __('By clicking here, you will be able to see and edit this card.') }}";
        var step_4_description = "{{ __('By clicking here, you will create a new card.') }}";
        var step_5_description = "{{ __('If you click this button, you will add a column to your board.') }}";
        var userTourLogs = "{{ $userTrelloModal }}";
        var getStatusColumnData = "{{ route('seller.trello-board.status-column-data', '')}}";
        var currentPeoplePage = 1;
        var currentModalPeoplePage = 1;
        let shouldRemoveAddTaskMore = false;
        let isFirstLogin = "{{ Auth::User()->is_first_login }}";
        
        /**
         * Get people listing for modal
         */
        function get_people_list_for_modal(pageNumber) {
            var task_id = $('#task_id').val();
            var search_people_text = $('#search_people_from_modal').val();
            $.ajax({
                url: "{{ route('seller.people-list')}}",
                type: 'GET',
                data: { 'page': pageNumber,  'search_text' : search_people_text, 'from_modal' : 1, 'trello_task_id': task_id},
                success: function(response) {
                    $('.trello_task_people_list').append(response.html);
                },
                error: function(error) {
                    $('.trello_task_people_list').append('');
                }
            });
        }

        /**
         * Get people listing
         */
        function get_people_list(pageNumber) {
            var search_people_text = $('.search_people').val();
            $.ajax({
                type: "get",
                url: "{{ route('seller.people-list')}}",
                data: {'search_text' : search_people_text, 'page': pageNumber, 'trello_board_id': trelloBoardId},
                dataType: "json",
                success:function(data) {
                    if(data.success) {
                        $('.people_list').append(data.html);
                    } else {
                        $('.people_list').append('');
                    }
                },
                error: function (data) {
                    $('.people_list').append('');
                }
            });
        }

        /**
         * Call function on people scroll
         */
        function getPeopleList() {
            var scrollContainer = $('#main-page-people-list'); // Select 
            var scrollHeight = scrollContainer.prop('scrollHeight');
            var scrollTop = scrollContainer.scrollTop();
            var containerHeight = scrollContainer.height();
            if (scrollHeight - scrollTop === containerHeight) {
                currentPeoplePage++;
                get_people_list(currentPeoplePage);
            }
        }
    </script>
    <script src="{{ asset('assets/js/trello_board.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
