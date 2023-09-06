<div class="modal fade" id="addExperience" tabindex="-1" role="dialog" aria-labelledby="addExperience"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-user-plus blue me-2"></i>
                    <h6>{{__('Add professional experience')}}</h6>
                </div>
                <form id="add_experience_form" action="{{route('seller.profile.add-experience')}}" method="POST"
                      enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 mb-4 d-flex align-items-center">
                            <img id="new_experience_image_preview" class="me-3" style="height: 50px;width: 50px"
                            src="{{ asset('assets/images/add-member.png') }}" alt=""/>
                            <a class="btn btn-outline-white w-100 br-20px" id="new_experience_image_trigger"
                               href="javascript:;">+ {{__('Photo')}}</a>
                            <input id='new_experience_image' type='file' name="image" hidden/>
                        </div>
                        <span class="text-danger print-error-msg-image" style="display:none"></span>
                        <div class="col-12 mb-4">
                            <input type="text" name="title" class="form-control ps-0" id="" placeholder="{{__('Title')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-title" style="display:none"></span>
                        </div>
                        <div class="col-12 mb-4">
                            <input type="text" name="company" class="form-control ps-0" id="" placeholder="{{__('Company')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-company" style="display:none"></span>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <input type="text" name="start_date" class="form-control ps-0" id="experience_start_date" placeholder="{{__('Start date')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-start_date" style="display:none"></span>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <input type="text" name="end_date" class="form-control ps-0" id="experience_end_date" placeholder="{{__('End date')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-end_date" style="display:none"></span>
                        </div>
                        <div class="col-12 d-flex align-items-center check-2 mb-4">
                            <input type="checkbox" name="experience_current_job" class="form-check-input me-2" value="1" id="experience-current-job">
                            <label class="form-check-label" for="">{{__('This is my current job')}}</label>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-blue-gradient w-100" type="submit">{{__('Add experience')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
