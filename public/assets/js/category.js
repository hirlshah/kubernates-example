$( document ).ready(function() {
  
  /**
   * Add Category
   */
  $("body").on("click", ".new_category_button", function(e) {
    var model_type = $(this).data('model-type');
    $('.add_category_form').trigger("reset");
    $('#add_category_modal').modal('show');
    $('.add_category_form').find('#model_type').val(model_type);
    $('.add_category_form').find('#category_form_submit_btn').text('+ ' + addCategoryText);
    $('.add_edit_modal_title').text(addCategoryText);
    $('.text-danger').hide();
  });

  /**
   * Edit category
   */
  $('.edit_category_button').on('click',function(){
    var model_type = $(this).data('model-type');
    $('.add_category_form').trigger("reset");
    $('#add_videosubcategory_modal').modal('hide');
    $('#add_category_modal').modal('show');
    $('.text-danger').hide();
    $('.add_category_form').attr('action', updateCategoryRoute + '/' + $(this).data('id'));
    $('.add_category_form').find('#model_type').val(model_type);
    $('.add_category_form').find('#category_form_submit_btn').text('+ ' + editCategoryText);
    $('.add_edit_modal_title').text(editCategoryText);
    $.get(showCategoryRoute + '/' + $(this).data('id'), function( data ) {
      $('.add_category_form').find('#category_name').val(data.name);
    });
  });

  /**
   * Add category form submit
   */
  $(".add_category_form" ).submit(function( event ) {
    event.preventDefault();
    $('.add_category_form').find('#category_form_submit_btn').prop('disabled', true);
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(form[0]);

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
        }else{
          printErrorMsg(data.errors);
          $('.add_category_form').find('#category_form_submit_btn').prop('disabled', false);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
        $('.add_category_form').find('#category_form_submit_btn').prop('disabled', false);
      }
    });
  });

  /**
   * Delete category modal
   */
  $('body').on('click', '.modal-popup-delete-category', function(e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-confirm').attr('data-url', del_url);
    $('#modal_delete_warning').show();
    $('.modal_title').text(removeCategoryText);
  });

  /**
   * Delete sub category modal
   */
  $('body').on('click','.modal-popup-delete-sub-category',function(e){
    var del_url = $(this).data('url');
    $('.modal-delete-confirm').attr('data-url', del_url);
    $('#modal_delete_warning').show();
    $('.modal_title').text(removeSubCategoryText);
  });

  /**
   * Add sub category
   */
  $( ".sub_category_form" ).submit(function( event ) {
    event.preventDefault();
    $('.sub_category_form').find('.spinner').addClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(this);
    let method = $(this).attr('method');
    
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
            window.location.reload();
          }
        } else {
          printErrorMsg(data.errors);
          $('.sub_category_form').find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
        $('.sub_category_form').find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
      }
    });
  });

  /**
   * Show sub category
   */
  $("#category").on('change',function(event){
    event.preventDefault();
    var categoryId = $(this).val();
    if(categoryId) {
      let data = {
        'category_id':categoryId
      };
      $.get(showSubCategoryRoute, data, function (response) {
        if(response != ''){
          $("#sub_category_id").empty();
          $.each(response,function(id,name){
            $("#sub_category_id").append('<option value="'+name+'">'+id+'</option>');
          });
        } else {
          $("#sub_category_id").empty();
          $("#sub_category_id").append('<option value = "">'+ noDataFoundText +'</option>');
        }
      });
    }
  });

});