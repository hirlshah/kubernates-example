<li id="comment-{{ $comment->id }}">
    <div class="comment-body">
        <div class="mb-3">
            <div class="d-flex align-items-center gap-2">
                <img src="@if(!empty($comment->user) && !empty($comment->user->thumbnail_image) && Storage::disk('public')->exists($comment->user->thumbnail_image)) {{ App\Classes\Helper\CommonUtil::getUrl($comment->user->thumbnail_image) }} @else {{ asset('assets/images/profile-1.png') }} @endif" width="48px" height="48px" class="rounded-circle">
                <div>
                    <h3 class="fw-500 fs-18">@if(!empty($comment->user) && !empty($comment->user->name)) {{ $comment->user->name }} @endif</h3>
                    <p class="fw-400 fs-14 grey-666666 mb-0">{{ Carbon\Carbon::parse($comment->created_at)->format('F j, Y \a\t h:ia') }}</p>
                </div>
            </div>
        </div>
        @if(!empty($comment->message))
            {{ $comment->message }}
        @endif
    </div>
    <div class="comment-attachment-list">
        @if(!empty($comment->attachments))
            @foreach($comment->attachments as $attachment)
                <a href="{{ App\Classes\Helper\CommonUtil::getUrl($attachment->name) }}" target="_blank">
                    @if($attachment->type == \App\Enums\AttachmentTypes::IMAGE)
                        <img src="{{ App\Classes\Helper\CommonUtil::getUrl($attachment->name) }}" height="50px" width="50px">
                    @elseif($attachment->type == \App\Enums\AttachmentTypes::VIDEO)
                        <video src="{{ App\Classes\Helper\CommonUtil::getUrl($attachment->name) }}" class="h-96 w-full object" height="50px" width="50px"></video>
                    @elseif($attachment->type == \App\Enums\AttachmentTypes::PDF)
                        <img src="{{ asset('images/mime/pdf.svg') }}" height="50px" width="50px">
                    @endif
                </a>
            @endforeach
        @endif
    </div>
    @if($comment->user_id != Auth::User()->id)
        <div class="comment-reply">
            <a href="#" class="reply-link">{{ __('Reply')}}</a>
            <div class="reply-comment-section mb-4" style="display:none">
                <input type="hidden" value="{{ $comment->id }}" name="parent_id" id="comment_parent_id">
                <h6 class="fs-14 fw-500 mb-4">{{ __('Comments') }}</h6>
                <div class="form-group d-flex align-items-center">
                    <div class="d-flex align-items-center gap-2 w-100">
                        <img class="rounded-circle" src="@if(isset(Auth::user()->thumbnail_image) && Storage::disk('public')->exists(Auth::user()->thumbnail_image)){{ App\Classes\Helper\CommonUtil::getUrl(Auth::user()->thumbnail_image) }}@else{{ asset('assets/images/profile-1.png') }} @endif" width="48px" height="48px" style="object-fit: cover;object-position: center;">
                        <textarea id="comment-body" class="form-control border-0 pe-3 pt-2 reply-comment-body" name="body" rows="1" placeholder="{{ __('Write a comment...')}}"></textarea>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <label for="reply-comment-attachment">
                            <i class="feather-paperclip text-primary fs-20 cursor-pointer"></i>
                        </label>
                        <input class="d-none reply-comment-attachment" type="file" id="reply-comment-attachment" name="attachment[]" multiple>
                        <div class="form-group">
                            <button class="btn btn-blue comment-reply-btn">{{ __('Send')}}<span class="spinner"></span></button>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    @endif
    <ul class="children">
        @if(!empty($comment->replies))
            @foreach($comment->replies as $child)
                @include('seller.trello_board.component.trello_task_comment_replies', ['comment' => $child])
            @endforeach
        @endif
    </ul>
</li>

