@if(!empty($users))
    @foreach($users as $key => $user)
        <div class="mb-2 people_listing_check">
            <input class="people-check people-checked" id="people_{{ $key }}" type="checkbox" name="peoples" value="{{ $user->id }}" @if(in_array($user->id, $trelloBoardUserIds)) checked @endif />
            <label class="people-check-label" for="people_{{ $key }}">
                <img class="rounded-circle" style="width:49px; height:49px; object-fit: cover;" src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}@else {{ asset('assets/images/people.png') }} @endif" alt=""> <span class="fw-600 fs-16">{{ $user->name }} {{ $user->last_name }}</span>
            </label>
        </div>
    @endforeach
@endif