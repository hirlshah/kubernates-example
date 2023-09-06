$(function (){
  let graphCommon = {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: '',
        data: [],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
        ],
        borderWidth: 1
      }]
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
            maxTicksLimit: 7
          }
        }]
      }
    }
  };

  let graphs = [];

  let messageGraphOption = JSON.parse(JSON.stringify(graphCommon));
  messageGraphOption.data.datasets[0].label = "# de messages envoyés";
  let ctx1 = document.getElementById('message-sent-graph').getContext('2d');
  graphs['message-sent-graph'] = new Chart(ctx1, messageGraphOption);

  let customerGraphOption = JSON.parse(JSON.stringify(graphCommon));
  customerGraphOption.data.datasets[0].label = "# de nouveaux clients";
  let ctx2 = document.getElementById('new-customer-graph').getContext('2d');
  graphs['new-customer-graph'] = new Chart(ctx2, customerGraphOption);

  let distributorGraphOption = JSON.parse(JSON.stringify(graphCommon));
  distributorGraphOption.data.datasets[0].label = "# de nouveaux distributeurs";
  let ctx3 = document.getElementById('new-distributor-graph').getContext('2d');
  graphs['new-distributor-graph'] = new Chart(ctx3, distributorGraphOption);

  let start = moment().subtract(6, 'days');
  let end = moment();

  function cb(start, end) {
    if(start.format('YYYY-MM-DD') != end.format('YYYY-MM-DD')) {
      $('.graph-date-range span').html(start.format('YYYY-MM-DD') + ' <br> ' + end.format('YYYY-MM-DD'));
    } else {
      $('.graph-date-range span').html(start.format('YYYY-MM-DD'));
    }
  }

  $('.graph-date-range').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
  });

  cb(start, end);
  $('.graph-date-range').on('apply.daterangepicker', function(ev, picker) {
    let chartId = picker.element.data('chart-id');
    getGraphData(chartId, picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));

    if(picker.startDate.format('YYYY-MM-DD') != picker.endDate.format('YYYY-MM-DD')) {
      $(`.graph-date-range[data-chart-id=${chartId}] span`).html(start.format('YYYY-MM-DD') + ' <br> ' + end.format('YYYY-MM-DD'));
    } else {
      $(`.graph-date-range[data-chart-id=${chartId}] span`).html(start.format('YYYY-MM-DD'));
    }
  });
  
  getGraphData('message-sent-graph', start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
  getGraphData('new-customer-graph', start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
  getGraphData('new-distributor-graph', start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

  function getGraphData(chartId, start_date, end_date) {
    let route = routes[chartId];
    let data = {
      start_date,
      end_date
    };
    $.post(route, data, function (response){
      if(response.success){
        graphs[chartId].data.datasets[0].data = Object.values(response.data);
        graphs[chartId].data.labels = Object.keys(response.data);
        graphs[chartId].update();
      }
    });
  }
});  