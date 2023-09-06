<div class="modal fade" id="book-a-call" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="my-modal-title d-flex align-items-center">
                    <i class="feather-video blue fs-22 mr-4"></i>
                    <h6>{{__('Book a call')}}</h6>
                </div>
                <form id="book_call_form" action="" method="POST" enctype="multipart/form-data">
                    <div class="new-user-profile d-flex align-items-center my-4">
                        <img src="{{ asset('assets/images/new-user-profile.png') }}" alt="">
                        <div class="new-user-details text-start ms-3">
                            <h5>{{__('Aarit Armander')}}</h5>
                            <span class="fs-12">{{__('Your newest member')}}</span>
                        </div>
                    </div>
                    <h6>{{__('Meeting proposal')}}</h6>
                    <div class="form-group date mt-3">
                        <label for="date" class="mb-1">{{__('Date')}}</label>
                        <input type="date" class="form-control ps-0" id="" placeholder="{{__('MM/DD/YYYY')}}" max="9999-12-31">
                    </div>
                    <a href="" class="btn btn-outline-white px-5 py-3 mt-4 w-100 br-20px">{{__("See Aarit’s Calendar")}}</a>
                    <div class="form-group date mt-5">
                        <label for="Hour" class="mb-1">{{__('Hour')}}</label>
                        <select class="custom-select form-control ps-0">
                            <option>{{__('Select an available time for this day')}}</option>
                            <option value="1">{{__('One')}}</option>
                            <option value="2">{{__('Two')}}</option>
                            <option value="3">{{__('Three')}}</option>
                        </select>
                    </div>
                    <h6 class="mt-5">{{__('Meeting room')}}</h6>
                    <div class="form-group date mt-3">
                        <label for="Link" class="mb-1">{{__('Link')}}</label>
                        <input type="text" class="form-control ps-0" id="" placeholder="{{__('www.yourlink.com')}}">
                    </div>
                    <a href="#" class="btn btn-blue-gradient mt-5 w-100">{{__('invite')}}</a>
                </form>
            </div>
        </div>
    </div>
</div>
