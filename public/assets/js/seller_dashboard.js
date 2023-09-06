$(document).ready(function () {

  /**
   * Add edit task
   */
  $("body").on("click", ".add_edit_task", function (e) {
    e.preventDefault();
    $('#add_task_form').trigger("reset");
    $('#dailiesModal').modal('show');
    $('#task-data').html('');
    $('#add_task').hide();
    $('#dailiesModal').find('.dailies_modal_title').text(personalGoalText);

    var modalType = $(this).data('type');
    if (modalType == 'add-team-goal') {
      $('#is_team').val(1);
      $('#dailiesModal').find('.dailies_modal_title').text(teamGoalText);
    } else if (modalType == 'add-personal-goal') {
      $('#is_team').val(0);
      $('#dailiesModal').find('.dailies_modal_title').text(personalGoalText);
    }
    loadTask(false, $(this).data('type'), $('#is_team').val());
    if ($(this).data('type') && $(this).data('type') == 'add-task') {
      $('#add_task').show();
    }
    $('.text-danger').hide();
  });

  /**
   * Add task
   */
  $("body").on("click", "#add_task", function (e) {
    loadTask(true, null);
  });
});

$(function () {
  /**
   * Load follow ups
   */
  function loadFollowUps(params) {
    if (!params) params = {};
    if(params.partial && params.partial == 'less-days') {
      addBootstrapAjaxLoader($('#follow-ups-render #view-more-less-days').parent());
      $('#follow-ups-render #view-more-less-days').prop('disabled', true);
    } else {
      $('#follow-ups-render').html('');
      addBootstrapAjaxLoader($('#follow-ups-render'));
    }
    $.get(followUpRenderRoute, params, function (response) {
      if (params.partial) {
        if (params.partial == 'less-days') {
          $('#follow-ups-render #view-more-less-days').parent().replaceWith($(response).find('#followup-less-days').html());
          removeBootstrapAjaxLoader($('#follow-ups-render #view-more-less-days').parent());
          $('#follow-ups-render #view-more-less-days').prop('disabled', false);
        }
      } else {
        $('#follow-ups-render').replaceWith(response);
        removeBootstrapAjaxLoader($('#follow-ups-render'));
      }
    });
  }
  loadFollowUps();

  /**
   * Follow up filter
   */
  $(document).on('click', '.follow-up-filter', function () {
    let filter_type = $(this).data('filter-type');
    let params = { 'filter_type': filter_type };
    loadFollowUps(params);
  });

  /**
   * View more less days filter
   */
  $(document).on('click', '#view-more-less-days', function () {
    let filter_type = $(this).data('filter-type');
    let page = parseInt($(this).data('page')) + 1;
    let params = { 'filter_type': filter_type, 'page_partial': page, partial: 'less-days' };
    loadFollowUps(params);
  });

  /**
   * Qr code modal
   */
  $('.qr-code').click(function () {
    $('#qrCode').modal('show');
  })

  /**
   * Redirect on event detail page
   */
  $(document).on('click', '#event', function (e) {
    window.location.href = $(this).attr('data-url');
  });
});