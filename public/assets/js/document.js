/**
 * Add Document
 */
let tag_counter = 0;
$( document ).ready(function() {

  /**
   * Add document
   */
  if($( "#add_document_form").length){
    loadTagDropdown();
    $('#new_document_button').click(() => {
      $('.print-error-msg-title').hide();
      $('.print-error-msg-document').hide();
      $('#add_document_form').attr('action', createRoute);
      $('#add_document_form').attr('method', 'POST');
      $('.add_document').show();
      $('.update_document').hide();
      $('.add_edit_document_modal_title').text(addDocumentText);
      $('#add_document_form').find('#document_title').val('');
      $('#add_document_form').find('#document_description').val('');
      $('#add_document_form').find('.d_tag').val('');
      $('#d_tag_1, #d_tag_2').hide();
      $("#new_document_image_preview").attr({ "src": companyDefaultImage });
      tag_counter = 0;
      $('#add_document_modal').modal('show');
    });

    let myModalEl = document.getElementById('add_document_modal')
    myModalEl.addEventListener('shown.bs.modal', function (event) {
      $('#add_document_modal .select2-dynamic').select2({
        dropdownParent: $('#add_document_modal')
      });
    })
    myModalEl.addEventListener('hidden.bs.modal', function (event) {
      $('#add_document_modal .select2-dynamic').select2('destroy');
    })

    $('#new_document_image_trigger').click(() => {
      $('#new_document_image').trigger('click');
    })

    $('#new_document_image').change(function(e) {
      loadImageFromInput('new_document_image_preview', e);
    });

    $( "#add_document_form" ).submit(function( event ) {
      event.preventDefault();
      $('.d_tag').each(function (){
        if($(this).val().trim() == ''){
          $(this).prop('disabled', true);
        }
      });
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);
      let method = $(this).attr('method');
      if(method == 'PATCH'){
        formData.append('_method','PATCH')
      }

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
          }
        },
        error:function (data){
          printErrorMsg(data.responseJSON.errors);
        },
        complete: function (){
          $('.d_tag').prop('disabled',false);
        }
      });
    });

    /**
     * Edit document
     */
    $('body').on('click', '.edit_document', function(){
      let url= $(this).attr("data-url");
      $('#add_document_form').attr('method', 'PATCH');
      $('#add_document_form').attr('action', updateRoute + '/' + $(this).data('id'));
      $.get(url, function( data ) {
        $('#add_document_form').find('#document_title').val(data.data.title);
        $('#add_document_form').find('#document_description').val(data.data.description);
        $('#add_document_form').find('#category').val(data.data.category_id);
        var sub_category_id = data.data.sub_category_id;

        //append sub category values
        if(data.data.category_id) {
          let dataCategory = {
            'category_id':data.data.category_id
          };
          $.get(showSubCategoryRoute, dataCategory, function (response) {
            if(response != '') {
              $('#add_document_form').find("#sub_category_id").empty();
              $.each(response,function(id,name){
                $('#add_document_form').find("#sub_category_id").append('<option value="'+name+'">'+id+'</option>');
              });
              if(sub_category_id != 0 && sub_category_id != null) {
               $('#add_document_form').find("#sub_category_id").val(sub_category_id); 
              }
            } else {
              $('#add_document_form').find("#sub_category_id").empty();
              $('#add_document_form').find("#sub_category_id").append('<option value = "">'+ noDataFoundText + '</option>');
            }
          });
        }
        $('.d_tag').val('');
        $('#d_tag_1, #d_tag_2').hide();
        $('#new_tag_button').show();
        if(data.data.image) {
          $("#new_document_image_preview").attr({ "src": imageUrl + "storage/" + data.data.image });
        } else {
          $("#new_document_image_preview").attr({ "src": companyDefaultImage });
        }
        if(data.tags.length){
          $.each(data.tags, function (k, v){
            tag_counter++;
            $('#d_tag_'+k).val(v).show();
          });
          tag_counter--;
        }
        $('.add_edit_document_modal_title').text(editDocumentText);
      });
      $('.add_document').hide();
      $('.update_document').show();
      $('#add_document_modal').modal('show');
    });
  }

  /**
   * Delete document
   */
  $('body').on('click','.modal-popup-delete-document',function (e) {
    e.preventDefault();
    var del_url = $(this).data('url');
    $('.modal-delete-confirm-document').attr('data-url',del_url);
    $('#modal_delete_warning_document').modal('show');
  });

  $('body').on('click','.modal-delete-confirm-document',function () {
    var del_url = $(this).attr('data-url');
    $.ajax({
      url: del_url,
      type: 'DELETE',
      success: function(result) {
        $('#modal_delete_warning_document').modal('hide');
        window.location.reload();
      }
    });
  });

  $('body').on('click', '.modal-close-btn-document', function() {
    $('#modal_delete_warning_document').modal('hide');
  });

  if($('#successMessage').length) {
    window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000);
  }

  /**
   * Add tag
   */
  if ($("#add_tag_form").length) {
    $('#new_tag_button').click(function (){
      tag_counter++;
      $('#d_tag_'+tag_counter).show();
      if(tag_counter >= 2){
        $(this).hide();
      }
    });

    $("#add_tag_form").submit(function(event) {
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
              loadTagDropdown();
              $('#add_tag_modal').modal('hide');
              $("input[type=text]").each(function() {
                $(this).val('');
              });
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

  /**
   * Load tag
   */
  function loadTagDropdown() {
    $.getJSON('/seller/tag/get-list', {}, function(response) {
      if (response) {
        let options = "";
        for (const key in response) {
          options += `<option value='${key}'>${response[key]}</option>`;
        }
        $('#document_tags').html(options);
      }
      if ($('#document_tags option').length) {
        $('#document_tags').show();
      } else {
        $('#document_tags').hide();
      }
    });
  }

  /**
   * Add sub category modal show
   */
  $('#new_document_sub_category_button').on('click',function() {
    $('.sub_category_form').trigger("reset");
    $('#add_subcategory_modal').modal('show');
    $('.text-danger').hide();
  });

});