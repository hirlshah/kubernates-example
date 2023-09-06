let callbackAction;
let phoneValidation = false;
let blockDuplication = false;

$(window).on('load', function (e) {
  $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 351);
  $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
});

$(function () {
  var statusRangeKeysParseArr = jQuery.parseJSON(statusRangeKeysArr);
  $(".dummy-scroll-main").on('scroll', function (e) {
    $(".drag-drop-scroll-main").scrollLeft($(".dummy-scroll-main").scrollLeft());
  });

  $(".drag-drop-scroll-main").on('scroll', function (e) {
    $(".dummy-scroll-main").scrollLeft($(".drag-drop-scroll-main").scrollLeft());
  });

  removeAjaxLoader($('#ContactDataDiv'));

  /** Search contacts */
  var searchBar = document.getElementById("searchContact");
  const debounce = (func, wait, immediate) => {
    var timeout;
    return function executedFunction() {
      var context = this;
      var args = arguments;

      var later = function () {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };

      var callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };
  searchBar.addEventListener('keyup', debounce(function () {
    var searchText = $(this).val();
    var sorting_data = $('#sorting-contacts').val();
    getContactBoardFirstColumnDataFunction(getContactBoardFirstColumnData + '?search=' + searchText +'&sorting_data=' + sorting_data);
    $.each(statusRangeKeysParseArr, function(key,value) {
      getBoardStatusDataFunction(getBoardStatusData + '/' + value + '?search=' + searchText +'&sorting_data=' + sorting_data, value);
    });
  }, 1000));

  $('.follow_up_date_picker').datetimepicker({
    timepicker:false,
    format:'d/m/Y'
  });

  /**
   * Create contact form submit
   */
  $("#create-contact-form" ).submit(function( event ) {
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    let boardId = $('#board_id_hidden').val();
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
            let isProfileComplete = data.data.email && data.data.phone ? true : false;
            let html = `<div class="flex-column contact-user-card draggable-user-card" data-id="${data.data.id}" data-status-id="0" data-board-id="${boardId}" data-is-complete-profile="${isProfileComplete}" data-follow-count="${data.data.follow_up_count}">
                          <div class="contact-label"></div>
                          <div class="row">
                            <div class="col-4">
                                <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="selected_image"
                                      style="background-image: url(${data.data.user_pic});">
                                </div>
                            </div>
                            <div class="col-8 m-auto card-contact-name">${data.data.name}</div>
                            </div>
                        </div>`;
            $('#contact_list').append(html);
            $('#addNewContact').modal('hide');
            $('#addNewContact input').val('');
            addListeners();
            window.location.reload();
          }
        }else{
          printErrorMsg(data.errors);
        }
      },
      error: function (data) {
        if (data.statusText == 'Unauthorized') {
          location.reload();
        }
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  $('#contact_image_button').click(()=>{
    $('#contact_image').trigger('click');
  })

  $('#contact_edit_image_button').click(()=>{
    $('#contact_edit_image').trigger('click');
  })

  $('#contact_image').change(function (e){
    loadImageFromInputDiv('contact_image_preview', e);
  });

  $('#contact_edit_image').change(function (e){
    loadImageFromInputDiv('contact_edit_image_preview', e);
  });

  /**
   * Contact user card add
   */
  $('body').on('click','.contact-user-card-add', function(){
    if($('.add-more').length <= 0) {
      $('#addNewContact').find('#contact_image_preview').attr('src','/assets/images/add-member-large.png');
      //$('#addNewContact').modal('show');
      let html = `<div class="col-12 flex-column create-contact-user-card draggable-user-card add-more" data-id="" data-status-id="" data-board-id="" data-is-complete-profile="0">
                <div class="contact-label"></div>
                <div class="row">
                <div class="col-4">
                    <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="selected_image"
                        style="background-image: url(${userIcon});">
                    </div>
                </div>
                <div class="col-8 m-auto card-contact-name">
                    <input type="text" class="form-control add_contact" placeholder="`+fullNameText+`" id="contact_full_name" name="name" value="">
                </div>
                </div>
            </div>`;
      $('.add_contact_list').append(html);
      $('#contact_full_name').focus();
    }
  })

  $(document).on('keypress', '.add_contact', function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
      if ($(this).val() != ''){
        createContact();
        $(this).val('');
      }
      return false;
    }
  });

  $(document).on('blur', '.add_contact', function () {
    if ($(this).val() != ''){
      createContact();
      $(this).val('');
    }
  });

  /** Delete Contact Start */

  $('.contact-delete').click(()=>{
    $('#deleteContactConfirm').find('.delete_contact_name').text($('#contactDetail').find('#contact_edit_full_name').val());
    if($('#contactDetail').find('#contact_edit_image_preview').attr('src')){
      $('#deleteContactConfirm').find('.delete_contact_preview').attr('src',$('#contactDetail').find('#contact_edit_image_preview').attr('src'));
    }
    $('#deleteContactConfirm').modal('show');
  })

  $('body').on('click', '.contact-delete-outer', function(event){
    event.stopPropagation();
    $('#deleteContactConfirm').find('.delete_contact_name').text($(this).data('name'));
    $('#deleteContactConfirm').find('.delete_contact_preview').attr('src', $(this).data('image'));
    $('#contact_delete_confirm').attr('data-id', $(this).data('id'));
    $('.contact_delete_id').val($(this).data('id'));
    $('#deleteContactConfirm').modal('show');
  })

  $('#contact_delete_cancel').click(()=>{
    $('#deleteContactConfirm').modal('hide');
  })

  $('body').on('click', '#contact_delete_confirm', function(){
    let id = $('.contact_delete_id').val();
    if($('#contactDetail').attr('data-id')){
      id = $('#contactDetail').attr('data-id');
    }
    $.ajax({
      type:'DELETE',
      url: deleteRoute + '/' + id,
      success: function(result) {
        $('.contact-board').find('[data-id="'+id+'"]').remove();
        $('.contact_delete_id').val('');
        $('#contactDetail').attr('data-id','');
        $('#deleteContactConfirm').modal('hide');
        $('#contactDetail').modal('hide');
      }
    });
  })
  /** Delete Contact End */

  /**
   * Contact detail
   */
  $('body').on('click', '.contact-user-card', function(){
    $('#contactDetail').find('.view_link').hide();
    $('#contactDetail').find('#contact_link').hide();
    $('#contactDetail').addClass('modal-data-detail');
    $('#contactDetail').find('.contact-edit').trigger('click');
    $('#contactDetail').find('.ai-writing-btn').attr('data-id', $(this).data('id'));
    $('#contactDetail').find('.add-contact-label-modal').attr('data-id', $(this).data('id'));
    $('#update-contact-form').attr('action', updateRoute + '/' + $(this).data('id'));
    $.get(showRoute + '/' + $(this).data('id'), function( data ) {
      $('#contactDetail').find('.contact_name').text(data.data.name);
      $('#contactDetail').find('#contact_edit_image_preview').css('background-image', 'url("' + data.data.user_pic + '")');
      $('#contactDetail').find('#contact_edit_full_name').val(data.data.name);
      $('#contactDetail').find('#contact_edit_email').val(data.data.email);
      $('#contactDetail').find('#contact_edit_phone').val(data.data.phone);
      $('#contactDetail').find('#contact_edit_contacted_through').val(data.data.contacted_through);
      $('#contactDetail').find('#contact_edit_created_at').val(data.data.created_at);
      $('#contactDetail').find('#contact_edit_message').val(data.data.message);
      $('#contactDetail').find('#contact_link').val(data.data.link);
      $('#contactDetail').find('p.label-modal-text').html('');

      if(data.data.label){
        $.each(data.data.label,function(key,value){
          if(value.name) {
            $('#contactDetail').find('p.label-modal-text').append('<span style="font-weight: bold;color:white;margin-bottom: 10px;width: fit-content;padding: 0 10px;border-radius:50px;background-color:'+value.color+'">'+value.name+'</span>');
          }

        });
      }

      if(data.data.link != null){
        $('#contactDetail').find('.view_link').show();
        $('#contactDetail').find('.view_link').attr('href',data.data.link);
        $('#contactDetail').find('.view_link').text(data.data.link);
        $('#contactDetail').find('.edit_link').show();
      } else {
        $('#contactDetail').find('#contact_link').show();
        $('#contactDetail').find('.edit_link').hide();
      }
      
      $('#contactDetail').find('#contacted_follow_up_date').val(data.data.follow_up_date);
      $('#contactDetail').attr('data-id', data.data.id);
      $('.contact-user-card[data-id="'+data.data.id+'"]').find('.contact-user-card-profile-add').css('background-image', 'url("' + data.data.user_pic + '")');
      $('#survey-details').html(data.survey);
      $('.print-error-msg-email').hide();
      $('.print-error-msg-phone').hide();
      if (phoneValidation == true) {
        phoneValidation = false;
      }
    });
    $('#contactDetail').modal('show');
  });

  $('.contact-edit').click(function(){
    $('#contactDetail').removeClass('modal-data-detail');
    $('#contactDetail :input').prop('readonly', false);
    $('.follow_up_date_picker').removeAttr('disabled');
  });

  $('.edit_link').click(function(e){
    $('#contactDetail').find('.view_link').hide();
    $('#contactDetail').find('#contact_link').show();
  });

  /**
   * Update contact form submit
   */
  $( "#update-contact-form" ).submit(function( event ) {
    event.preventDefault();
    event.stopPropagation();
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
            let isProfileComplete = data.data.email && data.data.phone ? true : false;
            $('#contactDetail').addClass('modal-data-detail');
            $('#contactDetail :input').prop('readonly', true);
            $('.contact-user-card[data-id="'+data.data.id+'"]').find('.contact-user-card-profile-add').css('background-image', 'url("' + data.data.user_pic + '")');
            $('.contact-user-card[data-id="'+data.data.id+'"]').find('.card-contact-name').text(data.data.name);
            $('.contact-user-card[data-id="'+data.data.id+'"]').find('.contact-image').attr('src', data.data.user_pic);
            $('.contact-delete-outer[data-id="'+data.data.id+'"]').attr('data-image', data.data.user_pic);
            $('#contactDetail').modal('hide');
            $('.contact-user-card[data-id="'+data.data.id+'"]').attr('data-is-complete-profile',isProfileComplete);
            $('.contact-user-card[data-id="'+data.data.id+'"]').data('isCompleteProfile',isProfileComplete);
            $('.contact-user-card[data-id="'+data.data.id+'"]').attr('data-follow-count',data.data.follow_up_count);
            $('.contact-user-card[data-id="'+data.data.id+'"]').data('followCount',data.data.follow_up_count);
            applyColorCoding();
          } else {
            $('#contactDetail').modal('hide');
          }
        }else{
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  /**
   * Contact message send form submit
   */
  $( "#contactMessageSendForm" ).submit(function( event ) {
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
            callbackAction();
            $('#contactSendMessage textarea').val('');
            $('#contactSendMessage #contact_send_message_id').val('');
            $('#contactSendMessage').modal('hide');
          }
        }else{
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  $('#contact_follow_up_date').datetimepicker({
    timepicker:false,
    format:'d/m/Y'
  });

  /**
   * Contact follow up form submit
   */
  $( "#contactFollowUpForm" ).submit(function( event ) {
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
            callbackAction();
            $('#contactFollowUpForm textarea').val('');
            $('#contactFollowUpForm #follow_up_contact_id').val('');
            $('#contactFollowUp').modal('hide');
            $('.contact-user-card[data-id="'+data.contact_id+'"]').attr('data-follow-count',data.follow_up_count);
            $('.contact-user-card[data-id="'+data.contact_id+'"]').data('followCount',data.follow_up_count);
            applyColorCoding();
          }
        }
      },
      error:function (data){
        $('.follow-up-error').hide();
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  $('.clear_follow_up_date_picker').click(function(){
    $('.follow_up_date_picker').val('');
  });

  /**
   * Filter contact
   */
  $('#sorting-contacts').on('change',function(){
    var sorting_data = $(this).val();
    var searchText = $('#searchContact').val();
    getContactBoardFirstColumnDataFunction(getContactBoardFirstColumnData + '?sorting_data=' + sorting_data +'&search=' +searchText);
    $.each(statusRangeKeysParseArr, function(key,value) {
      getBoardStatusDataFunction(getBoardStatusData + '/' + value + '?sorting_data=' + sorting_data +'&search=' +searchText , value);
    });
  });

  /**
   * Contacts collapse
   */
  $(document).on('click', '.collapse-minus', function() {
    $('.contact-user-card').css('padding-right','5%');
    $('.contact-user-card').css('padding-left','10%');
    $('.contact-user-card').css('padding-top','3%');
    $('.contact-user-card').css('padding-bottom','5%');
    $('.contact-user-card').css('font-size','95%');
    $('.contact-user-card-profile-add').css('display','none');
    $('.contact-user-card .col-4').css('display','none');
    $('.contact-user-card .card-contact-name').removeClass('m-auto');
    $('.contact-delete-direct').css('top', '10px');
    $(this).removeClass('collapse-minus');
    $(this).addClass('collapse-plus');
    $(this).text("+");
  });

  $(document).on('click', '.collapse-plus', function() {
    $('.contact-user-card').css('padding-right','10%');
    $('.contact-user-card').css('padding-left','10%');
    $('.contact-user-card').css('padding-top','10%');
    $('.contact-user-card').css('padding-bottom','10%');
    $('.contact-user-card').css('font-size','100%');
    $('.contact-user-card').css('font-weight','bold');
    $('.contact-user-card-profile-add').css('display','flex');
    $('.contact-user-card .col-4').css('display','block');
    $('.contact-user-card .card-contact-name').addClass('m-auto');
    $('.contact-delete-direct').css('top', '16px');
    $(this).removeClass('collapse-plus');
    $(this).addClass('collapse-minus');
    $(this).text("-");
  });

  /**
   * Add contact label
   */
  var contactId = null;
  $(document).on('click', '.add-contact-label-modal', function (event){
    event.preventDefault();
    event.stopPropagation();
    $('#contact-labels-update-form').attr('action', contactLabelsUpdateRoute + '/' + $(this).attr('data-id'));
    $.get(getLabelRoute + '/' + $(this).attr('data-id') + '/contact', function( data ) {
      $('.contact-label-list').html(data);
    });
    $('#contactDetail').modal("hide");
    $('#contact-label-list-modal').modal("show");
    contactId = $(this).attr('data-id');
  });

  /**
   * Label update form submit
   */
  $('#contact-labels-update-form').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();
    $('#contactDetail').removeClass('modal-data-detail');
    $('#contactDetail :input').prop('readonly', false);
    $('.follow_up_date_picker').removeAttr('disabled');
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
          $('#contactDetail').modal("show");
          $('#contactDetail').find('.contact-modal-label').html(data.html);
          $('#contact-label-list-modal').modal("hide");
          $('[data-id="'+data.data.id+'"]').find('.contact-label').html(data.html);
        }else{
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  /**
   * Add new contact label modal
   */
  $(document).on('click', '.add-new-contact-label-model', function (event){
    event.preventDefault();
    event.stopPropagation();
    $('#contact-label-form')[0].reset();
    $('#contact-label-form').attr('action', labelStoreRoute);
    $('#contact-add-update-label').modal("show");
  });

  /**
   * Show ai writing modal
   */
  $(document).on('click', '.ai-writing-btn', function (event){
    event.preventDefault();
    event.stopPropagation();
    $('#contactDetail').modal("hide");
    contactId = $(this).attr('data-id');
    $('#ai-writing-modal').modal("show");
  });

  $('#ai-writing-modal').on('shown.bs.modal', function(){
    $('#ai_writing_message_div').html('');
    get_ai_models(contactId);    
  })

  function get_ai_models(contact_id) {
    event.preventDefault();
    $('.ai_models_div').html('');
    addBootstrapAjaxLoader($('.ai_models_div'));
    $.ajax({
      url: getAiModelsRoute, 
      type: 'GET',
      data: {
        contact_id: contact_id
      },
      success: function(response) {
        if(response.success == true) {
          $('.ai_models_div').html(response.html);
          removeBootstrapAjaxLoader($('.ai_models_div'));
        } else {
          $('.ai_models_div').html('');
          removeBootstrapAjaxLoader($('.ai_models_div'));
        }
      },
      error: function() {
        $('.ai_models_div').html('');
        removeBootstrapAjaxLoader($('.ai_models_div'));
      }
    });
  }

  /**
   * Disable or enable generate message button
   */
  $(document).on('change', '.ai_writing_model', function (event){
    event.preventDefault();
    if($(this).val() != 'undefined' && $(this).val() != '') {
      $('.generate_message_btn').prop('disabled', false);
    } else {
      $('.generate_message_btn').prop('disabled', true);
    }
  });

  /**
   * Generate ai writing message
   */
  $(document).on('click', '.generate_message_btn', function (event){
    event.preventDefault();
    $('#ai_writing_message_div').html('');
    $('.generate_message_btn').prop('disabled', true);
    addBootstrapAjaxLoader($('#ai_writing_message_div'));
    let prompt = $('.ai_writing_model').val();

    $.ajax({
      url: generateAiMessageRoute, 
      type: 'POST',
      data: {
        prompt: prompt
      },
      success: function(response) {
        if(response.success == true) {
          $('#ai_writing_message_div').html(response.html);
          removeBootstrapAjaxLoader($('#ai_writing_message_div'));
          $('.generate_message_btn').prop('disabled', false);
        } else {
          $('#ai_writing_message_div').html('');
          removeBootstrapAjaxLoader($('#ai_writing_message_div'));
          $('.generate_message_btn').prop('disabled', false);
        }
      },
      error: function() {
        $('#ai_writing_message_div').html('');
        removeBootstrapAjaxLoader($('#ai_writing_message_div'));
        $('.generate_message_btn').prop('disabled', false);
      }
    });
  });

  /**
   * Copy ai message
   */
  $('body').on('click', '.copy_ai_message_btn', function (e) {
    $('.copy_generate_link_url').removeClass('display');
    $('.copy_generate_link_url').children().html(copyLinkText);
    setTimeout(copyText($(this)), 1500);
  });

  function copyText(element) {
    var copyText = document.getElementById("copy_ai_message_input");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);
    element.next('.copy_generate_link_url').children().html(aiMessageText);
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

  /**
   * Create label form submit
   */
  $( "#contact-label-form" ).submit(function( event ) {
    event.preventDefault();
    event.stopPropagation();
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
        if($.isEmptyObject(data.errors)) {
          $('#contact-add-update-label').modal("hide");
          $.get(getLabelRoute + '/' + contactId + '/contact', function( data ) {
            $('.contact-label-list').html(data);
          });
          $('#contactDetail').modal("hide");
          $('#contact-label-list-modal').modal("show");
          $('#contact-label-form')[0].reset();
        } else {
          printErrorMsg(data.errors);
        }
      },
      error:function (data) {
        printErrorMsg(data.responseJSON.errors);
      }
    });
    return false;
  });

  $(document).on('click', '.edit-label', function (event) {
    $('#contact-label-form').attr('action', labelUpdateRoute + '/' + $(this).attr('data-id'));
    $.get(getLabelDataRoute + '/' + $(this).attr('data-id'), function( data ) {
      $('#contact-label-form').find('#label_name').val(data.data.name);
      $('#contact-label-form').find('#label_color').val(data.data.color);
      $('#contact-label-form').find('.input-color-label').css('background-color', data.data.color);
    });
    $('#contact-add-update-label').modal("show");
  });

  /**
   * Delete label
   */
  $('body').on('click','.modal-popup-delete-label',function (e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-confirm-label').attr('data-url',del_url);
    $('#delete_label_modal').show();
  });

  $('body').on('click','.modal-delete-confirm-label',function () {
    var del_url = $(this).attr('data-url');
    $.ajax({
      url: del_url,
      type: 'GET',
      success: function(result) {
        if(result.success) {
          $('#delete_label_modal').hide();
          $('[data-label-id="' + result.label_id + '"]').remove();
        } else {
          $('#delete_label_modal').hide();
        }
      }
    });
  });

  $('body').on('click','.contact-import-btn',function() {
    $('#contact-upload-modal').modal('show');
  });

  /**
  * Trigger click on browse button
  */
  $('#contact-import-broswer').click(() => {
    $('#contact-import-upload').trigger('click');
    $('.preview-upload').removeAttr("disabled",false);
  })

  $(document).on('change', 'input[type=color]', function() {
    this.parentNode.style.backgroundColor = this.value;
  });

  /**
   * Contact upload form submit
   */
  $("#contact-upload-form").submit(function( event ) {
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    let boardId = $('#board_id_hidden').val();
    let sortArray = [];
    $('.contact-user-card').each(function(){
      sortArray.push($(this).data('id'));
    })
    formData.append('sort_array', JSON.stringify(sortArray));
    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)){
          if(data.success) {
            window.location.reload();
          }
        }
      },
      error: function (data) {
        if(data.responseJSON.upload_error == undefined) {
          printErrorMsg(data.responseJSON.errors);
        }
        if(data.responseJSON.upload_error == true) {
          $('.upload-error-msg').removeClass('d-none');
          $.each(data.responseJSON.errors, function(key, value) {
            $('.upload-error-msg').append('<li class="text-danger"><span>'+value+'</span></li>');
          });
        }
      }
    });
  });

  /**
   * Upload contact preivew start
   */
  $('body').on('click', '.preview-upload', function() {
    if($("#contact-import-upload")[0].files.length) {
    let url = $(this).data('actions');
    var formData = new FormData();
    formData.append("file", $('#contact-import-upload')[0].files[0]);
    formData.append("update_status", $('#contact-upload-update-status').val());
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
              $('#contact_upload_list').modal('show');
              $('#contact-upload-modal').modal('hide');
              $('.contact-upload-preview').html(data.html);
            }
          } else {
            $('#contact_upload_list').modal('hide');
            $('#contact-upload-modal').modal('hide');
            $('.contact-upload-preview').html("");
          }
        },
        error: function (data) {
        
        }
      });
    } 
  });

  $('body').on('click','.cancel-upload',function(){
    window.location.reload();
  });

  $('body').on('click','.confirm-upload-btn',function(){
    $('#contact_upload_list').modal("hide");
    $('#contact-upload-modal').modal("show");
  });

  $('#contact-import-upload').change(function(e) {
    let file = $(this).val().split('.').pop();
    var validExtensions = ["xlsx","ods","xls"];
    if (validExtensions.indexOf(file) == -1) {
      $('.print-error-msg-file').removeAttr("style");
      $('.print-error-msg-file').text(fileExtensionValidation);
    }
  });
  /**
   * Upload Contact Preivew End
   */

  /** Contact Board get data */
  $.each(statusRangeKeysParseArr,function(key,value) {
    getBoardStatusDataFunction(getBoardStatusData + '/' + value, value);
  });

  getContactBoardFirstColumnDataFunction(getContactBoardFirstColumnData);
  addListeners();
  applyColorCoding();

  /**
   * Load image from input div
   */
  function loadImageFromInputDiv(element, e) {
    var output = document.getElementById(element);
    output.style.backgroundImage = "url("+URL.createObjectURL(e.target.files[0])+")";
    output.onload = function() {
      URL.revokeObjectURL(output.style.backgroundImage) // free memory
    }
  }

  /**
   * Get contact board first column data function
   */
  async function getContactBoardFirstColumnDataFunction(url) {
    addBootstrapAjaxLoader($('#contact-board-first-column'));
    $.get(url, function( data ) {
      $('#contact-board-first-column').html(data.html);
      addListeners();
      applyColorCoding();
      removeBootstrapAjaxLoader($('#contact-board-first-column'));
    });
  }

  /**
   * Get board status data function
   */
  async function getBoardStatusDataFunction(url, value) {
    addBootstrapAjaxLoader($('#contact-status-'+value));
    $.get(url, function( data ) {
      $('#contact-status-'+value).html(data.html);
      addListeners();
      applyColorCoding();
      removeBootstrapAjaxLoader($('#contact-status-'+value));
    });
  }

  /**
   * Add listeners to drag and drop events
   */
  let originalOffset = 0;
  function addListeners() {
    var delay = 0;
    if (window.matchMedia("(max-width: 767px)").matches) {
      delay = 400;
    }
    $(".draggable-user-card").draggable({
      scroll: true,
      containment: ".drag-drop-scroll",
      scrollSensitivity: 30,
      delay: delay,
      revert: function (){
        if ($(this).hasClass('drag-revert')) {
          $(this).removeClass('drag-revert');
          return true;
        }
      },
      start: function() {
        $(this).addClass("card-selected");
      },
      stop: function() {
        $(this).removeClass("card-selected");
        originalOffset = 0;
      },
      drag: function( event, ui ) {
        if(originalOffset == 0 ){
          originalOffset = ui.offset.left;
        }
        let offsetDiff = originalOffset - ui.offset.left;
        let leftPos = $('.drag-drop-scroll-main').scrollLeft();
        if(offsetDiff < 0){
          $('.drag-drop-scroll-main').animate({scrollLeft: leftPos+Math.abs(offsetDiff)}, 0);
        } else {
          $('.drag-drop-scroll-main').animate({scrollLeft: leftPos-Math.abs(offsetDiff)}, 0);
        }
        originalOffset = ui.offset.left;
      }
    });

    $(".drag-drop-card").droppable({
      accept: ".draggable-user-card",
      greedy: true,
      tolerance: 'intersect',
      drop: function(event, ui) {
        let dragData = ui.draggable.data();
        let dropData = $(this).data();
        let callbackLater = false;
        let draggable = $(this);
        if (dragData.statusId == distributorID && dropData.statusId == clientID) {
          if (!confirm(confirmText)) {
            ui.draggable.draggable('option', 'revert', true);
            return !event;
          }
        }

        if(dragData.isCompleteProfile == true && dropData.statusId == 8) {
          $('#contactFollowUpForm').find('#follow_up_contact_id').val(dragData.id);
          getFollowUpDate(dragData.id);
          $('#contactFollowUp').modal('show');
          callbackLater = true;
          $(ui.draggable).addClass('drag-revert')
        }

        callbackAction = () => {
          if($(this).find(".contact-user-card").length == 0){
            ui.draggable
              .css("left", "0")
              .css("top", "0")
              .prependTo($(this).find(".droppable-contact"));
          } else {
            let i = 0;
            $(this).find('.contact-user-card').each(function () {
              //compare
              if ($(this).offset().top > ui.draggable.offset().top) {
                ui.draggable
                  .css("left", "0")
                  .css("top", "0").insertBefore($(this));
                i = 1;
                return false; //break loop
              }
            })

            //if element dropped at the end of cart
            if (i != 1) {
              ui.draggable
                .css("left", "0")
                .css("top", "0").appendTo($(this).find('.droppable-contact'));
            }
          }

          let sortArray = [];
          $('.contact-user-card').each(function(){
            sortArray.push($(this).data('id'));
          })

          if(dropData.statusId == 9) {
            $('#not-interested').modal({backdrop: 'static', keyboard: false});
            $('#not-interested').modal('show');
            nonInteressesPopUp(dragData.statusId, dragData.boardId, dragData.id, dropData.statusId, sortArray);
          } else {
            $.post(statusRoute, {'current_status': dragData.statusId, 'board_id': dragData.boardId, 'id':dragData.id, 'update_status': dropData.statusId, 'sort_array' : sortArray}, function (response){
              $('.contact-user-card[data-id="'+dragData.id+'"]').attr('data-status-id', dropData.statusId);
              $('.contact-user-card[data-id="'+dragData.id+'"]').data('statusId', dropData.statusId);
            });
          }

          if(dropData.statusId == 8) {
            $('#contactFollowUpForm').find('#follow_up_contact_id').val(dragData.id);
            getFollowUpDate(dragData.id);
            $('#contactFollowUp').modal('show');
            $('#contactFollowUpForm').trigger("reset");
            callbackLater = true;
            $(ui.draggable).addClass('drag-revert')
          }
        }

        if(!callbackLater){
          callbackAction();
        } else {
          return false;
        }
      }
    });
  }

  /**
   * Apply color coding
   */
  function applyColorCoding(){
    $('.contact-user-card').each(function (){
      let data = $(this).data();
      if(data.followCount !== ""){
        if(data.followCount > 0){
          $(this).css('background-color', '#DE6449');
        } else if(data.followCount == 0 || data.followCount == -1){
          $(this).css('background-color', '#FDCA14');
        } else {
          $(this).css('background-color', '#FFFFFF');
        }
      } else if (data.followCount == "") {
        $(this).css('background-color', '#FFFFFF');
      }
    });
  }

  /**
   * Get follow up date
   */
  function getFollowUpDate(id) {
    $.get('/seller/contacts/follow-up-date/' + id, function( data ) {
      if(data.success){
        $('#contactFollowUpForm').find('#contact_follow_up_date').val(data.date ? data.date : '');
        $('#contactFollowUpForm').find('#contact_follow_up_reson').val(data.reason ? data.reason : '');
      }
    })
  }

  /**
   * Create contact direct
   */
  function createContact() {
    addAjaxLoader($('#create_contact_card'), 200);
    blockDuplication = true;
    let url = createRoute;
    let boardId = $('#board_id_hidden').val();
    let name = $('.add_contact').val();
    let formData = new FormData();
    formData.append("name", name);
    if(memberUserId) {
      formData.append("user_id", memberUserId);
    }
    if(name === ''){
      $('.add_contact_list').html('');
      return false;
    }
    let sortArray = [];
    $('.contact-user-card').each(function(){
      sortArray.push($(this).data('id'));
    })
    formData.append('sort_array', JSON.stringify(sortArray));
    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        if($.isEmptyObject(data.errors)){
          if(data.success){
            let isProfileComplete = data.data.email && data.data.phone ? true : false;
            let html = `<div class="flex-column contact-user-card draggable-user-card" data-id="${data.data.id}" data-status-id="0" data-board-id="${boardId}" data-is-complete-profile="${isProfileComplete}" data-follow-count="${data.data.follow_up_count}">
                        <div class="contact-label"></div>
                        <div class="row">
                        <div class="col-4">
                            <div class="bg-repeat-n bg-cover bg-center rounded-circle shadow-custom me-3 contact-user-card-profile-add" id="selected_image"
                                  style="background-image: url(${data.data.user_pic});">
                            </div>
                        </div>
                        <div class="col-8 m-auto card-contact-name">${data.data.name}</div>
                        <a href="javascript:void(0)" data-id="${data.data.id}" class="contact-delete-outer" data-name="${data.data.name}" data-image="${data.data.user_pic}"><i class="feather-trash contact-delete-direct"></i></a>
                        </div>
                    </div>`;
            $('.added_contact_list').next('.droppable-contact').prepend(html);
            $('.add_contact_list').html('');
            $('.add_contact').val('');
            addListeners();
            applyColorCoding();
          }
        }else{
          printErrorMsg(data.errors);
        }
      },
      error: function (data) {
        if (data.statusText == 'Unauthorized') {
          location.reload();
        }
        printErrorMsg(data.responseJSON.errors);
      },
      complete: function (){
        blockDuplication = false;
        removeAjaxLoader($('#create_contact_card'));
      }
    });
    return false;
  }

  /**
   * Non intresses columns logic
   */
  function nonInteressesPopUp(current_status, board_id, drag_id, update_status, sort_array) {
    $(document).one('click', '.present-modal', function (event) {
      event.preventDefault();
      $('#not-interested').modal('hide');
      $.post(statusRoute, {'current_status': current_status, 'board_id': board_id, 'id':drag_id, 'update_status': update_status, 'sort_array' : sort_array, 'is_present' : $(this).attr('data-present')}, function (response){
        $('.contact-user-card[data-id="'+drag_id+'"]').attr('data-status-id', update_status);
        $('.contact-user-card[data-id="'+drag_id+'"]').data('statusId', update_status);
      });
      $('.present-modal').off('click');
    });
  }
});
