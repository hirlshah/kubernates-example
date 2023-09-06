@if(!empty($modelsData))
    <select class="custom-select form-control ps-0 ai_writing_model w-auto pe-5 mb-3">
        <option value="">{{__('Select')}}</option>
        @foreach($modelsData as $data)
            <option value="{{ $data['value'] }}">{{ $data['model']}}</option>
        @endforeach
    </select>
@endif