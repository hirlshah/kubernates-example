$(function () {
  
  let data = {};
  $.get(chartRoute, data, function (response) {
    contactPerDay(response.contactPerDay);  
    contactPerWeek(response.contactPerWeek);  
    contactPerMonth(response.contactPerMonth);  
    clientPerDay(response.clientPerDay);  
    clientPerWeek(response.clientPerWeek);  
    clientPerMonth(response.clientPerMonth);  
    distributorPerDay(response.distributorPerDay);  
    distributorPerWeek(response.distributorPerWeek);  
    distributorPerMonth(response.distributorPerMonth);  
    followupPerDay(response.followupPerDay);  
    followupPerWeek(response.followupPerWeek);  
    followupPerMonth(response.followupPerMonth);  
  });

  $(document).on('click', '.chartData', function () { 
    data['functionName'] = $(this).data('function');
    data['data'] = $(this).data('data');
    $.get(getChartRoute, data, function (response) {
      switch (response.functionName) {
        case 'contactPerDay':
          contactPerDay(response);
          break;
        case 'contactPerWeek':
          contactPerWeek(response);
          break;
        case 'contactPerMonth':
          contactPerMonth(response);
          break;
        case 'clientPerDay':
          clientPerDay(response);
          break;
        case 'clientPerWeek':
          clientPerWeek(response);
          break;
        case 'clientPerMonth':
          clientPerMonth(response);
          break;
        case 'distributorPerDay':
          distributorPerDay(response);
          break;
        case 'distributorPerWeek':
          distributorPerWeek(response);
          break;
        case 'distributorPerMonth':
          distributorPerMonth(response);
          break;
        case 'followupPerDay':
          followupPerDay(response);
          break;
        case 'followupPerWeek':
          followupPerWeek(response);
          break;
        case 'followupPerMonth':
          followupPerMonth(response);
          break;
        default:
          alert("Something went wrong.");
      }
    });
  });

  function contactPerDay(data) {
    let contactPerDay = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(contactPerDay));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('contact-per-day').getContext('2d');
    graphs['contact-per-day'] = new Chart(ctx1, messageGraphOption);

    $('#contact-per-day-name').html(data.now);
    $('#contact-per-day-previous').data('data',data.previous);
    $('#contact-per-day-next').data('data', data.next);
    $('#contact-per-day-previous').data('function',data.functionName);
    $('#contact-per-day-next').data('function', data.functionName);
    
  };

  function contactPerWeek(data) {
    let contactPerWeek = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(contactPerWeek));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('contact-per-week').getContext('2d');
    graphs['contact-per-week'] = new Chart(ctx1, messageGraphOption);

    $('#contact-per-week-name').html(data.now);
    $('#contact-per-week-previous').data('data',data.previous);
    $('#contact-per-week-next').data('data', data.next);
    $('#contact-per-week-previous').data('function',data.functionName);
    $('#contact-per-week-next').data('function', data.functionName);
    
  };

  function contactPerMonth(data) {
    let contactPerMonth = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(contactPerMonth));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('contact-per-month').getContext('2d');
    graphs['contact-per-month'] = new Chart(ctx1, messageGraphOption);

    $('#contact-per-month-name').html(data.now);
    $('#contact-per-month-previous').data('data',data.previous);
    $('#contact-per-month-next').data('data', data.next);
    $('#contact-per-month-previous').data('function',data.functionName);
    $('#contact-per-month-next').data('function', data.functionName);
    
  };

  function clientPerDay(data) {
    let clientPerDay = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(clientPerDay));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('client-per-day').getContext('2d');
    graphs['client-per-day'] = new Chart(ctx1, messageGraphOption);

    $('#client-per-day-name').html(data.now);
    $('#client-per-day-previous').data('data',data.previous);
    $('#client-per-day-next').data('data', data.next);
    $('#client-per-day-previous').data('function',data.functionName);
    $('#client-per-day-next').data('function', data.functionName);
    
  };

  function clientPerWeek(data) {
    let clientPerWeek = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(clientPerWeek));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('client-per-week').getContext('2d');
    graphs['client-per-week'] = new Chart(ctx1, messageGraphOption);

    $('#client-per-week-name').html(data.now);
    $('#client-per-week-previous').data('data',data.previous);
    $('#client-per-week-next').data('data', data.next);
    $('#client-per-week-previous').data('function',data.functionName);
    $('#client-per-week-next').data('function', data.functionName);
    
  };

  function clientPerMonth(data) {
    let clientPerMonth = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(clientPerMonth));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('client-per-month').getContext('2d');
    graphs['client-per-month'] = new Chart(ctx1, messageGraphOption);

    $('#client-per-month-name').html(data.now);
    $('#client-per-month-previous').data('data',data.previous);
    $('#client-per-month-next').data('data', data.next);
    $('#client-per-month-previous').data('function',data.functionName);
    $('#client-per-month-next').data('function', data.functionName);
    
  };

  function distributorPerDay(data) {
    let distributorPerDay = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(distributorPerDay));
    messageGraphOption.data.datasets[0].label = 'Total';
    let ctx1 = document.getElementById('distributor-per-day').getContext('2d');
    graphs['distributor-per-day'] = new Chart(ctx1, messageGraphOption);

    $('#distributor-per-day-name').html(data.now);
    $('#distributor-per-day-previous').data('data',data.previous);
    $('#distributor-per-day-next').data('data', data.next);
    $('#distributor-per-day-previous').data('function',data.functionName);
    $('#distributor-per-day-next').data('function', data.functionName);
    
  };

  function distributorPerWeek(data) {
    let distributorPerWeek = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(distributorPerWeek));
    messageGraphOption.data.datasets[0].label = 'Total';
    let ctx1 = document.getElementById('distributor-per-week').getContext('2d');
    graphs['distributor-per-week'] = new Chart(ctx1, messageGraphOption);

    $('#distributor-per-week-name').html(data.now);
    $('#distributor-per-week-previous').data('data',data.previous);
    $('#distributor-per-week-next').data('data', data.next);
    $('#distributor-per-week-previous').data('function',data.functionName);
    $('#distributor-per-week-next').data('function', data.functionName);
    
  };

  function distributorPerMonth(data) {
    let distributorPerMonth = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(distributorPerMonth));
    messageGraphOption.data.datasets[0].label = 'Total';
    let ctx1 = document.getElementById('distributor-per-month').getContext('2d');
    graphs['distributor-per-month'] = new Chart(ctx1, messageGraphOption);

    $('#distributor-per-month-name').html(data.now);
    $('#distributor-per-month-previous').data('data',data.previous);
    $('#distributor-per-month-next').data('data', data.next);
    $('#distributor-per-month-previous').data('function',data.functionName);
    $('#distributor-per-month-next').data('function', data.functionName);
    
  };

  function followupPerDay(data) {
    let followupPerDay = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(followupPerDay));
    messageGraphOption.data.datasets[0].label = 'Total';
    let ctx1 = document.getElementById('followup-per-day').getContext('2d');
    graphs['followup-per-day'] = new Chart(ctx1, messageGraphOption);

    $('#followup-per-day-name').html(data.now);
    $('#followup-per-day-previous').data('data',data.previous);
    $('#followup-per-day-next').data('data', data.next);
    $('#followup-per-day-previous').data('function',data.functionName);
    $('#followup-per-day-next').data('function', data.functionName);
    
  };

  function followupPerWeek(data) {
    let followupPerWeek = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(followupPerWeek));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('followup-per-week').getContext('2d');
    graphs['followup-per-week'] = new Chart(ctx1, messageGraphOption);

    $('#followup-per-week-name').html(data.now);
    $('#followup-per-week-previous').data('data',data.previous);
    $('#followup-per-week-next').data('data', data.next);
    $('#followup-per-week-previous').data('function',data.functionName);
    $('#followup-per-week-next').data('function', data.functionName);
    
  };

  function followupPerMonth(data) {
    let followupPerMonth = {
      type: 'line',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          fill: false,
          lineTension: 0.4,        
          radius: 5, 
          borderColor: [
            '#151D3B',
          ],
          borderWidth: 2
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
    let messageGraphOption = JSON.parse(JSON.stringify(followupPerMonth));
    messageGraphOption.data.datasets[0].label = 'Total';
    let ctx1 = document.getElementById('followup-per-month').getContext('2d');
    graphs['followup-per-month'] = new Chart(ctx1, messageGraphOption);

    $('#followup-per-month-name').html(data.now);
    $('#followup-per-month-previous').data('data',data.previous);
    $('#followup-per-month-next').data('data', data.next);
    $('#followup-per-month-previous').data('function',data.functionName);
    $('#followup-per-month-next').data('function', data.functionName);
    
  };

});