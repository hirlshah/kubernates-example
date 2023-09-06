<div class="drag-drop-scroll mb-4 task-board pb-5">
    @foreach($taskStatuses as $key => $status)
        <div class="drag-drop-task-card" data-status-id="{{$status->id}}">
            <div class="p-3 bg-white rounded-4">
                <h5 class="sortable-task-status-div edit-task-status mb-3" data-id="{{ $status->id }}">{{ $status->title }}</h5>
                <div class="drag-drop-task-card-list" data-status-id="{{$status->id}}" id="trello-status-{{ $status->id }}">
                <!-- task data section-->
                </div>
                <div class="col-12 task-card-add mt-0" data-status-id="{{$status->id}}">
                    <div class="d-flex mx-auto align-items-center gap-2">
                        <div class="task-card-add-img"><i class="feather-plus"></i></div>
                        <div class="m-auto fs-16">{{ __('Add Card') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="drag-drop-card" id="add-new-task-status">
        <div class="add-more-task-status-append"></div>
        <div class="task-card-add-status py-2 rounded-4">
            <div class="col-4" style="text-align: center">
                <div class="task-card-add-status-img">
                    <i class="feather-plus"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var trelloStatusIdArr = "{{ $taskStatusIds }}";
</script>
