$(function () {
  
  let data = {};
  $.get(getMemberStatRoute, data, function (response) {
    $('#team_member_stats').html(response.view);
    genderData(response.gender); 
    ageData(response.age); 
  });

  function genderData(data) {
    let genderData = {
      type: 'doughnut',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          backgroundColor: [
              "#1C64B9",
              "#E3E3E3",
              "#1C64B9",
            ],
            borderColor: [
              "#1C64B9",
              "#E3E3E3",
              "#1C64B9",
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1]
        }]
      },
      //options
      options : {
        responsive: true,
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        }
      }
    };    
    let graphs = [];
    let messageGraphOption = JSON.parse(JSON.stringify(genderData));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('gender-data').getContext('2d');
    graphs['gender-data'] = new Chart(ctx1, messageGraphOption);    
  };

  function ageData(data) {
    let ageData = {
      type: 'doughnut',
      data: {
        labels: data.data,
        datasets: [{
          label: '',
          data: data.count,
          backgroundColor: [
              "#56B2FF",
              "#1C64B9",
              "#E3E3E3",
              "#B1913C",
            ],
            borderColor: [
              "#56B2FF",
              "#1C64B9",
              "#E3E3E3",
              "#B1913C",
            ],
            borderWidth: [1, 1, 1, 1, 1,1,1]
        }]
      },
      //options
      options : {
        responsive: true,
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        }
      }
    };    
    let graphs = [];
    let messageGraphOption = JSON.parse(JSON.stringify(ageData));
    messageGraphOption.data.datasets[0].label = 'Count';
    let ctx1 = document.getElementById('age-data').getContext('2d');
    graphs['age-data'] = new Chart(ctx1, messageGraphOption);    
  };
});