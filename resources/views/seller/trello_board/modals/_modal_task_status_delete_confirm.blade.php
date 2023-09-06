<div class="modal fade" id="deleteTaskStatusConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteTaskStatusConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <h6 class="fw-bold mb-3">{{__('Delete Task Status ?')}}</h6>
                <p class="fs-14">{{__('Are you sure you want to delete this task Stats from your list?')}}</p>
                <div class="row btns">
                    <div class="col-xl-6">
                        <button class="btn btn-blue w-100 mb-2 mb-xl-0" id="task_status_delete_confirm" data-id=''>{{__('Yes, delete')}}</button>
                        <input type="hidden" class="task_status_delete_id" value="">
                    </div>
                    <div class="col-xl-6">
                        <button class="btn btn-white-shadow w-100" id="task_status_delete_cancel">{{__("Don’t delete it")}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
