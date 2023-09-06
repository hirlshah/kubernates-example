<div class="modal fade" id="add_people_to_trello_task_modal" tabindex="-1" role="dialog" aria-labelledby="add_people_to_trello_task_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body p-4 p-sm-5" id="people_list_for_modal">
                <div class="d-flex align-items-center mb-4">
                    <h6 class="add_edit_modal_title fs-18">{{__('Assign people')}}</h6>
                    <button class="btn btn-blue assign_people_to_trello_task ms-auto">{{ __('Save btn')}}</button>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="people-list mt-4">
                            <div class="people-search">
                                <input type="text" class="fs-16 mb-0 font-weight-normal form-control ps-0 mb-3" placeholder="{{ __('Search people')}}" id="search_people_from_modal">
                            </div>
                            <div class="trello_task_people_list" onscroll="getPeopleListForModal()"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .trello_task_people_list {
        overflow-x: hidden;
        overflow-y: auto;
        height: 500px;
    }
</style>
<script>
    function getPeopleListForModal() {
        var scrollContainer = $('.trello_task_people_list');
        var scrollHeight = scrollContainer.prop('scrollHeight');
        var scrollTop = scrollContainer.scrollTop();
        var containerHeight = scrollContainer.height();

        if (scrollHeight - scrollTop === containerHeight) {
            currentModalPeoplePage++;
            get_people_list_for_modal(currentModalPeoplePage);
        }
    }
</script>
