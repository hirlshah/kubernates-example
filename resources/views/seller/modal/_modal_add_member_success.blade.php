<div class="modal fade" id="add-member-response" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body text-center">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <h4 class="blue mb-3">{{__('All good!')}}</h4>
                <p>{{__('Aarit is now your newest member.')}}</p>
                <div class="new-user-profile d-flex align-items-center justify-content-center my-5">
                    <img src="{{ asset('assets/images/new-user-profile.png') }}" alt="">
                    <div class="new-user-details text-start ms-3">
                        <h5>{{__('Aarit Armander')}}</h5>
                        <span class="fs-12">{{__('Your newest member')}}</span>
                    </div>
                </div>
                <a href="" class="btn btn-blue-gradient w-100">{{__('Thanks')}}</a>
            </div>
        </div>
    </div>
</div>