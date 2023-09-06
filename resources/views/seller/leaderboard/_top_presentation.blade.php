<div class="leader-thumb-large mb-n3">
    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
        <div class="leader-thumb-avatar" style="background-image: url({{ $top10pAttended[1]['image'] ?? asset((config('app.rankup.company_default_image_file'))) }})">
        </div>
    </div>
</div>
@if(isset($top10pAttended[1]))
    <div class="text-center mb-2">
        <h6>{{$top10pAttended[1]['name']}}</h6>
    </div>
    <div class="leader-content">
        <div class="d-flex align-items-center justify-content-center mb-1">
            <i class="feather-map-pin blue me-2"></i>
            <span>{{$top10pAttended[1]['location']}}</span>
            @if(isset($top10pAttended[1]['uplineName']))
                <i class="feather-user blue me-2"></i>
                <span>{{$top10pAttended[1]['uplineName']}}</span>
            @endif
        </div>
        <div class="d-flex align-items-center justify-content-center">
            <i class="feather-award blue me-2"></i>
            <span>{{$top10pAttended[1]['count']}}</span>
        </div>
    </div>
@endif
<div class="leader-main-list mt-2">
    @if(count($top10pAttended) > 0)
        @foreach($top10pAttended as $key=>$data)
            @if ($loop->first) @continue @endif
            <div class="leader-main-row">
                <span>{{$data['count']}}</span>
                <div class="leader-thumb-small">
                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                        <div class="leader-thumb-avatar" style="background-image: url({{$data['image']}})"></div>
                    </div>
                </div>
                <span class="fs-16 ms-1">{{$data['name']}}</span>
                @if(!is_null($data['uplineName']))
                <div class="ms-auto">
                    <i class="feather-user blue me-2"></i>
                    <span class="fs-14">{{$data['uplineName']}}</span>
                </div>
                @endif
            </div>
        @endforeach
    @endif
</div>