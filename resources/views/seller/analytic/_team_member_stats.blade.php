<div class="row">
    <div class="col-xxl-5 col-12 mb-4">
        <h5 class="mb-4">{{__('Team Stats')}}</h5>    
        <div class="card">
            <div class="card py-4 m-4 px-4 mb-4">
                <div class="y-contacts-row">
                    <div class="contact-multiple-images me-3 mb-0">
                        @foreach($memberImages as $image)
                            @continue(empty($image))
                            @break($loop->iteration == 7)
                            <div class="single-contact" style="background-image: url({{ asset(" storage/".$image) }})"></div>
                        @endforeach
                    </div>
                    <div class="right">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5>{{__('Team Stats')}}</h5>
                        </div>
                        <p class="mb-0">{{__('These are your team stats')}}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <h6 class="mb-3">{{__('General')}}</h6>
                <ul class="list-name-value">
                    <li>
                        <span class="left">{{__('Connex. of the day')}}</span>
                        <span class="right">{{$counts['memberDaily']}}</span>
                    </li>
                    <li>
                        <span class="left">{{__('Connex. of the week')}}</span>
                        <span class="right">{{$counts['memberWeekly']}}</span>
                    </li>
                    <li>
                        <span class="left">{{__('Connex. of the month')}}</span>
                        <span class="right">{{$counts['memberMonthly']}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xxl-7 col-12 mb-4">
        <div class="card mt-8">
            <div class="card-body p-4">
                <ul class="list-name-value">
                    <li>
                        <span class="left"><i class="feather-user blue me-2"></i>{{__('Users')}}</span>
                        <span class="right">{{$counts['totalUsers']}}</span>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">{{__('Age')}}</h6>
                        <canvas id="gender-data" style="width:100%;max-width:400px;"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">{{__('Gender')}}</h6>
                        <canvas id="age-data" style="width:100%;max-width:400px;"></canvas>
                    </div>
                    <div class="col-md-12 mt-2">
                        <h6 class="mb-3">{{__('Localisation')}}</h6>
                        <ul class="list-name-value">
                            @foreach($counts['city'] as $c)
                                <li>
                                    <span class="left"><i class="feather-user blue me-2"></i>{{$c['city']}}</span>
                                    <span class="right">{{$c['count']}}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>