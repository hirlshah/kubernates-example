<div class="modal fade" id="addEducation" tabindex="-1" role="dialog" aria-labelledby="addExperience"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <i class="feather-user-plus blue me-2"></i>
                    <h6>{{__('Add education')}}</h6>
                </div>
                <form id="add_education_form" action="{{route('seller.profile.add-education')}}" method="POST"
                      enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <input type="text" name="title" class="form-control ps-0" id="" placeholder="{{__('Title')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-title" style="display:none"></span>
                        </div>
                        <div class="col-12 mb-4">
                            <input type="text" name="establishment" class="form-control ps-0" id="" placeholder="{{__('Establishment')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-establishment" style="display:none"></span>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <input type="text" name="start_date" class="form-control ps-0" id="education_start_date" placeholder="{{__('Start date')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-start_date" style="display:none"></span>
                        </div>
                        <div class="col-sm-6 mb-4">
                            <input type="text" name="end_date" class="form-control ps-0" id="education_end_date"
                            placeholder="{{__('End date')}}" aria-describedby="">
                            <span class="text-danger print-error-msg-end_date" style="display:none"></span>
                        </div>
                        <div class="col-12 d-flex align-items-center check-2 mb-4">
                            <input type="checkbox" name="scholarship_current_job" class="form-check-input me-2"
                            value="1" id="scholarship-current-job">
                            <label class="form-check-label" for="">{{__('Current scholarship')}}</label>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-blue-gradient w-100" type="submit">{{__('Add education')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
