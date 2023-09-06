$(window).on('load', function (e) {
  $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 433);
  $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
});

$( document ).ready(function() {

  $('#contactDetail').find('p.label-modal-text').html('');
  /**
   *  Education JS
   */
  $(".dummy-scroll-main").on('scroll', function (e) {
    $(".drag-drop-scroll-main").scrollLeft($(".dummy-scroll-main").scrollLeft());
  });
  $(".drag-drop-scroll-main").on('scroll', function (e) {
    $(".dummy-scroll-main").scrollLeft($(".drag-drop-scroll-main").scrollLeft());
  });
  if ($("#add_education_form").length) {
    $('#education_start_date').datetimepicker({
      timepicker: false,
      format: 'd/m/Y'
    });

    $('#education_end_date').datetimepicker({
      timepicker: false,
      format: 'd/m/Y'
    });

    $('#add_education_button').click(() => {
      document.getElementById('add_education_form').reset();
      $('#addEducation').modal('show');
    });

    $('#new_education_image_trigger').click(() => {
      $('#new_education_image').trigger('click');
    })

    $('#new_education_image').change(function (e) {
      loadImageFromInput('new_education_image_preview', e);
    });

    $('#scholarship-current-job').on('change', function() {
      var checked = this.checked
      if(checked) {
        document.getElementById('education_end_date').disabled = true;
        document.getElementById('education_end_date').value = null;
      }else{
        document.getElementById('education_end_date').disabled = false;
        document.getElementById('education_end_date').value = null;
      }
    });

    /**
     *  Add Education
     */
    $("#add_education_form").submit(function (event) {
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
          if ($.isEmptyObject(data.errors)) {
            if (data.success) {
              window.location.reload();
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function (data) {
          printErrorMsg(data.responseJSON.errors);
        }
      });
    });
  }

  /**
   * Experience Js
   */
  if ($("#add_experience_form").length) {
    $('#experience_start_date').datetimepicker({
      timepicker: false,
      format: 'd/m/Y'
    });

    $('#experience_end_date').datetimepicker({
      timepicker: false,
      format: 'd/m/Y'
    });

    $('#add_experience_button').click(() => {
      document.getElementById('add_experience_form').reset();
      $('#addExperience').modal('show');
    });

    $('#new_experience_image_trigger').click(() => {
      $('#new_experience_image').trigger('click');
    })

    $('#new_experience_image').change(function (e) {
      loadImageFromInput('new_experience_image_preview', e);
    });

    $('#experience-current-job').on('change', function() {
      var checked = this.checked
      if(checked) {
        document.getElementById('experience_end_date').disabled = true;
        document.getElementById('experience_end_date').value = null;
      }else{
        document.getElementById('experience_end_date').disabled = false;
        document.getElementById('experience_end_date').value = null;
      }
    });

    /**
     * Add Experience
     */
    $("#add_experience_form").submit(function (event) {
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
          if ($.isEmptyObject(data.errors)) {
            if (data.success) {
              window.location.reload();
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function (data) {
          printErrorMsg(data.responseJSON.errors);
        }
      });
    });
  }

  /**
   * Delete experience
   */
  $('body').on('click', '#delete-edu-exp', function(e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-edu-exp-confirm').attr('data-url', del_url);
    $('#modal_delete_edu_exp_warning').show();
  });

  $('body').on('click', '.modal-close-btn', function() {
    $('#modal_delete_edu_exp_warning').hide();
  });

  $(".modal-delete-edu-exp-confirm").click(function (event) {
    event.preventDefault();
    $.ajax({
      type: 'POST',
      url: $(this).data('url'),
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        if ($.isEmptyObject(data.errors)) {
          if (data.success) {
            $('#modal_delete_edu_exp_warning').hide();
            window.location.reload();
          }
        }
      }
    });
  });

   /**
   * Update Profile Js
   */
  if ($(".update_profile_form").length) {

    $('#update_profile_button').click(() => {
      $('#update_profile').modal('show');
    });

    /**
     * Update Experience
     */
    $(".update_profile_form").submit(function (event) {
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
          if ($.isEmptyObject(data.errors)) {
            if (data.success) {
             $('.profile-page-name').html(data.data.name);
             $('.profile-page-description').html(data.data.description);
              $('#update_profile').modal('hide');
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function (data) {
          printErrorMsg(data.responseJSON.errors);
        }
      });
    });
  }

  /**** download ics file ***/
  $('body').on('click', '.download-event-ics', function() {
    window.location.href = $(this).data('url');
    return true;
  });

  $('.info-text-input').hide();
  $('body').on('click', '#info', function() {
    $('.info-text').hide();
    $('.info-text-input').show();
    $(".info-save-btn").show();  
  });

  /**
   * Update Profile Info Js
   */
  if ($(".update_profile_info").length) {

    /**
     * Update Profile Info
     */
    $(".update_profile_info").submit(function (event) {
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function (data) {
          if ($.isEmptyObject(data.errors)) {
            if (data.success) {
              $('.profile-page-info').html(data.data.name);
              $('.info-text').show();
              $('.info-text-input').hide();
              $(".info-save-btn").hide(); 
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function (data) {
          printErrorMsg(data.responseJSON.errors);
        }
      });
    });
  }

  /**
   * Load Task popup
   */
  $("body").on("click", ".add_edit_task", function(e) {
    $('#add_task_form').trigger("reset");
    $('#dailiesModal').modal('show');
    $('#task-data').html('');
    loadTask(false,$(this).data('type'));
    $('.text-danger').hide();
  });

  /**
   * Add Task popup
   */
  $("body").on("click", "#add_task", function(e) {
    loadTask(true,$(this).data('type'));
  });

  /**
   * Close  model
   */
  $('body').on('click', '.my-modal-close', function() {
    $(this).parents('.modal').modal('hide');
  });
});

