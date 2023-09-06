/*
 * Theme Custom JS
 */
$(document).ready(function() {
    let timezoneOffset = new Date().getTimezoneOffset();
    Cookies.set('timezone_offset', timezoneOffset, {path    : '/', sameSite: 'Lax'});
    Cookies.set('timezone_name', moment.tz.guess(), {path    : '/', sameSite: 'Lax'});
  
    /*
     * Delete pop up
     */
    $('body').on('click', '.modal-popup-delete', function(e) {
      e.preventDefault();
      var del_url = $(this).data('url');
      $('.modal-delete-confirm').attr('data-url', del_url);
      $('#modal_delete_warning').show();
    });
  
    $('body').on('click', '.modal-delete-confirm', function() {
      var del_url = $(this).attr('data-url');
      $.ajax({
        url: del_url,
        type: 'DELETE',
        success: function(result) {
          $('#modal_delete_warning').hide();
          if(result.data == 'category' || result.data == 'event') {
            window.location.reload();
          }
          window.dataGridTable.ajax.reload();
        }
      });
    });
  
    $('body').on('click', '.modal-close-btn', function() {
      $('#modal_delete_warning').hide();
    });
  
    $('body').on('click', '.my-modal-close', function() {
      removePrintErrorMsg();
      $(this).parents('.modal').modal('hide');
    });
  
    /*
     * View popup
     */
    $('#wrapper-dashboard').on('click','.modal-popup-view',function () {
      var view_url = $(this).data('url');
      $.get({
        url:view_url,
        success:function (data) {
          var view_html = '';
          $.each(data,function(k,v){
              view_html +='<tr><td>'+k+'</td><th>'+v+'</th></tr>';
          });
          $('#modal-table-data').html(view_html);
          $('#modal_for_view').show();
        }
      })
    });
  
    $('body').on('click', '.modal-close-btn-show', function() {
      $('#modal_for_view').hide();
    });
    
    /*
     * Verified user modal
     */
    $('#wrapper-dashboard').on('click','.verified-user-btn',function () {
      var verified_url = $(this).data('url');
      $.get({
        url:verified_url,
        success:function (data) {
          window.dataGridTable.ajax.reload();
        }
      })
    });
  
    $('form input, form select, form textarea').change(function() {
      if ($(this).val()) {
        let name = $(this).attr('name');
        if (!name.includes("[]")) {
          $('.print-error-msg-' + name).hide();
        }
      }
    });
  
    $('body').on('click', '.feather-copy', function (e) {
      copyText($(this));
    });
  
    /**
     * Add Member
     */
    if ($("#add_member_form").length) {
      $('#new_member_button').click(() => {
        $('#add_member_modal').modal('show');
      });
      $('#new_member_button_stats').click(() => {
        $('#add_member_modal').modal('show');
      });
      $('#new_member_profile_image_trigger').click(() => {
        $('#new_member_add_profile_image').trigger('click');
      })
  
      $('#new_member_add_profile_image').change(function(e) {
        loadImageFromInput('new_member_profile_image_preview', e);
      });
  
      $("#add_member_form").submit(function(event) {
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
          success: function(data) {
            if ($.isEmptyObject(data.errors)) {
              if (data.success) {
                window.location.reload();
              }
            } else {
              printErrorMsg(data.errors);
            }
          },
          error: function(data) {
            printErrorMsg(data.responseJSON.errors);
          }
        });
      });
    }
  
    $('.terms-policy').click(function(){
      $('.conditions').trigger('click');
    });
  
    /*
     * Show video when click on top yellow banner
     */
    $('body').on('click', '#top_banner', function (e) {
      e.preventDefault();
      let url = window.location.pathname;
      $("#playVideo").attr('src', '');
      $.get(showBannerVideoRoute, { 'url': url }, function (data) {
        $("#playVideo").attr('src', data.url);
      });
      $('#showVideoModal').modal('show');
    });
  
    /*
     * Stop playing video when close the modal
     */
    $('#showVideoModal').on('hidden.bs.modal', function (e) {
      $("#playVideo").attr('src', '');
    })
  
  });
  
  function printErrorMsg(msg) {
    $.each(msg, function(key, value) {
      $(".print-error-msg-" + key + "").css('display', 'block');
      $(".print-error-msg-" + key + "").html(value[0]);
    });
  }
  
  function surveyErrorMessage(errors, errorContainer) {
    errorContainer.empty();
    $.each(errors, function (key, value) {
      errorContainer.text(value);
    });
    errorContainer.show();
  }
  
  function removePrintErrorMsg() {
    $('body').find('.print-error-msg-file').attr("style","display:none");
    $('body').find('.print-error-msg-name').attr("style","display:none");
    $('body').find('.print-error-msg-email').attr("style","display:none");
    $('body').find('.print-error-msg-first-name').attr("style","display:none");
    $('body').find('.print-error-msg-last-name').attr("style","display:none");
    $('body').find('.print-error-msg-phone').attr("style","display:none");
    $('body').find('.print-error-msg-contact_image').attr("style","display:none");
    $('body').find('.print-error-msg-link').attr("style","display:none");
    $('body').find('.print-error-msg-follow_up_date').attr("style","display:none");
    $('body').find('.print-error-msg-reason').attr("style","display:none");
    $('body').find('.print-error-msg-title').attr("style","display:none");
    $('body').find('.print-error-msg-description').attr("style","display:none");
    $('body').find('.print-error-msg-tags').attr("style","display:none");
    $('body').find('.print-error-msg-document').attr("style","display:none");
    $('body').find('.print-error-msg-image').attr("style","display:none");
    $('body').find('.print-error-msg-parent_id').attr("style","display:none");
    $('body').find('.print-error-msg-video_link').attr("style","display:none");
    $('body').find('.print-error-msg-survey_questions').attr("style","display:none");
    $('body').find('.print-error-msg-custom_title').attr("style","display:none");
    $('body').find('.print-error-msg-video').attr("style","display:none");
    $('body').find('.upload-error-msg').attr("style","display:none");
  }
  
  function loadImageFromInput(element, e,type) {
    if(type == "prospection-video") {
      var fileSize = (e.target.files[0].size);
      if(fileSize > 1000000) {
        $('body').find('.print-error-msg-video_cover_image').removeAttr("style");
        $('body').find('.print-error-msg-video_cover_image').text(fileSizeText);
      }
    }
    var output = document.getElementById(element);
    output.src = URL.createObjectURL(e.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  }
  
  function copyText(element) {
    var input = document.getElementById(element.attr('id')).appendChild(document.createElement("input"));
    input.value = element.data('href');
    input.focus();
    input.select();
    document.execCommand('copy');
    input.parentNode.removeChild(input);
    element.next(".tooltiptext").show();
    element.next('.tooltiptext').html('Copied: ' + element.data('href'));
    setTimeout(function () {
      element.next(".tooltiptext").hide();
    }, 1000);
  }
  
  $(".feather-copy").mouseout(function() {
    $(this).next(".tooltiptext").html("Copy to clipboard");
  });
  
  function cloneFormGroup(source, target, counter, processor) {
    let clone = $(source).html();
    clone = clone.replace(/##/g, counter);
    $(target).append(clone);
    if (processor) {
      clone = processor(clone);
    }
  }
  
  function dropdownFilterFunction(input, dropdownId) {
    let filter,a, i, li;
    //input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    li = $(dropdownId + ' li:not(.search) p');
    $.each(li, function (){
      txtValue = $(this).text();
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        $(this).parent().show();
      } else {
        $(this).parent().hide();
      }
    });
  }
  
  function showFormLoader(form){
    let submitButton = form.find('button:submit');
    submitButton.prop('disabled', true);
    submitButton.after('<span class="form-submit-wait"><i class="fas fa-spin feather-loader"></i></span>');
  }
  
  function removeFormLoader(form){
    let submitButton = form.find('button:submit');
    submitButton.prop('disabled', false);
    form.find('.form-submit-wait').remove();
  }
  
  function addAjaxLoader(jqueryElement, height){
    let html = `<div class="ajax-loader-main" style="min-height: ${height}px"><div class="ajax-loader"><i class="feather-loader"></i></div></div>`;
    jqueryElement.append(html);
    $('#content').css('overflow','hidden');
  }
  
  function removeAjaxLoader(jqueryElement){
    jqueryElement.find('ajax-loader-main').remove();
    $('#content').css('overflow','');
  }
  
  function addBootstrapAjaxLoader(jqueryElement, height){
    let html = `<div class="text-center bootstrap-loader-main"><div class="spinner-border text-primary"></div></div>`;
    jqueryElement.prepend(html);
  }
  
  function removeBootstrapAjaxLoader(jqueryElement){
    jqueryElement.find('bootstrap-loader-main').remove();
  }