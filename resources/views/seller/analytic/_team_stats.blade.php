<div class="row">
    <h5 class="mb-4 col-sm-10">{{ __('Total team stats') }}</h5>
</div>
<div class="card p-4 p-md-5" id="team_stats_div">
    <div class="people-contact pb-md-3">
        <div class="people-contact-col">
            <div class="top mb-4">
                <h5 class="fs-18 mb-4">{{__('Attended the zoom')}}</h5>
                <div class="custom-badge-tooltip">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $zoomAttended = (isset($board_contacts[5]) && count($confirm) > 0) ? formatPercentage(count($board_contacts[5]) * 100 / count($confirm)) : 0;
                            $badgeData = getBadgeIndicatorClass($zoomAttended, ContactBoardStatus::ATTENDED_THE_ZOOM);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}">
                            <span>{!! $badgeData['tooltip'] !!}</span>
                        </div>
                        {{ $zoomAttended }}
                        % {{__('confirmed Zoom invites')}}
                    </h6>
                </div>
            </div>
            <h1 class="blue mt-auto fs-60 fw-bold">{{isset($board_contacts[5]) ? count($board_contacts[5]) : 0 }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top mb-4">
                <h5 class="fs-18 mb-4">{{__('New distributor')}}</h5>
                <div class="custom-badge-tooltip">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $newDistributor = isset($board_contacts[6]) && !is_null($board_contacts[5]) ? formatPercentage(count($board_contacts[6]) * 100 / count($board_contacts[5])) : 0;
                            $badgeData = getBadgeIndicatorClass($newDistributor, ContactBoardStatus::NEW_DISTRIBUTOR);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $newDistributor }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div>
            </div>
            <h1 class="blue mt-auto fs-60 fw-bold">{{ isset($board_contacts[6]) ? count($board_contacts[6]) : 0 }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top mb-4">
                <h5 class="fs-18 mb-4">{{__('New client')}}</h5>
                <div class="custom-badge-tooltip">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $newClient = isset($board_contacts[7]) && !is_null($board_contacts[5]) ? formatPercentage(count($board_contacts[7]) * 100 / count($board_contacts[5])) : 0;
                            $badgeData = getBadgeIndicatorClass($newClient, 'on your new customers');
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $newClient }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div>
            </div>
            <h1 class="blue mt-auto fs-60 fw-bold">{{ isset($board_contacts[7]) ? count($board_contacts[7]) : 0 }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top mb-4">
                <h5 class="fs-18 mb-4">{{__('Followup')}}</h5>
                <div class="custom-badge-tooltip">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $followup = isset($board_contacts[8]) && !is_null($board_contacts[5]) ? formatPercentage(count($board_contacts[8]) * 100 / count($board_contacts[5])) : 0;
                            $badgeData = getBadgeIndicatorClass($followup);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $followup }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div>
            </div>
            <h1 class="blue mt-auto fs-60 fw-bold">{{ isset($board_contacts[8]) ? count($board_contacts[8]) : 0 }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top mb-4">
                <h5 class="fs-18 mb-4">{{__('Not interested')}}</h5>
                <div class="custom-badge-tooltip">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $notInterested = isset($board_contacts[9]) && !is_null($board_contacts[5]) ? formatPercentage(count($board_contacts[9]) * 100 / count($board_contacts[5])) : 0;
                            $badgeData = getBadgeIndicatorClass($notInterested);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $notInterested }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div>
            </div>
            <h1 class="blue mt-auto fs-60 fw-bold">{{ isset($board_contacts[9]) ? count($board_contacts[9]) : 0 }}</h1>
        </div>
    </div>
</div>
