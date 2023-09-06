$(function (){
  let coupon_discount_percentage;
  let coupon_discount_amount;
  let coupon_description;
  let coupon_min_downline;
  let is_promo_active;

  /**
   * My subscription page, add card
   */
  if ($("#add_card_form").length) {
    $("#add_card_form input[name='card_number']").inputmask({removeMaskOnSubmit: true, mask: "9999 9999 9999 9999"});
    $("#add_card_form input[name='expiry_date']").inputmask({
      alias: 'datetime',
      inputFormat: 'mm/yy'
    });
    $("#add_card_form").submit(function(event) {
      $('.error-card').addClass('d-none');
      $('.print-error-msg-card_holder_name').removeClass('d-none');
      $('.print-error-msg-card_number').removeClass('d-none');
      $('.print-error-msg-expiry_date').removeClass('d-none');
      $('.print-error-msg-cvv').removeClass('d-none');
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);
      $('.form-submit').find('.feather-loader').removeClass('d-none');
      $('#add_card_submit').prop('disabled',true);
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
              $('.error-card').addClass('d-none');
              window.location.reload();
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function(data) {
          $('.form-submit').find('.feather-loader').addClass('d-none');
          $('#add_card_submit').prop('disabled');
          $('.error-card').removeClass('d-none');
          $('.error-card').text(data.responseJSON.error_msg);
          printErrorMsg(data.responseJSON.errors);    
        },
        complete: function (){
          $('.form-submit').find('.feather-loader').removeClass('d-none');
          $('#add_card_submit').prop('disabled', false);
        }
      });
    });
  }

  /**
   * Cancel plan
   */
  if ($("#cancel_plan").length) {
    $("#cancel_subscription_form").submit(function (event) {
      event.preventDefault();
      $('#cancel_plan_submit').prop('disabled', true);
      $('.form-submit').removeClass('d-none');
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
              setTimeout(function () {
                window.location.reload();
              }, 1000);
            } else {
              $('.cancel_plan_error_message').text(data.error_msg);
            }
          }
        },
        error: function (data) {     
          printErrorMsg(data.responseJSON.errors);
        },
        complete: function () {
          $('.form-submit').addClass('d-none');
          $('#cancel_plan_submit').prop('disabled', false);
        }
      });
    });
  }

  /**
   * Update plan
   */
  $('.update-plan-details').click(function () {
    //if card count is 0 then first open add card modal
    if (cardCount == 0) {
      $('#add-credit-card').modal('show');
      return;
    }
    selectedPlan = $(this).data('id');
    $('.plan-selection-card').removeClass('bg-primary');
    $(`.plan-selection-card[data-id=${selectedPlan}]`).addClass('bg-primary');
    $('#plan-success-alert').hide();
    $('.plan-error').hide();
    let plan_coupon = $('#plan_coupon').val();
    $('.update-plan-details-'+selectedPlan).prop('disabled', true);
    $('.form-submit-wait-'+selectedPlan).removeClass('d-none');
    $.ajax({
      type: 'POST',
      url: updatePlanRoute,
      data: { plan_coupon, selected_plan: selectedPlan},
      cache: false,
      success: function(data) {
        if ($.isEmptyObject(data.errors)) {
          if(data.success == 'requires_action') { 
            window.location.href = data.redirect_to_url;
          } else if (data.success) {
            window.location.reload();
          } else  {
            window.location.reload();
          }
        } else {
          printErrorMsg(data.errors);
          $('.update-plan-details-'+selectedPlan).prop('disabled', false);
          $('.form-submit-wait-'+selectedPlan).addClass('d-none');
        }
      },
      error: function(data) {
        printErrorMsg(data.responseJSON.errors);
        $('.update-plan-details-'+selectedPlan).prop('disabled', false);
        $('.form-submit-wait-'+selectedPlan).addClass('d-none');
      },
      complete: function () {
        $('.update-plan-details-'+selectedPlan).prop('disabled', false);
        $('.form-submit-wait-'+selectedPlan).addClass('d-none');
      }
    });
  });

  /**
   * Card active inactive
   */
  $("input[name='card-activation']").change(function (){
    let activeCard = $(this);
    if(activeCard.is(':checked')){
      $.post(activateCardRoute.replace("#id#", activeCard.val()), {}, function (data){
        if ($.isEmptyObject(data.errors)) {
          if (data.success) {
            $('input[name="card-activation"]').not(activeCard).prop('checked', false);
            $('input[name="card-activation"]').not(activeCard).parents('.credit-card').removeClass('active');
            activeCard.parents('.credit-card').addClass('active');
          }
        } else {
          alert('Unable to set active card. Please try again.');
        }
      });
    }
  });

  $('#plan_coupon_button').click(function (){
    $('#print-error-msg-plan_coupon').text('');
    let plan_coupon = $('#plan_coupon').val();
    $.ajax({
      type: 'POST',
      url: validateCouponRoute,
      data: { plan_coupon },
      cache: false,
      success: function(data) {
        if (data.success) {
          $('.print-error-msg-plan_coupon').hide();
          is_promo_active = true;
          coupon_discount_amount = data.coupon.amount_off;
          coupon_discount_percentage = data.coupon.percent_off;
          $('#coupon-success-amount-div').text(data.message).show();
        } else {
          is_promo_active = false;
          $('#coupon-success-amount-div').hide();
        }
      },
      error: function(data) {
        is_promo_active = false;
        $('#coupon-success-amount-div').hide();
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

  /**
   * Cancel active plan
   */
  $('.cancel-active-plan').click(function () {
    $("#cancel-subscription-reason-form").trigger("reset");
    $('#cancel_reason').html('');
    $("#user_cancel_plan_reason_id").prop("selectedIndex", 0);
    $('#cancel-subscription-reason-model').modal('show');
    $('.print-error-msg-user_cancel_plan_reason').hide();
    $('#cancel-plan-id').val($(this).data('planid'));
  });

  $('.cancel_subscription_back_btn').click(function () {
    $('#cancel-subscription-reason-model').modal('hide');
    $('.print-error-msg-user_cancel_plan_reason').hide();
    $('#user_cancel_plan_reason_id').val('');
    $('#user_cancel_plan_reason').val('');
    $('.cancel_my_subscription_free_month_btn').attr('data-planid','');
    $('.cancel_my_subscription_free_month_btn').attr('data-action','');
  });

  /**
   * Cancel subscription plan reason
   */
  $('body').on('click','.cancel_my_subscription_btn',function() {
    if($('#user_cancel_plan_reason_id').val() != '') {
      var userCancelPlanReason = $('#user_cancel_plan_reason_id').val();
      $('#user_cancel_plan_reason').val(userCancelPlanReason);
      $('.print-error-msg-user_cancel_plan_reason').hide();
      $('#cancel-subscription-reason-model').modal('hide');
      $('#cancel_subscription_free_month_model').modal('show');
    } else {
      $('.print-error-msg-user_cancel_plan_reason').show();
    }
  });

  /**
   * Hide cancel error message change
   */
  $('body').on('change','#user_cancel_plan_reason_id',function() {
    if($(this).val() != '') {
      $('.print-error-msg-user_cancel_plan_reason').hide(); 
    }
  });

  /**
   * Cancel subscription free month plan update 
   */
  $(".cancel_my_subscription_free_month_btn").on('click',function (event) {
    event.preventDefault();
    let submitButton = $(this);
    submitButton.prop('disabled', true);
    $('.form-submit').removeClass('d-none');
    let url = $(this).data('action');
    let plan_id = $(this).data('planid');
    $.ajax({
      url: url,
      type: 'GET',
      data: {'plan_id' : plan_id},
      success: function (data) {
        if ($.isEmptyObject(data.errors)) {
          if (data.success) {
            window.location.reload();
          } else {
            $('.error_message').text(data.error_msg);
          }
        }
      },
      complete: function (){
        $('.form-submit').addClass('d-none');
        submitButton.prop('disabled', false);
      }
    });
  });

  /**
   * Cancel subscription plan reason
   */
  $('body').on('click','.cancel_subscription_free_month_back_btn',function() {
    if($('#user_cancel_plan_reason_id').val() != '') {
      var userCancelPlanReason = $('#user_cancel_plan_reason_id').val();
      $('#user_cancel_plan_reason').val(userCancelPlanReason);
      $('.print-error-msg-user_cancel_plan_reason').hide();
      $('#cancel_subscription_free_month_model').modal('hide');
      $('#cancel-subscription-reason-model').modal('hide');
      $('#cancel_subscription_form').trigger("reset");
      $('.cancel_plan_error_message').text('');
      $('#cancel_plan').modal('show');
      $('.reason_error_message').hide();
    } else {
      $('.print-error-msg-user_cancel_plan_reason').show();
    }
  });

  /**
   * Download ics file 
   */
  $('body').on('click', '.download-ics', function() {
    window.location.href = $(this).data('url');
    return true;
  });

  /**
   * Close cancel plan modal
   */
  $(".cancel-close").on("click", function() {
    $('.print-error-msg-card_holder_name').addClass('d-none');
    $('.print-error-msg-card_number').addClass('d-none');
    $('.print-error-msg-expiry_date').addClass('d-none');
    $('.print-error-msg-cvv').addClass('d-none');
    $('#add-credit-card').modal('hide');
    $('#cancel_plan').modal('hide');
  });

  $('#card_number').on('input propertychange paste', function() {
    var value = $('#card_number').val();
    var formattedValue = formatCardNumber(value);
    $('#card_number').val(formattedValue);
  });

});

/**
 * Card number format
 */
function formatCardNumber(value) {
  var value = value.replace(/\D/g, '');
  var formattedValue;
  var maxLength;
  // american express, 15 digits
  if ((/^3[47]\d{0,13}$/).test(value)) {
    formattedValue = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ');
    maxLength = 17;
  } else if((/^3(?:0[0-5]|[68]\d)\d{0,11}$/).test(value)) { // diner's club, 14 digits
    formattedValue = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{6})/, '$1 $2 ');
    maxLength = 16;
  } else if ((/^\d{0,16}$/).test(value)) { // regular cc number, 16 digits
    formattedValue = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/, '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ');
    maxLength = 19;
  } else if ((/^\d{0,19}$/).test(value)) { // regular cc number, 16 digits
    formattedValue = value.replace(/(\d{4})/, '$1 ').replace(/(\d{4}) (\d{4})/, '$1 $2 ').replace(/(\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 ').replace(/(\d{4}) (\d{4}) (\d{4}) (\d{4})/, '$1 $2 $3 $4 ');
    maxLength = 24;
  }
  $('#card_number').attr('maxlength', maxLength);
  return formattedValue.trim();
}