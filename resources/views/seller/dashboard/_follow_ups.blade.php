<div id="follow-ups-render" class="card p-0 mb-xxl-4 mb-5">
    <div class="follow-up-main p-3">
        <h6 class="mb-2 grey-c0c0c0">{{__('Filter by')}}</h6>
        <div class="filter-btns mx-n1 mb-3">
            <a href="javascript:;" data-filter-type="all" class="follow-up-filter px-3 btn btn-white-black my-1 mx-1 {{$filterType == 'all'?'active':''}}">{{__('All')}}</a>
            <a href="javascript:;" data-filter-type="yours" class="follow-up-filter px-3 btn btn-white-black my-1 mx-1 {{$filterType == 'yours'?'active':''}}">{{__('Yours')}}</a>
            <a href="javascript:;" data-filter-type="team" class="follow-up-filter px-3 btn btn-white-black my-1 mx-1 {{$filterType == 'team'?'active':''}}">{{__('My Team')}}</a>
            <a href="javascript:;" class="px-3 btn btn-white-black my-1 mx-1 d-inline-flex {{in_array($filterType, $teamIds)?'active':''}}" id="teamMember" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('Team Member')}} <i class="feather-chevron-down blue fs-20 mt-1 ms-1"></i></a>
            <a href="javascript:;" data-filter-type="past" class="follow-up-filter px-3 btn btn-white-black my-1 mx-1 {{$filterType == 'past'?'active':''}}">{{__('Past')}}</a>
            <div id="follow-up-member-filter" class="dropdown-menu custom-dropdown" aria-labelledby="teamMember">
                <ul>
                    <li class="search">
                        <div class="input-group">
                            <input type="text" onkeyup="dropdownFilterFunction(this, '#follow-up-member-filter')" class="form-control ps-0" placeholder="{{__('User name')}}" aria-label="Search" aria-describedby="basic-addon1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="feather-search"></i></span>
                            </div>
                        </div>
                    </li>
                    @foreach($teams as $teamMember)
                        <li class="follow-up-filter member cursor-pointer {{$filterType == $teamMember->id?'active':''}}" data-filter-type="{{$teamMember->id}}">
                            <div class="image hw-60px bg-cover bg-center bg-repeat-n" style="background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($teamMember->profile_image) }})"></div>
                            <p>{{$teamMember->name}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if(count($followUpFuture) && $filterType != 'past')
            <div id="followup-less-days">
                @foreach($followUpFuture as $followUp)
                    <div class="card follow-up mb-3">
                        @if(!is_null($followUp->contact->link))
                            <a class="position-absolute bg-white" style="right: 1rem; top: 1.5rem;" href="{{ $followUp->contact->link }}" title="{{ $followUp->contact->link }}" target="_blank"><i class="feather-globe fs-20"></i></a>
                        @endif
                        <div class="card-body pt-lg-3 pt-2">
                            <div class="row">
                                <div class="col-xxxl-9">
                                    <div class="row gy-2">
                                        <div class="col-sm-6 border-right">
                                            <div class="follow-up-col text-start">
                                                <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $followUp->contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($followUp->contact->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                </div>
                                                <div class="right">
                                                    <p class="mb-2">{{$followUp->contact->name}}</p>
                                                    <p class="mb-0 grey-c0c0c0 fs-14">
                                                        {{convertDateFormatWithTimezone($followUp->follow_up_date,'Y-m-d H:i:s','d/M/Y','CRM-TO-FRONT')}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="follow-up-col text-start">
                                                <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $followUp->user && $followUp->user->profile_image ? App\Classes\Helper\CommonUtil::getUrl($followUp->user->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                <div class="right">
                                                    <p class="mb-2">@if(!empty($followUp->user)) {{$followUp->user->name}} @endif</p>
                                                    <p class="mb-0 grey-c0c0c0 fs-14">@if($followUp->user) {{$followUp->user->getUplineName()??'--'}} @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($followUp->contact)
                                    <div class="col-12">
                                        @if(!is_null($followUp->contact->link))
                                            {!! turnUrlIntoHyperlink($followUp->contact->link) !!}
                                        @endif
                                        {!! turnUrlIntoHyperlink($followUp->contact->message) !!}
                                    </div>
                                @endif
                                {{-- <div class="col-xxxl-3 col-lg-12 col-md-12 d-flex align-items-center">--}}
                                    {{-- <a href="javascript:void(0)" class="btn btn-outline-blue px-2 my-2 w-100">{{__('Send a
                                        reminder')}}</a>--}}
                                    {{-- </div>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($followUpNormal->lastPage() > $pagePartial)
                    <div class="text-center mb-3">
                        <a href="javascript:;" id="view-more-less-days" data-page="{{$pagePartial}}" class="btn btn-outline-black fs-16 py-2 legitRipple mx-auto me-auto">{{__('View More')}}</a>
                    </div>
                @endif
            </div>
        @endif
        @if(count($followUpPast) && in_array($filterType,['all','past']))
            <div id="followup-past">
                @foreach($followUpPast as $followUp)
                    <div class="card follow-up mb-3 bg-danger text-white">
                        <div class="card-body">
                            @if(!is_null($followUp->contact->link))
                                <a class="position-absolute bg-white" style="right: 1rem; top: 1.5rem;" href="{{ $followUp->contact->link }}" title="{{ $followUp->contact->link }}" target="_blank">
                                    <i class="feather-globe fs-20"></i>
                                </a>
                            @endif
                            <div class="row">
                                <div class="col-xxxl-9">
                                    <div class="row gy-2">
                                        <div class="col-sm-6 border-right">
                                            <div class="follow-up-col text-start">
                                                <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $followUp->contact->profile_image ? App\Classes\Helper\CommonUtil::getUrl($followUp->contact->profile_image) : asset('assets/images/user-icon2.png') }})">
                                                </div>
                                                <div class="right">
                                                    <p class="mb-2">{{$followUp->contact->name}}</p>
                                                    <p class="mb-0 grey-c0c0c0 fs-14">
                                                        {{convertDateFormatWithTimezone($followUp->follow_up_date,'Y-m-d H:i:s','d/M/Y','CRM-TO-FRONT')}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="follow-up-col text-start">
                                                <div class="hw-60px bg-cover bg-center me-3" style="background-image: url({{ $followUp->user && $followUp->user->profile_image ? App\Classes\Helper\CommonUtil::getUrl($followUp->user->profile_image) : asset('assets/images/user-icon2.png') }})"></div>
                                                <div class="right">
                                                    <p class="mb-2">@if(!empty($followUp->user) && !empty($followUp->user->name)) {{$followUp->user->name}} @endif</p>
                                                    <p class="mb-0 grey-c0c0c0 fs-14">
                                                        @if(!empty($followUp->user))  
                                                            {{$followUp->user->getUplineName()??'--'}} 
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($followUp->contact)
                                    <div class="col-12">
                                        @if(!is_null($followUp->contact->link))
                                            {!! turnUrlIntoHyperlink($followUp->contact->link) !!}
                                        @endif
                                        @if(str_contains($followUp->contact->message, 'https') || str_contains($followUp->contact->message, 'www'))
                                            @foreach(explode(' ',$followUp->contact->message) as $row)
                                                @if(str_contains($row, 'https')  || str_contains($row, 'www'))
                                                    <u> {!! turnUrlIntoHyperlink($row) !!} </u>
                                                @else
                                                    {{ $row }}
                                                @endif
                                            @endforeach
                                        @else
                                            {!! turnUrlIntoHyperlink($followUp->contact->message) !!}
                                        @endif
                                    </div>
                                @endif
                                {{-- <div class="col-xxxl-3 d-flex align-items-center">--}}
                                    {{-- <a href="javascript:void(0)" class="btn btn-outline-blue px-2 my-2 w-100">{{__('Send a reminder')}}</a>--}}
                                {{-- </div>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
                @if($followUpPast->lastPage() > $pagePartial)
                    <div class="text-center">
                        <a href="javascript:;" id="view-more-less-days" data-page="{{$pagePartial}}" class="btn btn-outline-black fs-16 py-2 legitRipple mx-auto me-auto">{{__('View More')}}</a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>