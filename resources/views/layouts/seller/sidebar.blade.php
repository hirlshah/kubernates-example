<nav id="sidebar" class="d-flex flex-column flex-shrink-0 pb-0">
    <div class="sidebar-header d-flex">
        <a href="{{ route('seller-dashboard') }}">
            <img width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
        </a>
        <select class="selectpicker ms-3 text-light" id="change_language" data-width="fit">
            <option value="en" @if(Config::get('app.locale')=='en') selected @endif>{{__('EN')}}</option>
            <option value="fr" @if(Config::get('app.locale')=='fr') selected @endif>{{__('FR')}}</option>
            <option value="es" @if(Config::get('app.locale')=='es') selected @endif>{{__('ES')}}</option>
            <option value="cs" @if(Config::get('app.locale')=='cs') selected @endif>{{__('CZ')}}</option>
        </select>
    </div>

    <ul class="list-unstyled components nav nav-pills flex-column mb-auto">
        @if(auth()->user()->hasRole('Admin'))
            @can('admin-dashboard')
            <li class="{{ (Request::is('admin/dashboard') ? 'active' : '') }}">
                <a href="{{ url('admin/dashboard') }}"><span class="menu-icon"><i class="feather-bar-chart"></i></span>
                    {{__('Dashboard')}}</a>
            </li>
            @endcan
            @can('user-list')
            <li class="{{ (Request::is('admin/users') ? 'active' : '') }}">
                <a href="{{ url('admin/users') }}"><span class="menu-icon"><i class="feather-user"></i></span>
                    {{__('Users')}}</a>
            </li>
            <li class="{{ (Request::is('admin/unverified-users') ? 'active' : '') }}">
                <a href="{{ url('admin/unverified-users') }}"><span class="menu-icon"><i class="feather-user"></i></span>
                    {{__('unverified Users')}}</a>
            </li>
            <li class="{{ (Request::is('admin/helps') ? 'active' : '') }}">
                <a href="{{ url('admin/helps') }}"><span class="menu-icon"><i class="feather-help-circle"></i></span>
                    {{__('Help')}}</a>
            </li>
            @endcan
            {{-- <li class="{{ (Request::is('admin/coupons') ? 'active' : '') }}">
                <a href="{{ url('admin/coupons') }}"><span class="menu-icon"><i
                            class="feather-credit-card"></i></span>{{__('Coupons')}}</a>
            </li>
            <li class="{{ (Request::is('admin/pages') ? 'active' : '') }}">
                <a href="{{ url('admin/pages') }}"><span class="menu-icon"><i
                            class="feather-layout"></i></span>{{__('Pages')}}</a>
            </li> --}}
        @elseif(auth()->user()->hasRole('Seller'))
            @php
                $trolloNotExist = \App\Models\ModuleConfig::checkForModuleNotExist('Board');
                $trainingNotExist = \App\Models\ModuleConfig::checkForModuleNotExist('Trainings');
                $documentNotExist = \App\Models\ModuleConfig::checkForModuleNotExist('Documents');
                $prospectionNotExist = \App\Models\ModuleConfig::checkForModuleNotExist('Prospection');
            @endphp
            @can('seller-dashboard')
                <li class="{{ (Request::is('seller/dashboard') ? 'active' : '') }}">
                    <a href="{{ url('seller/dashboard') }}"><span class="menu-icon"><i class="feather-bar-chart"></i></span>
                        {{__('Dashboard')}}</a>
                </li>
            @endcan
            @can('seller-contacts-board')
                <li class="{{ (Request::is('seller/contacts') ? 'active' : '') }}">
                    <a href="{{ url('seller/contacts') }}"><span class="menu-icon"><i class="feather-user-plus"></i></span>
                        {{__('Contacts')}}</a>
                </li>
            @endcan
            @if(!$trolloNotExist)
                <li class="{{ Route::currentRouteName() == 'seller-task-board' || Route::currentRouteName() == 'seller.trello-boards' || Route::currentRouteName() == 'seller-user-task-board-stats' ? 'active' : '' }} tour-btn" id="trello-board-option">
                    <a href="{{ route('seller.trello-boards') }}"><span class="menu-icon"><i class="feather-sidebar"></i></span>
                        {{__('Task Board')}}
                        {!! $global_pro_badge !!}
                    </a>
                </li>
            @endif
            @can('seller-events')
                <li class="{{ (Request::is('seller/events') ? 'active' : '') }}">
                    <a href="{{ url('seller/events') }}"><span class="menu-icon"><i class="feather-tv"></i></span>
                        {{__('Events')}} </a>
                </li>
            @endcan
            @if(!$trainingNotExist)
                <li class="{{ Route::currentRouteName() == 'seller.video-detail' ? 'active' : '' }}">
                    <a href="{{ url('seller/videos') }}"><span class="menu-icon"><i class="feather-youtube"></i></span>
                        {{__('Trainings')}}</a>
                </li>
            @endif
            @if(!$documentNotExist)
                <li class="{{ (Request::is('seller/documents') ? 'active' : '') }}">
                    <a href="{{ url('seller/documents') }}"><span class="menu-icon"><i class="feather-clipboard"></i></span>
                        {{__('Documents')}}</a>
                </li>
            @endif

            @if(!$prospectionNotExist)
                <li class="{{ (Request::is('seller/prospection') ? 'active' : '') }}">
                    <a href="{{ url('seller/prospection') }}" class="d-flex" style="align-items: baseline;"><span class="menu-icon"><i class="feather-video"></i></span>
                        {{__('Prospecting video')}}</a>
                </li>
            @endif
            @can('seller-members')
                <li class="{{ (Request::is('seller/members') || Request::is('seller/members/stats') ? 'active' : '') }}">
                    <a href="{{ url('seller/members') }}"><span class="menu-icon"><i class="feather-users"></i></span>
                        {{__('Members')}}</a>
                    {!! $global_pro_badge !!}
                </li>
            @endcan
            <li class="{{ (Request::is('seller/analytics') ? 'active' : '') }}">
                <a href="{{ url('seller/analytics') }}"><span class="menu-icon"><i class="feather-activity"></i></span>
                    {{__('Analytics')}} </a>
                {!! $global_pro_badge !!}
            </li>
            <li class="{{ (Request::is('seller/leaderboard') ? 'active' : '') }}">
                <a href="{{ url('seller/leaderboard') }}"><span class="menu-icon"><i class="feather-calendar"></i></span>
                    {{__('Leaderboards')}}
                    {!! $global_pro_badge !!}
                </a>
            </li>
            <li class="{{ (Request::is('seller/my-profile') ? 'active' : '') }}">
                <a href="{{ url('seller/my-profile') }}"><span class="menu-icon"><i class="feather-user"></i></span>
                    {{__('My Profile')}}</a>
            </li>
        @endif
    </ul>
    <ul class="list-unstyled bottom-menu mt-5 mb-0">
        @if(!auth()->user()->hasRole('Admin') && config('app.rankup.company_title') != 'Ibuumerang')
            <li>
                <a href="{{ url('seller/help') }}" class="btn-blue btn w-100 d-block text-start">
                    <span class="text-white font-600">{{__('Learn how to use the rankup app to his maximum')}}</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('Seller'))
            <li>
                <a href="mailto:{{ (config('app.rankup.support_email')) }}">
                    <span class="menu-icon">
                        <i class="feather-mail"></i>
                    </span>
                    {{ __('Support') }}
                </a>
            </li>
            <li class="{{ (Request::is('seller/settings/account') || Request::is('seller/settings/notifications') || Request::is('seller/settings/terms-and-policy') || Request::is('seller/settings/my-subscription') ? 'active' : '') }}">
                <a href="{{ url('seller/settings/account') }}">
                    <span class="menu-icon">
                        <i class="feather-settings"></i>
                    </span>
                    {{__('Settings')}}
                </a>
            </li>
        @endif
        <li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <span class="menu-icon"><i class="feather-log-out"></i></span>{{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</nav>
