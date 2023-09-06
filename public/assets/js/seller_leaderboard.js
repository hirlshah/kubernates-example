$(function () {
  let data = {};
  get_presentation_given_data();
  get_customer_acquisition_data();
  get_distributor_acquisition_data();
  get_presentations_data();
  get_message_sent_data();
  get_present_at_zoom_data();

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
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });

  $('body').on('click', '.calendar-btn', function () {
    $(this).addClass('active').siblings().removeClass('active');
    dateFilterType = $(this).data('type');
    if(dateFilterType != 'customRange') {
      get_presentation_given_data();
      get_customer_acquisition_data();
      get_distributor_acquisition_data();
      get_presentations_data();
      get_message_sent_data();
      get_present_at_zoom_data();
    }
  });

  $('#custom-range').on('apply.daterangepicker', function (ev, picker) {
    var startDate = picker.startDate;
    var endDate = picker.endDate;
    start = startDate.format('YYYY-MM-DD');
    end = endDate.format('YYYY-MM-DD');
    dateFilterType = 'customRange';
    get_presentation_given_data();
    get_customer_acquisition_data();
    get_distributor_acquisition_data();
    get_presentations_data();
    get_message_sent_data();
    get_present_at_zoom_data();
  });

  /**
   * Get presentation given data
   */
  function get_presentation_given_data() {
    $('#presentation_given').html('');
    addBootstrapAjaxLoader($('#presentation_given'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(presentationGivenRoute, data, function (response){
      $('#presentation_given').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#presentation_given'));
    });
  }

  /**
   * Get customer acquisition data
   */
  function get_customer_acquisition_data() {
    $('#customer_acquisition').html('');
    addBootstrapAjaxLoader($('#customer_acquisition'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(customerAcquisitionRoute, data, function (response){
      $('#customer_acquisition').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#customer_acquisition'));
    });
  }

  /**
   * Get distributor acquisition data
   */
  function get_distributor_acquisition_data() {
    $('#distributor_acquisition').html('');
    addBootstrapAjaxLoader($('#distributor_acquisition'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(distributorAcquisitionRoute, data, function (response){
      $('#distributor_acquisition').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#distributor_acquisition'));
    });
  }

  /**
   * Get presentations data
   */
  function get_presentations_data() {
    $('#presentations').html('');
    addBootstrapAjaxLoader($('#presentations'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(presentationsRoute, data, function (response){
      $('#presentations').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#presentations'));
    });
  }

  /**
   * Get message sent data
   */
  function get_message_sent_data() {
    $('#message_sent').html('');
    addBootstrapAjaxLoader($('#message_sent'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(messageSentRoute, data, function (response){
      $('#message_sent').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#message_sent'));
    });
  }

  /**
   * Get present at zoom data
   */
  function get_present_at_zoom_data() {
    $('#present_at_zoom').html('');
    addBootstrapAjaxLoader($('#present_at_zoom'), 300);
    if (dateFilterType == 'Day') {
      data.dateFilterType = 'Day';
    }
    if (dateFilterType == 'Week') {
      data.dateFilterType = 'Week';
    }
    if (dateFilterType == 'Month') {
      data.dateFilterType = 'Month';
    }
    if (dateFilterType == 'customRange') {
      data.dateFilterType = 'customRange';
    }
    if(start) {
      data.start = start;
    }
    if(end) {
      data.end = end;
    }
    $.get(presentAtZoomRoute, data, function (response){
      $('#present_at_zoom').html(response.view);
      $('#page-title').html(response.pageName);
      removeBootstrapAjaxLoader($('#present_at_zoom'));
    });
  }

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
  }
});
