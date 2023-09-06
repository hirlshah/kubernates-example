<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script type="text/javascript">
    (function (w,d) {var loader = function () {var s = d.createElement("script"), tag = d.getElementsByTagName("script")[0]; s.src="https://cdn.iubenda.com/iubenda.js"; tag.parentNode.insertBefore(s,tag);}; if(w.addEventListener){w.addEventListener("load", loader, false);}else if(w.attachEvent){w.attachEvent("onload", loader);}else{w.onload = loader;}})(window, document);
</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::openGraph() !!}
    {!! MetaTag::tag('description') !!}
    {!! MetaTag::tag('image', asset(config('app.rankup.company_logo_path'))) !!}
    <!-- favicon icon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('app.rankup.company_favicon')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('app.rankup.company_favicon')) }}">

    <!-- Scripts -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('app.rankup.company_favicon')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('app.rankup.company_favicon')) }}">

    <!-- Scripts -->
    <script src="{{ asset('assets/js/main/jquery.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/select2_4.0.4.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/jquery_3.2.1.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/jquery-ui.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment_locales.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/moment/moment-timezone-with-data-10-year-range.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/bootstrap.bundle.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/bootstrap/popper.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js?ver=')}}{{env('JS_VERSION') }}" defer></script>
    <script src="{{ asset('assets/js/plugins/loaders/blockui.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/ui/ripple.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/select2.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('plugins/datetimepicker/jquery.datetimepicker.full.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/main/Chart.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/plugins/js.cookie.min.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="{{ asset('assets/js/custom.js?ver=')}}{{env('JS_VERSION')}}"></script>
    <script src="https://cdn.tiny.cloud/1/{{env('TINY_MCE_KEY')}}/tinymce/5/tinymce.min.js" referrerpolicy="origin">
    </script>

    <!-- Slick Js -->
    <script src="{{ asset('assets/js/slick-carousel/slick.min.js?ver=')}}{{env('JS_VERSION') }}"></script>

    <!--Calendar js  -->
    <script src="{{ asset('assets/js/plugins/color-calendar/bundle.min.js?ver=')}}{{env('JS_VERSION')}}"></script>


    <!-- Appzi: Capture Insightful Feedback -->
    <script async src="https://w.appzi.io/w.js?token=xYokn"></script>
    <!-- End Appzi -->

    <!-- Touch punch js  -->
    <script src="{{ asset('assets/js/main/jquery_touch_punch.min.js?ver=')}}{{env('JS_VERSION')}}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/icons/feather-icons-web/feather.css') }}">

    <!-- Styles -->
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/main/fontawesome-all.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset(config('app.rankup.company_css_file')) }}" rel="stylesheet">
    <link href="{{ asset('plugins/datetimepicker/jquery.datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}"  rel="stylesheet"/>

    <!-- Slick css -->
    <link href="{{ asset('assets/css/slick-carousel/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/slick-carousel/slick.css') }}" rel="stylesheet">

    <!--Calendar css  -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/color-calendar/theme-basic.css') }}" />
    <link href="{{ asset('assets/css/main/select2_4.0.4.min.css') }}" rel="stylesheet" />

    <!--Progress Bar css  -->
    <script src="{{ asset('plugins/progress-bar/jQuery-plugin-progressbar.js?ver=')}}{{env('JS_VERSION') }}"></script>
    <link href="{{ asset('plugins/progress-bar/jQuery-plugin-progressbar.css') }}" rel="stylesheet">
    
    <!-- shepherd.js css -->
    <link rel="stylesheet" href="{{ asset('assets/css/shepherd/shepherd.css')}}"/>
    @yield('head')
