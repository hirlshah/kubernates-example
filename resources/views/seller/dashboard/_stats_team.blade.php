<div class="card py-4 ps-4 pe-0 p-md-5"  id="team_stats">
    <div class="people-contact pb-md-5">
        <div class="people-contact-col">
            <div class="top mb-2">
                <h5 class="fs-18">{{__('Total Team Contacts')}}</h5>
            </div>
            <h1 class="blue stats-count fw-bold">{{ !empty($totalTeamContactsCount) ? $totalTeamContactsCount : 0 }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Message sent')}}</h5>
                @php
                    $messageSent = !empty($totalTeamContactsCount) ? formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / ($totalTeamContactsCount ? $totalTeamContactsCount : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass(formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / $totalTeamMessageGoal), '100-85');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-sent">{{ formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / $totalTeamMessageGoal) }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / $totalTeamMessageGoal) }}"class="progress-bar-value-message-sent" data-line-id="teammessage-sent">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-sent"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <input type="hidden" class="progress-bar-value-message-sent" value="{{ formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / $totalTeamMessageGoal) }}" data-duration="1000" data-color="#ccc,#56b2ff"  data-line-id="teammessage-sent">
            <div class="d-none progress-bar-circle position" data-done="{{ $teamContactStats['MESSAGE_SENT'] }}" data-percent="{{ formatPercentageMax100($teamContactStats['MESSAGE_SENT'] * 100 / $totalTeamMessageGoal) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($teamContactStats['MESSAGE_SENT']).'/'.round($totalTeamMessageGoal) }}"></div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['MESSAGE_SENT'] }} @if($teamStatsPeriod != 'INTERVAL') / {{ round($totalTeamMessageGoal) }} @endif </h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Message answered')}}</h5>
                @php
                    $messageAnswered = !empty($teamContactTotalStats['MESSAGE_SENT']) ? formatPercentageMax100($teamContactStats['MESSAGE_ANSWERED'] * 100 / ($teamContactStats['MESSAGE_SENT'] ? $teamContactStats['MESSAGE_SENT'] : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass($messageAnswered, '75-60');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-answered">{{ $messageAnswered }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $messageAnswered }}" class="progress-bar-value-message-sent" data-line-id="teammessage-answered">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-answered"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['MESSAGE_ANSWERED'] }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Zoom invite sent')}}</h5>
                @php
                    $zoomInviteSent = !empty($teamContactTotalStats['MESSAGE_ANSWERED']) ? formatPercentageMax100($teamContactStats['ZOOM_INVITE_SENT'] * 100 / ($teamContactStats['MESSAGE_ANSWERED'] ? $teamContactStats['MESSAGE_ANSWERED'] : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass($zoomInviteSent, '100-90');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-zoominvite">{{ $zoomInviteSent }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $zoomInviteSent }}" class="progress-bar-value-message-sent" data-line-id="teammessage-zoominvite">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-zoominvite"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['ZOOM_INVITE_SENT'] }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Confirmed for zoom')}}</h5>
                @php
                    $zoomConfirmed = !empty($teamContactTotalStats['ZOOM_INVITE_SENT']) ? formatPercentageMax100($teamContactStats['CONFIRMED_FOR_ZOOM'] * 100 / ($teamContactStats['ZOOM_INVITE_SENT'] ? $teamContactStats['ZOOM_INVITE_SENT'] : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass($zoomConfirmed, '70-50');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-zoominvite">{{ $zoomConfirmed }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $zoomConfirmed }}" class="progress-bar-value-message-sent" data-line-id="teammessage-zoominvite">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-zoominvite"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['CONFIRMED_FOR_ZOOM'] }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Attended the zoom')}}/{{__('Video viewed')}}</h5>
                @php
                    $zoomAttended = !empty($teamContactTotalStats['CONFIRMED_FOR_ZOOM']) ? formatPercentageMax100($teamContactStats['ATTENDED_THE_ZOOM'] * 100 / ($teamContactStats['CONFIRMED_FOR_ZOOM'] ? $teamContactStats['CONFIRMED_FOR_ZOOM'] : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass($zoomAttended, '80-70', 'on your attendance rate');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-zoomconfirm">{{ $zoomAttended }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $zoomAttended }}" class="progress-bar-value-message-sent" data-line-id="teammessage-zoomconfirm">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-zoomconfirm"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['ATTENDED_THE_ZOOM'] }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('New distributor')}}</h5>
                @php
                    $newDistributor = !empty($teamContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($teamContactStats['NEW_DISTRIBUTOR'] * 100 / ($teamContactStats['ATTENDED_THE_ZOOM'] ? $teamContactStats['ATTENDED_THE_ZOOM']: 1)) : 0;

                    $badgeData = getBadgeIndicatorClass( $newDistributor, '30-20', ContactBoardStatus::NEW_DISTRIBUTOR);
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-attendedzoom-scrollbar">{{ $newDistributor }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $newDistributor }}" class="progress-bar-value-message-sent" data-line-id="teammessage-attendedzoom-scrollbar">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-attendedzoom-scrollbar"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <input type="hidden" class="progress-bar-value-message-sent" data-line-id="teammessage-attendedzoom" value="{{ formatPercentageMax100($teamContactStats['NEW_DISTRIBUTOR'] * 100 / $userTeamPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff">
            <div class="d-none progress-bar-circle position" data-done="{{ $teamContactStats['NEW_DISTRIBUTOR'] }}" data-percent="{{ formatPercentageMax100($teamContactStats['NEW_DISTRIBUTOR'] * 100 / $userTeamPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($teamContactStats['NEW_DISTRIBUTOR']).'/'.round($userTeamPerformanceRadialSettingArr['no_of_distributors']) }}"></div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['NEW_DISTRIBUTOR'] }} @if($teamStatsPeriod != 'INTERVAL') / {{ round($userTeamPerformanceRadialSettingArr['no_of_distributors']) }} @endif</h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('New client')}}</h5>
                @php
                    $newClient = !empty($teamContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($teamContactStats['NEW_CLIENT'] * 100 / ($teamContactStats['ATTENDED_THE_ZOOM'] ? $teamContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                    $badgeData = getBadgeIndicatorClass( $newClient, '50-40', 'on your closing rate');
                @endphp
            </div>
            <div class="custom-badge-tooltip mt-auto mb-2">
                <div class="scroll-value">
                    <span class="scroll-value-count total-value-message-sent" data-line-id="teammessage-client-scrollbar">{{ $newClient }}%</span>
                </div>
                <div class="progress custom-progress-bar-color-2 mt-md-3">
                    <input type="hidden" value="{{ $newClient }}" class="progress-bar-value-message-sent" data-line-id="teammessage-client-scrollbar">
                    <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="teammessage-client-scrollbar"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                </div>
            </div>
            <input type="hidden" class="progress-bar-value-message-sent" value="{{ formatPercentageMax100($teamContactStats['NEW_CLIENT'] * 100 / $userTeamPerformanceRadialSettingArr['no_of_clients']) }}" data-duration="1000" data-color="#ccc,#56b2ff"  data-line-id="teammessage-client">
            <div class="d-none progress-bar-circle position" data-done="{{ $teamContactStats['MESSAGE_SENT'] }}" data-percent="{{ formatPercentageMax100($teamContactStats['NEW_CLIENT'] * 100 / $userTeamPerformanceRadialSettingArr['no_of_clients']) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($teamContactStats['NEW_CLIENT']).'/'.round($userTeamPerformanceRadialSettingArr['no_of_clients']) }}"></div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['NEW_CLIENT']}} @if($teamStatsPeriod != 'INTERVAL') / {{ round($userTeamPerformanceRadialSettingArr['no_of_clients']) }} @endif </h1>
        </div>
        <div class="people-contact-col">
            <div class="top">
                <h5 class="fs-18 mb-4">{{__('Followup')}}</h5>
                {{-- <div class="custom-badge-tooltip mt-auto mb-2">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $followup = !empty($teamContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($teamContactStats['FOLLOWUP'] * 100 / ($teamContactStats['ATTENDED_THE_ZOOM'] ? $teamContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                            $badgeData = getBadgeIndicatorClass($followup);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $followup }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div> --}}
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['FOLLOWUP'] }}</h1>
        </div>
        <div class="people-contact-col">
            <div class="top mb-2">
                <h5 class="fs-18 mb-4">{{__('Not interested')}}</h5>
                {{-- <div class="custom-badge-tooltip mt-auto mb-2">
                    <h6 class="grey-c0c0c0 fs-14">
                        @php
                            $notInterested = !empty($teamContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($teamContactStats['NOT_INTERESTED'] * 100 / ($teamContactStats['ATTENDED_THE_ZOOM'] ? $teamContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                            $badgeData = getBadgeIndicatorClass($notInterested);
                        @endphp
                        <span class="custom-badge {{ $badgeData['class'] }}"></span>
                        <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                        {{ $notInterested }}
                        % {{__('presents at Zoom')}}
                    </h6>
                </div> --}}
            </div>
            <h1 class="blue stats-count fw-bold">{{ $teamContactStats['NOT_INTERESTED'] }}</h1>
        </div>
    </div>
</div>
