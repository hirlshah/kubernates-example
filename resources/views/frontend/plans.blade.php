@extends('layouts.frontend.index')
@section('content')
    <section id="plans-page" style="background-image: url({{ asset('assets/images/plans-bg.png') }})">
        <div class="container">
            <div class="text-center">
                <img class="img-fluid plan-logo" width="115" src="{{ asset(config('app.rankup.company_logo_path')) }}" alt="">
            </div>
            <div class="plan-wrapper">
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4 mb-xl-0">
                        <div class="plan-column card">
                            <div class="card-body">
                                <h3 class="plan-title">Free</h3>
                                <ul  class="plan-details">
                                    <li>Lorem ipsum</li>
                                    <li>Lorem ipsum dolor</li>
                                    <li>Lorem ipsum sit</li>
                                    <li>Sit amet aliquon</li>
                                </ul>
                                <h2 class="plan-price">$0/mo</h2>
                                <a class="btn btn-blue-gradient fw-bold" href="">Subscribe to this plan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4 mb-xl-0">
                        <div class="plan-column card">
                            <div class="card-body">
                                <h3 class="plan-title">Standard</h3>
                                <ul  class="plan-details">
                                    <li>Lorem ipsum</li>
                                    <li>Lorem ipsum dolor</li>
                                    <li>Lorem ipsum sit</li>
                                    <li>Sit amet aliquon</li>
                                </ul>
                                <h2 class="plan-price">$0/mo</h2>
                                <a class="btn btn-blue-gradient fw-bold" href="">Subscribe to this plan</a>
                                <p class="plan-bottom">Recommended</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="plan-column card">
                            <div class="card-body">
                                <h3 class="plan-title">Pro</h3>
                                <ul  class="plan-details">
                                    <li>Lorem ipsum</li>
                                    <li>Lorem ipsum dolor</li>
                                    <li>Lorem ipsum sit</li>
                                    <li>Sit amet aliquon</li>
                                </ul>
                                <h2 class="plan-price">$0/mo</h2>
                                <a class="btn btn-blue-gradient fw-bold" href="">Subscribe to this plan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

