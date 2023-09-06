$(document).ready(function () {

  if(typeof trelloStatusIdArr !== 'undefined') {
    var statusIdParseArr = jQuery.parseJSON(trelloStatusIdArr);
  }

  let originalOffset = 0;

  addListeners();

  /**
   * Add listeners
   */
  function addListeners() {

    var wi = 0;
    $('.drag-drop-task-card').each(function () {
      wi = wi + $(this).width();
      $('.drag-drop-scroll-wrapper').width(wi);
      $('.drag-drop-scroll').width(wi);
    });

    $(".droppable-task").draggable({
      scroll: true,
      containment: ".drag-drop-scroll",
      scrollSensitivity: 90,
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
        $(this).removeClass("card-selected")
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

    /**
     * Drag drop task card
     */
    $(".drag-drop-task-card").droppable({
      accept: ".droppable-task",
      greedy: true,
      tolerance: 'intersect',
      drop: function(event, ui) {
        let dragData = ui.draggable.data();
        let dropData = $(this).data();
        let callbackLater = false;
        callbackAction = () => {
          if($(this).find(".droppable-task").length == 0) {
            ui.draggable
              .css("left", "0")
              .css("top", "0")
              .prependTo($(this).find('.drag-drop-task-card-list'));
          } else {
            let i = 0;
            $(this).find('.droppable-task').each(function () {
              if ($(this).offset().top > ui.draggable.offset().top) {
                ui.draggable
                  .css("left", "0")
                  .css("top", "0").insertBefore($(this));
                i = 1;
                return false; //break loop
              }
            })

            if($(this).find('.drag-drop-task-card-list .droppable-task:nth-last-child(1)').offset().top < ui.draggable.offset().top) {
              ui.draggable
                .css("left", "0")
                .css("top", "0").appendTo($(this).find('.drag-drop-task-card-list'));
            }

            //if element dropped at the end of cart
            if (i != 1) {
              ui.draggable
                .css("left", "0")
                .css("top", "0");
            }
          }

          let sortArray = [];
          $('.droppable-task').each(function(){
            sortArray.push($(this).data('id'));
          })

          $.post(statusRoute, { 'id':dragData.id, 'statusId': dropData.statusId, 'sort_array' : sortArray}, function (response){
            $('.droppable-task[data-id="'+dragData.id+'"]').attr('data-status-id', dropData.statusId);
            $('.droppable-task[data-id="'+dragData.id+'"]').data('statusId', dropData.statusId);
          });
        }
        if(!callbackLater){
          callbackAction();
        } else {
          return false;
        }
      }
    });
  }

  taskBoardSortable();

  /**
   * Task board sortable
   */
  function taskBoardSortable() {
    $(".task-board").sortable({
      handle: '.sortable-task-status-div',
      update: function(event, ui) {
        let sortArray = [];
        $('.drag-drop-task-card').each(function(){
          if($(this).data('status-id') !== undefined) {
            sortArray.push($(this).data('status-id'));
          }
        })

        $.post(taskStatusRoute, {'sort_array' : sortArray}, function (){
          addListeners();
        });
      },
    });
  }

  /**
   *  Add New Task
   */
  $('body').on('click', '.task-card-add', function() {
    const statusId = $(this).data('status-id');
    if ($('.add-task-more[data-status-id="' + statusId + '"]').length <= 0) {
      let html = `
        <div class="col-12 create-task-card-div draggable-task-card add-task-more" data-status-id="` + statusId + `">
          <div class="col-8 m-auto">
            <input type="text" class="form-control add_task" placeholder="` + taskPlaceholderText + `" name="title" value="">
            <span class="text-danger print-error-msg-title" style="display:none"></span>
          </div>
        </div>`;
      $('.drag-drop-task-card-list[data-status-id="' + statusId + '"]').append(html);
      $('.add_task').focus();
    }
  });

  let shouldRemoveAddTaskMore = false;

  $(document).on('keypress', '.add_task', function(e) {
    var key = e.which;
    const statusId = $(this).closest('.add-task-more').data('status-id');
    if(key === 13) {
      if($(this).val() != '') {
        createTask(statusId);
        $(this).val('');
      } else {
        shouldRemoveAddTaskMore = true;
        $(this).blur(); // Trigger blur event manually
      }
      return false;
    }
  });

  $(document).on('blur', '.add_task', function() {
    const $addTaskMore = $(this).closest('.add-task-more');
    const statusId = $addTaskMore.data('status-id');
    if($(this).val() != '') {
      createTask(statusId);
      $(this).val('');
    } else {
      shouldRemoveAddTaskMore = true;
    }
    if(shouldRemoveAddTaskMore) {
      setTimeout(function() {
        if (shouldRemoveAddTaskMore && $addTaskMore.parent().length > 0) {
          removeAddTaskMoreDiv(statusId);
        }
        shouldRemoveAddTaskMore = false;
      }, 100);
    }
    return false;
  });

  function removeAddTaskMoreDiv(statusId) {
    const $addTaskMore = $('.add-task-more[data-status-id="' + statusId + '"]');
    if ($addTaskMore.length && $addTaskMore.parent().length > 0) {
      $addTaskMore.remove();
    }
  }

  /**
   * Create task
   */
  function createTask(statusId) {
    let url = createRoute;
    let title = $('.add_task').val();
    let formData = new FormData();
    formData.append("title", title);
    formData.append("status_id", statusId);
    formData.append("trello_board_id", trelloBoardId);
    if(title === ''){
      removeAddTaskMoreDiv(statusId);
      return false;
    }
    let sortArray = [];
    $('.droppable-task[data-status-id="'+statusId+'"]').each(function(){
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
            let html = `<div class="col-12 droppable-task border-grey-e8e8e8 mb-3 rounded-4 task-edit-card cursor-pointer" data-id="`+data.task.id+`" data-status-id="`+data.task.trello_status_id+`">
                            <div class="col-12 task-card draggable-task-card flex-column shadow-none py-0">
                                <div class="d-flex align-items-center mb-4">
                                    <h5 class="card-task-name fs-18">`+data.task.title+`</h5>
                                    <div class="dropdown dropdown-menu-end ms-auto">
                                        <a class="edit-delete-dropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="feather-more-horizontal"></i>
                                        </a>
                                        <ul class="dropdown-menu py-0 shadow-custom-1 border-0" aria-labelledby="edit-delete-dropdown">
                                            <li>
                                                <a class="dropdown-item edit-btn-outline-blue task-card-btn py-2 cursor-pointer" data-id="`+data.task.id+`">
                                                    <i class="feather-edit pe-3"></i> 
                                                    `+editButtonText+`
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" class="dropdown-item edit-btn-outline-blue task-delete-outer py-2 cursor-pointer" data-id="`+data.task.id+`">
                                                    <i class="feather-trash pe-3"></i> 
                                                    `+deleteButtonText+`
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 date-sec mb-2">
                                    <i class="feather-calendar blue"></i> <p class="grey-666666 mb-0 text-sm fw-normal">`+data.deadline_date+`</p>
                                </div>
                            </div>
                        </div>`;
            $('.drag-drop-task-card-list[data-status-id="'+data.task.trello_status_id+'"]').append(html);
            removeAddTaskMoreDiv(data.task.trello_status_id);
            addListeners();
            taskBoardSortable();
          }
        } else {
          printErrorMsg(data.errors);
          removeAddTaskMoreDiv(statusId);
          return false;
        }
      },
      error:function (data){
        alert(TitleAlertText);
      },
    });
    return false;
  }

  /**
   * Get trello status wise task data
   */
  $.each(statusIdParseArr,function(key,value) {
    get_trello_status_column_data(getStatusColumnData + '/' + value, value);
  });

  /**
   * Get trello status wise task data function
   */
  async function get_trello_status_column_data(url, value) {
    addBootstrapAjaxLoader($('#trello-status-'+value));
    $.get(url, function( data ) {
      $('#trello-status-'+value).html(data.html);
      addListeners();
      removeBootstrapAjaxLoader($('#trello-status-'+value));
    });
  }

  /**
   * Get trello task details
   */
  function get_task_details() {
    addBootstrapAjaxLoader($('#trello_task_details'));
    var id = $('#task_id').val();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      type: "get",
      url: getTrelloTaskDetails,
      data: {'trello_task_id' : id},
      dataType: "json",
      success:function(data) {
        if(data.success) {
          $('#trello_task_details').html(data.view);
          dateTimePicker();
        } else {
          $('#trello_task_details').html('');
        }
      },
      error: function (data) {
        $('#trello_task_details').html('');
      },
      complete: function (){
        setTimeout(function () {
          removeBootstrapAjaxLoader($('#trello_task_details'));
        }, 2000);
      }
    });
  }

  /**
   * Get trello task comments
   */
  function get_task_comments() {
    addBootstrapAjaxLoader($('#trelloTaskComments'));
    var id = $('#task_id').val();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      type: "get",
      url: getTrelloTaskComments,
      data: {'trello_task_id' : id},
      dataType: "json",
      success:function(data) {
        if(data.success) {
          $('#trelloTaskComments').html(data.view);
        } else {
          $('#trelloTaskComments').html('');
        }
      },
      error: function (data) {
        $('#trelloTaskComments').html('');
      },
      complete: function (){
        setTimeout(function () {
          removeBootstrapAjaxLoader($('#trelloTaskComments'));
        }, 2000);
      }
    });
  }

  /**
   * Get trello board categories
   */
  function get_trello_board_categories() {
    var task_id = $('#task_id').val();
    var search_category_text = $('#search_category').val();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      type: "get",
      url: getTrelloBoardCategories,
      data: {'trello_board_id' : trelloBoardId, 'search_text' : search_category_text, 'trello_task_id': task_id},
      dataType: "json",
      success:function(data) {
        if(data.success) {
          $('#trello_board_categories').html(data.view);
        } else {
          $('#trello_board_categories').html('');
        }
      },
      error: function (data) {
        $('#trello_board_categories').html('');
      }
    });
  }

  /**
   * Edit trello task
   */
  $('body').on('click', '.task-card-btn', function(e){
    e.preventDefault();
    $('#taskDetail').find('#task_id').val($(this).data('id'));
    get_task_details();
    get_task_comments();
    $('#trello_task_title').hide();
    $('#trello_task_title_text').show();
    $('#taskDetail').modal('show');
  });

  $('body').on('click', '.task-edit-card', function(e) {
    e.preventDefault();
    var dropdownOpen = $(this).find('.edit-delete-dropdown').hasClass('show');
    if (!dropdownOpen) {
      $('#taskDetail').find('#task_id').val($(this).data('id'));
      get_task_details();
      get_task_comments();
      $('#trello_task_title').hide();
      $('#trello_task_title_text').show();
      $('#taskDetail').modal('show');
    }
  });

  /**
   * Initialize date picker for task deadline date
   */
  $('#taskDetail').on('shown.bs.modal', function () {
    dateTimePicker();
  });

  function dateTimePicker() {
    jQuery.datetimepicker.setLocale(lang);
    $('#task_deadline_date_btn').datetimepicker({
      timepicker: false,
      format: 'd/m/Y',
      onChangeDateTime: function(dp, $input) {
        const selectedDate = $input.val();
        const formattedDate = formatDate(selectedDate);
        document.getElementById('task_deadline_date_input').value = formattedDate;
        updateTrelloTask();
      }
    });
  }

  function formatDate(date) {
    const [day, month, year] = date.split('/');
    return `${day}/${month}/${year}`;
  }

  /**
   * Add trello board modal
   */
  $("body").on("click", ".new_trello_board_button", function(e) {
    $('.add_trello_board_form').trigger("reset");
    $('#add_trello_board_modal').modal('show');
    $('.text-danger').hide();
  });

  /**
   * Add trello board form submit
   */
  $(".add_trello_board_form").submit(function( event ) {
    event.preventDefault();
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
        if($.isEmptyObject(data.errors)) {
          if(data.success) {
            window.location.href = data.redirect_url;
          } else {
            window.location.reload();
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

  /**
   * Update trello board input show
   */
  $("body").on("click", "#update_trollo_board_title", function(e) {
    $('.trello_board_dropdown_section').hide();
    $('.update_trello_board_section').show();
  });

  /**
   * Update trello board
   */
  $("body").on("click", ".update_trello_board", function(e) {
    e.preventDefault();
    let formData = new FormData();
    if($('#trello_board_title_input').val()) {
      formData.append("title", $('#trello_board_title_input').val());
    }
    if($('#trello_board_id').val()) {
      formData.append("trello_board_id", $('#trello_board_id').val());
    }

    $.ajax({
      type: 'POST',
      url: updateTrelloBoard,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)) {
          if(data.success) {
            window.location.href = data.redirect_url;
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

  /**
   * Assign people to trello board
   */
  $("body").on("click", ".assign_people_to_trello_board", function(e) {
    event.preventDefault();
    let formData = new FormData();

    var peoples = [];
    $.each($("input[name='peoples']:checked"), function(){
      peoples.push($(this).val());
    });

    formData.append("trello_board_id", trelloBoardId);
    formData.append("peoples", peoples);

    $.ajax({
      type: 'POST',
      url: assignPeopleToTrelloBoard,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)) {
          if(data.success) {
            window.location.reload();
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      }
    });
  });

  $("body").on("click", ".people_listing_check", function(e) {
    e.preventDefault();

    $(this).find('input[type="checkbox"]').each(function() {
      var checkbox = $(this);
      var isChecked = checkbox.prop('checked');
      checkbox.prop('checked', !isChecked);
    });
  });

  $('body').on('change', '.trello_board_task_detail_update', function (e) {
    e.preventDefault();
    updateTrelloTask();
  });

  var $input = $('.trello_board_task_detail_update_input');
  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 1 second for example

  /**
   * On keyup, start the countdown
   */
  $('body').on('keyup', '.trello_board_task_detail_update_input', function (e) {
    clearTimeout(typingTimer);
    if(event.keyCode === 13) {
      updateTrelloTask();
    } else {
      typingTimer = setTimeout(updateTrelloTask, doneTypingInterval);
    }
  });

  /**
   * Search category
   */
  $('body').on('keyup', '#search_category', function (e) {
    clearTimeout(typingTimer);
    if(event.keyCode === 13) {
      get_trello_board_categories();
    } else {
      typingTimer = setTimeout(get_trello_board_categories, doneTypingInterval);
    }
  });

  /**
   * Search people from listing page
   */
  $('body').on('keyup', '#search_people', function (e) {
    clearTimeout(typingTimer);
    $('.people_list').empty();
    currentPeoplePage = 1;
    if(event.keyCode === 13) {
      get_people_list(currentPeoplePage);
    } else {
      typingTimer = setTimeout(get_people_list, doneTypingInterval);
    }
  });

  /**
   * Search people from trello task modal
   */
  $('body').on('keyup', '#search_people_from_modal', function (e) {
    clearTimeout(typingTimer);
    $('.trello_task_people_list').empty();
    currentModalPeoplePage = 1;
    if(event.keyCode === 13) {
      get_people_list_for_modal(currentModalPeoplePage);
    } else {
      typingTimer = setTimeout(get_people_list_for_modal, doneTypingInterval);
    }
  });

  /**
   * On keydown, clear the countdown
   */
  $input.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  /**
   * Update trello task for category on add category to trello task click
   */
  $("body").on("click", "#add_category_to_trello_task", function(e) {
    e.preventDefault();
    updateTrelloTask();
    $('#add_trello_board_category').modal('hide');
  });

  /**
   * Update trello task for category on add people to trello task click
   */
  $("body").on("click", ".assign_people_to_trello_task", function(e) {
    e.preventDefault();
    updateTrelloTask();
    $('#add_people_to_trello_task_modal').modal('hide');
  });

  /**
   * Update trello task
   */
  function updateTrelloTask() {
    var deadline_date = categoryId = description = null;
    var id = $('#task_id').val();
    let url = trelloTaskUpdateRoute+'/'+id;
    let formData = new FormData();
    if($('#task_deadline_date_input').val()) {
      var deadline_date = $('#task_deadline_date_input').val();
      formData.append("deadline_date", deadline_date);
    }

    if($('#trello_task_title').val()) {
      var trello_task_title = $('#trello_task_title').val();
      formData.append("title", trello_task_title);
    }

    if($('#task_description').val()) {
      var description = $('#task_description').val();
      formData.append("description", description);
    }

    var categories = [];
    $.each($("input[name='categories']:checked"), function(){
      categories.push($(this).val());
    });

    formData.append("categories", categories);

    var peoples = [];
    $.each($("input[name='peoples_for_modal']:checked"), function(){
      peoples.push($(this).val());
    });
    formData.append("peoples", peoples);

    var files = $('#task_attachments')[0].files;

    for (var i = 0; i < files.length; i++) {
      formData.append('task_attachments[]', files[i]);
    }

    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        if($.isEmptyObject(data.errors)) {
          if(data.success) {
            get_trello_status_column_data(getStatusColumnData + '/' + data.status_id, data.status_id);
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error: function (data) {
        printErrorMsg(data.responseJSON.errors);
      },
      complete: function (){
        $('input:checkbox').removeAttr('checked');
        get_trello_board_categories();
        get_task_details();
      }
    });
  }

  /**
   * Share button
   */
  $("body").on("click", ".share_board_btn", function(e) {
    $('#share_board_modal').modal('show');
  });

  /**
   * Copy trello board link
   */
  $('body').on('click','.copy_trello_board_link', function(e) {
    e.preventDefault();
    copyText($(this));
  });

  /**
   * Copy text
   */
  function copyText(element) {
    var copyText = document.getElementById("copy_trello_board_link_input");

    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);

    element.next(".tooltiptext").show();
    element.next('.tooltiptext').html(copiedText);
    setTimeout(function () {
      element.next(".tooltiptext").hide();
    }, 1000);
  }

  /**
   * Share trello board via whatsapp
   */
  $('body').on('click','.whatsapp_btn', function(e) {
    e.preventDefault();
    var link = $(this).data('url');
    var wp = $(this).attr('data-href') + link;
    window.open(wp, "_blank");
  });

  /**
   * Share trello board via email
   */
  $('body').on('click','.email_btn', function(e) {
    e.preventDefault();
    var link = $(this).data('url');
    var linkTag = "%0D%0A"+link+"%0D%0A";
    var content = "mailto:dev@eugeniuses.com?subject= "+subjectText+"&body= " +welcomeText;
    var href = content +'%0D%0A'+ linkTag + '%0D%0A' + footerText;
    $('#my_email_anchor').attr('href',href);
    $("#my_email_anchor")[0].click();
  });

  /**
   * Show title input when click edit button
   */
  $("body").on("click", "#task_title_edit", function(e) {
    $('#trello_task_title').show();
    $('#trello_task_title_text').hide();
  });

  /**
   * Assign people to trello task
   */
  $("body").on("click", ".assign_people_to_trello_task_btn", function(e) {
    $('#add_people_to_trello_task_modal').modal('show');
    $('.text-danger').hide();
  });

  $('#add_people_to_trello_task_modal').on('shown.bs.modal', function () {
    currentModalPeoplePage = 1;
    $('#search_people_from_modal').val('');
    $('.trello_task_people_list').html('');
    get_people_list_for_modal(currentModalPeoplePage);
  });

  $('#add-people-dropdown').on('shown.bs.dropdown', function () {
    currentPeoplePage = 1;
    $('.search_people').val('');
    $('.people_list').html('');
    get_people_list(currentPeoplePage);
  });

  /**
   * Delete task attachment
   */
  $("body").on("click", ".delete_task_attachment", function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      type: "get",
      url: deleteTrelloTaskAttachment,
      data: {'id' : id},
      dataType: "json",
      success:function(data) {
        if(data.success) {
          get_task_details();
        }
      },
      error:function (data) {
        get_task_details();
      }
    });
  });

  /**
   * Add trello board category modal
   */
  $("body").on("click", "#add_category", function(e) {
    get_trello_board_categories();
    $('#add_trello_board_category').modal('show');
    $('.text-danger').hide();
  });

  /**
   * Create trello board category button
   */
  $("body").on("click", "#create_trello_board_category_btn", function(e) {
    $('#create_trello_board_category_form').trigger("reset");
    $('#create_trello_board_category_modal').modal('show');
    $('.text-danger').hide();
  });

  /**
   * Create trello board category form submit
   */
  $("#create_trello_board_category_form").submit(function( event ) {
    event.preventDefault();
    let form = $(this);
    let url = form.attr('action');
    let formData = new FormData(form[0]);
    formData.append("trello_board_id", trelloBoardId);

    $.ajax({
      type: 'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success:function(data){
        if($.isEmptyObject(data.errors)) {
          if(data.success) {
            $('#create_trello_board_category_modal').modal('hide');
            get_trello_board_categories();
          }
        } else {
          printErrorMsg(data.errors);
        }
      },
      error:function (data){
        printErrorMsg(data.responseJSON.errors);
      },
    });
  });

  /**
   * Submit comment form
   */
  $('#comment-form').submit(function(e) {
    e.preventDefault();
    var form = $(this);

    if($('#comment-body').val() != '' || $('#comment-attachment').val() != '') {
      form.find('.add_comment_btn').prop('disabled', true);
      form.find('.spinner').addClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
      var formData = new FormData(form[0]);
      var id = $('#task_id').val();
      formData.append("task_id", id);

      $.ajax({
        url: addTrelloTaskCommentRoute,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          form.find('textarea[name="body"]').val('');
          form.find('input[name="attachment"]').val('');

          var attachmentHtmlArr = [];
          var attachmentHTML = '';
          if (response.comment.attachments) {
            $.each(response.comment.attachments, function(index, attachment) {
              var attachmentHTML = '<a href="' + attachment.name + '" target="_blank">';
              if (attachment.type == 'image') {
                attachmentHTML += '<img src="' + attachment.name + '" height="50px" width="50px">' + ' ';
              } else if (attachment.type == 'video') {
                attachmentHTML += '<video src="' + attachment.name + '" class="h-96 w-full object" height="50px" width="50px"></video>' + ' ';
              } else if (attachment.type == 'pdf') {
                attachmentHTML += '<img src="' + attachment.pdf_img + '" height="50px" width="50px">' + ' ';
              }
              attachmentHTML += '</a>';
              attachmentHtmlArr.push(attachmentHTML);
            });
          }

          var commentAttachmentHtml = attachmentHtmlArr.join(''); // Join the elements without a comma


          var mainCommentHTML = `
            <li id="comment-${response.comment.id}">
              <div class="comment-body">
                <div class="mb-3">
                  <div class="d-flex align-items-center gap-2">
                    <img src="${response.comment.user_image}" width="48px" height="48px" class="rounded-circle">
                    <div>
                      <h3 class="fw-500 fs-18">${response.comment.user_name}</h3>
                      <p class="fw-400 fs-14 grey-666666 mb-0">${response.comment.created_at}</p>
                    </div>
                  </div>
                </div>
                ${response.comment.message}
              </div>
              <div class="comment-attachment-list">
                  ${commentAttachmentHtml}
              </div>
            </li>
          `;
          $('#trelloTaskComments').children().children().append(mainCommentHTML);
          if(response.comment.trello_status_id != null) {
            get_trello_status_column_data(getStatusColumnData + '/' + response.comment.trello_status_id, response.comment.trello_status_id);
          }
        },
        complete: function (){
          var fileInput = document.getElementById('comment-attachment');
          fileInput.value = '';
          form.find('.add_comment_btn').prop('disabled', false);
          form.find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
        }
      });
    }
  });

  /**
   * Submit comment reply
   */
  $("body").on("click", ".comment-reply-btn", function(e) {
    e.preventDefault();
    var replyBody = $(this).closest('.reply-comment-section').find('.reply-comment-body').val();
    var parentId = $(this).closest('.reply-comment-section').find('#comment_parent_id').val();

    var files = $('#reply-comment-attachment')[0].files;

    let formData = new FormData();
    var replyAttachmentArr = [];
    var attachment = 0;
    for (var i = 0; i < files.length; i++) {
      formData.append("attachment[]", files[i]);
      attachment = 1;
    }

    if (replyBody != '' || attachment == 1) {
      var $button = $(this); // Store the reference to $(this)
      $button.prop('disabled', true);
      $button.find('.spinner').addClass('spinner-border spinner-border-sm w-4 h-4 mx-2');

      var id = $('#task_id').val();
      formData.append("task_id", id);
      formData.append("parent_id", parentId);
      formData.append("body", replyBody);

      $.ajax({
        url: addTrelloTaskCommentRoute,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          var attachmentHtmlArr = [];
          var attachmentHTML = '';
          if (response.comment.attachments) {
            $.each(response.comment.attachments, function(index, attachment) {
              var attachmentHTML = '<a href="' + attachment.name + '" target="_blank">';
              if (attachment.type == 'image') {
                attachmentHTML += '<img src="' + attachment.name + '" height="50px" width="50px">' + ' ';
              } else if (attachment.type == 'video') {
                attachmentHTML += '<video src="' + attachment.name + '" class="h-96 w-full object" height="50px" width="50px"></video>' + ' ';
              } else if (attachment.type == 'pdf') {
                attachmentHTML += '<img src="' + attachment.pdf_img + '" height="50px" width="50px">' + ' ';
              }
              attachmentHTML += '</a>';
              attachmentHtmlArr.push(attachmentHTML);
            });
          }

          var replyCommentAttachmentHtml = attachmentHtmlArr.join(''); // Join the elements without a comma

          var replyCommentHTML = `
            <li id="comment-${response.comment.id}">
              <div class="comment-body">
                <div class="mb-3">
                  <div class="d-flex align-items-center gap-2">
                    <img src="${response.comment.user_image}" width="48px" height="48px" class="rounded-circle">
                    <div>
                      <h3 class="fw-500 fs-18">${response.comment.user_name}</h3>
                      <p class="fw-400 fs-14 grey-666666 mb-0">${response.comment.created_at}</p>
                    </div>
                  </div>
                </div>
                ${response.comment.message}
              </div>
              <div class="comment-attachment-list">
                ${replyCommentAttachmentHtml}
              </div>
            </li>
          `;
          $('#comment-' + response.comment.parent_id + ' > .children').append(replyCommentHTML);
          if(response.comment.trello_status_id != null) {
            get_trello_status_column_data(getStatusColumnData + '/' + response.comment.trello_status_id, response.comment.trello_status_id);
          }
        },
        complete: function() {
          $('.reply-comment-body').val('');
          var fileInput = document.getElementById('reply-comment-attachment');
          fileInput.value = '';
          $button.prop('disabled', false);
          $button.find('.spinner').removeClass('spinner-border spinner-border-sm w-4 h-4 mx-2');
          $button.closest('.reply-comment-section').hide(); // Use the stored reference to remove the section
        }
      });
    }
  });

  /**
   * Reply to comment button
   */
  $(document).on('click', '.reply-link', function(e) {
    e.preventDefault();
    $(this).closest('.comment-reply').find('.reply-comment-section').toggle();
  });

  /**
   * Task attachment file trigger button
   */
  $("body").on("click", "#task_attachment_file_trigger", function(e) {
    $('#task_attachments').trigger('click');
  });

  /**
   * Delete task modal
   */
  $('body').on('click', '.task-delete-outer', function(event){
    event.preventDefault();
    event.stopPropagation();
    $('#task_delete_confirm').attr('data-id', $(this).data('id'));
    $('.task_delete_id').val($(this).data('id'));
    $('#deleteTaskConfirm').modal('show');
  })

  /**
   * Delete task cancel
   */
  $('#task_delete_cancel').click(()=>{
    $('#deleteTaskConfirm').modal('hide');
  })

  /**
   * Delete task
   */
  $('body').on('click', '#task_delete_confirm', function(){
    let id = $('.task_delete_id').val();
    $.ajax({
      type:'DELETE',
      url: deleteRoute + '/' + id,
      success: function(result) {
        $('.droppable-task[data-id="'+id+'"]').remove();
        $('.task_delete_id').val('');
        $('#deleteTaskConfirm').modal('hide');
        addListeners();
        taskBoardSortable();
      }
    });
  })

  /**
   *  Add status
   */
  $('body').on('click','.task-card-add-status', function(){
    let html = `
        <div class="col-12 create-task-card-div add-task-status">
          <div class="col-8 m-auto card-task-name">
             <input type="text" class="form-control add_task_status" placeholder="`+statusPlaceholderText+`" name="title" value="">
             <span class="text-danger print-error-msg-title" style="display:none"></span>
          </div>
        </div>`;
    $('.add-more-task-status-append').append(html);
    $('.add_task_status').focus();
  });

  $(document).on('keypress', '.add_task_status', function (e) {
    var key = e.which;
    if(key === 13) {
      if ($(this).val() != ''){
        createTaskStatus();
        $(this).val('');
      } else {
        $('.add-more-task-status-append').html('');
      }
      return false;
    }
  });

  $(document).on('blur', '.add_task_status', function () {
    if ($(this).val() != ''){
      createTaskStatus();
      $(this).val('');
    } else {
      $('.add-more-task-status-append').html('');
    }
    return false;
  });

  /**
   *  Add status
   */
  function createTaskStatus() {
    let url = createStatusRoute;
    let title = $('.add_task_status').val();
    let formData = new FormData();
    formData.append("title", title);
    formData.append("trello_board_id", trelloBoardId);
    if(title === ''){
      $('.add-more-task-status-append').html('');
      return false;
    }
    $.ajax({
      type:'POST',
      url: url,
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        if($.isEmptyObject(data.errors)){
          $('#trello-task-main-div').html(data);
          var statusIdParseArr = jQuery.parseJSON(trelloStatusIdArr);
          $.each(statusIdParseArr,function(key,value) {
            get_trello_status_column_data(getStatusColumnData + '/' + value, value);
          });
          addListeners();
          taskBoardSortable();
        }else{
          printErrorMsg(data.errors);
          $('.add-more-task-status-append').html('');
          return false;
        }
      },
      error:function (data){
        alert(TitleAlertText);
      },
    });
    return false;
  }

  /**
   * Edit status
   */
  $('body').on('click', '.edit-task-status', function(){
    $('#edit-task-status-details').attr('action', updateStatusRoute + '/' + $(this).data('id'));
    $.get(editStatusRoute + '/' + $(this).data('id'), function( data ) {
      $('.task-status-delete').attr('data-id', data.data.id);
      $('#taskStatusDetail').find('#task_status_title').val(data.data.title);
    });
    $('#taskStatusDetail').modal('show');
  });

  /**
   * Edit status form submit
   */
  $("#edit-task-status-details").submit(function( event ) {
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
            $('.edit-task-status[data-id="'+data.data.id+'"]').text(data.data.title);
            $('#taskStatusDetail').modal('hide');
            addListeners();
            taskBoardSortable();
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
   *  Delete status
   */
  $('body').on('click', '.task-status-delete', function(event){
    event.preventDefault();
    event.stopPropagation();
    $('#task_status_delete_confirm').attr('data-id', $(this).attr('data-id'));
    $('.task_status_delete_id').val($(this).attr('data-id'));
    $('#deleteTaskStatusConfirm').modal('show');
  })

  /**
   * Cancel delete status
   */
  $('#task_status_delete_cancel').click(()=> {
    $('#deleteTaskStatusConfirm').modal('hide');
  })

  /**
   * Confirm delete status
   */
  $('body').on('click', '#task_status_delete_confirm', function(){
    let id = $('.task_status_delete_id').val();
    $.ajax({
      type:'DELETE',
      url: deleteStatusRoute + '/' + id,
      success: function() {
        $('.task_status_delete_id').val('');
        $('#deleteTaskStatusConfirm').modal('hide');
        $('#taskStatusDetail').modal('hide');
        $('.drag-drop-task-card[data-status-id="'+id+'"]').remove();
        addListeners();
        taskBoardSortable();
      }
    });
  })

  if(typeof (isFirstLogin) != "undefined" && isFirstLogin != 0) {
    if($('#trello-task-main-div').length) {
    // new_trello_board_button
      var tour = new Shepherd.Tour({
        defaultStepOptions: {
          cancelIcon: {
            enabled: true
          },
          classes: 'shadow-md bg-purple-dark',
          scrollTo: {
            behavior: 'smooth',
            block: 'center'
          }
        },
        useModalOverlay: {
          enabled: true
        },
      });

      // window.matchMedia is for screen size (in mobile view skip first step)
      if (window.matchMedia("(min-width: 992px)").matches) {
        // start step-1
        tour.addStep({
          title: step_1_title,
          text: step_1_description,
          attachTo: {
            element: '#trello-board-option',
            on: 'left'
          },
          classes: 'tutorial-step-1',
          buttons: [{
            text: close,
            classes: 'btn btn-light',
            action() {
              $('.tour-btn').removeClass('active-tour-btn');
              return this.cancel();
            },
          },
          {
            text: Next,
            classes: 'btn btn-success',
            action() {
              $('.tour-btn').removeClass('active-tour-btn');
              $('.new_trello_board_button').addClass('active-tour-btn');
              setTimeout(function () {
                $('.shepherd-modal-is-visible').addClass('active');
              }, 100);
              return this.next();
            },
          }]
        });
        // end step-1
      }

      // Start step -2
      tour.addStep({
        title: step_2_title,
        text: step_2_description,
        classes: 'tutorial-step-2',
        attachTo: {
          element: '.new_trello_board_button',
          on: 'bottom'
        },
        buttons: [{
          text: close,
          classes: 'btn btn-light',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            return this.cancel();
          },
        },
        {
          text: Previous,
          classes: 'btn btn-secondary',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            return this.back();
          },
        },
        {
          text: Next,
          classes: 'btn btn-success',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            if(!$('.draggable-task-card').length) {
              $('.task-card-add').addClass('active-tour-btn');
            }
            return this.next();
          },
        }]
      });
      // end step 2


      if($('.draggable-task-card').length) {
        // Start step - 3
        tour.addStep({
          title: step_3_title,
          text: step_3_description,
          classes: 'tutorial-step-3',
          attachTo: {
            element: '.draggable-task-card',
            on: 'left'
          },
          buttons: [{
            text: close,
            classes: 'btn btn-light',
            action() {
              $('.tour-btn').removeClass('active-tour-btn');
              return this.cancel();
            },
          },
            {
              text: Previous,
              classes: 'btn btn-secondary',
              action() {
                $('.tour-btn').removeClass('active-tour-btn');
                $('.new_trello_board_button').addClass('active-tour-btn');
                setTimeout(function () {
                  $('.shepherd-modal-is-visible').addClass('active');
                }, 100);
                return this.back();
              },
            },
            {
              text: Next,
              classes: 'btn btn-success',
              action() {
                $('.tour-btn').removeClass('active-tour-btn');
                $('.task-card-add').addClass('active-tour-btn');
                setTimeout(function () {
                  $('.shepherd-modal-is-visible').addClass('active');
                }, 100);
                return this.next();
              },
            }]
        });
        // end step 3
      }

      // Start step - 4
      tour.addStep({
        title: step_4_title,
        text: step_4_description,
        classes: 'tutorial-step-4',
        attachTo: {
          element: '.task-card-add',
          on: 'left'
        },
        buttons: [{
          text: close,
          classes: 'btn btn-light',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            return this.cancel();
          },
        },
        {
          text: Previous,
          classes: 'btn btn-secondary',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            if(!$('.draggable-task-card').length) {
              $('.new_trello_board_button').addClass('active-tour-btn');
              setTimeout(function () {
                $('.shepherd-modal-is-visible').addClass('active');
              }, 100);
            }
            return this.back();
          },
        },
        {
          text: Next,
          classes: 'btn btn-success',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            $('.sortable-task-status-div').addClass('active-tour-btn');
            setTimeout(function () {
              $('.shepherd-modal-is-visible').addClass('active');
            }, 100);
            return this.next();
          },
        }]
      });
      // end step 4

      // Start step - 5
      tour.addStep({
        title: step_5_title,
        text: step_5_description,
        attachTo: {
          element: '.sortable-task-status-div',
          on: 'left'
        },
        classes: 'tutorial-step-5',
        buttons: [{
          text: close,
          classes: 'btn btn-light',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            return this.cancel();
          },
        },
        {
          text: Previous,
          classes: 'btn btn-secondary',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            $('.task-card-add').addClass('active-tour-btn');
            setTimeout(function () {
              $('.shepherd-modal-is-visible').addClass('active');
            }, 100);
            return this.back();
          },
        },
        {
          text: Next,
          classes: 'btn btn-success',
          action() {
            $('.tour-btn').removeClass('active-tour-btn');
            return this.next();
          },
        }]
      });
      // end step 5

      tour.start();
    }
  }

  /**
   * Trello board search
   */
  $(document).on('change input', '#sorting_type, #trello_board_search_text', function() {
    var sorting = $('#sorting_type').val();
    var searchText = $('#trello_board_search_text').val();

    $.ajax({
      url: indexRoute,
      method: 'GET',
      data: {
        sorting_type: sorting,
        search_text: searchText
      },
      success: function(response) {
        $('#trello_board_count_text').text(response.trello_board_count);
        $('#trello_board_data').html(response.html);
      }
    });
  });

  $('#trello_board').on('change', function() {
    var url = $(this).val();
    window.location.href = url;
  });

  /**
   * Delete trello board modal
   */
  $('body').on('click', '.modal_popup_trello_board_btn', function(event){
    event.preventDefault();
    $('#trello_delete_confirm').attr('data-id', $(this).data('id'));
    $('.trello_board_delete_id').val($(this).data('id'));
    $('#delete_trello_board_modal').modal('show');
  })

  /**
   * Delete trello board cancel
   */
  $('#trello_board_delete_cancel').click(()=>{
    $('#delete_trello_board_modal').modal('hide');
  })

  /**
   * Delete trello board confirm
   */
  $('body').on('click', '#trello_delete_confirm', function(){
    let id = $('.trello_board_delete_id').val();
    $.ajax({
      type:'DELETE',
      url: deleteTrelloBoardRoute + '/' + id,
      success: function(result) {
        $('.trello_board_'+id).remove();
        $('#delete_trello_board_modal').modal('hide');
        $('#trello_board_count_text').text(result.trello_board_count_text);
      }
    });
  })

});

