$(function () {
  $('#register-form').parsley();
  $('.stripe-errors').html('');

  $('#no_upline').click(function () {
    if ($(this).prop('checked') == true) {
      $('#upline_email').attr('readonly', true);
    } else {
      $('#upline_email').attr('readonly', false);
    }
  });

  $('#upline_email').change(function () {
    $.get(validateRegisterFieldsRoute, { email: $(this).val(), type: $(this).data('type') }, function (response) {
      $("#uplineValidate").removeClass();
      if (response.status == false) {
        $('#uplineValidate').html(response.text).addClass('text-danger');
      } else {
        $('#uplineValidate').html(response.text).addClass('text-success');
      }
    });
  });

  $('.email_field').change(function () {
    $.get(validateRegisterFieldsRoute, { email: $(this).val(), type: $(this).data('type') }, function (response) {
      if (response.status == false) {
        $('#email_error').html(response.text);
        $('.submit-btn').prop('disabled', true);
      } else {
        $('#email_error').html('');
        if ($('#user_name_error').html() == '') {
          $('.submit-btn').prop('disabled', false);
        }
      }
    });
  });

  $('.user_name_field').change(function () {
    $.get(validateRegisterFieldsRoute, { user_name: $(this).val(), type: $(this).data('type') }, function (response) {
      if (response.status == false) {
        $('#user_name_error').html(response.text);
        $('.submit-btn').prop('disabled', true);
      } else {
        $('#user_name_error').html('');
        if ($('#email_error').html() == '') {
          $('.submit-btn').prop('disabled', false);
        }
      }
    });
  });

  $( "#register-form" ).submit(function( event ) {
    $('.stripe-errors').html('');
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(form[0]);
    addAjaxLoader($('#register-page'), 3000);
    $('.register-submit-btn').prop('disabled', true);
    $.ajax({
      type: 'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data) {
        if(data.success == true) {
          window.location.href = data.redirect_thank_you;
          setTimeout(() => {
            $('.spinner-register-remove').remove();
            $('#content').css('overflow','');
          }, 500);
        } else {
          $('.stripe-errors').text(data.message);
          $('.register-submit-btn').prop('disabled', false);
          setTimeout(() => {
            $('.spinner-register-remove').remove();
            $('#content').css('overflow','');
          }, 500);
        }
      },
      error:function (data){
        $('.register-submit-btn').prop('disabled', false);
        setTimeout(() => {
          $('.spinner-register-remove').remove();
          $('#content').css('overflow','');
        }, 500);
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });
});

/*
 * Validation for file type
 */
window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
    var fileExtension = value.split('.').pop();

    return requirement.includes(fileExtension);
  }, 32)
  .addMessage('en', 'fileextension', imageTypeValidationText);

/*
 * Validation for max file size
 */
window.Parsley.addValidator('maxFileSize', {
  validateString: function (_value, maxSize, parsleyInstance) {
    if (!window.FormData) {
      alert('You are making all developers in the world cringe. Upgrade your browser!');
      return true;
    }
    var files = parsleyInstance.$element[0].files;
    return files.length != 1 || files[0].size <= maxSize * 1024 * 1024;
  },
  requirementType: 'integer',
  messages: {
    en: imageSizeValidationText,
    fr: imageSizeValidationText
  }
});

function addAjaxLoader(jqueryElement, height){
  let html = `<div class="hstack justify-content-center position-fixed top-0 start-0 end-0 bottom-0 bg-dark w-100 h-100 spinner-register-remove" style="--bs-bg-opacity: .8;"><div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
  </div></div>`;
  jqueryElement.append(html);
  $('#content').css('overflow','hidden');
}