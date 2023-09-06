@extends('layouts.seller.index')
@section('title', 'Documents')
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
                <i class="feather-clipboard me-3"></i>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">{{__('Documents')}}</li>
                    </ol>
                </nav>
                @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                    <div class="custom-badge-tooltip">
                        <span class="custom-badge">   
                            <a href="#modal_play_vimeo" class="modal-popup-vimeo" data-url="https://player.vimeo.com/video/753170627?h=601c768277" data-toggle="modal"><i class="feather-help-circle mx-2"></i></a>
                        </span>
                        <div class="tooltiptext"><span>{{__('Click here to view a video about this')}} @if(app()->getLocale() == "en" ) {{config('app.rankup.comapny_name')}} {{ __('feature')}} @else {{ __('feature')}} {{config('app.rankup.comapny_name')}} @endif</span></div>
                    </div>
                @endif
            </div>
            <div class="content-header-right d-flex align-items-center ms-auto">
                <button id="new_document_button" type="button" class="btn btn-blue me-2">+ {{__('New Document')}}</button>
                <button type="button" class="btn btn-blue me-2 new_category_button" style="float:right;" data-model-type="document">+ {{__('New categorie')}}</button>
                <button id="new_document_sub_category_button" type="button" class="btn btn-blue" style="float:right;">+ {{__('New sub categorie')}}</button>
                @include('seller.common._language')
            </div>
        </div>
        <div class="content-body p-0">
            <div class="card table-card px-lg-5 px-2 py-3">
                <div>
                    <div class="card-header header-elements-inline px-0 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="card-title fs-18 mt-auto mb-auto">{{__('Recent documents')}}</h6>
                            <div class="header-elements">
                                <div class="list-icons">
                                    <a class="list-icons-item" data-action="collapse"></a>
                                    <a class="list-icons-item" data-action="reload"></a>
                                    <a class="list-icons-item" data-action="remove"></a>
                                </div>
                            </div>
                            <div>
                                <input type="text" name="document_search" class="form-control document_search" placeholder="{{__('Search')}}" />
                            </div>
                        </div>
                    </div>
                </div>
                <span class="fs-12 grey-727272">{{__('Categories')}}</span>
                <div class="event-categories-btn " id="document-category-filter">
                    @foreach($categories as $key=>$value)
                        <label data-id="{{$key}}" class="btn btn-white-black me-3 position-relative my-2 delete-document-trash {{$filteredCategory == $key? 'active' : ''}}">
                            {{$value}}
                            <input type="radio" class="d-none category-filter" name="category_filter" value="{{$key}}">
                            @if(in_array($key, $authCategoryIds) && $value !== __('All contents'))
                                <a href="javascript:;" data-url="{{ url( '/seller/categories/' . $key ) }}" class="modal-popup-delete-category category-delete-button">
                                    <i class="feather-trash"></i>
                                </a>
                            @endif
                        </label>
                    @endforeach
                </div>
                <div id="document-ajax-list">
                    @include('seller.documents._document_pagination')
                </div>
            </div>
        </div>
    </div>
    @include('seller.documents._modal_subcategory')
    @include('seller.documents._modal_create_document')
    @include('seller.documents._modal_delete_document')
    @include('seller.modal._modal_create_tag')
    @include('seller.category._modal_create_category', ['modalType' => 'document'])
