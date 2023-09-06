<div id="panel-stats">
    {!! $panelView !!}
</div>
<div class="content-body p-2">
    <div class="dummy-scroll-main mb-2">
        <div class="dummy-scrollbar"></div>
    </div>
    <div class="drag-drop-scroll-main">
        <div class="drag-drop-scroll-wrapper">
            <div class="drag-drop-scroll mb-4 contact-board pb-5">
                @php $i = 1; @endphp
                @foreach($statusRange as $key => $status)
                    <div class="drag-drop-card-1 drag-drop-card" data-status-id="{{$key}}">
                        <h6 class="contact-status">{{__($status)}} (@if($loop->last) {{ isset($board_contacts[$key]) ?
                            count($board_contacts[$key]) + $distributorEventUsers->count() : 0 }} @else {{
                            isset($board_contacts[$key]) ? count($board_contacts[$key]) : 0 }} @endif)</h6>
                        @if(isset($board_contacts[$key]))
                            @foreach($board_contacts[$key] as $contact)
                                <div class="card follow-up mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-sm-6 my-2 border-right contact-user-detail"
                                                        data-id="{{$contact->id}}">
                                                        <div class="d-flex align-items-center flex-wrap">
                                                            <div class="hw-60px bg-cover bg-center me-3"
                                                            style="background-image: url({{ $contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                            </div>
                                                            <div class="right">
                                                                <p class="mb-0">{{$contact->name}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 my-2">
                                                        <div class="d-flex align-items-center flex-wrap">
                                                            <div class="hw-60px bg-cover bg-center me-3"
                                                            style="background-image: url({{ $contact->User->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->User->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                            </div>
                                                            <div class="right">
                                                                <p class="mb-2">{{$contact->User->name}}</p>
                                                                <p class="mb-0 grey-c0c0c0 fs-14">
                                                                    {{$contact->User->getUplineName()??'--'}}</p>
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
                                                    <div class="col-sm-6 my-2 border-right contact-user-detail">
                                                        <div class="d-flex align-items-center flex-wrap">
                                                            <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $contact->User->profile_image ? App\Classes\Helper\CommonUtil::getUrl($contact->User->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                            </div>
                                                            <div class="right">
                                                                <p class="mb-0">{{$contact->User->getFullName()}}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 my-2">
                                                        <div class="d-flex align-items-center flex-wrap">
                                                            <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $contact->User->getUpline() ? App\Classes\Helper\CommonUtil::getUrl($contact->User->getUpline()->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                            </div>
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
                            @endforeach
                        @endif
                    </div>
                    @php $i++; @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>
