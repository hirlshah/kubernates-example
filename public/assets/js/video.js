let tag_counter = 0;
$( document ).ready(function() {

  /**
   * Add video
   */
  if($("#add_video_form").length){
    $('#new_tag_button').click(function (){
      tag_counter++;
      $('#d_tag_'+tag_counter).show();
      if(tag_counter >= 2){
        $(this).hide();
      }
    });

    let myModalEl = document.getElementById('add_video_modal')
    myModalEl.addEventListener('shown.bs.modal', function (event) {
      $('#add_video_modal .select2-dynamic').select2({
        dropdownParent: $('#add_video_modal')
      });
    })

    myModalEl.addEventListener('hidden.bs.modal', function (event) {
      $('#add_video_modal .select2-dynamic').select2('destroy');
    })

    $('#new_video_button').click(() => {
      $('.print-error-msg-title').hide();
      $('.print-error-msg-video_link').hide();
      $('#add_video_form').attr('action', storeVideoRoute);
      $('#add_video_form').attr('method', 'POST');
      $('#add_video_form').find('#video_title').val('');
      $('#add_video_form').find('#video_description').val('');
      $('#add_video_form').find('.d_tag').val('');
      $('#d_tag_1, #d_tag_2').hide();
      tag_counter = 0;
      $('#add_video_form').find('#video_form_submit_btn').text('+ ' + addVideoText);
      $('.add_edit_modal_title').text(addVideoText);
      $('#add_video_form').find('#video_method').val("POST");
      $('#add_video_modal').modal('show');
    });

    $("#add_video_form").submit(function( event ) {
      event.preventDefault();
      $('.d_tag').each(function (){
        if($(this).val().trim() == ''){
          $(this).prop('disabled', true);
        }
      });
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(form[0]);
      let sortArray = [];
      $('.draggable-video-card').each(function(){
          sortArray.push($(this).data('id'));
      })
      formData.append('sort_array', sortArray);
      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success:function(data){
          if($.isEmptyObject(data.errors)){
            if(data.success){
              window.location.reload();
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error:function (data){
          printErrorMsg(data.responseJSON.errors);
        },
        complete: function (){
          $('.d_tag').prop('disabled',false);
        }
      });
    });
  }

  /**
   * Delete video
   */
  $('body').on('click','.modal-popup-delete-video',function (e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-confirm-video').attr('data-url',del_url);
    $('#modal_delete_warning_video').show();
  });

  $('body').on('click','.modal-delete-confirm-video',function () {
    var del_url = $(this).attr('data-url');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url: del_url,
      type: 'DELETE',
      success: function(data) {
        $('#modal_delete_warning_video').hide();
        if(data.success == true) {
          window.location.href = data.redirect_url;
        }
      }
    });
  });

  $('body').on('click', '.modal-close-btn-video', function() {
    $('#modal_delete_warning_video').hide();
  });

  /**
   * Edit video
   */
  $('body').on('click', '.modal-popup-edit-video', function(){
    $('#add_video_modal').modal('show');
    $('#add_video_form').attr('action', updateVideoRoute + '/' + $(this).data('id'));
    $.get(showVideoRoute + '/' + $(this).data('id'), function( data ) {
      $('#add_video_form').attr('method', 'patch');
      $('#add_video_form').find('#video_title').val(data.data.title);
      $('#add_video_form').find('#video_description').val(data.data.description);
      $('#add_video_form').find('#video_link').val(data.data.video);
      $('#add_video_form').find('#category').val(data.data.category);
      var sub_category_id = data.data.sub_category_id;

      //append sub category values
      if(data.data.category) {
        let dataCategory = {
          'category_id':data.data.category
        };
        $.get(showSubCategoryRoute, dataCategory, function (response) {
          if(response != '') {
            $('#add_video_form').find("#sub_category_id").empty();
            $.each(response,function(id,name){
              $('#add_video_form').find("#sub_category_id").append('<option value="'+name+'">'+id+'</option>');
            });
            if(sub_category_id != 0 && sub_category_id != null) {
             $('#add_video_form').find("#sub_category_id").val(sub_category_id); 
            }
          } else {
            $('#add_video_form').find("#sub_category_id").empty();
            $('#add_video_form').find("#sub_category_id").append('<option value = "">'+ noDataFoundText + '</option>');
          }
        });
      }
      $('.d_tag').val('');
      $('#d_tag_1, #d_tag_2').hide();
      $('#new_tag_button').show();
      if(data.data.tags.length){
        $.each(data.data.tags, function (k, v){
          tag_counter++;
          $('#d_tag_'+k).val(v).show();
        });
        tag_counter--;
      }
      $('#add_video_form').find('#video_form_submit_btn').text(editVideo);
      $('#add_video_form').find('#video_method').val("PATCH");
      $('.add_edit_modal_title').text(editVideoText);
    });
  });

  /**
   * Video modal
   */
  $('body').on('click', '.modal-popup-video', function(e) {
    e.preventDefault();
    var video = $(this).data('url');
    $('#modal_play_video_url').html(video);
    $('#modal_play_video').modal('show');
  });

  $('body').on('click', '.video-modal-close', function() {
    $('#modal_play_video_url').attr('src', '');
    $('#modal_play_video').modal('hide');
  });

  if($('#successMessage').length){
    window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000);
  }



/**
 * Training video sorted function
 */
  $("#draggble-training-video").sortable({
    out: function( event, ui ) {
      let sortArray = [];
      $('.sortable-training-video-card').each(function(){
        sortArray.push($(this).data('id'));
      })
      $.post(dragDropRoute, {'sort_array' : sortArray});
    }
  });

  /** 
   * Draggble training categories 
   */
  $("#draggble-training-categories").sortable({
    start: function(evt, ui) {
      $(".nav-horizontal-scroll-onhover-items").removeClass("nav-active")
    },
    out: function( event, ui ) {
      let sortArray = [];
      $('.sortable-training-category-card').each(function(){
        sortArray.push($(this).data('id'));
      })
      $.post(categoryDragDropRoute, {'sort_array' : sortArray});
    }
  });

  /**
   * Video sortable
   */
  $("#sortable").sortable({
    out: function( event, ui ) {
      let sortArray = [];
      $('.sortable-video-card').each(function(){
        sortArray.push($(this).data('id'));
      })
      $.post(dragDropRoute, {'sort_array' : sortArray});
    }
  });
  $("#sortable").disableSelection();

  /**
   * Add sub category modal show
   */
  $('#new_document_video_sub_category_button').on('click',function(){
    $('.sub_category_form').trigger("reset");
    $('#add_videosubcategory_modal').modal('show');
    $('.text-danger').hide();
  });    

  /**
   * Update sub category modal show
   */
  $('.edit_sub_category_button').on('click',function(){
    $('.sub_category_form').trigger("reset");
    $('#add_category_modal').modal('hide');
    $('#add_videosubcategory_modal').modal('show');
    $('.text-danger').hide();
    $('.sub_category_form').find('#subcategory_form_submit_btn').text('+ ' + editSubCategoryText);
    $('.add_edit_modal_title').text(editSubCategoryText);
    $('.sub_category_form').attr('action', updateSubCategoryRoute + '/' + $(this).data('id'));
    $.get(showCategoryRoute + '/' + $(this).data('id'), function( data ) {
      $('.sub_category_form').find('#sub_category_name').val(data.name);
      $('.sub_category_form').find('#perent_id').val(data.parent_id);
    });
  });
});
