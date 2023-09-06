<div class="col-xxxl-8 col-xl-12 mb-5">
    <div class="leaderboard-col-head-main">
        <i class="feather-award icon"></i>
        <h5 class="col-title">{{__('Awards')}}</h5>
    </div>
    <div class="card">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More presentations')}}/{{__('day')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostPresentationInADay']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostPresentationInADay']['name']}}</h6>
                                @if(!is_null($awards['mostPresentationInADay']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentationInADay']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostPresentationInADay']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentationInADay']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More sales')}}/{{__('day')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">Nadia Alegro</h6>
                                <div class="mt-1">
                                    <i class="feather-user blue me-2"></i>
                                    <span class="fs-12 d-inline-block">Andrea Lawrence</span>
                                </div>
                                <div class="mt-1">
                                    <i class="feather-award blue me-2"></i>
                                    <span class="fs-12 d-inline-block">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('Most messages sent')}}/{{__('day')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostMessageSentInADay']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostMessageSentInADay']['name']}}</h6>
                                @if(!is_null($awards['mostMessageSentInADay']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostMessageSentInADay']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostMessageSentInADay']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostMessageSentInADay']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('The most present')}}/{{__('day')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostPresentInADay']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostPresentInADay']['name']}}</h6>
                                @if(!is_null($awards['mostPresentInADay']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentInADay']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostPresentInADay']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentInADay']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More presentations')}}/{{__('week')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostPresentationInAWeek']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostPresentationInAWeek']['name']}}</h6>
                                @if(!is_null($awards['mostPresentationInAWeek']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentationInAWeek']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostPresentationInAWeek']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentationInAWeek']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More sales')}}/{{__('week')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">Nadia Alegro</h6>
                                <div class="mt-1">
                                    <i class="feather-user blue me-2"></i>
                                    <span class="fs-12 d-inline-block">Andrea Lawrence</span>
                                </div>
                                <div class="mt-1">
                                    <i class="feather-award blue me-2"></i>
                                    <span class="fs-12 d-inline-block">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('Most messages sent')}}/{{__('week')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostMessageSentInAWeek']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostMessageSentInAWeek']['name']}}</h6>
                                @if(!is_null($awards['mostMessageSentInAWeek']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostMessageSentInAWeek']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostMessageSentInAWeek']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostMessageSentInAWeek']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('The most present')}}/{{__('week')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostPresentWeek']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostPresentWeek']['name']}}</h6>
                                @if(!is_null($awards['mostPresentWeek']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentWeek']['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($awards['mostPresentWeek']['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentWeek']['count']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More presentations')}}/{{__('month')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $top10Presentation[1]['image'] ?? asset((config('app.rankup.company_default_image_file'))) }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$top10Presentation[1]['name'] ?? null}}</h6>
                                @if(!isset($top10Presentation) && !is_null(($top10Presentation[1]['uplineName'])))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$top10Presentation[1]['uplineName']}}</span>
                                    </div>
                                @endif
                                @if(isset($top10Presentation[1]['count']))
                                    <div class="mt-1">
                                        <i class="feather-award blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$top10Presentation[1]['count']}}</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('More sales')}}/{{__('month')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ asset((config('app.rankup.company_default_image_file'))) }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">Nadia Alegro</h6>
                                <div class="mt-1">
                                    <i class="feather-user blue me-2"></i>
                                    <span class="fs-12 d-inline-block">Andrea Lawrence</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('Most messages sent')}}/{{__('month')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostMessageSentInMonth']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostMessageSentInMonth']['name']}}</h6>
                                @if(!is_null($awards['mostMessageSentInMonth']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostMessageSentInMonth']['uplineName']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-sm-6 mb-4">
                    <h6 class="leaderboard-col-child-head">{{__('The most present')}}/{{__('month')}}</h6>
                    <div class="card single-leader">
                        <div class="card-body d-inline-flex align-items-center">
                            <div class="left mt-n2 mb-n4">
                                <div class="leader-thumb-small">
                                    <div class="leader-thumb-bg" style="background-image: url({{ asset('assets/images/leader-bg.svg') }})">
                                        <div class="leader-thumb-avatar" style="background-image: url({{ $awards['mostPresentMonth']['image'] }})"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <h6 class="fw-normal">{{$awards['mostPresentMonth']['name']}}</h6>
                                @if(!is_null($awards['mostPresentMonth']['uplineName']))
                                    <div class="mt-1">
                                        <i class="feather-user blue me-2"></i>
                                        <span class="fs-12 d-inline-block">{{$awards['mostPresentMonth']['uplineName']}}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>