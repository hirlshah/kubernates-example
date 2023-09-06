<div class="modal fade" id="create_trello_board_category_modal" tabindex="-1" role="dialog" aria-labelledby="create_trello_board_category_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered pt-5" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 p-sm-5">
                <a href="#" class="my-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="feather-x"></i>
                </a>
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title">{{__('Create category')}}</h6>
                </div>
                <form id="create_trello_board_category_form" action="{{route('seller.add-trello-board-category')}}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="form-group mb-4">
                                <label class="form-label">{{__('Name')}}</label>
                                <input type="text" name="title" class="form-control ps-0" placeholder="{{__('Name')}}">
                                <span class="text-danger print-error-msg-title" style="display:none"></span>
                            </div>
                            <div class="form-group mb-4">
                                <label for="color" class="form-label">{{ __('Select a color')}}</label>
                                <input type="color" id="color" name="color" value="#ff0000" class="visible">
                                <span class="text-danger print-error-msg-color" style="display:none"></span>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-blue" type="submit">+ {{__('Create')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>