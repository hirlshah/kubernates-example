@if(!auth()->guest())
    <div id="top_banner">
        <div class="upgrade-warning d-none justify-content-between align-items-center">
            <div class="left d-flex align-items-center">
                <i class="feather-youtube fs-20 me-3"></i>
                    {{__('Learn more about this RankUp function')}}.
            </div>
            <div class="right mt-sm-0 mt-3 ms-5">
                <span class="text-decoration-underline">{{__('See the Video')}}</span> <i class="feather-arrow-right ms-3"></i>
            </div>
        </div>
    </div>
@endif
