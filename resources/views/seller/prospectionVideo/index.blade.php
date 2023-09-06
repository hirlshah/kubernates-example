@extends('layouts.seller.index')
@section('content')
    <div id="content">
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
                <i class="feather-video me-3 text-primary"></i>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">{{__('Prospecting Video')}}</li>
                    </ol>
                </nav>
                <div class="custom-badge-tooltip">
                </div>
            </div>
            <div class="content-header-right d-flex align-items-center ms-auto">
                <button id="new_prospection_video_button" type="button" class="btn btn-blue me-2">+ {{__('New Video')}}</button>
                <button type="button" class="btn btn-blue me-2 new_category_button" style="float:right;" data-model-type="formation">+ {{__('New categorie')}}</button>
                <button id="new_prospection_video_sub_category_button" type="button" class="btn btn-blue" style="float:right;">+ {{__('New sub categorie')}}</button>
            </div>
        </div>
        <div class="content-body p-0">
            <div class="card table-card px-0 py-3">
                <div class="card-header header-elements-inline px-lg-5 px-2 py-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="card-title fs-14 mt-auto mb-auto ms-0">{{__('All videos')}} ({{ $prospectionVideosCount }})</h6>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                                <a class="list-icons-item" data-action="reload"></a>
                                <a class="list-icons-item" data-action="remove"></a>
                            </div>
                        </div>
                        <div class="form-search position-relative">
                            <input type="text" name="prospection_video_search" class="form-control border-top-0 border-start-0 border-end-0 rounded-0 shadow-none px-0  prospection_video_search" placeholder="{{__('Search')}}" />
                            <i class="feather-search position-absolute top-50 translate-middle end-0" style="z-index: 10"></i>
                        </div>
                    </div>
                </div>
                <div class="px-lg-5 px-2 bg-grey-f9f9f9 py-2">
                    <span class="fs-14 fw-500 pt-4 d-block">{{__('Categories')}}</span>
                    <div class="event-categories-btn" id="prospection_video_category_filter">
                        @foreach($categories as $key => $value)
                            <label href="javascript:void(0);" data-id="{{$key}}" class="btn btn-white-black mx-2 position-relative my-2 prospection-video delete-video-trash {{$filteredCategory == $key? 'active' : ''}}">
                                {{ucwords($value)}}
                                <input type="radio" class="d-none prospection_video_filter" name="category_filter" value="{{$key}}">
                                @if(in_array($key, $authCategoryIds) && $value !== __('All contents'))
                                    <a href="javascript:;" data-url="{{ route('seller.category.destroy',$key) }}" class="modal-popup-delete-category category-delete-button">
                                        <i class="feather-trash"></i>
                                    </a>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>
                <div id="prospection_video-ajax-list" class="px-lg-5 px-2">
                    @include('seller.prospectionVideo._prospection_video_pagination')
                </div>
            </div>
        </div>
    </div>
    @include('seller.prospectionVideo._modal_delete_prospection_video')
    @include('seller.prospectionVideo._modal_create_prospection_video')
    @include('seller.prospectionVideo._modal_prospection_video_play')
    @include('seller.prospectionVideo._modal_create_survey')
    @include('seller.category._modal_create_category', ['modalType' => 'formation'])
    @include('seller.prospectionVideo._modal_subcategory')
@endsection
<script>
    let deleteProspectionRoute="{{route('prospection.video.destroy', '')}}";
    let updateProspectionVideoRoute = "{{route('prospection.update', '')}}";
    let showProspectionVideoRoute = "{{route('prospection.show', '')}}";
    var noDataFoundText = "{{__('No Data Found')}}";
    var selectSurveyText = "{{__('Select the survey')}}";
    var updateVideoText = "{{__('Update video')}}";
    var addVideoText = "{{__('Add a video')}}";
    var copyLinkText = "{{__('Copy to clipboard')}}"
    var linkCopyToYourClipboardText = "{{__('The link was copied to the clipboard')}}";
    var videoTitle = "{{__('Please, enter a title of the video you would like others to see.')}}";
    var showSubCategoryRoute = "{{ route('seller.category.sub-categories') }}";
    var removeCategoryText = "{{__('Are you sure you want to delete this category?')}}";
    var removeSubCategoryText = "{{__('Are you sure you want to delete this subcategory?')}}";
    var lang = "{{ app()->getLocale() }}"; 
    var toCompleteText = "{{__('to complete')}}";
    var fileSizeText = "{{__('File is too big, please select an image smaller than 1 MB')}}";
    var defaultProspectionImage = "{{ asset((config('app.rankup.company_thumbnail_path'))) }}";
    var updateSurveyText = "{{__('Update survey')}}";
    var showSurveyQuestionAnswerRoute = "{{ route('prospection.survey.question-answer','')}}";
    var storeSurveyRoute = "{{route('survey.store')}}";
    var updateSurveyRoute = "{{route('prospection.survey.update')}}";
    let addSubCategoryText = "{{ __('Add Sub Category')}}";
    let addCategoryText = "{{ __('Add Category')}}";
</script>
@section('scripts')
    <script src="{{ asset('assets/js/prospection_video.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/category.js?ver=')}}{{env('JS_VERSION')}}"></script>
@endsection