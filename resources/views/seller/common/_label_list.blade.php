<div class="d-flex justify-content-between align-items-center label-item">
    <label class="form-check-label d-flex justify-content-between align-items-center">
        <input type="radio" value="none" name="labels[]">
        <div class="label-title d-flex justify-content-between align-items-center ms-3">
            <span>{{ __('None') }}</span>
        </div>
    </label>
</div>
@foreach($labels as $key => $label)
    <div class="d-flex justify-content-between align-items-center label-item py-2" data-label-id="{{ $label->id }}">
        <label class="form-check-label d-flex justify-content-between align-items-center">
            @if(isset($modalLabels[$label->id]))
                <input type="checkbox" value="{{ $label->id }}" name="labels[]" checked>
            @else
                <input type="checkbox" value="{{ $label->id }}" name="labels[]">
            @endif
            <div class="label-title d-flex justify-content-between align-items-center ms-3">
                <div class="color-label-ball" style="background-color: {{ $label->color }};"></div>
                <span>{{ $label->name }}</span>
            </div>
        </label>
        <i class="feather-edit edit-label ms-auto me-3" data-id="{{ $label->id }}"></i>
        <i class="feather-trash modal-popup-delete-label cursor-pointer" data-url="{{ route('seller.label.destroy', ['id' => $label->id]) }}"></i>
    </div>
@endforeach
