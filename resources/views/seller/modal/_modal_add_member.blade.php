<div class="modal fade" id="add_member_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="my-modal-title d-flex align-items-center">
                    <i class="feather-user-plus blue fs-22 mr-3"></i>
                    <h6>{{__('Add a Member')}}</h6>
                </div>
                <form id="add_member_form" action="{{route('seller.add-member')}}" method="POST"
                enctype="multipart/form-data">
                    <div class="my-5 text-center">
                        <img id="new_member_profile_image_preview" class="img-fluid d-block mx-auto profile-image-round" src="{{ asset('assets/images/add-member.png') }}" alt="">
                        <a href="javascript:;" id="new_member_profile_image_trigger"
                           class="btn btn-outline-white px-5 py-3 mt-4">+ {{__('Photo')}}</a>
                        <span class="text-danger print-error-msg-profile_image" style="display:none"></span>
                        <input id='new_member_add_profile_image' type='file' name="profile_image" hidden/>
                    </div>
                    <div class="form-group mb-5">
                        <input type="text" name="full_name" class="form-control ps-0" id="add_full_name" placeholder="{{__('Full Name')}}">
                        <span class="text-danger print-error-msg-full_name" style="display:none"></span>
                    </div>
                    <div class="form-group mb-5">
                        <input type="email" name="email" class="form-control ps-0" id="exampleFormControlInput1" placeholder="{{__('Email')}}">
                        <span class="text-danger print-error-msg-email" style="display:none"></span>
                    </div>
                    <h6>{{__('Leg selection for next member registration:')}}</h6>
                    <div class="radio-btns mt-3 mb-5">
                        <div class="form-check d-inline-flex align-items-center me-4">
                            <input class="form-check-input" type="radio" name="tree_pos" id="tree_pos_left" value="left" checked>
                            <label class="form-check-label ms-2" for="exampleRadios1">
                                {{__('Left leg')}}
                            </label>
                        </div>
                        <div class="form-check d-inline-flex align-items-center">
                            <input class="form-check-input" type="radio" name="tree_pos" id="tree_pos_right" value="right">
                            <label class="form-check-label ms-2" for="exampleRadios2">
                               {{__('Right leg')}}
                            </label>
                        </div>
                        <span class="text-danger print-error-msg-tree_pos" style="display:none"></span>
                    </div>
                    <button type="submit" id="add_member_submit" href="javascript:;" class="btn btn-blue-gradient w-100">{{__('Add member')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>