</head>
    <body class="light">
        <div id="wrapper-dashboard">
            @include('layouts.seller.sidebar')
            <main>
                @yield('content')
            </main>
        </div>
        <!-- Delete modal -->
        <div id="modal_delete_warning" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h6 class="modal-title text-white fs-24">{{__('Warning!!')}}</h6>
                        <a href="#" class="close modal-close-btn text-white fs-24" data-dismiss="modal">&times;</a>
                    </div>

                    <div class="modal-body p-3">
                        <h6 class="font-weight-semibold modal_title">{{__('Are you sure you want to delete this record ?')}}</h6>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-black modal-close-btn" data-dismiss="modal">{{__('Close')}}</button>
                        <button type="button" class="btn bg-danger modal-delete-confirm text-white">{{__('Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /delete modal -->

        <!-- View Modal -->
        <div id="modal_for_view" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-teal-300 view-table-bg">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Details')}}</h5>
                        <button type="button" class="close modal-close-btn-show" data-dismiss="modal"
                        id="header_close_button_show">&times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table_for_view">
                            <tbody id="modal-table-data">

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-black modal-close-btn-show" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <style type="text/css">
            .modal-close-btn-show{
                background: transparent;
                cursor: pointer;
            }
            #header_close_button_show{
                border: none;
                font-size : 25px;
            }
        </style>
        <!-- /view modal -->
        @include('seller.modal._modal_add_member')
        @include('seller.modal._modal_video')
        @include('seller.modal._modal_vimeo')
        <script>
        var lang = "{{ app()->getLocale() }}";
        var clearText = "{{__('Clear')}}";
        var applyText = "{{__('Apply')}}";
        let primaryColor = "{{ config('app.rankup.company_primary_color') }}";
        let secondColor = "{{ config('app.rankup.company_second_color') }}";
        let showBannerVideoRoute = "{{route('show.banner.video')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).ready(function() {
                $('#sidebarCollapse').on('click', function() {
                    $('#wrapper-dashboard').toggleClass('active');
                });
            });
            $(function (){
                if($('*').hasClass('select2')) {
                    $('.select2').select2();
                }
                if($('.datetimepicker').length){
                    $( ".datetimepicker" ).datetimepicker({
                        dateFormat: 'd/m/Y',
                        changeMonth: true,
                        changeYear: true
                    });
                }
            });

            /**
             * Vimeo Modal : Start
             */
            if($('.modal-popup-vimeo').length) {
              $('body').on('click', '.modal-popup-vimeo', function(e) {
                e.preventDefault();
                var video = $(this).data('url');
                $('#cartoonVideo').show();
                $('#cartoonVideo').attr('src',video);
              });
            }

            if($('.vimeo-modal-close').length) {
              $('body').on('click', '.vimeo-modal-close', function () {
                $('#cartoonVideo').attr('src', '');
                $('#cartoonVideo').hide();
              });
            }

            /**
             * Vimeo Modal : End
             */

             /**
             * Close video play outside modal click
             */
             $('.modal').on('hidden.bs.modal', function () {
                $('.close_vimeo_play_video').each(function() {
                    $(".close_vimeo_play_video iframe").attr("src", $(".close_vimeo_play_video iframe").attr("src"));
                });
            });

            /**
             * Change language 
             */
            $('#change_language').on('change', '', function (event) {
                event.preventDefault();
                let value = this.value;
                let langUrl = "{{url('language')}}/"+value;
                $.ajax({
                    type:'GET',
                    url: langUrl,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success:function(data){
                        if(data.success){
                            window.location.reload();
                        }else{
                            printErrorMsg(data.errors);
                        }
                    },
                    error:function (data){
                        
                    },
                });
            });
            if($("div").hasClass("formation-category")) {
                (function (window) {
                let HorizontalNav = function (selector) {
                    this.nav = window.document.querySelector(selector);
                    this.options = {
                    items: { attribute: "data-page" },
                    submenu: {
                        selector: ".nav-horizontal-scroll-onhover-items",
                        show: "nav-active"
                    },
                    };
                    this.init();
                    return this;
                };

                HorizontalNav.prototype = {
                    active: undefined,
                    mouseLeaveEvent: undefined,
                    init: function () {
                    let self = this;
                    this.mouseLeaveEvent = self.mouseLeave.bind(self);
                    if ("ontouchstart" in window) {
                        this.nav.addEventListener("touchstart", self.hoverEvent.bind(self));
                    } else {
                        this.nav.addEventListener("mouseover", self.hoverEvent.bind(self));
                    }
                    this.nav.addEventListener("click", function (evt) {
                        evt.preventDefault();
                        evt.stopPropagation();
                        let item = self.item(evt.target);
                        self.showPage(item);
                    });
                    },
                    item: function (target) {
                    let self = this;
                    if (self.options.items.attribute) {
                        while (
                        target.getAttribute(self.options.items.attribute) == undefined ||
                        target == self.nav
                        )
                        target = target.parentNode;
                    }
                    return target == self.nav ? undefined : target;
                    },
                    hideSubNavs: function () {
                    let self = this;
                    (
                        this.nav.querySelectorAll("." + self.options.submenu.show) || []
                    ).forEach(function (n) {
                        n.classList.remove(self.options.submenu.show);
                    });
                    },
                    showSubNav: function (item) {
                    let self = this;
                    let submenu = item.querySelector(self.options.submenu.selector);
                    if (submenu) {
                        submenu.classList.add(self.options.submenu.show);
                        submenu.style.top = item.scrollHeight + 10 + "px";
                        // submenu.style.left =
                        // item.offsetLeft - item.parentNode.scrollLeft + "px";
                        submenu.removeEventListener("mouseleave", self.mouseLeaveEvent);
                        submenu.addEventListener("mouseleave", self.mouseLeaveEvent);
                    }
                    },
                    hoverEvent: function (evt) {
                    let self = this;
                    let item = self.item(evt.target);
                    if (self.active) {
                        if (self.active == item) return;
                        else if (self.active.contains(item)) return;
                        else self.hideSubNavs();
                    }
                    self.active = item;
                    self.showSubNav(item);
                    },
                    mouseLeave: function (evt) {
                    let self = this;
                    if (self.active)
                        self.active
                        .querySelector(self.options.submenu.selector)
                        .classList.remove(self.options.submenu.show);
                        self.active = undefined;
                    }
                };
                new HorizontalNav(".main-category");
                })(window);
                    $(function(){
                        $('.category-list').on("click", function (){
                            $(this).children('.nav-horizontal-scroll-onhover-items').addClass("nav-active")
                        });
                    });
                    $(function(){
                        $("body").on("click", function (){
                            $(".nav-horizontal-scroll-onhover-items").removeClass("nav-active")
                        });
                    });
                    $(function(){
                        $('.category-list').mouseenter(function(){
                            $(this).children('.nav-horizontal-scroll-onhover-items').addClass("nav-active")
                        });
                    });
                    $(function(){
                        $('.category-list').mouseleave(function(){
                            $(".nav-horizontal-scroll-onhover-items").removeClass("nav-active")
                        });
                    });
                }
            </script>
        <!-- shepherd js -->
        <script src="{{ asset('assets/js/shepherd/shepherd.js')}}"></script>
        @yield('scripts')
    </body>
</html>
