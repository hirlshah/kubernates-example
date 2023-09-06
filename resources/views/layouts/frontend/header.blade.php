<!-- Header  -->
<header>
    <nav class="navbar navbar-expand-xl navbar-dark">
        <div class="container">
            @if(Route::current()->getName() != 'frontend.event.details')
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto align-items-xl-center">
                        <!-- <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">{{__('Who are we?')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{__('What we do?')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{__('Pricing')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{__('Talk to us')}}</a>
                        </li> -->
                        <div class="btn-icons">
                            @guest
                                <a type="button" class="btn btn-blue" href="{{ route('register') }}">{{__('Join now')}}</a>
                                <a type="button" class="btn btn-outline-blue" href="{{ route('login') }}">{{__('Login')}}</a>
                            @else
                                @if(auth()->user()->hasRole('Admin'))
                                    <a type="button" class="btn btn-outline-blue" href="{{route('admin-dashboard')}}">{{__('Dashboard')}}</a>
                                @else
                                    <a type="button" class="btn btn-outline-blue" href="{{route('seller-dashboard')}}">{{__('Dashboard')}}</a>
                                @endif
                            @endguest
                            <a href=""><i class="feather-facebook"></i></a>
                            <a href=""><i class="feather-instagram"></i></a>
                        </div>
                    </ul>
                </div>
            @endif
        </div>
    </nav>
</header>
