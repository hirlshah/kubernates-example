$(function (){
  let data = {};
  if(typeof overrideUserId !== "undefined"){
    data.user_id = overrideUserId;
  }

  addBootstrapAjaxLoader($('#ajax-member-stats'), 300);
  $.get(sellerDashboardStatsRoute, data, function (response) {
    $('#ajax-member-stats').html(response);
    removeBootstrapAjaxLoader($('#ajax-member-stats'));
    setTimeout(function () {
      addBootstrapAjaxLoader($('#team_stats_data'), 300);
      $.get(sellerDashboardStatsTeamRoutee, data, function (response) {
          $('#team_stats_data').html(response);
          removeBootstrapAjaxLoader($('#team_stats_data'));
      });
    }, 500);
  });
  
  $(document).on('change', '#personal_stats_period', function (){
    addBootstrapAjaxLoader($('#personal_stats_div'), 300);
      if ($(this).val() == 'Range') {
        $('.daterangepicker').show();
      }
      data.personal_stats_period = $(this).val();
      data.filter_type = 'personal';
      $.get(sellerDashboardStatsRoute, data, function (response){
        $('#personal_stats_div').html($(response).find('#personal_stats_div').html());
        removeBootstrapAjaxLoader($('#personal_stats_div'));
        $(".progress-bar-circle").loading();
      });
  });

  $(document).on('change', '#team_stats_period', function () {
    data.team_stats_period = $(this).val();
    data.filter_type = 'team';
    $.get(sellerDashboardStatsTeamRoutee, data, function (response) {
      $('#team_stats').html(response);
    });
  });

  $(document).on('apply.daterangepicker','#custom-range', function (ev, picker) {
    var startDate = picker.startDate;
    var endDate = picker.endDate;
    start = startDate.format('YYYY-MM-DD');
    end = endDate.format('YYYY-MM-DD');
    alert(start + "--" + end);
  });

});
