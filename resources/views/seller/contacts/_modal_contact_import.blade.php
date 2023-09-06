<!-- Modal -->
<div class="modal fade" id="contact-upload-modal"  tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close mt-2" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body">  
                <h6 class="modal-title font-avenir fw-800 pb-3">{{__('Import')}}</h6>
                <form action="{{ route('seller.contacts.upload') }}" id="contact-upload-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="board_id" id="board_id_hidden" value="{{$board->id}}">
                          
                    <div class="mb-4">
                        <input id="contact-import-upload" type="file" name="file" hidden/>
                        <a class="btn btn-outline-blue w-100 mt-2 br-20px" id="contact-import-broswer" href="javascript:;">{{__('Upload CSV/XLS')}}</a>
                        <span class="text-danger print-error-msg-file" style="display:none"></span>
                        <span class="text-danger print-error-msg-name" style="display:none"></span>
                        <span class="text-danger print-error-msg-email" style="display:none"></span>
                      </div>
                      <ul class="upload-error-msg d-none"></ul>
                    <div class="mb-4 pb-4">
                      <label class="form-label">{{__('Import to column')}}</label>
                      <select class="form-select shadow-none border-top-0 border-start-0 border-end-0 px-0 rounded-0 border-bottom-1" name="update_status" id="contact-upload-update-status" style="background-position: center right 0px;outline:none;">
                        <option value="">&emsp; {{__('Select column')}}</otpion>
                        <option value="">&emsp; {{__('contacts')}}</otpion>
                        @foreach(\App\Enums\ContactBoardStatus::toSelectArray() as $key => $contact)
                            <option value="{{ $key }}">&emsp; {{ __($contact) }}</otpion>
                        @endforeach
                      </select>
                    </div>
                    <button type="button" class="btn btn-outline-blue w-100 br-20px mb-3 preview-upload" disabled="true" data-actions="{{ route('seller.contacts.read.upload-data')}}">{{__('Preview import')}}</button>
                    <button type="submit" class="btn btn-blue-gradient w-100 br-20px">{{__('Confirm')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>