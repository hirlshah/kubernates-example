@if(isset($contacts))
    @foreach($contacts as $contact)
        <div class="flex-column contact-user-card draggable-user-card" data-id="{{$contact->id}}" data-status-id="0" data-board-id="{{$board->id}}" data-follow-count="{{$contact->followUp?$contact->followUp->getDayCount():''}}"
        data-is-complete-profile="{{ $contact->email && $contact->phone ? true : false }}">
            <div class="contact-label">
                @include('seller.contacts.contact_card_label')
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="selected_image"
                    style="background-image: url({{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }});">
                    </div>
                  </div>
                <div class="col-8">
                    <p class="card-contact-name mb-0" style="word-wrap: break-word; width:100%;">{{ $contact->name }}</p>
                </div>
                <a href="javascript:void(0)" data-id="{{ $contact->id }}" class="contact-delete-outer" data-name="{{ $contact->name }}" data-image="{{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }}"><i class="feather-trash contact-delete-direct"></i></a>
            </div>
        </div>
    @endforeach
@endif