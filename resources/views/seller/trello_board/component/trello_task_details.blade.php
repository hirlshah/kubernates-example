<div class="row mb-4">
    <div class="w-75 d-flex align-items-center mb-md-0 mb-3 gap-3">
        <h3 class="mb-0 font-weight-normal" id="trello_task_title_text">@if(!empty($task->title)) {{ $task->title }} @endif</h3>
        <input type="text" class="trello_board_task_detail_update_input fs-16 mb-0 font-weight-normal form-control w-auto" id="trello_task_title" value="{{ $task->title }}" style="display:none;" name="title">
        <a class="btn btn-edit p-lg-3 p-2" id="task_title_edit"><i class="feather-edit fs-20 text-blue"></i></a>
    </div>
</div>
<div>
    <div class="row mb-3 align-items-center gy-2">
        <div class="col-lg-2 col-sm-4 pe-0">
            <i class="feather-calendar blue fw-600"></i>
            <label class="form-label fw-500 mb-0">{{__('Deadline')}}</label>
        </div>
        <div class="col-lg-10 col-sm-8">
            <div class="d-flex align-items-center">
                <input type="text" class="form-control border-0 p-0" id="task_deadline_date_input" name="deadline_date" value="@if(!empty($task->deadline_date)) {{ convertDateFormatWithTimezone($task->deadline_date, 'Y-m-d H:i:s','d/m/Y') }} @else {{ convertDateFormatWithTimezone($task->created_at, 'Y-m-d H:i:s','d/m/Y') }} @endif" autocomplete="off" style="width:110px;">
                <i class="feather-edit fs-12 text-blue cursor-pointer task_deadline_date_btn" id="task_deadline_date_btn"></i>
            </div>
        </div>
    </div>
</div>
<div class="row align-items-center mb-2 gy-2">
    <div class="col-lg-2 col-sm-4 pe-0">
        <i class="feather-tag text-primary"></i>
        <label class="form-label fw-500 mb-0">{{__('Category')}}</label>
    </div>
    <div class="col-lg-10 col-sm-8">
        <div class="d-flex align-items-center gap-2 btn-toolbar">
            @if(!empty($task->categories))
                @foreach($task->categories as $category)
                    <span class="badge rounded-pill bg-primary text-white fs-12 px-2 font-500" style="background-color: {{ $category->color }}">
                        {{ $category->title }}
                    </span>
                @endforeach
            @endif
            <a class="btn btn-outline-cadet-grey br-20px py-1 px-2 fw-400 fs-12" href="javascript:;" id="add_category">+ {{__('Add')}}</a>
        </div>
    </div>
</div>
<div class="row align-items-center gy-2">
    <div class="col-lg-2 col-sm-4">
        <i class="feather-users text-blue fw-700"></i>
        <label class="form-label fw-500 mb-0">{{__('Assigned')}}</label>
    </div>
    <div class="col-lg-10 col-sm-8">
        <div class="d-lg-flex align-items-center">
            <div class="d-flex align-items-center">
                <div class="avatar-group assigned-avatar d-flex">
                    @if(!empty($task->users))
                        @foreach($task->users as $user)
                            <a href="#" class="avatar avatar-xs">
                                <img src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }} @else {{ asset('assets/images/people.png') }} @endif" alt="">
                            </a>
                        @endforeach
                    @endif
                </div>
                <a class="btn btn-outline-cadet-grey br-20px py-1 px-2 fw-400 fs-12 assign_people_to_trello_task_btn" href="javascript:;">+ {{__('Assign people')}}</a>
            </div>
        </div>
    </div>
</div>
<hr>
<div>
    <label class="form-label fw-500">{{__('Description')}}</label>
    <textarea class="form-control trello_board_task_detail_update_input border-0" id="task_description" name="description" rows="6" cols="50">@if(!empty($task->description)) {{ $task->description }} @endif</textarea>
</div>
<hr>

<div>
    <div>
        <label class="form-label fw-500">{{__('Attachments')}}</label>
    </div>
    <div class="pb-4">
        <div class="board-img d-flex align-items-center gap-1 btn-toolbar">
            @if(!empty($task->attachments))
                @foreach($task->attachments as $attachment)
                    @if(isset($attachment->type) && isset($attachment->attachment) && Storage::disk('public')->exists($attachment->attachment))
                        @if($attachment->type == \App\Enums\AttachmentTypes::IMAGE)
                            <div style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($attachment->attachment) }}); width:80px; height:80px; background-position: center; background-size: cover; background-blend-mode: overlay; background-color: rgb(0 0 0 / 40%);"></div>
                            <a href="javascript;" class="delete_task_attachment" data-id="{{ $attachment->id }}"><i class="feather-trash fs-20"></i></a>
                        @elseif($attachment->type == \App\Enums\AttachmentTypes::VIDEO)
                            <video src="{{ App\Classes\Helper\CommonUtil::getUrl($attachment->attachment) }}" class="h-96 w-full object" height="80px" width="80px"></video>
                            <a href="javascript;" class="delete_task_attachment" data-id="{{ $attachment->id }}"><i class="feather-trash fs-20"></i></a>
                        @elseif($attachment->type == \App\Enums\AttachmentTypes::PDF)
                            <img src="{{ asset('images/mime/pdf.svg') }}" height="80px" width="80px">
                            <a href="javascript;" class="delete_task_attachment" data-id="{{ $attachment->id }}"><i class="feather-trash fs-20"></i></a>
                        @endif
                    @endif
                @endforeach
            @endif
            <div>
                <input id='task_attachments' type='file' name="task_attachments[]" class="trello_board_task_detail_update" multiple hidden/>
                <div class="task-card-add p-0 m-0 align-items-center justify-content-center" style="width:80px; height:80px;">
                    <a id="task_attachment_file_trigger">
                        <div class="task-card-add-img"><i class="feather-plus"></i></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
