<div class="modal fade" id="update_profile" tabindex="-1" role="dialog" aria-labelledby="update_profile"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-user-plus blue me-2"></i>
                    <h6>{{__('Update Profile')}}</h6>
                </div>
                <form class="update_profile_form" action="{{route('seller.update-profile')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 mb-4">
                            {{__('First Name')}}
                            <input type="text" name="name" class="form-control ps-0" aria-describedby="" value ="{{ Auth::User()->name}}">
                            <span class="text-danger print-error-msg-name" style="display:none"></span>
                        </div>
                        <div class="col-12 mb-4">
                            {{__('Name')}}
                            <input type="text" name="last_name" class="form-control ps-0" aria-describedby="" value ="{{ Auth::User()->last_name}}">
                            <span class="text-danger print-error-msg-last_name" style="display:none"></span>
                        </div>
                         <div class="col-12 mb-4">
                            {{__('Description')}}
                            <textarea name="description" class="form-control ps-0" id="" aria-describedby="">{{ Auth::User()->description }}</textarea>
                            <span class="text-danger print-error-msg-description" style="display:none"></span> 
                        </div>
                        <div class="col-12">
                            <button class="btn btn-blue-gradient w-100" type="submit">{{__('Update Profile')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
