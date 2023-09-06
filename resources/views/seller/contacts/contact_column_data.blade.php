@foreach($contacts as $contact)
    <div class="flex-column contact-user-card draggable-user-card" data-id="{{$contact->id}}" data-status-id="{{$id}}" data-board-id="{{$board->id}}" data-follow-count="{{$contact->followUp?$contact->followUp->getDayCount():''}}"
    data-is-complete-profile="{{ $contact->email && $contact->phone ? true : false }}" @if($contact->survey->count() > 0) style="border: 2px solid #198754;" @endif @if($contact->videoVisiters->count() > 0) style="border: 4px solid #74B72E;" @endif>
        <div class="contact-label">
            @include('seller.contacts.contact_card_label')
        </div>
        <div class="row">
            <div class="col-4">
                <img class="contact-user-card-profile-add contact-image" src="{{ $contact->profile_image && Storage::disk('public')->exists($contact->profile_image) ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}" />
            </div>
            <div class="col-8 m-auto card-contact-name text-break">{{ $contact->name }}</div>
            <a href="javascript:void(0)" data-id="{{ $contact->id }}" class="contact-delete-outer" data-name="{{ $contact->name }}" data-image="{{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}"><i class="feather-trash contact-delete-direct"></i></a>
        </div>
    </div>
@endforeach