<div id="modal_delete_warning_document" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content overflow-visible">
            <div class="modal-header bg-danger">
                <h6 class="modal-title text-white fs-24">{{__('Warning!!')}}</h6>
                <a class="close modal-close-btn-document cursor-pointer text-white fs-24" data-dismiss="modal">&times;</a>
            </div>

            <div class="modal-body p-3">
                <h6 class="font-weight-semibold">{{__('Are you sure you want to delete this document ?')}}</h6>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-black modal-close-btn-document" data-dismiss="modal">{{__('Close')}}</button>
                <button type="button" class="btn bg-danger modal-delete-confirm-document text-white">{{__('Delete')}}</button>
            </div>
        </div>
    </div>
</div>
