<div class="modal fade" id="add_trello_board_category" tabindex="-1" role="dialog" aria-labelledby="add_trello_board_category" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content overflow-visible">
            <div class="modal-body p-4 py-sm-4 px-sm-5">
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title fs-18">{{__('Add category')}}</h6>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="category-list mt-4">
                            <div class="category-search">
                                <input type="text" class="fs-16 mb-0 font-weight-normal form-control ps-0 mb-3" placeholder="{{ __('Search category') }}" id="search_category">
                            </div>
                            <h6 class="fw-400 fs-18 mb-3">{{ __('Categories') }}</h6>
                            <div id="trello_board_categories">
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <button class="btn shadow-none fw-500 text-blue p-0" id="create_trello_board_category_btn">+ {{__('Create a new category')}}</button>
                            <div class="text-end">
                                <button class="btn btn-blue mt-4" id="add_category_to_trello_task">{{ __('Save btn')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
