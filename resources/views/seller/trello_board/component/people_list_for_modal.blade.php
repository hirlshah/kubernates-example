@if(!empty($users))
    @foreach($users as $key => $user)
        <div class="mb-2 people_listing_check">
            <input class="people-check" id="people_for_modal_{{ $key }}" type="checkbox" name="peoples_for_modal" value="{{ $user->id }}" @if(in_array($user->id, $trelloTaskUserIds)) checked @endif />
            <label class="people-check-label" for="people_for_modal_{{ $key }}">
                <img class="rounded-circle" style="width:49px; height:49px; object-fit: cover;" src="@if($user->thumbnail_image && Storage::disk('public')->exists($user->thumbnail_image))  {{ App\Classes\Helper\CommonUtil::getUrl($user->thumbnail_image) }}@else {{ asset('assets/images/people.png') }} @endif" alt=""> <span class="fs-16" style="font-weight:600;">{{ $user->name }} {{ $user->last_name }}</span>
            </label>
        </div>
    @endforeach
@endif