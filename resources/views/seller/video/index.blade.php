@extends('layouts.seller.index')
@section('content')

<div id="content">
    @include('seller.common._upgrade_warning')
    @if(Session::has('success'))
    <div class="alert alert-success" id="successMessage">
        {{Session::get('success')}}
    </div>
    @endif
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-youtube me-3"></i>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Trainings')}}</li>
                </ol>
            </nav>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip">
                    <span class="custom-badge">
                        <a href="#modal_play_vimeo" class="modal-popup-vimeo"
                            data-url="https://player.vimeo.com/video/753170627?h=601c768277" data-toggle="modal"><i
                                class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="content-header-right d-flex align-items-center ms-auto">
            <button id="new_video_button" type="button" class="btn btn-blue me-2">+ {{__('New Video')}}</button>
            <button type="button" class="btn btn-blue me-2 new_category_button" style="float:right;"
                data-model-type="formation">+ {{__('New categorie')}}</button>
            <button id="new_document_video_sub_category_button" type="button" class="btn btn-blue"
                style="float:right;">+ {{__('New sub categorie')}}</button>
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body p-0">
        <div class="card px-lg-5 px-2 py-3 shadow-none">
            <div>
                <h6 class="fs-18">{{ __('Categories') }}</h6>
                <div class="formation-category mt-sm-3 mb-5 position-relative">
                    <div class="main-category">
                        <ul class="py-3 gap-3" id="draggble-training-categories">
                            @foreach($videoCategories as $videoCategory)
                                @php
                                    $video = $videoCategory->categoryVideos->first();
                                @endphp
                                @if($video)
                                    <li data-page="one" class="category-list cursor-pointer sortable-training-category-card text-nowrap @if($mainVideo->category_id == $videoCategory->id) bg-primary text-white category-active @endif" data-id="{{ $videoCategory->id }}">
                                        <a data-href="{{ route('seller.video-detail', ['id' => $video->id, 'category' => $videoCategory->id]) }}" class="category_link text-nowrap">
                                            <span class="category-name position-relative">{{ $videoCategory->name }}
                                            </span> 
                                        </a>
                                        <div class="nav-horizontal-scroll-onhover-items">
                                            <ul>
                                                <li class="hstack gap-3">
                                                    <a data-href="{{ route('seller.video-detail', ['id' => $video->id, 'category' => $videoCategory->id]) }}" class="category_link text-nowrap">
                                                        {{ $videoCategory->name }}
                                                    </a>
                                                    <i class="feather-edit ms-auto cursor-pointer edit_category_button" data-id="{{ $videoCategory->id }}" data-model-type="formation"></i>
                                                </li>
                                                @foreach($videoCategory->subCategories as $subCategory)
                                                    @php
                                                        $subVideo = $subCategory->subCategoryVideos->first();
                                                    @endphp
                                                    @if($subVideo)
                                                        <li class="hstack gap-3">
                                                            <a data-href="{{ route('seller.video-detail', ['id' => $subVideo->id, 'category' => $subCategory->id]) }}" class="category_link text-nowrap">
                                                                {{ $subCategory->name }}
                                                            </a>
                                                            <i class="feather-edit ms-auto cursor-pointer edit_sub_category_button" data-id="{{ $subCategory->id }}"></i>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </li>
                                @else
                                <div class="text-center w-100">
                                    <h4 class="text-danger">{{ __('No Record Found') }}</h4>
                                </div>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('seller.video._modal_subcategory')
@include('seller.video._modal_create_video')
@include('seller.video._modal_delete_video')
@include('seller.video._modal_video')
@include('seller.category._modal_create_category', ['modalType' => 'formation'])
@endsection
@section('scripts')
<script>
let storeVideoRoute = "{{route('videos.store')}}"
let updateVideoRoute = "{{route('videos.update', '')}}"
let showVideoRoute = "{{route('videos.show', '')}}"
let dragDropRoute = "{{route('video-drag-drop')}}"
let showSubCategoryRoute = "{{ route('seller.category.sub-categories') }}";
var noDataFoundText = "{{__('No Data Found')}}";
var removeCategoryText = "{{__('Are you sure you want to delete this category?')}}";
var removeSubCategoryText = "{{__('Are you sure you want to delete this subcategory?')}}";
var addVideoText = "{{__('Add a video')}}";
var addFormation = "{{__('+ Add a formation')}}";
var editVideo = "{{__('+ Edit video')}}";
var editVideoText = "{{__('Edit Video')}}";
</script>
<script src="{{ asset('assets/js/video.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script src="{{ asset('assets/js/category.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script>
$(function() {
    let currentLink = window.location.href;

    function loadData(link, params) {
        if (!params) params = {};
        $.get(link, params, function(response) {
            $('#video-ajax-list').html(response);
        });
    }

    $(document).on('click', '.a-pagination-links .page-link', function(e) {
        e.preventDefault();
        let link = $(this).attr('href');
        loadData(link);
    });

    $('body').on('click', '.category_filter_btn', function(e) {
        e.preventDefault();
        $(this).addClass('active');
        $('.formations-categories').next('span').removeClass('active');
        loadData(currentLink, {
            'category_filter': null
        });
    });

    $('.formations-categories').on('select2:select', function(e) {
        const value = $(this).val();
        $('.category_filter_btn').removeClass('active');
        $('.formations-categories').next('span').removeClass('active');
        $(this).next('span').addClass('active');
        var selectedOption = $(this).find('option:selected');
        var category_id = selectedOption.data('category');
        window.location.href = `/seller/video/${value}?category=${category_id}`;
    });

    //setup before functions
    var typingTimer; //timer identifier
    var doneTypingInterval = 2000; //time in ms, 5 second for example
    var $input = $('.video_search');

    //on keyup, start the countdown
    $input.on('keyup', function(event) {
        clearTimeout(typingTimer);
        if (event.keyCode === 13) {
            doneTyping();
        } else {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping() {
        loadData(currentLink, {
            'search': $input.val()
        });
    }

    //add active class on category filter
    $(document).on('click', '.delete-video-trash', function() {
        $(".delete-video-trash").removeClass("active");
        $(this).addClass("active");
    });
});
</script>
@endsection