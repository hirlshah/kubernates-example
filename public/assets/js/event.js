let tag_counter = 0;
$(function (){
  $.datetimepicker.setLocale('fr');
  $('[data-toggle="tooltip"]').tooltip();

  /**
   * Add event
   */
  if ($("#add_event_form").length) {
    $('#new_tag_button').click(function (){
      tag_counter++;
      $('#d_tag_'+tag_counter).show();
      if(tag_counter >= 2){
        $(this).hide();
      }
    });

    loadSurveyDropdown();
    $('#add_media_button').click(() => {
      $('#add_document_video_modal').modal('show');
    });

    $('#add_doc_vid_to_survey').click(function (){
      let count = 0;
      let documents = $.map($('.event_documents:checked'), function(n, i){
        count++;
        return n.value;
      }).join(',');
      let videos = $.map($('.event_videos:checked'), function(n, i){
        count++;
        return n.value;
      }).join(',');
      $('#event_documents').val(documents);
      $('#event_videos').val(videos);
      $('#media_count').text(` (${count} Added)`);
      $('#add_document_video_modal').modal('hide');
    });
    jQuery.datetimepicker.setLocale(lang);
    $('#event_meeting_date').datetimepicker({
      timepicker: false,
      format: 'd/m/Y',
      minDate : new Date()
    });

    $('#event_meeting_time').datetimepicker({
      datepicker: false,
      format: 'H:i',
      step: 30
    });

    $('#new_event_image_trigger').click(() => {
      $('#new_event_image').trigger('click');
    })

    $('#new_event_image').change(function(e) {
      loadImageFromInput('new_event_image_preview', e);
    });

    $(document).on('change', '.survey-questions-select', function() {
      if (ratingQuestions.includes(parseInt($(this).val()))) {
        $(this).parent().parent().find('.survey-answers-select').val('').prop('disabled', true).trigger('change');
      } else {
        $(this).parent().parent().find('.survey-answers-select').prop('disabled', false);
      }
    });

    let myModalEl = document.getElementById('add_event_modal')
    myModalEl.addEventListener('shown.bs.modal', function(event) {
        $('#add_event_modal .select2-dynamic').select2({
            dropdownParent: $('#add_event_modal')
        });
    })
    myModalEl.addEventListener('hidden.bs.modal', function(event) {
      $('#add_event_modal .select2-dynamic').select2('destroy');
    })

    $('#new_event_button').click(() => {
      $('.text-danger').hide();
      $('#add_event_form').trigger("reset");
      $('#add_event_form').attr('action', storeEventRoute);
      $('#add_event_form').attr('method', 'POST');
      $('#add_event_form').find('#launchEventBtn').text(eventEditButtonText);
      $('#add_event_form').find('#event_method').val("POST");
      $('.event_modal_title').text(createEventText);
      $("#new_event_image_preview").attr({ "src": companyDefaultImage });
      $('#add_event_modal').modal('show');
    });

    $("#add_event_form").submit(function(event) {
      event.preventDefault();
      $('.d_tag').each(function (){
        if($(this).val().trim() == ''){
          $(this).prop('disabled', true);
        }
      });

      $('#launchEventBtn').html('<i class="feather-loader"></i>');
      $('#launchEventBtn').prop('disabled', true);

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
              window.location.href = data.redirect_url;
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function(data) {
          printErrorMsg(data.responseJSON.errors);
        },
        complete: function (){
          $('.d_tag').prop('disabled',false);
          $('#launchEventBtn').html('Launch event');
          $('#launchEventBtn').prop('disabled', false);
        }
      });
    });
  }
  
  /**
   * Edit event
   */
  $('body').on('click', '.modal-popup-edit-event', function(){
    $('#add_event_modal').modal('show');
    $('.text-danger').hide();
    $('#add_event_form').attr('action', updateEventRoute + '/' + $(this).data('id'));
    $.get(showEventRoute + '/' + $(this).data('id'), function( data ) {
      $('#add_event_form').find('#event_name').val(data.data.name);
      $('#add_event_form').find('#event_content').val(data.data.content);
      $('#add_event_form').find('#event_meeting_date').val(data.data.meeting_date);
      $('#add_event_form').find('#event_meeting_time').val(data.data.meeting_time);
      $('#add_event_form').find('#meeting_url').val(data.data.meeting_url);
      $('#add_event_form').find('#presenter').val(data.data.presenter);
      $('#add_event_form').attr('method', 'patch');
      $('#add_event_form').find('#launchEventBtn').text(eventEditButtonText);
      if(data.data.presenter != null) {
        $("#presentator_id").val(data.data.presenter);
      }
      $("#presentator_name").val(data.data.presentator_name);
      $('#add_event_form').find('#event_method').val("PATCH");
      $('.event_modal_title').text(eventEditText);
      if(data.data.image) {
        $("#new_event_image_preview").attr({ "src": imageUrl + "storage/" + data.data.image });
      } else {
        $("#new_event_image_preview").attr({ "src": companyDefaultImage });
      }
    });
  });

  /**
   * Delete event
   */
  $('body').on('click', '.modal-popup-delete-event', function(e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-confirm').attr('data-url', del_url);
    $('#modal_delete_warning').show();
    $('.modal_title').text('Voulez-vous vraiment supprimer cet événement ?');
  });

  /**
   * Add survey
   */
  let survey_question_counter = 0;
  if ($("#add_survey_form").length) {
    $('#add_question_row').click(function() {
      survey_question_counter++;
      cloneFormGroup('#survey_copy_div', '#survey_target_div', survey_question_counter, function(survey_question_counter) {
        $('#survey_target_div:visible .select2-dynamic:not(.select2-hidden-accessible)').select2({
          dropdownParent: $('#add_survey_modal')
        });
      });
      $('#survey_target_div .text-danger.print-error-msg-survey_questions:last').css('display', 'none');
    });
    $('#add_survey_modal').on('click', '#remove_question_row', function() {
      $(this).closest('.row').remove();
    });
    $('#add_question_row').trigger('click');
    $('#add_question_row').trigger('click');
    let myModalEl = document.getElementById('add_survey_modal')
    myModalEl.addEventListener('shown.bs.modal', function(event) {
      $('#survey_target_div .select2-dynamic').select2({
        dropdownParent: $('#add_survey_modal')
      });
    })
    myModalEl.addEventListener('hidden.bs.modal', function(event) {
      $('#survey_target_div .select2-dynamic').select2('destroy');
    })

    $('#new_survey_button').click(() => {
      $('#add_survey_modal').modal('show');
    });

    $("#add_survey_form").submit(function(event) {
      event.preventDefault();
      $('.print-error-msg-survey_questions').css('display', 'none');
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);
      formData.delete('survey_questions[##]');

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
          if ($.isEmptyObject(data.errors)) {
            $("#event_survey_id").attr("value",data.data.id);
            if (data.success) {
              loadSurveyDropdown();
              $('#add_survey_modal').modal('hide');
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function(data) {
          surveyErrorMessage(data.responseJSON.errors, $('.print-error-msg-survey_questions'));
        }
      });
    });
  }
  
  /**
   * Download ics file
   */
  $('body').on('click', '.download-ics', function() {
    window.location.href = $(this).data('url');
    return true;
  });

  /**
   * Add event reps
   */
  $(document).on('click', '.event-reps-add-btn', function (e) {
    event.preventDefault();
    const eventid = $(this).attr('data-id');
    $.get($(this).data('href'), {}, function (response){
      if(response.success){
        $('#confirm-presence-'+eventid).hide();
        $('#confirmed-presence-'+eventid).css("display", "inherit");
      }
    });
  });

  /**
   * One-on-one call
   */
  $('#one-on-one-call').click(function () {
    $('.copy-event-one-on-one-link .tooltiptext').show();
    $.ajax({
      type: "GET",
      url: oneOnOneCall,
      success: function (response) {
        if(response.success){                    
          var input = document.getElementById("copy-event-one-on-one-link").appendChild(document.createElement("input"));
          input.value = response.url;
          input.focus();
          input.select();
          document.execCommand('copy');
          input.parentNode.removeChild(input);
          setTimeout(function () {
            $('.copy-event-one-on-one-link .tooltiptext').hide();
          }, 2000);
        } else {
          if(response.redirect_url) {
            window.location.href = response.redirect_url;
          }
        }
      },
      error:function(data){
        $('.copy-event-one-on-one-link .tooltiptext').hide();
        alert("There\'s some issue.");
      },
      dataType: "json",
    });
  });

  /**
   * Event repo - not used for now
   */
  $('body').on('click', '.event-repo', function() {
    let event_url = $(this).data('event-url');
    $.ajax({
      type: 'GET',
      url: $(this).data('url'),
      cache: false,
      processData: false,
      contentType: false,
      success: function(data) {
        if ($.isEmptyObject(data.errors)) {
          window.location.href = event_url;
        } else {
          printErrorMsg(data.errors);
        }
      },
      error: function(data) {
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });
});

/**
 * Load survey dropdown
 */
function loadSurveyDropdown() {
  $.getJSON('/seller/survey/get-list', {}, function(response) {
    if (response) {
      let options = "";
      var surveyId = $('#event_survey_id').val();
      if(surveyId) {
        options += `<option value=''>`+defaultSurveyOption+`</option>`;
      }
      options += `<option value=''>`+surveyText+`</option>`;
      for (const key in response) {
        options += `<option value='${key}'>${response[key]}</option>`;
      }
      $('#event_survey_list').html(options);
    }
    if ($('#event_survey_list option').length) {
      $('#no-survey-span').hide();
      $('#event_survey_list').show();
    } else {
      $('#no-survey-span').show();
      $('#event_survey_list').hide();
    }
  });
}

/**
 * Copy event link
 */
$('.copy-event-link').click(function () {
  copyText($(this));
});
 
function copyText(element) {
  var input = document.getElementById(element.attr('id')).appendChild(document.createElement("input"));
  input.value = element.data('href');
  input.focus();
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);
  element.next(".tooltiptext").show();
  element.next('.tooltiptext').html(copiedText);
  setTimeout(function () {
    element.next(".tooltiptext").hide();
  }, 1000);
}

