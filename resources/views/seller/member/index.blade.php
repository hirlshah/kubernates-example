@extends('layouts.seller.index')
@section('head')
    <script src="{{ asset('plugins/gojs/go.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('plugins/gojs/CustomButton.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection
@section('content')
<div id="content">
    @include('seller.common._upgrade_warning')
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-user-plus me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Members')}}</li>
                </ol>
            </nav>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip">
                    <span class="custom-badge">
                        <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753175297?h=5402fd71b1" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                </div>
            @endif
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-5 col-xl-12 mb-4 {{ config('app.rankup.comapny_name') == 'ibuumerang_rankup' ? 'd-none': ''}}">
                    <div class="column-main-title">
                        <h5 class="title d-block">{{__('Referral URL')}}</h5>
                        <span>{{__('Bring more people to your map.')}}</span>
                    </div>
                    <div class="column-box referral">
                        <div class="left text-center pe-4">
                            <div class="text-center d-inline-block profile">
                                <div class="hw-90px bg-cover bg-repeat-n bg-center profile-icon mb-3" style="@if(isset($member->thumbnail_image) && Storage::disk('public')->exists($member->thumbnail_image)) background-image: url({{ App\Classes\Helper\CommonUtil::getUrl($member->thumbnail_image) }}) @else background-image: url({{ asset('assets/images/profile-icon-large.png') }}) @endif"></div>
                                
                                <h6>{{ $member->name }}</h6>
                            </div>
                        </div>
                        <div class="right ps-4">
                            <h6 class="blue">{{__('Referral URL')}}</h6>
                            <div class="refrral-code">
                                <input value="{{route('home')}}/?ref={{$member->referral_code}}" Type="text"></input>
                                <i class="feather-copy blue fs-20" id="copy_{{$member->referral_code}}" data-href="{{route('home')}}/?ref={{$member->referral_code}}"></i>
                                <div class="tooltiptext"><span>{{__('Copy to clipboard')}}</span></div>
                            </div>
                            <button class="btn btn-blue fs-16 w-100 py-2 qr-code">{{__('QR Code')}}</button>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7 col-xl-12 mb-4 vstack">
                    <div class="column-main-title">
                        <h5 class="title">{{__('Members')}}</h5>
                    </div>
                    <div class="column-box referral d-grid mt-auto">
                        <div class="row">
                            <div class="col-xxl-12 col-xl-12 pe-xxl-4 my-4 my-xxl-0">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="feather-search blue fs-24 me-3"></i>
                                    <h6>{{__('Locate Member')}}</h6>
                                </div>
                                <p class="fs-13 font-weight-light mb-4">{{__('Easily find a member by typing his name below.')}}
                                </p>
                                <div class="input-group custom-input-1">
                                    <input type="hidden" value="" id="member_id">
                                    <input type="text" class="form-control" placeholder="{{__('Type the')}}" id="tree_search_users" name="focusText">
                                    <div class="input-group-append ms-4">
                                        <button class="btn btn-custom-1" type="button" id="treeSearchBtn">{{__('Search')}}</button>
                                    </div>
                                </div>
                                <div class="member_list" onscroll="getPeopleList()" id="member_list_div"></div>
                                <span class="text-danger" id="print-error-msg-search-member" style="display: none">{{__('Member not found.')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 mb-4">
                <div class="col-md-6 col-12 d-flex justify-content-between align-items-center">
                    <div class="column-main-title mb-0">
                        <h5 class="title">{{__('All your members')}}</h5>
                    </div>
                </div>
                <div class="col-md-6 col-12 text-md-end my-3 my-md-0">
                    <a href="{{ route('analytics') }}" class="btn btn-outline-black fs-16 py-2"><i class="feather-activity fs-20 me-1"></i>
                    {{__('Check your team stats')}}</a>
                </div>
                <div class="col-12 graph-button-group">
                    <button id="zoomIn" class="btn btn-transparent btn-sm text-primary">{{__('Zoom In')}}</button>
                    <button id="zoomOut" class="btn btn-transparent btn-sm text-primary">{{__('Zoom Out')}}</button>
                    <button id="zoomToFit" class="btn btn-transparent btn-sm text-primary">{{__('Zoom to Fit')}}</button>
                </div>
            </div>
            <div class="row">
                <div id="member-tree-data" class="col-12">
                    <section id="myDiagramDiv"></section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('seller.modal._modal_qr')
@include('seller.modal._modal_add_member')
@include('seller.modal._modal_book_call')
<div class="modal fade" id="userProfile" tabindex="-1" role="dialog" aria-labelledby="userProfile" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 335px">
        <div class="modal-content overflow-visible">
            <div class="modal-body text-center">
                <img class="mw-75 br-50 mb-2" src="{{ asset('assets/images/new-user-profile.png') }}" alt="" />
                <h6 class="mb-2">{{__('Laurie Watson')}}</h6>
                <p class="mb-2 fs-14">{{__('Commercial at GroupInn')}}</p>
                <div class="location d-flex align-items-center justify-content-center mb-3">
                    <i class="feather-map-pin blue me-2"></i>
                    <span class="fs-14">{{ config('app.rankup.location') }}</span>
                </div>
                <a href="" class="btn btn-blue">{{__("See Laurie's profile")}}</a>
            </div>
        </div>
    </div>
</div>
<!-- Modals -->
@endsection
@section('scripts')
    <script>
        let profileRoute =  "{!! route('seller.member.profile', ['id'=>'#id#']) !!}";
        let parentUpdateRoute =  "{!! route('seller.member.update-parent') !!}";
        let addFavouriteRoute =  "{!! route('seller.member.add-favourite') !!}";
        let removeFavouriteRoute =  "{!! route('seller.member.remove-favourite') !!}";
        let memberGetTreeData =  "{!! route('seller.members.tree.data') !!}";
        let searchedPeopleRoute = "{{ route('seller.member.searched-people')}}";
        var currentPeoplePage = 1;
        var typingTimer;                //timer identifier
        var doneTypingInterval = 1000;  //time in ms, 1 second for example
        let viewProfileText = "{{__("View profile of")}}";
    </script>
    <script src="{{ asset('/assets/js/members.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <style>
        #myDiagramDiv * {
            outline: none;
            -webkit-tap-highlight-color: rgba(255, 255, 255, 0); /* mobile webkit */
        }
    </style>
@endsection
