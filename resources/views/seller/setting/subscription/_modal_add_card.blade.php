<div class="modal fade" id="add-credit-card" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <h6 class="mb-4 fs-18">+ {{__('Add Card')}}</h6>
                <div class="row">
                    <form id="add_card_form" action="{{route('seller.add-card')}}" method="POST"
                    enctype="multipart/form-data">
                        <div class="col-12 form-group mb-4">
                            <label for="">{{__('Name in card')}}</label>
                            <input type="text" name="card_holder_name" class="form-control ps-0" placeholder="{{__('John Lawrence')}}">
                            <span class=" text-danger print-error-msg-card_holder_name" style="display:none"></span>
                        </div>
                        <div class="col-12 form-group mb-4">
                            <label for="">{{__('Number in card')}}</label>
                            <input type="text" name="card_number" class="form-control ps-0" id="card_number"placeholder="{{__('4242 4242 4242 4242')}}">
                            <span class="text-danger print-error-msg-card_number" style="display:none"></span>
                        </div>
                        <div class="row">
                            <div class="col-8 form-group mb-4">
                                <label for="">{{__('Exp. date')}}</label>
                                <input type="text" name="expiry_date" class="form-control ps-0" placeholder="{{__('DD/MM')}}">
                                <span class="text-danger print-error-msg-expiry_date" style="display:none"></span>
                            </div>
                            <div class="col-4 form-group mb-4">
                                <label for="">{{__('CVV')}}</label>
                                <input type="password" name="cvv" class="form-control ps-0" placeholder="{{__('XXX')}}">
                                <span class="text-danger print-error-msg-cvv" style="display:none"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <button type="submit" id="add_card_submit" href="javascript:;" class="btn btn-blue-gradient w-100">{{__('Add this card')}}<span class="form-submit d-none"><i class="fas fa-spin feather-loader"></i></span></button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-white-shadow fs-16 fw-bold cancel-close" data-dismiss="modal" aria-label="close">{{__('Cancel')}}</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-danger d-none error-card"></div>
                </div>
            </div>
        </div>
    </div>
</div>
