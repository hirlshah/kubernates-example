@if(!empty($trelloTasks))
    @foreach($trelloTasks as $task)
        <div class="col-12 droppable-task border-grey-e8e8e8 mb-3 rounded-4 task-edit-card cursor-pointer" data-id="{{$task->id}}" data-status-id="{{$statusId}}">
            <div class="col-12 task-card draggable-task-card flex-column shadow-none py-0">
                <div class="d-flex align-items-center mb-4">
                    <h5 class="card-task-name fs-18">{{ $task->title }}</h5>
                    <div class="dropdown dropdown-menu-end ms-auto">
                        <a class="edit-delete-dropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="feather-more-horizontal"></i>
                        </a>
                        <ul class="dropdown-menu py-0 shadow-custom-1 border-0" aria-labelledby="edit-delete-dropdown">
                            <li>
                                <a class="dropdown-item edit-btn-outline-blue task-card-btn py-2 cursor-pointer" data-id="{{$task->id}}">
                                    <i class="feather-edit pe-3"></i>
                                    {{ __('Edit') }}
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-item edit-btn-outline-blue task-delete-outer py-2 cursor-pointer" data-id="{{$task->id}}">
                                    <i class="feather-trash pe-3"></i>
                                    {{ __('Delete') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 date-sec mb-2">
                    <i class="feather-calendar blue"></i>
                    <p class="grey-666666 mb-0 text-sm fw-normal">
                        @if(!empty($task->deadline_date))
                            {{ convertDateFormatWithTimezone($task->deadline_date, 'Y-m-d H:i:s','l, d M') }}
                        @else
                            {{ convertDateFormatWithTimezone($task->created_at, 'Y-m-d H:i:s','l, d M') }}
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2 date-sec mb-4">
                    @if(!empty($task->categories))
                        <div>
                            <i class="feather-tag text-primary"></i>
                        </div>
                        <div>
                            @foreach($task->categories as $category)
                                <span class="badge rounded-pill bg-red-ed4c5c text-white font-semibold fs-12 px-2 mb-2" style="background-color: {{ $category->color }}">
                                    {{ $category->title }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="board-img">
                    @if(!empty($task->firstImageAttachment) && Storage::disk('public')->exists($task->firstImageAttachment))
                        <img style="width:100%; height:150px; object-fit: cover;" src="{{ App\Classes\Helper\CommonUtil::getUrl($task->firstImageAttachment->attachment) }}" alt="">
                    @endif
                </div>
                <div class="d-flex align-items-center mt-4">
                    <div>
                        <div class="avatar-group">
                            @if(!empty($task->users))
                                @foreach($task->users as $user)
                                    <a href="#" class="avatar avatar-xs">
                                        <img src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}@else {{ asset('assets/images/people.png') }} @endif" alt="">
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <i class="feather-message-circle"></i> <p class="text-sm fw-normal mb-0">{{ $task->comments->count() }}</p>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <img src="{{ asset('assets/images/link-icon.svg') }}" alt=""> <p class="text-sm fw-normal mb-0">
                                @php
                                    $commentAttachmentCount = 0;
                                @endphp
                                @foreach($task->comments as $comment)
                                    @php
                                        $commentAttachmentCount += $comment->attachments->count();
                                    @endphp
                                @endforeach

                                {{ $commentAttachmentCount }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif