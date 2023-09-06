<div class="drag-drop-scroll mb-4 contact-board pb-5">
    <div id="contact_list" class="drag-drop-card" data-status-id="0">
        <h6 class="contact-status">{{__('Contacts')}}</h6>
        <div class="row contact-user-card-add">
            <div class="col-4">
                <div class="contact-user-card-profile"><i class="feather-plus"></i></div>
            </div>
            <div class="col-8 m-auto">{{__('Add contact')}}</div>
        </div>
        <div class="add_contact_list" id="create_contact_card"></div>
        <div class="added_contact_list"></div>
        <div class="droppable-contact" id="contact-board-first-column">
          
        </div>
    </div>
    @foreach($statusRange as $key => $status)
        <div class="drag-drop-card status" data-status-id="{{$key}}">
            @if($status == "Message sent")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Contacts to whom you sent your first message") }}</span></div></span></h6>
            @elseif($status == "Message answered")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact replied to your first message") }}</span></div></span></h6>
            @elseif($status == "Zoom invite sent")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("You have invited your contact to your presentation, to watch a presentation video or to pick up your products") }}</span></div></span></h6>
            @elseif($status == "Confirmed for zoom")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact confirmed that he was coming to your presentation or to watch your video") }}</span></div></span></h6>
            @elseif($status == "Attended the zoom")
                <h6 class="contact-status">{{__($status)}}/{{__('Video viewed')}} <span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact watched the video or came to your presentation") }}</span></div></span></h6>
            @elseif($status == "New distributor")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact is now one of your distributors") }}</span></div></span></h6>
            @elseif($status == "New client")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact has become your client") }}</span></div></span></h6>
            @elseif($status == "Followup")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact has an interest in becoming a customer or distributors in the future") }}</span></div></span></h6>
            @elseif($status == "Not interested")
                <h6 class="contact-status">{{__($status)}}<span class="custom-badge-tooltip"><i class="feather-help-circle mx-2"></i>
                <div class="tooltiptext"><span>{{ __("Your contact has no interest in being a customer or a distributor") }}</span></div></span></h6>
            @endif
            <div class="droppable-contact" id="contact-status-{{$key}}">
            </div>
        </div>
    @endforeach
</div>