@endsection
@section('scripts')
    <script>
        let updateRoute = "{{route('documents.update','')}}"
        let createRoute = "{{route('documents.store')}}"
        let showRoute = "{{route('documents.show','')}}"
        let imageUrl = "{{ asset('') }}"
        let showSubCategoryRoute = "{{ route('seller.category.sub-categories') }}";
        var noDataFoundText = "{{__('No Data Found')}}";
        var removeCategoryText = "{{__('Are you sure you want to delete this category?')}}";
        var removeSubCategoryText = "{{__('Are you sure you want to delete this subcategory?')}}";
        var addDocumentText = "{{__('Add a document')}}";
        var editDocumentText = "{{__('Edit document')}}";
        var companyDefaultImage = "{{ asset(config('app.rankup.company_default_image_file')) }}";
        let addSubCategoryText = "{{ __('Add Sub Category')}}";
        let addCategoryText = "{{ __('Add Category')}}";
    </script>
    <script src="{{ asset('assets/js/document.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/category.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script>
        //Documents list and category filter
        $(function (){
            let currentLink = window.location.href;
            function loadData(link, params){
                if(!params) params = {};
                $.get(link, params, function (response){
                    $('#document-ajax-list').html(response);
                });
            }
            $(document).on( 'click', '.a-pagination-links .page-link',function (e){
                e.preventDefault();
                let link = $(this).attr('href');
                loadData(link);
            });

            //setup before functions
            var typingTimer;                //timer identifier
            var doneTypingInterval = 2000;  //time in ms, 5 second for example
            var $input = $('.document_search');

            //on keyup, start the countdown
            $input.on('keyup', function (event) {
                clearTimeout(typingTimer);
                if (event.keyCode === 13) {
                    doneTyping ();
                }else{
                    typingTimer = setTimeout(doneTyping, doneTypingInterval);
                }
            });

            //on keydown, clear the countdown
            $input.on('keydown', function () {
            clearTimeout(typingTimer);
            });

            //user is "finished typing," do something
            function doneTyping () {
                loadData(currentLink, {'search': $input.val()});
            }

            $(document).on('click', '#document-category-filter > label:not(.active)', function (){
                let filter = $(this).find('.category-filter').val();
                loadData(currentLink, {'category_filter': filter});
            });

            $(document).on('click', '#document-sub-category-filter', function (){
                let subcategoryfilter = $(this).find('.sub_category-filter').val();
                loadData(currentLink, {'sub_category_filter': subcategoryfilter});
            });

            //add active class on category filter
            $(document).on('click','.delete-document-trash',function(){
                $(".delete-document-trash").removeClass("active");
                $(this).addClass("active");
            });

         });
    </script>
    <script>
        //Drag and drop file
        document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (e) => {
            inputElement.click();
        });

        inputElement.addEventListener("change", (e) => {
            if (inputElement.files.length) {
            updateThumbnail(dropZoneElement, inputElement.files[0]);
            }
        });

        dropZoneElement.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZoneElement.classList.add("drop-zone--over");
        });

        ["dragleave", "dragend"].forEach((type) => {
            dropZoneElement.addEventListener(type, (e) => {
            dropZoneElement.classList.remove("drop-zone--over");
            });
        });

        dropZoneElement.addEventListener("drop", (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
            inputElement.files = e.dataTransfer.files;
            updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove("drop-zone--over");
        });
        });

        /**
         * Updates the thumbnail on a drop zone element.
         *
         * @param {HTMLElement} dropZoneElement
         * @param {File} file
         */
        function updateThumbnail(dropZoneElement, file) {
            let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

            // First time - remove the prompt
            if (dropZoneElement.querySelector(".drop-zone__prompt")) {
                dropZoneElement.querySelector(".drop-zone__prompt").remove();
            }

            // First time - there is no thumbnail element, so lets create it
            if (!thumbnailElement) {
                thumbnailElement = document.createElement("div");
                thumbnailElement.classList.add("drop-zone__thumb");
                dropZoneElement.appendChild(thumbnailElement);
            }

            thumbnailElement.dataset.label = file.name;

            // Show thumbnail for image files

            if (file.type.startsWith("image/")) {
                const reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = () => {
                thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
                thumbnailElement.style.backgroundSize = null;
                thumbnailElement.style.backgroundPosition = null;
                thumbnailElement.style.backgroundRepeat = null;
                };
            } else {
                let extName = file.name.split('.').pop();
                thumbnailElement.style.backgroundImage = `url('/images/mime/${extName}.svg')`;
                thumbnailElement.style.backgroundSize = '50%';
                thumbnailElement.style.backgroundPosition = 'center';
                thumbnailElement.style.backgroundRepeat = 'no-repeat';
            }
        }
    </script>
@endsection
