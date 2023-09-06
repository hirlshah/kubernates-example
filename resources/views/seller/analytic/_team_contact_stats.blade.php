<div class="row">
    <div class="col-12 mb-4 mt-4">
        <h5>{{__('Contacts')}}</h5>
    </div>
    <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18">{{__('Messages sent')}}</h6>
                    <div class="graph-date-range" data-chart-id="message-sent-graph">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <canvas id="message-sent-graph" style="width:60%;max-width:400px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18">{{__('New Customers')}}</h6>
                    <div class="graph-date-range" data-chart-id="new-customer-graph">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <canvas id="new-customer-graph" style="width:60%;max-width:400px;"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-xl-6 col-12 mx-auto mb-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="graph-title d-flex align-items-center justify-content-between mb-4">
                    <h6 class="fs-18">{{__('New distributors')}}</h6>
                    <div class="graph-date-range" data-chart-id="new-distributor-graph">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                <canvas id="new-distributor-graph" style="width:60%;max-width:400px;"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-4 mb-4">
        <h5 class="">{{__('Your team')}}</h5>
    </div>
    @foreach($members as $member)
        <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6 col-12">
            <div class="card mb-4">
                <div class="card-body d-flex align-items-center px-2" style="min-height:90px;">
                    <a href="{{ route('seller.member.profile', $member->id) }}" class="people-list">
                        <div class="hw-40px flex-none bg-center bg-cover bg-repeat-n me-2"
                        style="background-image: url({{ isset($member->profile_image) ? App\Classes\Helper\CommonUtil::getUrl($member->profile_image) : asset((config('app.rankup.company_default_image_file')))  }})">
                        </div>
                        <h6 class="min-content">{{$member->name}}</h6>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>