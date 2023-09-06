<div class="modal fade modal-width-70 pt-5" id="ai-writing-modal" tabindex="-1" role="dialog"
    aria-labelledby="ContactDetailLabel" aria-hidden="true" data-id="" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body pt-3">
                <div class="py-2">
                    <h6>{{__('Write AI message')}}</h6>
                </div>
                <hr>
                <label for="contact_full_name" class="form-label">{{__('Model')}}</label>
                <div class="ai_models_div">
                    @include('seller.contacts.ai_writing_models')
                </div>
                <button type="submit" class="btn btn-blue generate_message_btn mb-3" disabled>{{__('Generate')}}</button>
                <hr>
                <div class="row">
                    <p>
                        {{ __('Message')}}
                    </p>
                    <div id=ai_writing_message_div>
                        @include('seller.contacts.ai_writing_message')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>