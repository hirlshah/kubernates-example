<div class="container-fluid mb-4">
    <div class="row" style="overflow: overlay;">
        <table class="table table-responsive datatable-basic" id="analytics-table">
            <thead>
                <tr>
                    <th width="5%">{{__('ID')}}</th>
                    <th width="10%">{{__('Contact Name')}}</th>
                    <th width="10%">{{__('Added On')}}</th>
                    <th width="10%">{{__('One on on call')}}</th>
                    <th width="10%">{{__('Added By')}}</th>
                    <th width="10%">{{__('Status')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($contacts as $key => $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ convertDateFormatWithTimezone($contact->created_at, 'Y-m-d H:i:s','d M Y') }}</td>
                    <td>
                        @if($contact->event && empty($contact->event->meeting_date) && empty($contact->event->meeting_time))
                            {{__('One on on call')}}
                        @else
                            {{__('Added manually')}}
                        @endif
                    </td>
                    <td>{{ $contact->user->name }}</td>
                    <td>
                        @foreach($contact->boards as $board)
                            {{$position[$board->pivot->status]}}
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="container-fluid mt-4 mb-5 a-pagination-links">
    {{$contacts->links()}}
</div>
@if($ajaxSearch)
<script>
    $(function (){
        $('#analytics-table').dataTable( {
            "lengthChange": false,
            "searching": false,
            "bPaginate": false,
            "bInfo" : false
        });
    });
</script>
@endif
