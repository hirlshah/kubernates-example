@extends('layouts.frontend.index')
@section('content')
    <section id="home-1" style="background-image: url({{ asset('assets/images/shape/shape-1.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 d-flex align-items-center">
                    <div class="content">
                        <h1>Lorem ipsum <br> dolor sit amet.</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent, lectus magna.</p>
                        <button type="button" class="btn btn-blue fw-bold px-5">Lorem Ipsum</button>
                    </div>
                </div>
                <div class="col-md-6 col-12 img-col">
                    <img class="img-fluid mt-md-n3" src="{{ asset('assets/images/man-with-mobile.png') }}   " alt="">
                </div>
            </div>
        </div>
    </section>
    <section id="home-2">
        <div class="container">
            <div class="row first">
                <div class="col-md-6 col-12">
                    <h1 class="home-we-are mb-3">{{__('Who are we?')}}</h1>
                </div>
                <div class="col-md-6 col-12">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam purus sit. Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam purus sit.</p>
                </div>
            </div>
            <div class="row second">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="content">
                        <i class="feather-user"></i>
                        <h4>Lorem ipsum</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="content">
                        <i class="feather-shield"></i>
                        <h4>Lorem ipsum</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="content">
                        <i class="feather-message-circle"></i>
                        <h4>Lorem ipsum</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="content">
                        <i class="feather-facebook"></i>
                        <h4>Lorem ipsum</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="home-3" style="background-image: url({{ asset('assets/images/shape/shape-2.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 img-col order-md-1 order-2">
                    <img class="img-fluid mt-md-n4" src="{{ asset('assets/images/girl-with-mobile.png') }}" alt="">
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center order-md-2 order-1">
                    <div class="content">
                        <h1>Lorem Ipsum dolor.</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent, lectus magna fringilla consectetur adipiscing elit ut aliqui, purus sit amet luctus venent, lectus magna.</p>
                        <button type="button" class="btn btn-white-2 fw-bold px-5 mb-2 me-md-3">Lorem Ipsum</button>
                        <button type="button" class="btn btn-outline-white-2 fw-bold px-5 mb-2">Lorem Ipsum</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="home-4" style="background-image: url({{ asset('assets/images/bg-1.png') }})">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 d-flex align-items-center">
                    <div class="content">
                        <h1>{{__('What we do?')}}</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent.</p>
                        <div class="icons">
                            <a href="">
                                <i class="feather-user"></i>
                            </a>
                            <a href="">
                                <i class="feather-shield"></i>
                            </a>
                            <a href="">
                                <i class="feather-message-circle"></i>
                            </a>
                            <a href="">
                                <i class="feather-facebook"></i>
                            </a>
                        </div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent. Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venent. Lorem ipsum dolor sit amet,
                            consectetur adipiscing elit ut aliquam, purus sit amet luctus venent.</p>
                        <button type="button" class="btn btn-white fw-bold px-5 mt-md-5 mt-3">Saber mais</button>
                    </div>
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center img-col">
                    <img class="laptop-img" src="{{ asset('assets/images/mackbook.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <section id="contact-us">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-12 d-flex align-items-center">
                    <div class="content">
                        <h1>{{__('Contact us')}}</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing <br> elit ut aliquam, purus sit amet luctus venenatis.</p>
                        <ul class="list-unstyled mb-0">
                            <li>
                                <i class="feather-phone-call"></i> (00) 0 0000-000
                            </li>
                            <li>
                                <i class="feather-phone-call"></i> (00) 0 0000-000
                            </li>
                            <li>
                                <i class="feather-mail"></i> contact@email.com
                            </li>
                            <li>
                                <i class="feather-map-pin"></i> Lorem ipsum, 9999 - 00000-000
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="contact-form">
                        <form method="post" action="{{ route('frontend.contact.store') }}" id="contactUsForm">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-6 mb-5">
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                                           placeholder="{{__('Name')}}" name="name" id="contact_name">
                                    <span class="text-danger print-error-msg-name" style="display:none"></span>
                                </div>
                                <div class="col-6 mb-5">
                                    <input type="text" class="form-control {{ $errors->has('phone') ? 'error' : '' }}"
                                           placeholder="{{__('Phone No')}}" name="phone" id="contact_phone">
                                    <span class="text-danger print-error-msg-phone" style="display:none"></span>
                                </div>
                                <div class="col-12 mb-5">
                                    <input type="email" class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                                           placeholder="{{__('E-mail')}}" name="email" id="contact_email">
                                    <span class="text-danger print-error-msg-email" style="display:none"></span>
                                </div>
                                <div class="col-12 mb-5">
                                    <textarea class="form-control {{ $errors->has('message') ? 'error' : '' }}"
                                              placeholder="{{__('Message')}}" name="message" id="contact_message"
                                              rows="4"></textarea>
                                    <span class="text-danger print-error-msg-message" style="display:none"></span>
                                </div>
                                <div class="col-12">
                                    <input type="submit" name="send" value="{{__('Send')}}"
                                           class="btn btn-blue fw-bold w-100 p-4">
                                </div>
                                <p class="print-success-msg-contact-us-form" style="display: none;"></p>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="follow-us text-center">{{__('Follow us')}}</h6>
                            </div>
                            <div class="col-12 mt-4 icons">
                                <i class="feather-map-pin"></i>
                                <i class="feather-facebook"></i>
                                <i class="feather-instagram"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="map" style="background-image: url({{ asset('assets/images/map.png') }})">
    </section>
@endsection
