<div class="modal fade" id="user_list_modal" tabindex="-1" role="dialog" aria-labelledby="user_list_modal" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered pt-5 modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-body p-4 p-sm-5" id="people_list_for_modal">
                <div class="d-flex align-items-center mb-4">
                
                </div>
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="people-list mt-4">
                            <div class="people-search">
                                <input type="text" class="fs-16 mb-0 font-weight-normal form-control ps-0 mb-3" placeholder="{{ __('select presenter')}}" id="users_search">
                            </div>
                            <div class="text-center pt-3"><div class="spinner"></div></div>
                                <div class="users_list" onscroll="getUserList()"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .users_list {
        overflow-x: hidden;
        overflow-y: auto;
        height: 500px;
    }
</style>
<script>
    function getUserList() {
        var scrollContainer = $('.users_list');
        var scrollHeight = scrollContainer.prop('scrollHeight');
        var scrollTop = scrollContainer.scrollTop();
        var containerHeight = scrollContainer.height();

        if (scrollHeight - scrollTop === containerHeight) {
            currentUsersPage++;
            users_list(currentUsersPage);
        }
    }
</script>