/**
 * Show user list modal
 */
$("body").on("click", ".users_modal_btn", function(e) {
  $('#user_list_modal').modal('show');
  $('.text-danger').hide();
});

$('#user_list_modal').on('shown.bs.modal', function () {
  currentUsersPage = 1;
  $('#users_search').val('');
  $('.users_list').html('');
  users_list(currentUsersPage);
});

var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 1 second for example

/**
 * Search user
 */
$('body').on('keyup', '#users_search', function (e) {
  $('.spinner').addClass('text-primary spinner-border spinner-border-lg w-4 h-4 mx-2');
  clearTimeout(typingTimer);
  $('.users_list').empty();
  currentModalPeoplePage = 1;
  if(event.keyCode === 13) {
    users_list(currentUsersPage);
  } else {
    typingTimer = setTimeout(users_list, doneTypingInterval);
  }
});

/**
 * Change value
 */
$("body").on("change", ".user_listing_check input[type='radio']", function() {
  if ($(this).is(":checked")) {
    var selectedValue = $(this).val();
    var selectedOptionText = $(this).siblings('label.people-check-label').find('span.fs-16').text();
    $("#presentator_id").val(selectedValue);
    $("#presentator_name").val(selectedOptionText);
    $('#user_list_modal').modal('hide');
  }
});