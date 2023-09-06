<div class="card dailies py-3 mb-xxl-4 mb-5">
    <div class="dailies-start ps-4 ps-md-5">{{__('Dailies')}}</div>
    @if(isset($tasks) && !empty($tasks))
        <div class="dailies-center user-daily-tasks px-4 px-md-5" id="task_data_div">
            @include('seller.dashboard.dailies.task')
        </div>
    @endif
    <div class="dailies-end pe-4 pe-md-5 ms-auto">
        <i class="feather-edit dailies-icons add_edit_task" data-type="add-task"></i>
        <i class="feather-calendar dailies-icons calendar-btn" id="dailies-datepicker"></i>
    </div>
</div>
<form id="add_task_form" action="{{route('seller.tasks.store')}}" method="POST" enctype="multipart/form-data">
    <div class="modal fade dailies-modal" id="dailiesModal" tabindex="-1" aria-labelledby="dailiesModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">    
                    <h5 class="modal-title text-white dailies_modal_title" id="exampleModalLabel">{{__('Adjust your personal goals')}}</h5>
                    <a href="javascript:void(0)" class="close-icon text-white close_dailies_modal">
                        <i class="feather-x"></i>
                    </a>
                </div>
                <div class="modal-body py-0">
                    <input type="hidden"  name="is_team" id="is_team" value="0">
                    <div class="dailies-list_append-div vstack" id="task-data" style="gap: 3.5rem">

                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" id="add_task" class="btn add-btn w-100 mx-0">{{ __('Add a task') }}</a>
                    <button type="button" class="btn btn-blue-gradient w-100 mx-0" id="add_task_form_submit">{{ __('Save changes') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(function () {
        let sellerComplatedTaskDates = "{{route('seller.tasks.completed-task-dates')}}";
        jQuery.datetimepicker.setLocale(lang);
        var highlightedDates = {!! $completedTaskDates !!};
        $('#dailies-datepicker').datetimepicker({
            timepicker: false,
            format: "d/m/Y",
            formatDate: "d/m/Y",
            highlightedDates: highlightedDates,
            onChangeMonth:  function(date) {
                var changesMonth = date.getMonth() + 1;
                var changesYear = date.getFullYear();
                data = {
                    month: changesMonth,
                    year: changesYear
                };
                $.getJSON(sellerComplatedTaskDates, data,  function(response) {
                    if(response.success) {
                        $('#dailies-datepicker').datetimepicker('setOptions', { highlightedDates: response.data});
                    }
                });
            }
        });
    });
</script>