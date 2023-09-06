@extends('layouts.seller.index')
@section('content')

<div id="content">
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <i class="feather-youtube me-3"></i>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">{{__('Trainings')}}</li>
                </ol>
            </nav>
            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <div class="custom-badge-tooltip">
                    <span class="custom-badge">
                        <a href="#modal_play_vimeo" class="modal-popup-vimeo"
                        data-url="https://player.vimeo.com/video/753170627?h=601c768277" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                    </span>
                    <div class="tooltiptext"><span>{{__('Click here to view a video about this rank up feature')}}</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <button id="new_video_button" type="button" class="btn btn-blue me-2">+ {{__('New Video')}}</button>
            <button type="button" class="btn btn-blue me-2 new_category_button" style="float:right;" data-model-type="formation">+ {{__('New categorie')}}</button>
            <button id="new_document_video_sub_category_button" type="button" class="btn btn-blue" style="float:right;">+ {{__('New sub categorie')}}</button>
            @include('seller.common._language')
        </div>
    </div>
    <div class="content-body p-0">
        <div class="card px-lg-5 px-2 py-3 shadow-none">
            <div>
                <div>
                    <h5 class="my-lg-5 my-3">{{ $category->name }}</h5>
                    <h6 class="fs-18">{{ __('Categories') }}</h6>
                </div>
                <div class="formation-category mt-sm-3 mb-5 position-relative">
                    <div class="main-category">
                        <ul class="py-3 gap-3" id="draggble-training-categories">
                            @foreach($videoCategories as $videoCategory)
                                @php
                                    $video = $videoCategory->categoryVideos->first();
                                @endphp
                                @if($video)
                                    <li data-page="one" class="category-list cursor-pointer sortable-training-category-card text-nowrap @if($mainVideo->category_id == $videoCategory->id) bg-primary text-white category-active @endif" data-id="{{ $videoCategory->id }}">
                                            <span class="category-name position-relative">{{ $videoCategory->name }} <i class="ms-2 feather-move fs-18"></i>
                                            </span> 
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
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="vstack formations-categories-main-div">
                <div class="row align-items-start gy-4">
                    <div class="col-xl-4 col-lg-6 col-sm-6 vstack">
                        <div class="card h-100">
                            <div class="card-body position-relative">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select
                                            class="form-control border-0 shadow-none text-dark pe-5 ps-0 order_type d-none">
                                            <option value="most_recent" selected>{{ __('Most recent') }}</option>
                                            <option value="oldest">{{ __('Oldest')}}</option>
                                            <option value="asc">{{ __('A-Z')}}</option>
                                            <option value="desc">{{ __('Z-A')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-search position-relative">
                                            <input type="text" class="border-0 form-control rounded-0 shadow-none ps-0 pe-4 w-sm-75 ms-auto search-video" placeholder="Search">
                                            <i class="feather-search position-absolute top-50 translate-middle end-0" style="z-index: 10"></i>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-4 mt-3"></div>
                                </div>
                                <div id="video_data_div">
                                    @include('seller.video.sub_category_video_data')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-6 col-sm-6 sticky-top overlap-20">
                        <div class="card border-0 detail-video">
                            @if(isset($mainVideo))
                                <span id="youtube-player">
                                </span>
                                <div class="card-body">
                                    <h6 class="card-title mb-2">{{ $mainVideo->title }}</h6>
                                    <p>{{ $mainVideo->description }}</p>
                                    <div class="tags">
                                        @if(!empty($mainVideo->category))
                                            <span>{{ $mainVideo->category->name }}</span>
                                        @endif
                                        @if(!empty($mainVideo->subCategory))
                                            <span>{{ $mainVideo->subCategory->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
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
let categoryDragDropRoute = "{{route('category-drag-drop')}}";
let showSubCategoryRoute = "{{ route('seller.category.sub-categories') }}";
var noDataFoundText = "{{__('No Data Found')}}";
var removeCategoryText = "{{__('Are you sure you want to delete this category?')}}";
var removeSubCategoryText = "{{__('Are you sure you want to delete this subcategory?')}}";
var addVideoText = "{{__('Add a video')}}";
var addFormation = "{{__('+ Add a formation')}}";
var editVideo = "{{__('+ Edit video')}}";
var editVideoText = "{{__('Edit Video')}}";
let addRecordUrl = "{{ route('seller.add-video-completed', $mainVideo->id) }}";
let updateCategoryRoute = "{{route('seller.category.update', '')}}";
let showCategoryRoute = "{{route('seller.category.show', '')}}";
let updateSubCategoryRoute = "{{route('seller.sub-category.update', '')}}";
let editCategoryText = "{{ __('Edit category')}}";
let editSubCategoryText = "{{ __('Edit sub category')}}";
let addSubCategoryText = "{{ __('Add Sub Category')}}";
let addCategoryText = "{{ __('Add Category')}}";

</script>
<script src="{{ asset('assets/js/video.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script src="{{ asset('assets/js/category.js?ver=')}}{{env('JS_VERSION')}}"></script>
<script>
$(function() {
    let currentLink = window.location.href;

    function loadData(link, params) {
        if (!params) params = {};
        $.get(link, params, function(response) {
            $('#video_data_div').html(response);
        });
    }

    $('.order_type').on('change', function() {
        var selectedValue = $(this).val();
        var search = $('.search-video').val();
        loadData(currentLink, {
            'order_type': selectedValue,
            'search': search
        });
    });

    $('body').on('click', '.category_filter_btn', function(e) {
        e.preventDefault();
        $(this).addClass('active');
        loadData(currentLink, {
            'category_filter': null
        });
    });

    $('.category_link').on('click', function(e) {
        let url = $(this).data('href');
        window.location.href = url;
    });

    //setup before functions
    var typingTimer; //timer identifier
    var doneTypingInterval = 2000; //time in ms, 5 second for example
    var $input = $('.search-video');

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
<script src="https://www.youtube.com/iframe_api"></script>
<script>
let videoId = "{{ getYoutubeVideoId($mainVideo->video) }}";

    if (videoId != null && videoId != '') {
        var player; // YouTube player object

        // Function called when the YouTube IFrame API is ready
        function onYouTubeIframeAPIReady() {
            
            player = new YT.Player('youtube-player', {
                videoId: videoId,
                width: '100%',
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        // Function called when the player's state changes
        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                VideoPlayed();
            } else if (event.data === YT.PlayerState.PLAYING) {
                // console.log('Video is playing.');
            } else if (event.data === YT.PlayerState.PAUSED) {
                // console.log('Video is paused.');
            } else if (event.data === YT.PlayerState.BUFFERING) {
                // console.log('Video is buffering.');
            } else if (event.data === YT.PlayerState.CUED) {
                // console.log('Video is cued.');
            }
        }

        // Function to record video play in the database
        function VideoPlayed() {
            // Store the entry in the database
            $.ajax({
                type:'GET',
                url: addRecordUrl,
                success:function(data){
                    console.log('video completed');
                },
            });
        }
    }
</script>
@endsection