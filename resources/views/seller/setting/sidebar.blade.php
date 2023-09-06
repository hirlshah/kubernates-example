<div class="col-xxl-3 col-xl-4 col-lg-5 col-md-4 mb-4 mb-md-0">
    <div class="d-flex flex-column flex-shrink-0 py-md-5 py-4 px-3 shadow-custom rounded-3 h-100">
        <div class="nav flex-column nav-pills mb-auto" id="v-pills-tab" role="tablist"
            aria-orientation="vertical">
            <a href="{{ route('seller.setting.account') }} " class="tab-link {{ (Request::is('seller/settings/account')  ? 'active' : '') }}" id="v-pills-home-tab"><i class="feather-user"></i>
                {{__('Account')}} <i class="fa fa-long-arrow-right arrow-right me-0" aria-hidden="true"></i></a>

            <a href = "{{ route('seller.setting.notification') }} " class="tab-link {{ (Request::is('seller/settings/notifications')  ? 'active' : '') }}"><i class="feather-bell"></i>{{__('Notifications')}} <i class="fa fa-long-arrow-right arrow-right me-0" aria-hidden="true"></i></a>

            <a href= "javascript:void(0)" class="tab-link {{ (Request::is('seller/settings/terms-and-policy')  ? 'active' : '') }} terms-policy" id="v-pills-messages-tab"><i class="feather-book"></i>{{__('Terms and conditions')}}<i class="fa fa-long-arrow-right arrow-right me-0" aria-hidden="true"></i></a>

            @if(config('app.rankup.comapny_name') != "ibuumerang_rankup")
                <a href = " {{ route('seller.setting.my-subscription') }}" class="tab-link {{ (Request::is('seller/settings/my-subscription')  ? 'active' : '') }}" id="v-pills-settings-tab"><i class="feather-credit-card"></i>{{__('My subscription')}} <i class="fa fa-long-arrow-right arrow-right me-0" aria-hidden="true"></i></a>
            @endif
            
            <div style="display: none;">
                <a href="https://www.iubenda.com/conditions-generales/16199096"
                    class="conditions iubenda-white no-brand iubenda-noiframe iubenda-embed iubenda-noiframe tab-link" style="display: none !important;"
                    title="Conditions Générales "><i class="feather-clipboard"></i>{{__('Terms and conditions')}}</a>
            </div>
           
        </div>
        <!-- <div class="dropdown mt-4">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none"
                id="language-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="rounded-circle me-2" width="32" height="32">EN</span>
                English
                <i class="feather-chevron-down blue ms-2"></i>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="language-dropdown">
                <li><a class="dropdown-item" href="#">English</a></li>
                <li><a class="dropdown-item" href="#">English</a></li>
                <li><a class="dropdown-item" href="#">English</a></li>
                <li><a class="dropdown-item" href="#">English</a></li>
            </ul>
        </div> -->
    </div>
</div>
