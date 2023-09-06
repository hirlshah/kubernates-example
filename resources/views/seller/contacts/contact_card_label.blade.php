@php
    $labels = $contact->labels()->get();
@endphp
@if(isset($labels) && !empty($labels))
    @foreach($labels as $label)
        <span class="label-title" style="background-color: {{ $label->color }};" data-label-id="{{ $label->id }}">
            {{ $label->name }}
        </span>
    @endforeach
@endif