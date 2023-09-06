<div class="modal fade" id="qrCode" tabindex="-1" role="dialog" aria-labelledby="qrCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 346px;" role="document">
        <div class="modal-content overflow-visible">
            <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                <i class="feather-x"></i>
            </a>
            <div class="modal-body p-4">
                {!! QrCode::size(250)->generate(route('home')."/?ref=".$member->referral_code ); !!}
            </div>
        </div>
    </div>
</div>