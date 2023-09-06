<div class="flex-none pb-3" style="width: 233px;">
    <h6 class="contact-status shadow-none">
        @if($updateStatus == 0)
            {{__('Contacts')}}
        @elseif($updateStatus == 1)
            {{__('Message sent')}}
        @elseif($updateStatus == 2)
        {{__('Message answered')}}
        @elseif($updateStatus == 3)
        {{__('Zoom invite sent')}}
        @elseif($updateStatus == 4)
        {{__('Confirmed for zoom')}}
        @elseif($updateStatus == 5)
        {{__('Attended the zoom')}}
        @elseif($updateStatus == 6)
        {{__('New distributor')}}
        @elseif($updateStatus == 7)
        {{__('New client')}}
        @elseif($updateStatus == 8)
        {{__('Followup')}}
        @elseif($updateStatus == 9)
        {{__('Not interested')}}
        @endif
    </h6>
    @foreach($rows as $key => $value)
        <div class="flex-column draggable-user-card ui-draggable ui-draggable-handle mt-0"
            style="position: relative; background-color: rgb(255, 255, 255); margin-bottom: 9px; box-shadow: 0px 0px 70px rgba(0, 0, 0, 0.08);">
            <div class="contact-label">
            </div>
            <div class="row align-items-center">
                <div class="col-4">
                    <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" style="background-image: url({{asset('assets/images/user-icon2.png') }}); width:49px; height:49px;">
                    </div>
                </div>
                <div class="col-8">
                    <p class="mb-0 contact-name" style="word-wrap: break-word; width:100%;">@if(isset($value['Name'])) {{ $value['Name'] }} @endif</p>
                </div>
            </div>
        </div>
    @endforeach
</div>