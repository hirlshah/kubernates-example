<div class="content-body p-2 hide-content">
    <div class="dummy-scroll-main mb-2 d-none d-lg-block">
        <div class="dummy-scrollbar"></div>
    </div>
    <div class="drag-drop-scroll-main scroll-hide">
        <div class="drag-drop-scroll-wrapper">
            <div class="drag-drop-scroll mb-4 contact-board pb-5">
                @php $i = 1; @endphp
                @foreach($statusRange as $key => $status)
                    <div class="drag-drop-card-1 drag-drop-card" data-status-id="{{$key}}">
                        <h6 class="contact-status">{{__($status)}} 
                        (
                            @if($loop->last) 
                                {{ $distributorEventUsers->count() }} 
                            @else 
                            {{ isset($board_contacts[$key]) ? count($board_contacts[$key]) : 0 }}
                            @endif
                        )
                        </h6>
                        @if(isset($board_contacts[$key]))
                            @foreach($board_contacts[$key] as $contact)
                                <div class="card follow-up mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="border-bottom">
                                                        <div class="col-sm-12  my-2 py-2 contact-user-detail"
                                                        data-id="{{$contact->contact->id}}">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <div class="hw-60px bg-cover bg-center me-3 analytics-user-card-profile-add" style="background-image: url({{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                                </div>
                                                                <div class="col-9 right">
                                                                    <p class="mb-0">{{$contact->contact->name}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 my-2 py-2">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <div class="hw-60px bg-cover bg-center me-3 analytics-user-card-profile-add" style="background-image: url({{ isset($contact->User) && !empty($contact->User) && $contact->User->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->User->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                            </div>
                                                            <div class="col-9 right">
                                                                <p class="mb-2">{{ isset($contact->User) && !empty($contact->User) ? $contact->User->getFullName() : '--'}}</p>
                                                                <p class="mb-0 grey-c0c0c0 fs-14">
                                                                    {{ isset($contact->User) && !empty($contact->User) ? $contact->User->getUplineName() : '--'}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if($loop->last)
                            @foreach($distributorEventUsers as $contact)
                                <div class="card follow-up mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="border-bottom">
                                                        <div class="col-sm-12 my-2 contact-user-detail">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <div class="hw-60px bg-cover bg-center me-3 analytics-user-card-profile-add" style="background-image: url({{ $contact->User->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->User->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                                </div>
                                                                <div class="col-9 right">
                                                                    <p class="mb-0">{{$contact->User->getFullName()}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 my-2">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <div class="hw-60px bg-cover bg-center me-3 analytics-user-card-profile-add" style="background-image: url({{ $contact->User->getUpline() ? App\Classes\Helper\CommonUtil::getUrl($contact->User->getUpline()->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="right">
                                                                    <p class="mb-2">{{$contact->User->getUpline() ?
                                                                        $contact->User->getUpline()->getFullName() : '--'}}</p>
                                                                    <p class="mb-0 grey-c0c0c0 fs-14">{{($contact->User->getUpline() &&
                                                                        $contact->User->getUpline()->getUpline()) ?
                                                                        $contact->User->getUpline()->getUpline()->getFullName() : '--'}}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>
