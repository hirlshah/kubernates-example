<div class="table-responsive px-4 video-table shadow-none">
    <table class="table datatable-basic users-table font-circularxx" id="data-table">
        <thead>
        <tr>
            <th>{{__('Name')}}</th>
            <th>{{__('Full Video')}}</th>
            <th>{{__('Email')}}</th>
            <th>{{__('Phone number')}}</th>
            <th>{{__('Time spent')}}</th>
            <th>{{__('Date')}}</th>
        </tr>
        </thead>
        @if(!empty($videoVisitors))
            @foreach($videoVisitors as $videoVisitor)
                <tr>
                    <td class="text-323232">{{ $videoVisitor['name'] }}</td>
                    @if(!empty($videoVisitor['start_date']) && !empty($videoVisitor['end_date']))
                        <td class="text-323232">{{__('Yes')}}</td>
                    @else
                        <td class="text-323232">{{__('No')}}</td>
                    @endif
                    <td class="text-323232">{{ $videoVisitor['email'] }}</td>
                    @if(!empty($videoVisitor['phone']))
                        <td class="text-323232">{{ $videoVisitor['phone'] }}</td>
                    @else
                        <td class="text-323232">0</td>
                    @endif
                    <td class="text-323232">
                    @if(isset($videoVisitor['time']) && !empty($videoVisitor['time']))
                        @if(isset($videoVisitor['start_date']) && !empty($videoVisitor['start_date']) && isset($videoVisitor['end_date']) && !empty($videoVisitor['end_date']) && $videoVisitor['time'] == '0:00')
                            @php($startDate  =  Carbon\Carbon::parse($videoVisitor['start_date']))
                            @php($endDate  =  Carbon\Carbon::parse($videoVisitor['end_date']))
                            {{ gmdate('i:s', $endDate->diffInSeconds($startDate)) }}
                        @else
                            {{ $videoVisitor['time'] }}
                        @endif
                    @else
                        0:00
                    @endif
                    </td>
                    <td class="text-323232">@if(isset($videoVisitor['end_date']) && !empty($videoVisitor['end_date'])) {{ convertDateFormatWithTimezone($videoVisitor['end_date'], 'Y-m-d H:i:s','d M') }} @else {{ convertDateFormatWithTimezone($videoVisitor['start_date'], 'Y-m-d H:i:s','d M') }} @endif</td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
@if(!empty($videoVisitors))
    <div class="container-fluid mt-4 mb-5 a-prospection-pagination-links" data-user="0">
        {{ $videoVisitorsData->links() }}
    </div>
@endif
