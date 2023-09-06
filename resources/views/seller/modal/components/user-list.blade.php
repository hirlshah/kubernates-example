@if(!empty($users))
    @foreach($users as $key => $user)
        <div class="mb-2 user_listing_check">
            <input class="people-check people-checked" id="user_{{ $key }}" type="radio" name="user_id" value="{{ $user->id }}" />
            <label class="people-check-label ps-0" for="user_{{ $key }}">
                <span class="fw-600 fs-16">{{ $user->name }} ({{ $user->email }})</span>
            </label>
        </div>
    @endforeach
@endif