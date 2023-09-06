<div class="modal fade" id="taskDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg pt-5" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close cursor-pointer z-index" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <input type="hidden" id="task_id" name="task_id" value="">
                <div id="trello_task_details"></div>
                <hr>
                <div class="card p-4">
                    <form id="comment-form">
                        <h6 class="fs-14 fw-500 mb-4">{{ __('Comments') }}</h6>
                        <div class="form-group d-flex align-items-center justify-content-between">
                             <div class="d-flex align-items-center gap-2 w-75">
                                <img class="rounded-circle" src="@if(isset(Auth::user()->thumbnail_image) && Storage::disk('public')->exists(Auth::user()->thumbnail_image)){{ App\Classes\Helper\CommonUtil::getUrl(Auth::user()->thumbnail_image) }}@else{{ asset('assets/images/profile-1.png') }} @endif" width="48px" height="48px" style="object-fit: cover;object-position: center;">
                                <textarea id="comment-body" class="form-control border-0 pe-3 pt-2" name="body" rows="1" placeholder="{{ __('Write a comment...')}}"></textarea>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <label for="comment-attachment">
                                    <i class="feather-paperclip text-primary fs-20 cursor-pointer"></i>
                                </label>
                                <input class="d-none" type="file" id="comment-attachment" name="attachment[]" multiple>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-blue add_comment_btn">{{ __('Send')}}<span class="spinner"></span></button>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </form>
                    <div id="trelloTaskComments">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>