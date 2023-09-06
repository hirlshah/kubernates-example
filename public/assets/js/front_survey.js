$(function (){

  /**
   * User survey form submit
   */
  if($('#user-survey-form').length){
    $('#user-survey-form').submit(function (e){
      e.preventDefault();
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
          if(data.success){
            window.location.href = data.redirect_url;
          }
        },
        error:function (data){
          $('.survey-error-msg').hide();
          printErrorMsg(data.responseJSON.errors);
        }
      });
    })
  }
});