@if(!empty($users))
    <div class="mb-2 member_listing_check">
        @foreach($users as $key => $user)
            <div>
                <input class="people-check people-checked" id="people_{{ $key }}" type="radio" name="selected_person" value="{{ $user->name }}" data-id="{{ $user->id }}" />
                <label class="people-check-label ps-0" for="people_{{ $key }}">
                    <span class="fw-600 fs-16">{{ $user->name }} ({{ $user->email }})</span>
                </label>
            </div>
        @endforeach
    </div>
@endif
<script>
    /**
     * Change search bar value on people change
     */
    $("body").on("change", ".member_listing_check input[type='radio']", function() {
        if ($(this).is(":checked")) {
            var selectedValue = $(this).val();
            $("#tree_search_users").val(selectedValue);
            var id= $(this).data('id');
            $('#member_id').val(id);
            $('#treeSearchBtn').trigger('click');
        }
    });
</script>
