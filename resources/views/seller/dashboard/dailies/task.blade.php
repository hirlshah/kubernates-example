@foreach($tasks as $task)
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="tasks[{{ $task->id }}]" id={{ $task->id }} {{ (!empty($completedTasks) && isset($completedTasks[$task->id])) ? "checked" : "" }}>
        <label class="form-check-label" for={{ $task->id }}>
            {{ucFirst($task->title)}}
        </label>
    </div>
@endforeach