<div class="modal fade" id="taskStatusDetail" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content overflow-visible">
			<div class="modal-body">
				<div class="row mb-4">
					<div class="profile-image d-flex align-items-center mt-3 mb-md-0 mb-3">
						<span class="w-75 fs-16 mb-0 font-weight-normal d-block" ><b>{{__('Edit Task Status')}}</b></span>
						<div class="w-25 d-flex align-items-center justify-content-end task-status-delete" data-id="">
							<i class="feather-trash cursor-pointer fs-22" ></i>
						</div>
					</div>
				</div>
				<form id="edit-task-status-details" class="row g-3" action="{{route('seller.trello-status-update', '')}}" method="POST"
				enctype="multipart/form-data">
					<div class="col-md-12">
						<label class="form-label">{{__('Title')}}</label>
						<input type="text" class="form-control" id="task_status_title" name="title" value="">
						<span class="text-danger print-error-msg-title" style="display:none"></span>
					</div>
					<div class="d-flex justify-content-between">
						<div class="col-md-3" style="margin: inherit;">
							<button type="submit" class="btn btn-blue">{{__('Submit')}}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
