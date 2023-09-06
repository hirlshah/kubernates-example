$(window).on('load', function (e) {
  $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 240);
  $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
});

$(function () {

  if (userStatisticFlag == 0) {
    let url = window.location.pathname;
    $("#playVideo").attr('src', '');
    $.get(showBannerVideoRoute, { 'url': url }, function (data) {
      $("#playVideo").attr('src', data.url);
    });
    $('#showVideoModal').modal('show');
    $.get(userStatisticFlagRoute , function (data) {});
  }

  let data = {};
  callColumnsTab();
  $('#custom-range').hide();
  $('.filterDiv').hide();

  var weekAndMonthByLocale = getWeekAndMonthByLocale();

  $('#custom-range').daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: clearText,
      applyLabel: applyText,
      "daysOfWeek": weekAndMonthByLocale[0],  
      "monthNames": weekAndMonthByLocale[1]
      }
  }, function (start, end, label) {
    // console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

  /**
   * Get events
   */
  $('#eventSelect2').select2({
    placeholder: eventSelect2Title,
    ajax: {
      url: analyticsAjaxSearchRoute,
      data: function (params) {
        var query = {
          term: params.term,
        }
        // Query parameters will be ?search=[term]&dateFilterType=[data]..and so on
        return query;
      },
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return {
              text: item.name,
              id: item.id
            }
          })
        };
      },
      cache: true
    },
    closeOnSelect: false,
    allowHtml: true,
    allowClear: true,
    tags: true
  });

  /**
   * Data change on event select
   */
  $('#eventSelect2').on('select2:select', function (e) {
    var data = e.params.data;
    eventID = $('.eventselect').val();
    let active = $("ul.nav-tabs li button.active").attr('id');
    let array = {};
    if (active == 'analytics-tab') {
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    } else if (active == 'columns-tab') {
      callColumnsTab();
    }
  });

  /**
   * Data change on event clear
   */
  $('#eventSelect2').on('select2:clear', function (e) {
    eventID = [];
    let active = $("ul.nav-tabs li button.active").attr('id');
    if (active == 'analytics-tab') {
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    } else if (active == 'columns-tab') {
      callColumnsTab();
    }
  });

  /**
   * Data change on event unselect
   */
  $('#eventSelect2').on('select2:unselect', function (e) {
    eventID = $('.eventselect').val();
    let active = $("ul.nav-tabs li button.active").attr('id');
    let array = {};
    if (active == 'analytics-tab') {
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    } else if (active == 'columns-tab') {
      callColumnsTab();
    }
  });

  /**
   * Presentation pagination
   */
  $(document).on( 'click', '.page-item',function (e){
    e.preventDefault();
    link = $(this).children('a').attr('href');
    callColumnsTab();
  });

  /**
   * Analytic tab
   */
  $(document).on('click', '#analytics-tab', function () {
    attended_zoom_graph();
    message_sent_graph();
    confirm_zoom_graph();
    new_client_graph();
    follow_up_graph();
    not_intrested_graph();
    new_distributor_graph();
    $('#eventFilterDiv').hide();
    $('#custom-range').show();
    $('.filterDiv').show();
  });

  /**
   * Presentation statistic tab
   */
  $(document).on('click', '#columns-tab', function () {
    callColumnsTab();
    $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 240);
    $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
    $('#eventFilterDiv').show();
    $('#custom-range').hide();
    $('.filterDiv').hide();
  });

  /**
   * Stats change event
   */
  $(document).on('change', '#personalStat', function () {
    let active = $("ul.nav-tabs li button.active").attr('id');
    personalStat = true;
    let value = $(this).val();
    if (value == 'personal' && active == 'analytics-tab') {
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    } else if (value == 'personal' && active == 'columns-tab') {
      callColumnsTab();
    } else if (value == 'team' && active == 'analytics-tab') {
      personalStat = false;
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    } else if (value == 'team' && active == 'columns-tab') {
      personalStat = false;
      callColumnsTab();
    }
  });

  /**
   * Analytic tab filter
   */
  $(document).on('click', '.calendar-btn', function (e) {
    $(this).addClass('active').siblings().removeClass('active');
    let value = $(this).data('type');
    if (value == 'Day') {
      dateFilterType = 'Day';
    }
    if (value == 'Week') {
      dateFilterType = 'Week';
    }
    if (value == 'Month') {
      dateFilterType = 'Month';
    }
    if (value == 'customRange') {
      dateFilterType = 'customRange';
    }
  
    if (value != 'customRange') {
      let active = $("ul.nav-tabs li button.active").attr('id');
      if (active == 'analytics-tab') {
        attended_zoom_graph();
        message_sent_graph();
        confirm_zoom_graph();
        new_client_graph();
        follow_up_graph();
        not_intrested_graph();
        new_distributor_graph();
      }
      if (active == 'columns-tab') {
        callColumnsTab();
      }
    }
  });

  $('#custom-range').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate;
    var endDate = picker.endDate;
    start = startDate.format('YYYY-MM-DD');
    end = endDate.format('YYYY-MM-DD');
    let active = $("ul.nav-tabs li button.active").attr('id');
    if (active == 'analytics-tab') {
      attended_zoom_graph();
      message_sent_graph();
      confirm_zoom_graph();
      new_client_graph();
      follow_up_graph();
      not_intrested_graph();
      new_distributor_graph();
    }
    if (active == 'columns-tab') {
      callColumnsTab();
    }
  });

  /**
   * See more contact data
   */
  $(document).on('click', '.seeMoreBtn', function () {
    let event_id = $(this).data('id');
    let flag = $('#contactButton' + event_id).data('flag');
    $.get(analyticsColumnContactRoute, { 'personalStatFlag': personalStatFlag, eventID: [event_id], dateFilterType: dateFilterType, start: start, end: end }, function (response) {
      $('#contactColumn'+event_id).html(response);
      $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 621);
      $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
      $(".dummy-scroll-main").on('scroll', function (e) {
        $(".drag-drop-scroll-main").scrollLeft($(".dummy-scroll-main").scrollLeft());
      });
      $(".drag-drop-scroll-main").on('scroll', function (e) {
        $(".dummy-scroll-main").scrollLeft($(".drag-drop-scroll-main").scrollLeft());
      });
      $('#contactButton'+event_id).data('flag', false);
    });
    if (flag) {
      $('.seeMore-'+ event_id).addClass('d-none');
      $('.hide-content').removeClass('d-none');
      $('.seeLessBtn-'+event_id).removeClass('d-none');
    } else {
      $('.seeMore-'+ event_id).addClass('d-none');
      $('.hide-content').removeClass('d-none');
      $('.seeLessBtn-'+event_id).removeClass('d-none');
    }
    $('body').on('click', '.seeLessBtn-'+event_id, function () {
      $('#contactColumn'+event_id).html('');
      $('.seeMore-'+ event_id).removeClass('d-none');
      $('.seeLessBtn-'+event_id).addClass('d-none');
    });
  });
});

