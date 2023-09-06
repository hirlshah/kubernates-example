<div class="modal fade" id="add_trello_board_modal" tabindex="-1" role="dialog" aria-labelledby="add_trello_board_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title">{{__('Add board')}}</h6>
                </div>
                <form class="add_trello_board_form" action="{{route('seller.add-trello-board')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="form-group mb-4">
                                <label class="form-label">{{__('Title')}}</label>
                                <input type="text" name="title" class="form-control ps-0" placeholder="{{__('Title')}}">
                                <span class="text-danger print-error-msg-title" style="display:none"></span>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-blue" type="submit">+ {{__('Add board')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
