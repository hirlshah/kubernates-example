<div id="delete_label_modal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content overflow-visible">
            <div class="modal-header bg-danger">
                <h6 class="modal-title text-white fs-24">{{__('Warning!!')}}</h6>
                <a class="close modal-close-btn-video cursor-pointer text-white fs-24">&times;</a>
            </div>

            <div class="modal-body p-3">
                <h6 class="font-weight-semibold">{{__('Are you sure you want to delete this label ?')}}</h6>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-black modal-close-label-btn" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn bg-danger modal-delete-confirm-label text-white">{{__('Delete')}}</button>
            </div>
        </div>
    </div>
</div>
