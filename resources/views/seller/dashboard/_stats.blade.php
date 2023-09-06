<div>
    <div class="row gy-4 my-4">
        <div class="col-xxl-4 col-xl-3 my-xl-auto">
            <h5>{{__('Your personal stats')}}</h5>
                @if($userId == auth()->user()->id)
                    <a class="btn btn-blue dailies-icons py-1 px-2 add_edit_task mt-xxl-2" style="margin-top: 12px" data-type="add-personal-goal">{{ __('Adjust your personal goals') }}</a>
                @endif
        </div>
        <div class="col-xxl-8 col-xl-9 text-xl-end my-xl-auto">
            <div class="dropdown-menu-analytic filterDiv">
                <div class="calendar-top" id="personal_stats_period">
                    <div class="btn-group flex-xl-nowrap flex-wrap" role="group" aria-label="Basic example">
                        <button type="button" class="btn calendar-btn px-3 py-2  personal-stats active" id="calendar-day" data-type="Today">{{__('Today')}}</button>
                        <button type="button" class="btn calendar-btn px-3 py-2 personal-stats" id="calendar-week" data-type="WEEKLY">{{__('Weekly')}}</button>
                        <input type="button" class="btn calendar-btn px-3 py-2 personal-stats" id="custom-month" data-type="MONTHLY" value="{{__('Monthly')}}">
                        <input type="button" class="btn calendar-btn px-3 py-2  personal-stats cutom-interval" id="custom-interval" data-type="Interval" value="{{__('Interval')}}">
                        <button type="button" class="btn calendar-btn px-3 py-2 personal-stats" id="calendar-total" data-type="Total">{{__('Total')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card py-4 ps-4 pe-0 p-md-5 mb-4" id="personal_stats_div">
        <div class="people-contact pb-5">
            <div class="people-contact-col">
                <div class="top mb-2">
                    <h5 class="fs-18">{{__('Total Contacts')}}</h5>
                </div>
                <h1 class="blue stats-count fw-bold">{{ !empty($totalContacts) ? $totalContacts : 0 }}</h1>
            </div>
            <div class="people-contact-col pb-md-4">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('Message sent')}}</h5>
                    @php
                        $messageSent = !empty($totalContacts) ? formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / ($totalContacts ? $totalContacts : 1)) : 0;
                        $badgeData =  getBadgeIndicatorClass(formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / $totalMessageGoal), '100-85');
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="message-sent">{{ formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / $totalMessageGoal) }}%</span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / $totalMessageGoal) }}" class="progress-bar-value-message-sent" data-line-id="message-sent">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="message-sent"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <input type="hidden" class="progress-bar-value-message-sent" value="{{ formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / $totalMessageGoal) }}" data-duration="1000" data-color="#ccc,#56b2ff"  data-line-id="message-sent">
                <div class="d-none progress-bar-circle position" data-done="{{ $personalContactStats['MESSAGE_SENT'] }}" data-percent="{{ formatPercentageMax100($personalContactStats['MESSAGE_SENT'] * 100 / $totalMessageGoal) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($personalContactStats['MESSAGE_SENT']).'/'.round($totalMessageGoal) }}"></div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['MESSAGE_SENT'] }} @if($personalStatsPeriod != 'INTERVAL') / {{ round($totalMessageGoal) }} @endif </h1>
            </div>
            <div class="people-contact-col">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('Message answered')}}</h5>
                    @php
                        $messageAnswered = !empty($personalContactTotalStats['MESSAGE_SENT']) ? formatPercentageMax100($personalContactStats['MESSAGE_ANSWERED'] * 100 / ($personalContactStats['MESSAGE_SENT'] ? $personalContactStats['MESSAGE_SENT'] : 1)) : 0;
                        $badgeData = getBadgeIndicatorClass($messageAnswered, '75-60');
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="message-answered">{{ $messageAnswered }}% </span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ round($messageAnswered) }}" class="progress-bar-value-message-sent" data-line-id="message-answered">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="message-answered"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['MESSAGE_ANSWERED'] }}</h1>
            </div>
            <div class="people-contact-col">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('Zoom invite sent')}}</h5>
                    @php
                        $zoomInviteSent = !empty($personalContactTotalStats['MESSAGE_ANSWERED']) ? formatPercentageMax100($personalContactStats['ZOOM_INVITE_SENT'] * 100 / ($personalContactStats['MESSAGE_ANSWERED'] ? $personalContactStats['MESSAGE_ANSWERED']: 1)) : 0;
                        $badgeData = getBadgeIndicatorClass($zoomInviteSent, '100-90');
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="message-invitesent">{{ $zoomInviteSent }}% </span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ round($zoomInviteSent) }}" class="progress-bar-value-message-sent" data-line-id="message-invitesent">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="message-invitesent"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['ZOOM_INVITE_SENT'] }}</h1>
            </div>
            <div class="people-contact-col">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('Confirmed for zoom')}}</h5>
                    @php
                        $zoomConfirmed = !empty($personalContactTotalStats['ZOOM_INVITE_SENT']) ? formatPercentageMax100($personalContactStats['CONFIRMED_FOR_ZOOM'] * 100 / ($personalContactStats['ZOOM_INVITE_SENT'] ? $personalContactStats['ZOOM_INVITE_SENT'] : 1)) : 0;
                        $badgeData = getBadgeIndicatorClass($zoomConfirmed, '70-50');
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="message-confirmedforzoom">{{ $zoomConfirmed }}% </span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ round($zoomConfirmed) }}" class="progress-bar-value-message-sent" data-line-id="message-confirmedforzoom">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="message-confirmedforzoom"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['CONFIRMED_FOR_ZOOM'] }}</h1>
            </div>
            <div class="people-contact-col">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('Attended the zoom')}}/{{__('Video viewed')}}</h5>
                    @php
                        $zoomAttended = !empty($personalContactTotalStats['CONFIRMED_FOR_ZOOM']) ? formatPercentageMax100($personalContactStats['ATTENDED_THE_ZOOM'] * 100 / ($personalContactStats['CONFIRMED_FOR_ZOOM'] ? $personalContactStats['CONFIRMED_FOR_ZOOM'] : 1)) : 0;
                        $badgeData = getBadgeIndicatorClass($zoomAttended, '80-70', ContactBoardStatus::ATTENDED_THE_ZOOM);
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="message-attendedzoom">{{ $zoomAttended }}% </span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ round($zoomAttended) }}" class="progress-bar-value-message-sent" data-line-id="message-attendedzoom">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="message-attendedzoom"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['ATTENDED_THE_ZOOM'] }}</h1>
            </div>

            <div class="people-contact-col">
                @if($personalStatsPeriod == 'Today')
                    <div class="top">
                        <h5 class="fs-18 mb-4">{{__('New distributor')}}</h5>
                        @php
                            $newDistributor = !empty($personalContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / ($personalContactStats['ATTENDED_THE_ZOOM'] ? $personalContactStats['ATTENDED_THE_ZOOM']  : 1)) : 0;

                            $badgeData = getBadgeIndicatorClass($newDistributor, '30-20', ContactBoardStatus::NEW_DISTRIBUTOR);
                        @endphp
                    </div>
                    <div class="custom-badge-tooltip">
                        <div class="scroll-value">
                            <span class="scroll-value-count total-value-message-sent" data-line-id="distributor-scrollbar">{{ $newDistributor }}%</span>
                        </div>
                        <div class="progress custom-progress-bar-color-2 mt-md-3">
                            <input type="hidden" value="{{ $newDistributor }}" class="progress-bar-value-message-sent" data-line-id="distributor-scrollbar">
                            <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="distributor-scrollbar"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                        </div>
                    </div>
                    <input type="hidden" class="progress-bar-value-message-sent" data-line-id="distributor" value="{{ formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / $userPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff">
                    <div class="d-none progress-bar-circle position" data-done="{{ $personalContactStats['NEW_DISTRIBUTOR'] }}"  data-percent="{{ formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / $userPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($personalContactStats['NEW_DISTRIBUTOR']).'/'.round($userPerformanceRadialSettingArr['no_of_distributors']) }}"></div>
                    <h1 class="blue stats-count fw-bold">{{ $personalContactStats['NEW_DISTRIBUTOR'] }} @if($personalStatsPeriod != 'INTERVAL') / {{ round($userPerformanceRadialSettingArr['no_of_distributors']) }} @endif</h1>
                @else
                    <div class="to mb-md-4 mb-5p">
                        <h5 class="fs-18 mb-4">{{__('New distributor')}}</h5>
                        @php
                            $newDistributor = !empty($personalContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / ($personalContactStats['ATTENDED_THE_ZOOM'] ? $personalContactStats['ATTENDED_THE_ZOOM']  : 1)) : 0;

                            $badgeData = getBadgeIndicatorClass($newDistributor, '30-20', ContactBoardStatus::NEW_DISTRIBUTOR);
                        @endphp
                    </div>
                    <div class="custom-badge-tooltip">
                        <div class="scroll-value">
                            <span class="scroll-value-count total-value-message-sent" data-line-id="distributor-scrollbar">{{ $newDistributor }}%</span>
                        </div>
                        <div class="progress custom-progress-bar-color-2 mt-md-3">
                            <input type="hidden" value="{{ $newDistributor }}" class="progress-bar-value-message-sent" data-line-id="distributor-scrollbar">
                            <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="distributor-scrollbar"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                        </div>
                    </div>
                    <input type="hidden" class="progress-bar-value-message-sent" data-line-id="distributor" value="{{ formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / $userPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff">
                    <div class="d-none progress-bar-circle position" data-done="{{ $personalContactStats['NEW_DISTRIBUTOR'] }}" data-percent="{{ formatPercentageMax100($personalContactStats['NEW_DISTRIBUTOR'] * 100 / $userPerformanceRadialSettingArr['no_of_distributors']) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($personalContactStats['NEW_DISTRIBUTOR']).'/'.round($userPerformanceRadialSettingArr['no_of_distributors']) }}"></div>
                    <h1 class="blue stats-count fw-bold">{{ $personalContactStats['NEW_DISTRIBUTOR'] }} @if($personalStatsPeriod != 'INTERVAL') / {{ round($userPerformanceRadialSettingArr['no_of_distributors']) }} @endif</h1>
                @endif
            </div>
            <div class="people-contact-col">
                <div class="top">
                    <h5 class="fs-18 mb-4">{{__('New client')}}</h5>
                    @php
                        $newClient = !empty($personalContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($personalContactStats['NEW_CLIENT'] * 100 / ($personalContactStats['ATTENDED_THE_ZOOM'] ? $personalContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                        $badgeData = getBadgeIndicatorClass( $newClient, '50-40', 'on your new customers');
                    @endphp
                </div>
                <div class="custom-badge-tooltip">
                    <div class="scroll-value">
                        <span class="scroll-value-count total-value-message-sent" data-line-id="new-client-scrollbar">{{ $newClient }}%</span>
                    </div>
                    <div class="progress custom-progress-bar-color-2 mt-md-3">
                        <input type="hidden" value="{{ $newClient }}" class="progress-bar-value-message-sent" data-line-id="new-client-scrollbar">
                        <div class="custom-progress-bar-color-2-thumb custom-badge" data-line-id="new-client-scrollbar"><div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div></div>
                    </div>
                </div>
                <input type="hidden" class="progress-bar-value-message-sent" data-line-id="new-client" value="{{ formatPercentageMax100($personalContactStats['NEW_CLIENT'] * 100 / $userPerformanceRadialSettingArr['no_of_clients']) }}" data-duration="1000" data-color="#ccc,#56b2ff">
                <div class="d-none progress-bar-circle position" data-done="{{ $personalContactStats['NEW_CLIENT'] }}" data-percent="{{ formatPercentageMax100($personalContactStats['NEW_CLIENT'] * 100 / $userPerformanceRadialSettingArr['no_of_clients']) }}" data-duration="1000" data-color="#ccc,#56b2ff" data-value="{{ ($personalContactStats['NEW_CLIENT']).'/'.round($userPerformanceRadialSettingArr['no_of_clients']) }}"></div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['NEW_CLIENT'] }} @if($personalStatsPeriod != 'INTERVAL') / {{ round($userPerformanceRadialSettingArr['no_of_clients']) }} @endif</h1>
            </div>
            <div class="people-contact-col">
                <div class="top mb-2">
                    <h5 class="fs-18">{{__('Followup')}}</h5>
                    {{-- <div class="custom-badge-tooltip">
                        <h6 class="grey-c0c0c0 fs-14">
                            @php
                                $followup = !empty($personalContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($personalContactStats['FOLLOWUP'] * 100 / ($personalContactStats['ATTENDED_THE_ZOOM'] ? $personalContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                                $badgeData = getBadgeIndicatorClass($followup);
                            @endphp
                            <span class="custom-badge {{ $badgeData['class'] }}"></span>
                            <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                            {{ $followup }}
                            % {{__('presents at Zoom')}}
                        </h6>
                    </div> --}}
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['FOLLOWUP'] }}</h1>
            </div>
            <div class="people-contact-col">
                <div class="top mb-2">
                    <h5 class="fs-18">{{__('Not interested')}}</h5>
                    {{-- <div class="custom-badge-tooltip">
                        <h6 class="grey-c0c0c0 fs-14">
                            @php
                                $notInterested = !empty($personalContactTotalStats['ATTENDED_THE_ZOOM']) ? formatPercentageMax100($personalContactStats['NOT_INTERESTED'] * 100 / ($personalContactStats['ATTENDED_THE_ZOOM'] ? $personalContactStats['ATTENDED_THE_ZOOM'] : 1)) : 0;
                                $badgeData = getBadgeIndicatorClass($notInterested);
                            @endphp
                            <span class="custom-badge {{ $badgeData['class'] }}"></span>
                            <div class="tooltiptext {{ $badgeData['color'] }}"><span>{!! $badgeData['tooltip'] !!}</span></div>
                            {{ $notInterested }}
                            % {{__('presents at Zoom')}}
                        </h6>
                    </div> --}}
                </div>
                <h1 class="blue stats-count fw-bold">{{ $personalContactStats['NOT_INTERESTED'] }}</h1>
            </div>
        </div>
    </div>
   
    @if($userId == auth()->user()->id)
        @include('seller.dashboard.dailies._dailies_tasks', ['tasks' => $tasks, 'completedTasks' => $completedTasks, 'isEdit' => $isObjectifsEdit, 'completedTaskDates' => $completedTaskDates])
    @endif
    
</div>
<div class="row gy-4 my-4">
        <div class="col-xxl-4 col-xl-3 my-xl-auto">
            <h5> {{ __('Total team stats') }}</h5>
            @if($userId == auth()->user()->id)
                <a class="btn btn-blue dailies-icons py-1 px-2 add_edit_task mt-xxl-2" style="margin-top: 12px" data-type="add-team-goal">{{ __('Adjust your team goals') }}</a>
            @endif
        </div>
        <div class="col-xxl-8 col-xl-9 text-xl-end my-xl-auto">
            <div class="dropdown-menu-analytic filterDiv">
                <div class="calendar-top" id="team_stats_period">
                    <div class="btn-group flex-xl-nowrap flex-wrap" role="group">
                        <button type="button" class="btn calendar-btn px-3 py-2 active team-stats" id="team-calendar-day" data-type="Today">{{__('Today')}}</button>
                        <button type="button" class="btn calendar-btn px-3 py-2  team-stats" id="team-calendar-week" data-type="WEEKLY">{{__('Weekly')}}</button>
                        <input type="button" class="btn calendar-btn px-3 py-2  team-stats" id="team-custom-month" data-type="MONTHLY" value="{{__('Monthly')}}">
                        <input type="button" class="btn calendar-btn px-3 py-2   team-stats team-cutom-interval" id="team-custom-interval" data-type="Interval" value="{{__('Interval')}}">
                        <button type="button" class="btn calendar-btn px-3 py-2 team-stats" id="team-calendar-total" data-type="Total">{{__('Total')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="team_stats_data"></div>

<script>
    $(".progress-bar-circle").loading();
    let sellerDashboardStatsRoutee = "{{route('dashboard-member-personal-stats')}}";
    let userId = "{{ $userId }}";

    $('.progress-bar-value-message-sent').each(function(i, obj) {
        var percentage = obj.value;
        var lineId = obj.getAttribute('data-line-id');
        $(".total-value-message-sent[data-line-id='"+lineId+"']").css('color',getPercentageWiseColor(percentage));
        $(".total-value-message-sent[data-line-id='"+lineId+"']").css('left',percentage+'%');
        $(".custom-badge[data-line-id='"+lineId+"']").css('left',percentage+'%');
    });

    $(document).on('click', '.personal-stats', function (e) {
        $(this).addClass('active').siblings().removeClass('active');
        let value = $(this).data('type');
        let dateFilterType = value;
        if (value != 'Interval') {
            let data = {};
            data.personal_stats_period = dateFilterType;
            data.user_id = userId;
            ajaxPersonalFilterCall(data);
        }
    });

    var weekAndMonthByLocale = getWeekAndMonthByLocale();
    $('#custom-interval').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: clearText,
            applyLabel: applyText,
            "daysOfWeek": weekAndMonthByLocale[0],  
            "monthNames": weekAndMonthByLocale[1]
      }
    }, function (start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('#custom-interval').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate;
        var endDate = picker.endDate;
        start = startDate.format('YYYY-MM-DD');
        end = endDate.format('YYYY-MM-DD');
        let dataInterval = {};
        dataInterval.start = start;
        dataInterval.end = end;
        dataInterval.user_id = userId;
        ajaxPersonalFilterCall(dataInterval);
    });

    $(document).on('click', '.team-stats', function (e) {
        $(this).addClass('active').siblings().removeClass('active');
        let value = $(this).data('type');
        let dateFilterType = value;
        if (dateFilterType != 'Interval') {
            let data = {};
            data.team_stats_period = dateFilterType;
            data.user_id = userId;
            ajaxTeamFilterCall(data);
        }
    });

    $('#team-custom-interval').daterangepicker({
        autoUpdateInput: false,
        locale: {
        cancelLabel: clearText,
        applyLabel: applyText,
        "daysOfWeek": weekAndMonthByLocale[0],  
        "monthNames": weekAndMonthByLocale[1]
      }
    }, function (start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });

    $('#team-custom-interval').on('apply.daterangepicker', function (ev, picker) {
        var startDate = picker.startDate;
        var endDate = picker.endDate;
        start = startDate.format('YYYY-MM-DD');
        end = endDate.format('YYYY-MM-DD');
        let data = {};
        data.start = start;
        data.end = end;
        data.user_id = userId;
        ajaxTeamFilterCall(data);
    });

    function getPercentageWiseColor(percentage) {
        if(percentage > 0 && percentage < 25) {
            return secondColor;
        } else if(percentage > 25 && percentage < 50) {
            return secondColor;
        } else if(percentage > 50 && percentage <= 75) {
            return primaryColor;
        } else if(percentage > 75 && percentage < 100) {
            return primaryColor;
        }else if(percentage == 100){
            return primaryColor;
        }else if(percentage == 0){
            return secondColor;
        }
    }

    function ajaxPersonalFilterCall(data) {
        $('#personal_stats_div').html('');
        addBootstrapAjaxLoader($('#personal_stats_div'), 300);
        $.get(sellerDashboardStatsRoutee, data, function (response){
            $('#personal_stats_div').html($(response).find('#personal_stats_div').html());
            removeBootstrapAjaxLoader($('#personal_stats_div'));
            $(".progress-bar-circle").loading();
            $('.progress-bar-value-message-sent').each(function(i, obj) {
                var percentage = obj.value;
                var lineId = obj.getAttribute('data-line-id');
                $(".total-value-message-sent[data-line-id='"+lineId+"']").css('color', getPercentageWiseColor(percentage));
                $(".total-value-message-sent[data-line-id='"+lineId+"']").css('left',percentage+'%');
                $(".custom-badge[data-line-id='"+lineId+"']").css('left',percentage+'%');
            });
        });
    }
    function ajaxTeamFilterCall(data) {
        $('#team_stats_data').html('');
        addBootstrapAjaxLoader($('#team_stats_data'), 300);
        $.get(sellerDashboardStatsTeamRoutee, data, function (response) {
            $('#team_stats_data').html(response);
            removeBootstrapAjaxLoader($('#team_stats_data'));
            $('.progress-bar-value-message-sent').each(function(i, obj) {
                var percentage = obj.value;
                var lineId = obj.getAttribute('data-line-id');
                $(".total-value-message-sent[data-line-id='"+lineId+"']").css('color',getPercentageWiseColor(percentage));
                $(".total-value-message-sent[data-line-id='"+lineId+"']").css('left',percentage+'%');
                $(".custom-badge[data-line-id='"+lineId+"']").css('left',percentage+'%');
            });
        });
    }

    /* Get week and month name by locale */
    function getWeekAndMonthByLocale() {
        let weekName = [];
        let monthName = [];
        if(lang == "en") {
            weekName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            monthName = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        } else {
            weekName = ["dim", "lun", "mar", "mer", "jeu", "ven", "sam"];
            monthName = ["jan", "fév", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc"];
        }
        return [weekName,monthName];
    }
</script>
