/**
 * Load Performace Radial
 */
function loadPerformanceRadial() {
  var sheet = document.createElement('style'),
    $rangeInput = $('.range input'),
    prefs = ['webkit-slider-runnable-track', 'moz-range-track', 'ms-track'];

  document.body.appendChild(sheet);

  var getTrackStyle = function (el) {
    var curVal = el.value,
      val = (curVal - 1) * 11.111111112,
      style = '';

    // Set active label
    $('.range-labels li').removeClass('active selected');

    var curLabel = $('.range-labels').find('li:nth-child(' + curVal + ')');

    curLabel.addClass('active selected');
    curLabel.prevAll().addClass('selected');

    // Change background gradient
    for (var i = 0; i < prefs.length; i++) {
      style += '.range {background: linear-gradient(to right, #56b2ff 0%, #56b2ff ' + val + '%, #fff ' + val + '%, #fff 100%)}';
      style += '.range input::-' + prefs[i] + '{background: linear-gradient(to right, #56b2ff 0%, #56b2ff ' + val + '%, #b2b2b2 ' + val + '%, #b2b2b2 100%)}';
    }

    return style;
  }

  $rangeInput.on('input', function () {
    sheet.textContent = getTrackStyle(this);
  });

  // Change input value on label click
  $('.range-labels li').on('click', function () {
    var index = $(this).index();
    $rangeInput.val(index + 1).trigger('input');
  });


  var sheetDistributors = document.createElement('style'),
    $rangeInputDistributors = $('.range-distributors input'),
    prefsDistributors = ['webkit-slider-runnable-track', 'moz-range-track', 'ms-track'];

  document.body.appendChild(sheetDistributors);

  var getDistributorsTrackStyle = function (el) {
    var curValDistributors = el.value,
      valDistributors = (curValDistributors - 1) * 11.111111112,
      styleDistributors = '';

    // Set active label
    $('.range-distributors-labels li').removeClass('active selected');

    var curDistributorsLabel = $('.range-distributors-labels').find('li:nth-child(' + curValDistributors + ')');

    curDistributorsLabel.addClass('active selected');
    curDistributorsLabel.prevAll().addClass('selected');

    // Change background gradient
    for (var i = 0; i < prefsDistributors.length; i++) {
      styleDistributors += '.range-distributors {background: linear-gradient(to right, #56b2ff 0%, #56b2ff ' + valDistributors + '%, #fff ' + valDistributors + '%, #fff 100%)}';
      styleDistributors += '.range-distributors input::-' + prefsDistributors[i] + '{background: linear-gradient(to right, #56b2ff 0%, #56b2ff ' + valDistributors + '%, #b2b2b2 ' + valDistributors + '%, #b2b2b2 100%)}';
    }

    return styleDistributors;
  }

  $rangeInputDistributors.on('input', function () {
    sheetDistributors.textContent = getDistributorsTrackStyle(this);
  });

  // Change input value on label click
  $('.range-distributors-labels li').on('click', function () {
    var index = $(this).index();
    $rangeInputDistributors.val(index + 1).trigger('input');
  });
}

/**
 * Get task
 */
function get_task() {
  addBootstrapAjaxLoader($('#task_data_div'));
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    type: "get",
    url: taskDataRoute,
    dataType: "json",
    success:function(data) {
      if(data.success) {
        $('#task_data_div').html(data.html);
      } else {
        $('#task_data_div').html('');
      }
    },
    error: function (data) {
      $('#task_data_div').html('');
    },
    complete: function (){
      setTimeout(function () {
        removeBootstrapAjaxLoader($('#task_data_div'));
      }, 2000);
    }
  });
}

/**
 * Load task
 */
function loadTask(addNew = false, type = null, is_team = 0) {
  const data = {
    addNew: addNew,
    totalRow: $('.dailies-task').length ? $("#task-data > div:last").data("raw-id") : 0,
    type: type,
    is_team: is_team
  };
  $.getJSON(sellerTaskList, data, function (response) {
    $('#task-data').append(response.task_html);
    loadPerformanceRadial();

    $('.range input').trigger('input');
    $('.range-distributors input').trigger('input');

    //show value more the 10
    if ($('#is_team').val() == 1) {
      if ($("input[name=no_of_distributors]").val() >= 10) {
        $('.maxNoOfDistributors').show();
        $("input[name=custom_no_of_distributors]").val();
      }
    } else {
      if ($("input[name=no_of_distributors]").val() >= 10) {
        $('.maxNoOfDistributors').show();
        $("input[name=custom_no_of_distributors]").val();
      }
    }
    
    //Add new custom text field for no of distributors
    $('.range-distributors input').change(function (e) {
      e.preventDefault();
      if ($('#is_team').val() == 1) {
        if ($("input[name=no_of_distributors]").val() >= 10) {
          $('.maxNoOfDistributors').show();
        }
        else {
          $('.maxNoOfDistributors').hide();
          $("input[name=custom_no_of_distributors]").val('');
        }
      }
      else{
        if ($("input[name=no_of_distributors]").val() >= 10) {
          $('.maxNoOfDistributors').show();
        }
        else {
          $('.maxNoOfDistributors').hide();
          $("input[name=custom_no_of_distributors]").val('');
        }
      }
    });
  });
}

/**
 * Create dailies task
 */
$('body').on('click','.close_dailies_modal', function (e) {
  //$("#add_task_form_submit").click();
  $('#dailiesModal').modal('hide');
});

/**
 * Add task form submit
 */
$("body").on("click", "#add_task_form_submit", function(e) {
  e.preventDefault();
  let form = $('#add_task_form');
  let url = form.attr('action');
  let formData = new FormData(form[0]);
  let isValidate = true;
  $page_name = null;
  if($('#page_name').val() != 'undefined') {
  	$page_name = $('#page_name').val();
  }
  
  $('.text-danger').remove();
  $("#task-data .task-title-input").each(function(){
    if($(this).val() == '') {
      $(this).after('<span class="text-danger" style="display: block;">'+taskTitleRequired+'</span>');
      isValidate = false;
    }
  });

  $('.week').each(function() {
    let row = $(this);
    let rowDayChosen = false;
    row.find('.form-check-input').each(function() {
      if ($(this).prop('checked')) {
        rowDayChosen = true;
        return false; // Exit the loop early if a day is chosen in this row
      }
    });

    if(!rowDayChosen) {
      row.parent().after('<span class="text-danger" style="display: block;">'+taskDayErrorMsg+'</span>');
      isValidate = false;
    }
  });

  if(isValidate == false) {
    return true;
  }

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
          if($page_name != 'undefined' && $page_name == null) {
            let call_data = {};
            call_data.user_id = userId;
            if(data.is_team == 1) {
              call_data.team_stats_period = 'Today';
              ajaxTeamFilterCall(call_data);
            } else {
              call_data.personal_stats_period = 'Today';
              ajaxPersonalFilterCall(call_data);
            }
          }
          get_task();
          $('#dailiesModal').modal('hide');
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

$(document).on('click', '.task-close-icon', function () {
  $(this).closest('.dailies-task').remove();
});

/**
 * User task update
 */
$("body").on("change", ".user-daily-tasks .form-check-input", function (e) {
  var data = {
    id: $(this).attr('id'),
    value: this.checked,
  };

  $.ajax({
    type: 'POST',
    url: sellerTaskUserTaskUpdate,
    data: data,
    success: function (data) {
      if ($.isEmptyObject(data.errors)) {
        if (data.success) {

        }
      } else {
        printErrorMsg(data.errors);
      }
    },
    error: function (data) {
      printErrorMsg(data.responseJSON.errors);
    }
  });
});