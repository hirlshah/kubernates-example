@if(!empty($trelloBoards))
    @foreach($trelloBoards as $trelloBoard)
        @php
            $boardId = getEncrypted($trelloBoard->id);
        @endphp
        <div class="col-xxl-3 col-xl-4 col-sm-6 trello_board_{{ $trelloBoard->id }}">
            <div class="card h-100">
                <div class="card-body">
                    <a href="{{ route('seller-task-board', $boardId) }}">
                        <div class="mb-4 hstack">
                            <h5 class="card-task-name fs-18">{{ $trelloBoard->title }}</h5>
                            @if(auth()->user()->id == $trelloBoard->user_id)
                                <a href="javascript:;" data-id="{{ $trelloBoard->id }}" class="modal_popup_trello_board_btn text-danger ms-auto lh-1"><i class="feather-trash-2 fs-20"></i></a>
                            @endif
                        </div>
                        <div class="ms-auto hstack gap-2 mb-3">
                            <div class="hstack gap-1">
                                <i class="feather-sidebar text-primary"></i> <span class="text-sm fw-normal mb-0">{{ $trelloBoard->trelloStatuses->count() }} {{ __('Columns')}}</span>
                            </div>
                            <div class="hstack gap-1">
                                <i class="feather-copy text-primary"></i> <span class="text-sm fw-normal mb-0">
                                    {{ $trelloBoard->trelloTasks->count() }} {{ __('Cards')}}
                                </span>
                            </div>
                        </div>
                        <div class="hstack">
                            <div class="avatar-group d-flex">
                                @if(!empty($trelloBoard->users))
                                    @foreach($trelloBoard->users->take(3) as $user)
                                        <div class="avatar avatar-xxs">
                                            <img src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }} @else {{ asset('assets/images/people.png') }} @endif" alt="">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <p class="text-sm fw-normal mb-0 ms-2">{{ $trelloBoard->users->count() }} {{ __('Members') }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
@endif