<!-- Modal -->
<div class="modal fade" id="contact-add-update-label" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">
                <form id="contact-label-form" class="row g-3" action="" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label class="form-label">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="label_name" name="name" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">{{ __('Colors') }}</label>
                        <div class="col-md-12">
                            <label class="input-color-label mt-4">
                                <input type="color" id="label_color" name="color">
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="col-md-3" style="margin: inherit;">
                            <button type="submit" class="btn btn-blue">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