/**
 * Contact details model
 */
$(function () {
  $('body').on('click', '.contact-user-card', function(){
    $('#contactDetail').addClass('modal-data-detail');
    $('#contactDetail').find('.add-contact-label-modal').attr('data-id', $(this).data('id'));
    $('#contactDetail').find('.contact-edit').trigger('click');
    $.get(showRoute + '/' + $(this).data('id'), function( data ) {
      $('#contactDetail').find('.contact_name').text(data.data.name);
      $('#contactDetail').find('#contact_edit_image_preview').css('background-image', 'url("' + data.data.user_pic + '")');
      $('#contactDetail').find('#contact_edit_full_name').val(data.data.name);
      $('#contactDetail').find('#contact_edit_email').val(data.data.email);
      $('#contactDetail').find('#contact_edit_phone').val(data.data.phone);
      $('#contactDetail').find('#contact_edit_contacted_through').val(data.data.contacted_through);
      $('#contactDetail').find('#contact_edit_message').val(data.data.message);
      $('#contactDetail').find('p.label-modal-text').html('');
      
      if(data.data.label){
        $.each(data.data.label,function(key,value){
          if(value.name) {
            $('#contactDetail').find('p.label-modal-text').append('<span style="font-weight: bold;color:white;margin-bottom: 10px;width: fit-content;padding: 0 10px;border-radius:50px;background-color:'+value.color+'">'+value.name+'</span>');
          }
        });
      }
      $('#contactDetail').find('#contacted_follow_up_date').val(data.data.follow_up_date);
      $('#contactDetail').attr('data-id', data.data.id);
      $('.contact-user-card[data-id="'+data.data.id+'"]').find('.contact-user-card-profile-add').css('background-image', 'url("' + data.data.user_pic + '")');
    });
    $('#contactDetail').modal('show');
  });

  /*
   * Update Profile Photo
   */
  $('.image_update').on('change',function(ev){
    var postData = new FormData();
    postData.append('profile_image',this.files[0]);

    $.ajax({
      headers:{'X-CSRF-Token':$('meta[name=csrf_token]').attr('content')},
      async:true,
      type:"post",
      contentType:false,
      url:profilePicUploadRoute,
      data:postData,
      processData:false,
      success:function(data){
        if(data.success) {
          console.log("success");      
        }else {
          printErrorMsg(data.errors);
        }
      },
      error: function (data) {
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

});
