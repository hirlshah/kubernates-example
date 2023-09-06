/**
 * Add Prospection Video
 */
$( document ).ready(function() {

  loadSurveyDropdown();
  let currentLink = window.location.href;

  /**
   * Hide default video
   */
  $('#default-video').hide();

  /**
   * Trigger click on browse button
   */
  $('#new_video_trigger').click(() => {
    $('#video').trigger('click');
  })

  /**
   * Show video on change
   */
  $('#video').change(function(e) {
    $('#new_prospection_video').hide();
    $('#edit_prospection_video_show').hide();
    $('#default-video').show();
    var $source = $('#new_prospection_video_preview');
    $source[0].src = URL.createObjectURL(this.files[0]);
    $source.parent()[0].load();
  });

  /**
   * Show prospection video modal
   */
  $('#new_prospection_video_button').click(() => {
    $('#add_prospection_video_form').find('#prospection_video_method').val("POST");
    $("#add_prospection_video_form").trigger("reset");
    $('#default-video').hide();
    $('#new_prospection_video').show();
    $('#add_prospection_video_modal').modal('show');
    $('#edit_prospection_video_show').hide();
    $('.prospection_survey_div').show();
    $('#add_prospection_video_form').find('#prospection_survey_button').show();
    $('#add_prospection_video_form').find('.add-survey-btn').removeClass("d-none");
    $('#add_prospection_video_form').find('.spinner-prospection').addClass('d-none');
    $('#add_prospection_video_form').find('.add-survey-btn').removeClass('d-none');
    $('#add_prospection_video_form').find('.add-survey-btn').attr('style','');
    $('#add_prospection_video_form').find('.update-survey-btn').addClass('d-none');
    $('.add_edit_modal_title').text(addVideoText);
    $('#add_prospection_video_form').find('.print-error-msg-video_cover_image').attr("style","display:none");
    $('#add_prospection_video_form')[0].reset();
    $('#add_prospection_video_form').find('#video_cover_image_preview').attr("src",defaultProspectionImage);
  });

  /**
   * Hide spinner button
   */
  $('#add_prospection_video_form').find('.spinner-prospection').addClass('d-none');

  /**
   * Add prospection video
   */
  $("#add_prospection_video_form").submit(function( event ) {
    event.preventDefault();
    $('#add_prospection_video_form').find('.spinner-prospection').removeClass('d-none');
    $('#add_prospection_video_form').find('.spinner').addClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
    $('.prospection_video_btn').prop('disabled', true);
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)){
          $('#add_prospection_video_form').find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
          var current_progress = 0;
            var interval = setInterval(function() {
              current_progress += 10;
              $(".current-progress").css("width", current_progress + "%").attr("aria-valuenow", current_progress)
              .text(current_progress + "% "+ toCompleteText);
              if (current_progress >= 100)
                clearInterval(interval);
              
                if (current_progress == 100) {
                  window.location.reload();
                }
            },500);
        } else {
          printErrorMsg(data.errors);
          $('#add_prospection_video_form').find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
          $('#add_prospection_video_form').find('.spinner-prospection').addClass('d-none');
          $('.prospection_video_btn').prop('disabled', false);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
        $('#add_prospection_video_form').find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
        $('#add_prospection_video_form').find('.spinner-prospection').addClass('d-none');
        $('.prospection_video_btn').prop('disabled', false);
      }
    });
  });

  /**
   * Add survey
   */
  $("#add_survey_modal").submit(function( event ) {
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)){
          if(data.success){
            $('#add_prospection_video_modal').html('');
            $('#add_prospection_video_survey').html('');
            window.location.reload();
          }
        }else{
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

  /**
   * Add class survey modal
   */
  $('body').on('click','.blue-multisect',function(){
    $('.add_survey_video').removeClass('btn-dove-grey');
    $('.add_survey_video').addClass('btn-blue');
  });

  /**
   * Show delete video modal
   */
  $('body').on('click','.modal-popup-delete-prospection-video',function (e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    var id = $(this).data('id');
    $('.modal-delete-confirm-prospection-video').attr('data-url',del_url);
    $('.modal-delete-confirm-prospection-video').attr('data-id',id);
    $('#modal_delete_warning_prospection_video').modal('show');
  });

  /**
   * Delete prospection video
   */
  $('body').on('click', '.modal-delete-confirm-prospection-video', function(){
    let id = $(this).data('id');
    $.ajax({
      type:'DELETE',
      url: deleteProspectionRoute + '/' + id,
      success: function() {
        $('#modal_delete_warning_prospection_video').modal('hide');
        window.location.reload();
      }
    });
  })

  /**
   * Delete modal close
   */
  $('body').on('click', '.modal-close-btn-prospection-video', function() {
    $('#modal_delete_warning_prospection_video').modal('hide');
  });

  $('body').on('click', '.modal-popup-prospection-video', function(e) {
    e.preventDefault();
    var video = $(this).attr('data-url');
    $('#modal_play_prospection_video').modal('show');
    $('.modal_prospection_play_video').attr('src',video);
  });

  /**
   * Edit video
   */
  $('body').on('click', '.modal-popup-edit-prospection-video', function(){
    $('.text-danger').hide();
    $('#add_prospection_video_modal').modal('show');
    $('#add_prospection_video_form').find('.spinner-prospection').addClass('d-none');
    $('.add_edit_modal_title').text(updateVideoText);
    $('#add_prospection_video_form').attr('action', updateProspectionVideoRoute + '/' + $(this).attr('data-id'));
    $.get(showProspectionVideoRoute + '/' + $(this).attr('data-id'), function( data ) {
      $('#add_prospection_video_form').find('#prospection_video_title').val(data.data.title);
      $('#add_prospection_video_form').find('#prospection_video_custom_title').val(data.data.custom_title);
      $('#add_prospection_video_form').find('#prospection_video_description').val(data.data.description);
      $('#add_prospection_video_form').find('#category').val(data.data.category);
      $('#add_prospection_video_form').find('#prospection_survey_id').val(data.data.survey_id);
      var sub_category_id = data.data.sub_category_id;
      
      /** append sub category values */
      if(data.data.category) {
        let dataCategory = {
          'category_id':data.data.category
        };
        $.get(showSubCategoryRoute, dataCategory, function (response) {
          if(response != '') {
            $('#add_prospection_video_form').find("#sub_category_id").empty();
            $.each(response,function(id,name){
              $('#add_prospection_video_form').find("#sub_category_id").append('<option value="'+name+'">'+id+'</option>');
            });
            if(sub_category_id != 0 && sub_category_id != null) {
             $('#add_prospection_video_form').find("#sub_category_id").val(sub_category_id); 
            }
          } else {
            $('#add_prospection_video_form').find("#sub_category_id").empty();
            $('#add_prospection_video_form').find("#sub_category_id").append('<option value = "">'+ noDataFoundText + '</option>');
          }
        });
      }
      $('#add_prospection_video_form').find('#new_prospection_video').hide();
      $('#add_prospection_video_form').find('#default-video').hide();
      $('#add_prospection_video_form').find('#edit_prospection_video_show').show();
      $('#add_prospection_video_form').find('.modal_prospection_edit_video_show').attr('src', data.data.video);
      $('#add_prospection_video_form').find('#video_cover_image_preview').attr('src', data.data.video_cover_image);
      $('#add_prospection_video_form').find('#survey_id').attr('disabled',true);
      $('#add_prospection_video_form').find('#survey_id').val(data.data.survey_id);
      $('#add_prospection_video_form').find('#prospection_survey_button').show();
      $('#add_prospection_video_form').find('.add-prospection').addClass('d-none');
      $('#add_prospection_video_form').find('.update-prospection').removeClass('d-none');
      $('#prospection_add_survey_form').find('.update-survey-btn').attr("data-survey-id", data.data.survey_id);
      $('#add_prospection_video_form').find('#prospection_video_method').val("PATCH");

      if(data.data.survey_id) {
        $('#prospection_add_survey_form').attr('action', updateSurveyRoute );
        $('.update-survey-btn').attr("data-survey-id", data.data.survey_id);
        $('#add_prospection_video_form').find('.add-survey-btn').addClass("d-none");
        $('#add_prospection_video_form').find('.update-survey-btn').removeClass("d-none");
      } else {
        $('#prospection_add_survey_form').attr('action', storeSurveyRoute );
        $('#add_prospection_video_form').find('.add-survey-btn').attr("data-survey-id", '');
        $('#add_prospection_video_form').find('.add-survey-btn').removeClass("d-none");
        $('#add_prospection_video_form').find('.update-survey-btn').addClass("d-none");
       
        cloneFormGroup('#survey_clone_div', '#survey_target_div', 1, function() {
          $('#survey_target_div:visible .select2-dynamic:not(.select2-hidden-accessible)').select2({
            dropdownParent: $('#prospection_add_survey_modal')
          });
        });
        $("#survey_copy_div").remove();
      }
      
      $('#add_prospection_video_form').find('.add-prospection').addClass('d-none');
      $('#add_prospection_video_form').find('.update-prospection').removeClass('d-none');
    });
  });

  $('#add_prospection_video_modal').on('hidden.bs.modal', function () {
    $('#add_prospection_video_form').find('.modal_prospection_edit_video_show').attr('src', '');
  });

  /**
   * Copy to clipboard with title
   */
  $('body').on('click', '.modal-popup-video-generate', function (e) {
    $('.copy_generate_link_url').removeClass('display');
    $('.copy_generate_link_url').children().html(copyLinkText);
    setTimeout(copyText($(this)), 1500);
  });

  /**
   * Category filter
   */
  $('body').on('click', '#prospection_video_category_filter > label:not(.active)', function (){
    let filter = $(this).find('.prospection_video_filter').val();
    loadData(currentLink, {'category_filter': filter});
  });

  /**
   * Add and remove active class
   */
  $('body').on('click','.prospection-video',function(){
    $(".prospection-video").removeClass("active");
    $(this).addClass("active");
  });

  /**
   * Play video modal close
   */
  $('body').on('click','.modal-close-play-video',function(){
    $('#modal_play_prospection_video').modal('hide');
  });

  function loadData(link, params){
    if(!params) params = {};
    $.get(link, params, function (response){
      $('#prospection_video-ajax-list').html(response);
      $('.prospection_video_search').html('');
    });
  }

  /**
   * Copy prospection text
   */
  function copyText(element) {
    $('#prospection_link_copy').val(element.data('href'));
    var copyText = document.getElementById("prospection_link_copy");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
    element.next('.copy_generate_link_url').children().html(copyText.value);
    element.next('.copy_generate_link_url').addClass('show');
    element.next('.copy_generate_link_url').addClass('display');

    $(document).mouseup(function(e) {
      var container = $(".copy_generate_link_url");
      if (!container.is(e.target) && container.has(e.target).length === 0){
        element.next(container).removeClass('show');
        element.next('.copy_generate_link_url').removeClass('display');
        element.next('.copy_generate_link_url').children().html(copyLinkText);
      }
    });

    setTimeout(function () {
      element.next('.copy_generate_link_url').removeClass('display');
      element.next('.copy_generate_link_url').children().html(copyLinkText);
    }, 10000);
  }

  //Toggle the active class to the area is hovered

  $(".copy-link-tooltip").mouseenter(function(){
    $(this).find('.copy_generate_link_url').toggleClass("show");
  });

  $(".copy-link-tooltip").mouseleave(function(){
    $(this).find('.copy_generate_link_url').toggleClass("show");
  });

  /**
   * Setup before function
   */
  var typingTimer;
  var doneTypingInterval = 2000;
  var $input = $('.prospection_video_search');

  /**
   * Keyup start the countdown
   */
  $input.on('keyup', function (event) {
    clearTimeout(typingTimer);
    if (event.keyCode === 13) {
      doneTyping ();
    }else{
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    }
  });

  /**
   * Search video prospection
   */
  function doneTyping () {
    loadData(currentLink, {'search': $input.val()});
  }
   
  /**
   * Survey dropdown
   */
  function loadSurveyDropdown() {
    $.getJSON('/seller/survey/get-list', {}, function(response) {
      if (response) {
        let options = "";
        var surveyId = $('#prospection_survey_id').val();
        if(surveyId) {
          options += `<option value=''>`+selectSurveyText+`</option>`;
        }
        options += `<option value=''>`+selectSurveyText+`</option>`;
        $('#survey_id').html(options);
      }
      if ($('#survey_id option').length) {
        $('#no-survey-span').hide();
        $('#survey_id').show();
      } else {
        $('#no-survey-span').show();
        $('#survey_id').hide();
      }
    });
  }

  /**
   * Add Survey
   */
  let survey_question_counter = 0;
  if ($("#prospection_add_survey_form").length) {
    $('body').on('click','#prospection_add_question_row',function(){
      survey_question_counter++;
      cloneFormGroup('#survey_copy_div', '#survey_target_div', survey_question_counter, function(survey_question_counter) {
        $('#survey_target_div:visible .select2-dynamic:not(.select2-hidden-accessible)').select2({
          dropdownParent: $('#prospection_add_survey_modal')
        });
      });
      $('#survey_target_div .text-danger.print-error-msg-survey_questions:last').css('display', 'none');
    });

    $('body').on('click','#prospection_update_question_row',function(){
      let survey_update_question_counter = $("#survey_copy_div > div").length;
      survey_update_question_counter++;
      cloneFormGroup('#survey_clone_div', '#survey_copy_div', survey_update_question_counter, function(survey_update_question_counter) {
        $('#survey_target_div:visible .select2-dynamic:not(.select2-hidden-accessible)').select2({
          dropdownParent: $('#prospection_add_survey_modal')
        });
      });
    });

    $('#prospection_add_survey_modal').on('click', '.prospection_remove_question_row', function() {
      $(this).closest('.row').remove();
    });
    $('#prospection_add_question_row').trigger('click');
    
    let myModalEl = document.getElementById('prospection_add_survey_modal')
    myModalEl.addEventListener('shown.bs.modal', function(event) {
      $('#survey_target_div .select2-dynamic').select2({
        dropdownParent: $('#prospection_add_survey_modal')
      });
    })
    myModalEl.addEventListener('hidden.bs.modal', function(event) {
      $('#survey_target_div .select2-dynamic').select2('destroy');
    })

    $('.add-survey-btn').click(() => {
      $('#prospection_add_survey_form')[0].reset();
      $('#prospection_add_survey_modal').modal('show');
    });

    $('body').on('click','.update-survey-btn',function() {
      $('#prospection_add_survey_form')[0].reset();
      $('#prospection_add_survey_modal').find('.survey-text').text(updateSurveyText);
      $('#prospection_add_survey_modal').find('.update-survey').text(updateSurveyText);
      $('#prospection_add_survey_modal').modal('show');
      $('#add_prospection_video_modal').modal('hide');

      $.get(showSurveyQuestionAnswerRoute + '/' + $(this).attr('data-survey-id'), function( data ) {
        if(data) {
          $('.video-survey').html(data.data);
        }
      });

    });

    $('body').on('submit','#prospection_add_survey_form',function(event) {
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
            $("#prospection_survey_id").attr("value",data.data.id);
            if (data.success) {
              loadSurveyDropdown();
              $('#prospection_add_survey_modal').modal('hide');
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
   * Show sub category modal
   */
  $('#new_prospection_video_sub_category_button').on('click',function(){
    $('#add_videosubcategory_modal').modal('show');
  });

  /**
   *Sub category filter
   */
  $(document).on('click', '#prospection-sub-category-filter', function (){
    let subcategoryfilter = $(this).find('.sub_category-filter').val();
    loadData(currentLink, {'sub_category_filter': subcategoryfilter});
  });

  $('#video_cover_image_trigger').click(() => {
    $('#video_cover_image').trigger('click');
  });

  $('#video_cover_image').change(function(e) {
    let type = "prospection-video";
    loadImageFromInput('video_cover_image_preview', e,type);
  });
    
});
