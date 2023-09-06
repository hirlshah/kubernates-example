@if(isset($labels) && !empty($labels))
    @foreach($labels as $label)
        <span style="background-color: {{ $label->color }};font-weight: bold;color:white;margin-bottom: 10px;width: fit-content;padding: 0 10px;border-radius:50px;font-size: 12px;" data-label-id="{{ $label->id }}">{{ $label->name }}</span>
    @endforeach
@endif