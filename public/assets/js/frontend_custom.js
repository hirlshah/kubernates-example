/*
 * Theme Frontend Custom JS
 */
let timezoneName = moment.tz.guess();
let timezoneOffset = new Date().getTimezoneOffset();
$( document ).ready(function() {

  Cookies.set('timezone_offset', timezoneOffset, {path    : '/', sameSite: 'Lax'});
  Cookies.set('timezone_name', timezoneName, { path: '/', sameSite: 'Lax' });

  /*
   * Tab Section Js
   */
  $('.btnPrev').hide();
  //$('.submit-btn').hide();

  let currentStep = 1;
  $(".btnNext").click(function () {
    $('#register-popup-form').submit();
  });
  $(".btnPrev").click(function () {
    $('#pills-home-tab').addClass('active');
    $('#pills-profile-tab').removeClass('active');
    $('#information-tab').addClass('active show');
    $('#video-tab').removeClass('active show');
    $('.btnPrev').hide();
    $('.btnNext').show();
    //$('.submit-btn').hide();
  });

  /*
   * Contact us form submit
   */
  $( "#contactUsForm" ).submit(function( event ) {
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
          data = jQuery.parseJSON(data);
          if(data.success) {
            $(".print-success-msg-contact-us-form").css('display', 'block');
            $(".print-success-msg-contact-us-form").text(data.message);
            setTimeout(function () {
              $(".print-success-msg-contact-us-form").css('display', 'none');
            }, 5000);
            $("input[type=text] , textarea, input[type=email] ").each(function() {
              $(this).val('');
            });
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
  
  /*
   * Survey Star Rating JS
   */
  $('.stars:not(.stars-view) a').on('click', function () {
    $('.stars a.active').removeClass('active');
    $(this).addClass('active');
    $(this).prevAll().addClass('active');
    $('#rating').val($(this).data('id'));
  });

  $('form input, form select, form textarea').change(function (){
    if($(this).val()){
      let name = $(this).attr('name');
      $('.print-error-msg-' + name).hide();
    }
  });

  let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })

  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const ref = urlParams.get('ref')
  if(ref) {
    $('#register-modal').modal('show');
  }

});

/*
* Image Button
*/
$('.imgBtn').click(function(){
  $('#image').trigger('click');
});

/*
* Change Image based on selection
*/
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#selected_image').css('background-image', 'url("' + e.target.result + '")');
    };
    reader.readAsDataURL(input.files[0]);
  }
  let file = document.getElementById("image").files[0];
  if(maxFileSize < file.size/1000) {
    $('.print-error-profile_image').show();      
    $('.print-error-profile_image').text(maxfileSizeValidation);
  } else {
    $('.print-error-profile_image').hide();
  }
}

function printErrorMsg (msg) {
  $.each(msg, function (key, value) {
    key = key.replace(/\./g,"-")
    $(".print-error-msg-" + key + "").css('display', 'block');
    $(".print-error-msg-" + key + "").html(value[0]);
  });
}

$(function (){
  $.datetimepicker.setLocale('fr');
  $('#date_of_birth').datetimepicker({
    timepicker: false,
    format: 'Y-m-d',
    maxDate : new Date()
  });
});
