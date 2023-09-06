$(function () {
  /**
   * Analytics get users
   */
  var userId = 0;
  var slug = $('#prospection_slug').val();
  var link = prospectionAnaylyticsPeopleViewRoute +'/'+slug+'/'+userId;
  
  full_view_graph_data(userId);
  partial_view_graph_data(userId);
  not_played_graph_data(userId);
  get_visitors_user_statistics_data(slug, 0);
  get_people_section_data(link, 0);
  $('.refferal_user').on('change',function() {
    var userId = $(this).val();
    var link = prospectionAnaylyticsPeopleViewRoute +'/'+slug+'/'+userId;
    full_view_graph_data(userId);
    partial_view_graph_data(userId);
    not_played_graph_data(userId);
    get_visitors_user_statistics_data(slug, userId);
    get_people_section_data(link, userId);
  });

  /**
   * Analytics pagination 
   */
  $(document).on( 'click', '.a-prospection-pagination-links .page-link',function (e){
    e.preventDefault();
    let link = $(this).attr('href');
    let userId = $('.a-prospection-pagination-links').attr('data-user');
    get_people_section_data(link, userId);
  });
});

/**
 * Get full view graph data
 */
function full_view_graph_data(userId) {
  $('#full-graph').css('display', 'none');
  $('#full-graph').removeClass('chartjs-render-monitor');
  addBootstrapAjaxLoader($('#full_graph_div'));
  $.get(prospectionFullViewRoute, { prospectionVideoId: prospectionVideoId, refferal_user_id:userId}, function (response) {
    $('#full-graph').css('display', 'block');
    $('#full_graph_div').find('.bootstrap-loader-main').remove();

    fullGraph(response.full_view_data, response.full_view_data.label);
  });
}

/**
 * Get partial view graph data
 */
function partial_view_graph_data(userId) {
  $('#partial-graph').css('display', 'none');
  $('#partial-graph').removeClass('chartjs-render-monitor');
  addBootstrapAjaxLoader($('#partial_graph_div'));
  $.get(prospectionPartialViewRoute, { prospectionVideoId: prospectionVideoId, refferal_user_id:userId}, function (response) {
    $('#partial-graph').css('display', 'block');
    $('#partial_graph_div').find('.bootstrap-loader-main').remove();
    partialGraph(response.partial_view_data, response.partial_view_data.label);
  });
}

/**
 * Get not played graph data
 */
function not_played_graph_data(userId) {
  $('#not-played-graph').css('display', 'none');
  $('#not-played-graph').removeClass('chartjs-render-monitor');
  addBootstrapAjaxLoader($('#not_played_graph_div'));
  $.get(prospectionNotPlayedRoute, { prospectionVideoId: prospectionVideoId, refferal_user_id:userId}, function (response) {
    $('#not-played-graph').css('display', 'block');
    $('#not_played_graph_div').find('.bootstrap-loader-main').remove();
    notPlayedGraph(response.not_played_data, response.not_played_data.label);
  });
}

/**
 * Full graph
 */
function fullGraph(data, label) {
  let fullGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.title,
          data: data.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#56B2FF',
          ],
          color: [
            '#56B2FF',
          ],
          backgroundColor: [
            '#56B2FF',
          ],
          borderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        yAxes: [{
          ticks: {
            min: 0,
            stepSize: 1
          }
        }],
        xAxes: [{
          ticks: {
            display: true
          }
        }]
      }
    }
  };
  let full = [];
  let messageGraphOption = JSON.parse(JSON.stringify(fullGraph));
  messageGraphOption.data.datasets[0].label = data.data_title;
  let ctx1 = document.getElementById('full-graph').getContext('2d');
  full['full-graph'] = new Chart(ctx1, messageGraphOption);
};

/**
 * Partial graph
 */
function partialGraph(data, label) {
  let partialGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.title,
          data: data.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#56B2FF',
          ],
          color: [
            '#56B2FF',
          ],
          backgroundColor: [
            '#56B2FF',
          ],
          borderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        yAxes: [{
          ticks: {
            min: 0,
            stepSize: 1
          }
        }],
        xAxes: [{
          ticks: {
            display: true
          }
        }]
      }
    }
  };
  let partial = [];
  let messageGraphOption = JSON.parse(JSON.stringify(partialGraph));
  messageGraphOption.data.datasets[0].label = data.data_title;
  let ctx2 = document.getElementById('partial-graph').getContext('2d');
  partial['partial-graph'] = new Chart(ctx2, messageGraphOption);
};

/**
 * Not played graph
 */
function notPlayedGraph(data, label) {
  let notPlayedGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.title,
          data: data.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#56B2FF',
          ],
          color: [
            '#56B2FF',
          ],
          backgroundColor: [
            '#56B2FF',
          ],
          borderWidth: 2
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      scales: {
        yAxes: [{
          ticks: {
            min: 0,
            stepSize: 1
          }
        }],
        xAxes: [{
          ticks: {
            display: true
          }
        }]
      }
    }
  };
  let notPlayed = [];
  let messageGraphOption = JSON.parse(JSON.stringify(notPlayedGraph));
  messageGraphOption.data.datasets[0].label = data.data_title;
  let ctx3 = document.getElementById('not-played-graph').getContext('2d');
  notPlayed['not-played-graph'] = new Chart(ctx3, messageGraphOption);
};

/**
 * Get all the people who watched video
 */
function get_people_section_data(link, userId) {
  $('#analytic_people_section').html('');
  addBootstrapAjaxLoader($('#analytic_people_section'));
  $.get(link, function( response ) {
    removeBootstrapAjaxLoader($('#analytic_people_section'));
    $('#analytic_people_section').html(response.html);
    $('.a-prospection-pagination-links').attr('data-user', userId);
  }); 
}

/**
 * Get visitors user statistics data
 */
function get_visitors_user_statistics_data(slug, userId) {
  $('#analytic_count_section').html('');
  addBootstrapAjaxLoader($('#analytic_count_section'));
  var url = prospectionVisitorsCountSectionRoute +'/'+slug+'/'+userId;
  $.get(url, function( response ) {
    removeBootstrapAjaxLoader($('#analytic_count_section'));
    $('#analytic_count_section').html(response.html);
  }); 
}