function callColumnsTab() {
  $('#columnsData').html('');
  addBootstrapAjaxLoader($('#columnsData'), 300);
  $('#content').attr('style','overflow:hidden');
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  if (link) {
    $.get(link, { eventID: eventID, }, function (response) {
      $('#columnsData').html(response);
      $('.progress-bar-value-message-sent').each(function(i, obj) {
        var percentage = Math.round(obj.value);
        var lineId = obj.getAttribute('data-line-id');
        var eventId = obj.getAttribute('data-event-id');
        $(".total-value-message-sent[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('color', getPercentageWiseColor(percentage));
        $(".total-value-message-sent[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('left', percentage+'%');
        $(".custom-badge[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('left', percentage+'%');
      });
      $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 621);
      $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
      $(".dummy-scroll-main").on('scroll', function (e) {
        $(".drag-drop-scroll-main").scrollLeft($(".dummy-scroll-main").scrollLeft());
      });
      $(".drag-drop-scroll-main").on('scroll', function (e) {
        $(".dummy-scroll-main").scrollLeft($(".drag-drop-scroll-main").scrollLeft());
      });
      removeBootstrapAjaxLoader($('#columnsData'), 300);
      $('#content').css('overflow','');
    });
  } else {
    $.get(analyticsColumnRoute, { eventID: eventID}, function (response) {
      $('#columnsData').html(response);
      $('.progress-bar-value-message-sent').each(function(i, obj) {
        var percentage = Math.round(obj.value);
        var lineId = obj.getAttribute('data-line-id');
        var eventId = obj.getAttribute('data-event-id');
        $(".total-value-message-sent[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('color', getPercentageWiseColor(percentage));
        $(".total-value-message-sent[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('left', percentage+'%');
        $(".custom-badge[data-line-id='"+lineId+"'][data-event-id='"+eventId+"']").css('left', percentage+'%');
      });

      $('.dummy-scrollbar').width($('.drag-drop-scroll').width() + 621);
      $('.drag-drop-scroll-wrapper').width($('.drag-drop-scroll').width());
      $(".dummy-scroll-main").on('scroll', function (e) {
        $(".drag-drop-scroll-main").scrollLeft($(".dummy-scroll-main").scrollLeft());
      });
      $(".drag-drop-scroll-main").on('scroll', function (e) {
        $(".dummy-scroll-main").scrollLeft($(".drag-drop-scroll-main").scrollLeft());
      });
      $('#content').attr('style','');
      removeBootstrapAjaxLoader($('#columnsData'));
      $('#content').css('overflow','');
    });
  }
}

/**
 * Get percentage wise color
 */
function getPercentageWiseColor(percentage) {
  if(percentage > 0 && percentage < 25) {
    return secondColor;
  } else if(percentage > 25 && percentage < 50) {
    return secondColor;
  } else if(percentage > 50 && percentage < 75) {
    return primaryColor;
  } else if(percentage > 75 && percentage < 100) {
    return primaryColor;
  } else if(percentage == 100){
    return primaryColor;
  } else if(percentage == 0){
    return secondColor;
  } else{
    return secondColor;
  }
}

/**
 * Panel stats -> not used for now
 */
function callPanelStats() {
  if (personalStat) {
    $.get(analyticsPersonalStatRoute, { eventID: eventID }, function (response) {
      $('#panel-stats').html(response);
    });
  } else {
    $.get(analyticsTeamStatRoute, { eventID: eventID }, function (response) {
      $('#panel-stats').html(response);
    });
  }
}

/**
 * Get message send graph data
 */
function message_sent_graph() {
  $('#first-graph').css('display', 'none');
  $('#first-graph').removeClass('chartjs-render-monitor');
  $('.first-graph-percentage').empty();
  $('.first-graph-new-data').empty();
  $('.first-graph-old-data').empty();

  addBootstrapAjaxLoader($('#message_sent_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'1' }, function (response) {
    $('#first-graph').css('display', 'block');
    $('#message_sent_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    firstGraph(response.status1, response.label);
  });
}

/**
 * Get confirm zoom graph data
 */
function confirm_zoom_graph() {
  $('#fourth-graph').css('display', 'none');
  $('#fourth-graph').removeClass('chartjs-render-monitor');
  $('.fourth-graph-percentage').empty();
  $('.fourth-graph-new-data').empty();
  $('.fourth-graph-old-data').empty();
  addBootstrapAjaxLoader($('#confirm_zoom_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'4' }, function (response) {
    $('#fourth-graph').css('display', 'block');
    $('#confirm_zoom_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    fourthGraph(response.status4, response.label);
  });
}

/**
 * Get attended zoom graph data
 */
function attended_zoom_graph() {
  $('#fifth-graph').css('display', 'none');
  $('#fifth-graph').removeClass('chartjs-render-monitor');
  $('.fifth-graph-percentage').empty();
  $('.fifth-graph-new-data').empty();
  $('.fifth-graph-old-data').empty();
  addBootstrapAjaxLoader($('#attended_zoom_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'5' }, function (response) {
    $('#fifth-graph').css('display', 'block');
    $('#attended_zoom_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    fifthGraph(response.status5, response.label);
  });
}

/**
 * Get new distributor graph data
 */
function new_distributor_graph() {
  $('#sixth-graph').css('display', 'none');
  $('#sixth-graph').removeClass('chartjs-render-monitor');
  $('.sixth-graph-percentage').empty();
  $('.sixth-graph-new-data').empty();
  $('.sixth-graph-old-data').empty();
  addBootstrapAjaxLoader($('#new_distributor_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'6' }, function (response) {
    $('#sixth-graph').css('display', 'block');
    $('#new_distributor_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    sixthGraph(response.status6, response.label);
  });
}

/**
 * Get new client graph data
 */
function new_client_graph() {
  $('#seventh-graph').css('display', 'none');
  $('#seventh-graph').removeClass('chartjs-render-monitor');
  $('.seventh-graph-percentage').empty();
  $('.seventh-graph-new-data').empty();
  $('.seventh-graph-old-data').empty();
  addBootstrapAjaxLoader($('#new_client_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'7' }, function (response) {
    $('#seventh-graph').css('display', 'block');
    $('#new_client_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    seventhGraph(response.status7, response.label);
  });
}

/**
 * Get follow up graph data
 */
function follow_up_graph() {
  $('#eight-graph').css('display', 'none');
  $('#eight-graph').removeClass('chartjs-render-monitor');
  $('.eight-graph-percentage').empty();
  $('.eight-graph-new-data').empty();
  $('.eight-graph-old-data').empty();
  addBootstrapAjaxLoader($('#follow_up_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'8' }, function (response) {
    $('#eight-graph').css('display', 'block');
    $('#follow_up_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    eightGraph(response.status8, response.label);
  });
}

/**
 * Get not intrested graph data
 */
function not_intrested_graph() {
  $('#nine-graph').css('display', 'none');
  $('#nine-graph').removeClass('chartjs-render-monitor');
  $('.nine-graph-percentage').empty();
  $('.nine-graph-new-data').empty();
  $('.nine-graph-old-data').empty();
  addBootstrapAjaxLoader($('#not_interested_graph_div'));
  personalStatFlag = false;
  if (personalStat) {
    personalStatFlag = true;
  }
  $.get(analyticsRoute, { 'personalStatFlag': personalStatFlag, eventID: eventID, dateFilterType: dateFilterType, start: start, end: end, status:'9' }, function (response) {
    $('#nine-graph').css('display', 'block');
    $('#not_interested_graph_div').find('.bootstrap-loader-main').remove();
    $('#content').css('overflow','');
    nineGraph(response.status9, response.label);
  });
}

function firstGraph(data, label) {
  let firstGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(firstGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('first-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.first-graph-percentage').removeClass('bg-danger');
    $('.first-graph-percentage').addClass('bg-success');
  } else {
    $('.first-graph-percentage').removeClass('bg-success');
    $('.first-graph-percentage').addClass('bg-danger');
  }
  $('.first-graph-percentage').html(data.percentage+' %');
  $('.first-graph-new-data').html(data.new_count);
  $('.first-graph-old-data').html(data.old_count);
  graphs['first-graph'] = new Chart(ctx1, messageGraphOption);
};

function fourthGraph(data, label) {
  let fourthGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(fourthGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('fourth-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.fourth-graph-percentage').removeClass('bg-danger');
    $('.fourth-graph-percentage').addClass('bg-success');
  } else {
    $('.fourth-graph-percentage').removeClass('bg-success');
    $('.fourth-graph-percentage').addClass('bg-danger');
  }
  $('.fourth-graph-percentage').html(data.percentage+' %');
  $('.fourth-graph-new-data').html(data.new_count);
  $('.fourth-graph-old-data').html(data.old_count);
  graphs['fourth-graph'] = new Chart(ctx1, messageGraphOption);
};

function fifthGraph(data, label) {
  let fifthGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(fifthGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('fifth-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.fifth-graph-percentage').removeClass('bg-danger');
    $('.fifth-graph-percentage').addClass('bg-success');
  } else {
    $('.fifth-graph-percentage').removeClass('bg-success');
    $('.fifth-graph-percentage').addClass('bg-danger');
  }
  $('.fifth-graph-percentage').html(data.percentage+' %');
  $('.fifth-graph-new-data').html(data.new_count);
  $('.fifth-graph-old-data').html(data.old_count);
  graphs['fifth-graph'] = new Chart(ctx1, messageGraphOption);
};

function seventhGraph(data, label) {
  let seventhGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(seventhGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('seventh-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.seventh-graph-percentage').removeClass('bg-danger');
    $('.seventh-graph-percentage').addClass('bg-success');
  } else {
    $('.seventh-graph-percentage').removeClass('bg-success');
    $('.seventh-graph-percentage').addClass('bg-danger');
  }
  $('.seventh-graph-percentage').html(data.percentage+' %');
  $('.seventh-graph-new-data').html(data.new_count);
  $('.seventh-graph-old-data').html(data.old_count);
  graphs['seventh-graph'] = new Chart(ctx1, messageGraphOption);
};

function eightGraph(data, label) {
  let eightGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(eightGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('eight-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.eight-graph-percentage').removeClass('bg-danger');
    $('.eight-graph-percentage').addClass('bg-success');
  } else {
    $('.eight-graph-percentage').removeClass('bg-success');
    $('.eight-graph-percentage').addClass('bg-danger');
  }
  $('.eight-graph-percentage').html(data.percentage+' %');
  $('.eight-graph-new-data').html(data.new_count);
  $('.eight-graph-old-data').html(data.old_count);
  graphs['eight-graph'] = new Chart(ctx1, messageGraphOption);
};

/* Get week and month name by locale */
function getWeekAndMonthByLocale() {
  let weekName = [];
  let monthName = [];
  if(lang == "en") {
      weekName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
      monthName = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  } else {
      weekName = ["dim", "lun", "mar", "mer", "jeu", "ven", "sam"];
      monthName = ["jan", "fév", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc"];
  }
  return [weekName,monthName];
};

function nineGraph(data, label) {
  let nineGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(nineGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('nine-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.nine-graph-percentage').removeClass('bg-danger');
    $('.nine-graph-percentage').addClass('bg-success');
  } else {
    $('.nine-graph-percentage').removeClass('bg-success');
    $('.nine-graph-percentage').addClass('bg-danger');
  }
  $('.nine-graph-percentage').html(data.percentage+' %');
  $('.nine-graph-new-data').html(data.new_count);
  $('.nine-graph-old-data').html(data.old_count);
  graphs['nine-graph'] = new Chart(ctx1, messageGraphOption);
};

function sixthGraph(data, label) {
  let sixthGraph = {
    type: 'line',
    data: {
      labels: label,
      datasets: [
        {
          label: data.new.title,
          data: data.new.count,
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
        },
        {
          label: data.old.title,
          data: data.old.count,
          fill: false,
          lineTension: 0.4,
          radius: 5,
          borderColor: [
            '#727272',
          ],
          color: [
            '#727272',
          ],
          backgroundColor: [
            '#727272',
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
            /* max: 200,*/
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
  let graphs = [];
  let messageGraphOption = JSON.parse(JSON.stringify(sixthGraph));
  messageGraphOption.data.datasets[0].label = selectedPeriodText;
  messageGraphOption.data.datasets[1].label = previousPeriodText;
  let ctx1 = document.getElementById('sixth-graph').getContext('2d');
  if (data.percentage >= 0) {
    $('.sixth-graph-percentage').removeClass('bg-danger');
    $('.sixth-graph-percentage').addClass('bg-success');
  } else {
    $('.sixth-graph-percentage').removeClass('bg-success');
    $('.sixth-graph-percentage').addClass('bg-danger');
  }
  $('.sixth-graph-percentage').html(data.percentage + ' %');
  $('.sixth-graph-new-data').html(data.new_count);
  $('.sixth-graph-old-data').html(data.old_count);
  graphs['sixth-graph'] = new Chart(ctx1, messageGraphOption);
};

/**
 * Contact details model
 */
$(function () {
  let phoneValidation = false;
  $('body').on('click', '.contact-user-card, .contact-user-detail', function(){
    $('#contactDetail').addClass('modal-data-detail');
    $('#contactDetail').find('.contact-edit').trigger('click');
    $.get(showRoute + '/' + $(this).data('id'), function( data ) {
      $('#contactDetail').find('.contact_name').text(data.data.name);
      $('#contactDetail').find('#contact_edit_image_preview').css('background-image', 'url("' + data.data.user_pic + '")');
      $('#contactDetail').find('#contact_edit_full_name').val(data.data.name);
      $('#contactDetail').find('#contact_edit_email').val(data.data.email);
      $('#contactDetail').find('#contact_edit_phone').val(data.data.phone);
      $('#contactDetail').find('#contact_edit_contacted_through').val(data.data.contacted_through);
      $('#contactDetail').find('#contact_edit_message').val(data.data.message);
      $('#contactDetail').find('#contacted_follow_up_date').val(data.data.follow_up_date);
      $('#contactDetail').attr('data-id', data.data.id);
      $('.contact-user-card[data-id="'+data.data.id+'"]').find('.contact-user-card-profile-add').css('background-image', 'url("' + data.data.user_pic + '")');

      $('.print-error-msg-email').hide();
      $('.print-error-msg-phone').hide();
      if (phoneValidation == true) {
        if ($('#contact_edit_email').val() == '') {
          $('.print-error-msg-email').html('L\'e-mail est requis.');
          $('.print-error-msg-email').show();
        }
        if ($('#contact_edit_phone').val() == '') {
          $('.print-error-msg-phone').html('Le numéro de téléphone est requis.');
          $('.print-error-msg-phone').show();
        }
        phoneValidation = false;
      }
    });
    $('#contactDetail').modal('show');
  });
});
