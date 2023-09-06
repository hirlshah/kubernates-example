<d id="panel-stats">
    <div class="row">
        <h5 class="mb-md-4 col-sm-10"><b>{{ $title }}</b></h5>
    </div>

    @if(count($board_contacts) > 0)
        @foreach($board_contacts as $contact)
            @php $present = count($contact['status']->where('status',\App\Enums\ContactBoardStatus::ATTENDED_THE_ZOOM)) > 0 ? $contact['status']->where('status',\App\Enums\ContactBoardStatus::ATTENDED_THE_ZOOM)->first()->count : 0; @endphp
            <h5 class="pt-4">{{$contact['event_name']}}</h5>
            <div class="card p-4 p-md-5 mt-2 mb-2" id="stats_div">
                <div class="people-contact pb-md-3">
                    <div class="people-contact-col">
                        <div class="top">
                            <h5 class="fs-18 mb-4">{{__('Attended the zoom')}}</h5>
                            @php
                                $zoomAttended = ($present > 0 && $contact['confirm'] > 0) ? formatPercentageMax100($present * 100 / $contact['confirm']) : 0;
                                $badgeData = getBadgeIndicatorClass($zoomAttended, '80-70', ContactBoardStatus::ATTENDED_THE_ZOOM);
                            @endphp
                        </div>
                        <div class="custom-badge-tooltip mt-auto mb-4">
                            <div class="scroll-value mt-2">
                                <span class="scroll-value-count total-value-message-sent" data-line-id="attendedzoom" data-event-id="{{ $contact['event_id'] }}">{{ $zoomAttended }}%</span>
                                <input type="hidden" value="{{ $zoomAttended }}" class="progress-bar-value-message-sent" data-line-id="attendedzoom" data-event-id="{{ $contact['event_id'] }}">
                            </div>
                            <div class="progress custom-progress-bar-color-2">
                                <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="attendedzoom" data-event-id="{{ $contact['event_id'] }}"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                            </div>
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $present }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <div class="top">
                            <h5 class="fs-18 mb-2">{{__('New distributor')}}</h5>
                            @php
                                $distributor = count($contact['status']->where('status',\App\Enums\ContactBoardStatus::NEW_DISTRIBUTOR)) > 0 ? $contact['status']->where('status',\App\Enums\ContactBoardStatus::NEW_DISTRIBUTOR)->first()->count : 0;
                                $newDistributor = ($distributor > 0 && isset($present) && $present > 0) ? formatPercentageMax100($distributor * 100 / $present) : 0 ;
                                $badgeData = getBadgeIndicatorClass($newDistributor, '30-20', ContactBoardStatus::NEW_DISTRIBUTOR);
                            @endphp
                        </div>
                        <div class="custom-badge-tooltip mt-auto mb-2">
                            <div class="scroll-value">
                                <span class="scroll-value-count total-value-message-sent" data-line-id="distributor" data-event-id="{{ $contact['event_id'] }}">{{ $newDistributor }} %</span>
                            </div>
                            <div class="progress custom-progress-bar-color-2">
                                <input type="hidden" value="{{ $newDistributor }}" class="progress-bar-value-message-sent" data-line-id="distributor" data-event-id="{{ $contact['event_id'] }}">
                                <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="distributor" data-event-id="{{ $contact['event_id'] }}"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                            </div>
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $distributor }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <div class="top mb-4">
                            <h5 class="fs-18 mb-2">{{__('New client')}}</h5>
                            @php
                                $client = count($contact['status']->where('status',\App\Enums\ContactBoardStatus::NEW_CLIENT)) > 0 ?
                                $contact['status']->where('status',\App\Enums\ContactBoardStatus::NEW_CLIENT)->first()->count : 0;
                                $newClient = ($client > 0 && isset($present) && $present > 0) ? formatPercentageMax100($client * 100 / $present) : 0;
                                $badgeData = getBadgeIndicatorClass($newClient, '50-40', 'on your new customers');
                            @endphp
                        </div>
                        <div class="custom-badge-tooltip mt-auto mb-4">
                            <div class="scroll-value">
                                <span class="scroll-value-count total-value-message-sent" data-line-id="client" data-event-id="{{ $contact['event_id'] }}"> <span>{{ $newClient }}%</span></span>
                            </div>
                            <div class="progress custom-progress-bar-color-2">
                                <input type="hidden" value="{{ $newClient }}" class="progress-bar-value-message-sent" data-line-id="client" data-event-id="{{ $contact['event_id'] }}">
                                <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="client" data-event-id="{{ $contact['event_id'] }}"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                            </div>
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $client }}</h1>
                    </div>
                    <div class="people-contact-col">
                        @php
                            $followup = count($contact['status']->where('status',\App\Enums\ContactBoardStatus::FOLLOWUP)) > 0 ?
                            $contact['status']->where('status',\App\Enums\ContactBoardStatus::FOLLOWUP)->first()->count : 0;
                            $followupPercentage = ($followup > 0 && isset($present) && $present > 0) ? ($followup * 100 / $present) : 0;
                            $badgeData = getBadgeIndicatorClass($followup);
                        @endphp
                        <div class="top mb-4">
                            <h5 class="fs-18 mb-4">{{__('Followup')}}</h5>
                            {{-- <h6 class="grey-c0c0c0 fs-14">
                                <span class="custom-badge {{ $badgeData['class'] }}"></span>
                                <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                                {{ $followupPercentage }} %
                                {{__('presents at Zoom')}}
                            </h6> --}}
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $followup  }}</h1>
                    </div>
                    <div class="people-contact-col">
                        @php
                            $not_interested = count($contact['status']->where('status',\App\Enums\ContactBoardStatus::NOT_INTERESTED)) > 0 ?
                            $contact['status']->where('status',\App\Enums\ContactBoardStatus::NOT_INTERESTED)->first()->count : 0;
                            $notInterested = ($not_interested > 0 && isset($present) && $present > 0) ? formatPercentageMax100($not_interested * 100 / $present) : 0;
                            $badgeData = getBadgeIndicatorClass($notInterested);
                        @endphp
                        <div class="top mb-4">
                            <h5 class="fs-18 mb-4">{{__('Not interested')}}</h5>
                            {{-- <h6 class="grey-c0c0c0 fs-14">
                                <span class="custom-badge {{ $badgeData['class'] }}"></span>
                                <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                                {{ $notInterested }} %
                                {{__('presents at Zoom')}}
                            </h6> --}}
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $not_interested }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <div class="top mb-4">
                            <h5 class="fs-18 mb-4">{{__('Total distributor')}}</h5>
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $contact['total_distributor'] }}</h1>
                    </div>
                    <div class="people-contact-col">
                        <div class="top mb-4">
                            <h5 class="fs-18 mb-4">{{__('Total guests')}}</h5>
                        </div>
                        <h1 class="blue mt-auto fs-60 fw-bold">{{ $contact['total_guests'] }}</h1>
                    </div>
                </div>
                <button class="mt-3 float-left btn btn-sm btn-primary py-2 px-3 ms-auto seeMoreBtn seeMore-{{$contact['event_id']}}" id="contactButton{{$contact['event_id']}}" style="width:fit-content;" data-id="{{$contact['event_id']}}" data-flag="show">{{__('See more')}}</button>
                <button class="mt-3 float-left btn btn-sm btn-primary py-2 px-3 ms-auto seeLessBtn-{{$contact['event_id']}} d-none"  id="contactButton{{$contact['event_id']}}" style="width:fit-content;" data-id="{{$contact['event_id']}}" data-flag="show">{{ __('See less') }}</button>
            </div>

            <div class="mt-3" id="contactColumn{{$contact['event_id']}}">
            </div>
        @endforeach
        {{$contacts->links()}}
    @else
        <h5 class="text-danger">{{__('No data found')}}</h5>
    @endif
</div>
