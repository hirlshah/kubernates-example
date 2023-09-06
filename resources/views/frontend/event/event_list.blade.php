@extends('layouts.seller.index')
@section('content')
<div id="content">
    <button type="button" id="sidebarCollapse" class="btn custom-collapse-btn">
        <span class="minus"></span>
        <span class="minus"></span>
        <span class="minus"></span>
    </button>
    <div class="content-header d-flex align-items-center">
        <div class="content-header-left d-flex align-items-center">
            <img class="img-fluid me-3" src="{{ asset('assets/images/icons/coffee.svg') }}" alt="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active" aria-current="page">{{__('Events')}}</li>
                </ol>
            </nav>
        </div>
        <div class="content-header-right d-flex align-items-center ms-auto">
            <a class="notification" href=""><i class="feather-bell blue"></i></a>
        </div>
    </div>
    <div class="content-body">
        <div class="container-fluid mb-5">
            <div class="card p-sm-5 p-3 bg-repeat-n bg-cover bg-center"
                 style="background-image: url({{ asset('assets/images/bg-3.png') }})">
                <h4 class="white-f3f3f3">{{__('Conférence en ligne gratuite')}}</h4>
                <div class="dash blue fw-fw-bold fs-24">_</div>
                <p class="text-white">Lorem ipsum dolor sit amet, consecteturadipiscing elit.</p>
                <div class="book-your-call-buttons mb-3">
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">Social Media</a>
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">13 Jun. 2021</a>
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">17:15h</a>
                </div>
                <a href="" class="btn btn-dark-yellow fs-16 mw-max-content">{{__('See calendar')}}</a>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="fs-18">{{__('Recent events')}}</h6>
                <div class="filter">
                    <i class="feather-sliders fs-22"></i>
                    <ul>
                        <li>123</li>
                        <li>123</li>
                        <li>123</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <span class="fs-12 grey-727272 d-block">{{__('CATEGORIES')}}</span>
            <div class="event-categories-btn">
                <a href="" class="btn btn-white-black me-2 my-2 fw-bold active">{{__('All contents')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Social Media')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Networking')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Marketing')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Design')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Finance')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Podcasts')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Tech')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Entertainment')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Music')}}</a>
                <a href="" class="btn btn-white-black me-2 my-2 fw-normal">{{__('Communication')}}</a>
            </div>
        </div>
        <div class="container-fluid mb-4">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">{{__('See Details')}}</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">{{__('See Details')}}</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">{{__('See Details')}}</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">{{__('See Details')}}</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i>{{__(' Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i>{{__(' Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3 bg-repeat-n bg-cover bg-center height-100-30px"
                        style="background-image: url({{ asset('assets/images/bg-4.png') }});">
                        <div class="card-body d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <h6 class="card-title text-white mb-0">Conférence en ligne gratuite</h6>
                                <img src="{{ asset('assets/images/line.png') }}" class="img-fluid" alt="Line">
                                <p class="card-text text-white fs-14 mb-3 lh-lg fw-light">Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Quis nullam arcu, tortor, duis fringilla. Placerat
                                    scelerisque accumsan.</p>
                                <a href="" class="btn btn-dark-yellow fs-16 mw-max-content">{{__('Check calendar')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-6 col-xl-4">
                    <div class="card border-0 my-3">
                        <img src="{{ asset('assets/images/card-images/card-1.png') }}" class="card-img-top " alt="... ">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Lorem ipsum</h6>
                            <p class="card-text grey-666666 fs-14 mb-3">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Quis
                                nullam arcu, tortor, duis fringilla. Placerat scelerisque accumsan.</p>
                            <div class="tags mb-3">
                                <span class="">Social Media</span>
                                <span class="">13 Jun. 2021</span>
                                <span class="">17:15h</span>
                            </div>
                            <a href="" class="btn btn-blue px-3 py-3 fs-14 mr-3 mb-2 me-2">See Details</a>
                            <a href="" class="btn btn-outline-black px-3 py-3 fs-14 fw-normal mb-2">
                                <i class="feather-calendar me-1"></i> {{__('Add to calendar')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4 mb-5">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">100</a></li>
                </ul>
            </nav>
        </div>
        <div class="container-fluid mt-3">
            <div class="card p-sm-5 p-3 bg-repeat-n bg-cover bg-center" style="background-image: url({{ asset('assets/images/bg-5.png') }})">
                <h4 class="white-f3f3f3">Conférence en ligne gratuite</h4>
                <div class="dash blue fw-fw-bold fs-24">_</div>
                <p class="text-white">Lorem ipsum dolor sit amet, consecteturadipiscing elit.</p>
                <div class="book-your-call-buttons mb-3">
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">Social Media</a>
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">13 Jun. 2021</a>
                    <a href="" class="btn btn-outline-white fs-12 p-2 me-2 mb-2">17:15h</a>
                </div>
                <a href="" class="btn btn-dark-yellow fs-16 mw-max-content">{{__('See calendar')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection
