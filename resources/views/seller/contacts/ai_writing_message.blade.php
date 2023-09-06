@if(isset($aiMessage))
	<p>
		{{ $aiMessage }}
	</p>
	<span class="copy-link-tooltip">
        <button class="copy_ai_message_btn btn btn-light-blue"><i class="feather-link-2 me-1"></i>{{__('To copy')}}</button>
        <span class="copy_generate_link_url">
            <p class="mb-0">{{__('Copy to clipboard')}}.</p>
        </span>
        <input type="text" value="{{ $aiMessage }}" id="copy_ai_message_input" class="d-none">
    </span>
@endif
<script>
    $(document).ready(function() {
        $(".copy-link-tooltip").mouseenter(function(){
		    $(this).find('.copy_generate_link_url').toggleClass("show");
		});

		$(".copy-link-tooltip").mouseleave(function(){
		    $(this).find('.copy_generate_link_url').toggleClass("show");
		});
    });
</script>