<!-- Modal -->
<div class="modal fade" id="contact-label-list-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <form id="contact-labels-update-form" class="row g-3" action="{{route('seller.contacts.labels.update', '')}}" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="w-50 d-flex align-items-center mt-3 mb-md-0 mb-3">
                            <span class="fs-16 mb-0 font-weight-normal d-block" ><b>{{__('labels')}}</b></span>
                        </div>
                    </div>
                    <div class="col-md-12 contact-label-list">
                    </div>
                    <div class="d-flex">
                        <a class="add-new-contact-label-model btn btn-blue" style="margin-right: 5px;">{{ __('New') }}</a>
                        <button type="submit" class="btn btn-blue">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
