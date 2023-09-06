@extends('layouts.seller.index')

@section('content')
<div id="content">
    <p class="fw-bold">{{ __('Board - Stats') }}</p>
    <div class="card prospection-video-analytics">
        <div class="card-body p-xl-5 p-4">
            <div class="row gy-lg-5 gy-3 prospection-video-analytics-wrapper pb-4">
                <div class="col-lg-3">
                    <div class="vstack h-100 text-center">
                        <h6 class="fs-18 mb-3 font-circularxx">{{__('Cards added this week')}}</h6>
                        <h1 class="text-primary text-60 fw-800 mt-auto font-avenir">{{ $trelloBoardWeekCount }}</h1>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="vstack h-100 text-center">
                        <h6 class="fs-18 mb-3 font-circularxx">{{__('Columns added this week')}}</h6>
                        <h1 class="text-primary text-60 fw-800 mt-auto font-avenir">{{ $trelloBoardWeekColumnCount }}</h1>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="vstack h-100 text-center">
                        <h6 class="fs-18 mb-3 font-circularxx">{{__('Columns that were edited this week')}}</h6>
                        <h1 class="text-primary text-60 fw-800 mt-auto font-avenir">{{ $trelloBoardWeekUpdateColumnCount }}</h1>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="vstack h-100 text-center">
                        <h6 class="fs-18 mb-3 font-circularxx">{{__('Cards that were edited this weekteam')}}</h6>
                        <h1 class="text-primary text-60 fw-800 mt-auto font-avenir">{{ $trelloBoardWeekUpdateCount }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach($trelloStatuses as $status)
        <p class="fw-bold mt-4">{{ $status->title }}</p>
        <div class="card prospection-video-analytics">
            <div class="card-body p-xl-5 p-4">
                <div class="people-contact pb-5">
                    @foreach($trelloStatuses as $innerStatus)
                        @if($innerStatus->id != $status->id)
                            <div class="people-contact-col">
                                <div class="vstack h-100 text-center">
                                    <h6 class="fs-18 mb-3 font-circularxx text-cepet">{{ ucfirst($innerStatus->title) }}</h6>
                                    <h1 class="text-primary text-60 fw-800 mt-auto font-avenir">{{ $taskMoveCount[$status->id][$innerStatus->id] ?? 0 }}</h1>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
