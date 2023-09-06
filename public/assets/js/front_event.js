let event_meeting_date = null;
$(document).ready(function () {

  if(eventMeetingDate) {
    $.ajax({
      type: "POST",
      url: getEventDateRoute,
      data: {
        timezoneName: timezoneName,
        timezoneOffset: timezoneOffset,
      },
      cache: false,
      success: function (response) {
        if (response.success == true) {
          event_meeting_date = response.date;
          GetTimer();
        } else {
          alert(response.message);
        }
      },
      error:function(data){
      },
      dataType: "json",
    });
  }

  function GetTimer() {
    /**
     * Event Page CountDown Timer
     */
    if ($("section").hasClass("countdown-timer")) {       
      const countDownDate = new Date(event_meeting_date.replace(/\s/, 'T')).getTime();
      const x = setInterval(function () {
        const now = new Date().getTime();
        const distance = countDownDate - now;
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;
        if (distance <= 0) {
          clearInterval(x);
          document.getElementById("time").style.display = "none";
          document.getElementById("event-start-title").style.display = "none";
        }
        if (distance > 0) {
          document.getElementById("time").style.display = "inline-flex";
          document.getElementById("event-start-title").style.display = "inline-flex";
        }
      }, 1000);
    }
  }

  if (authUser == 'true') {
    $('#userdetailbutton').hide();
    $('#zoom_meeting_button').show();
  } else {
    if (referralCookie != 1) {
      //if cookie is not set then set modal attributes to the button
      $('#userdetailbutton').data('toggle', 'modal');
      $('#userdetailbutton').data('target', 'myEventModal');
      $('#zoom_meeting_button').hide();
      $('#meeting_button').hide();
    } else if (event != eventCookie) {
      //cookie is set but for different event then set modal attributes to the button
      $('#userdetailbutton').data('toggle', 'modal');
      $('#userdetailbutton').data('target', 'myEventModal');
      $('#meeting_button').hide();
    } else {
      //if cookie set and event is same then change button text
      $('#userdetailbutton').hide();
      $('#presence_confirmed').show();
      $('#zoom_meeting_button').show();
      $('#meeting_button').show();
    }
  }
  
  const diff = FindDiff();
  if (diff['diffDays'] <= -1 || diff['diffMins'] <= -1 || diff['diffMins'] <= -5) {
    $('#event_survey_button').show();
    $('#zoom_meeting_button').html(zoomMeetingText);
  }

  $('.print-common-error-msg').hide();
  $('.print-common-success-msg').hide();
  if(eventMeetingDate){
    const diff = FindDiff();
    if(diff['diffDays'] <= 0 && diff['diffHrs'] <= 0 && diff['diffMins'] <= 30){
      //if time is less than 30 min then show join zoom button
      $('#userdetailbutton').html(zoomMeetingText);
    }
  } else {
    $('#user-info-form-call').show();
    $('#userdetailbutton').hide();
    //$('#zoom_meeting_button').html('Enter the call');
  }

  function FindDiff(){
    const today = new Date();
    const event_meeting_date = new Date(eventMeetingDate);
    const diffMs = (event_meeting_date - today); // milliseconds between now & event_meeting_date
    const array = [];
    array['diffDays'] = Math.floor(diffMs / 86400000); // days
    array['diffHrs'] = Math.floor((diffMs % 86400000) / 3600000); // hours
    array['diffMins'] = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
    return array;
  }

  $('#userdetailbutton').click(function (e) {
    e.preventDefault();
    if(referralCookie == 1 && event == eventCookie){
      //if cookie is set and if time is less then 30 then redirect to meeting URL
      if(eventMeetingDate == null || (diff['diffDays'] <= 0 && diff['diffHrs'] <= 0 && diff['diffMins'] <= 30)){
        window.open(eventMeetingURL, '_blank').focus();
      }
    } else {
      $('#myEventModal').modal('show');
    }
  });

  $("#user-info-form").on("submit",function(e){
    e.preventDefault();
    const data = new FormData(this);
    const diff = FindDiff();
    if (diff['diffDays'] <= 0 && diff['diffHrs'] <= 0 && diff['diffMins'] <= 30) {
      data.append('board_status', attendedZoom);
    } else {
      data.append('board_status', confirmedZoom);
    }

    $.ajax({
      type: "POST",
      url: $("#user-info-form").attr("action"),
      data: data,
      contentType: false,
      cache: false,
      processData:false,
      success: function (response) {
        if (response.url) {
          window.location.href = response.url;
          return;
        }
        $('.print-common-error-msg').hide();
        $('#user-info-form').trigger("reset");
        $('.print-common-success-msg').html(response.message);
        $('.print-common-success-msg').show();
        setTimeout(function() {
          $('.print-common-success-msg').delay(3000).fadeOut('slow');
          $('#myEventModal').modal('hide');
        }, 3000);
        location.reload();
      },
      error:function(data){
        $('.print-common-error-msg').html(data.responseJSON.message);
        $('.print-common-error-msg').show();
        if(data.responseJSON.message = 'The given data was invalid.'){
          if(data.responseJSON.errors.email){
            $('.print-error-msg-email').html(data.responseJSON.errors.email);
            $('.print-error-msg-email').show();
          }
          if (data.responseJSON.errors.first_name){
            $('.print-error-msg-first-name').html(data.responseJSON.errors.first_name);
            $('.print-error-msg-first-name').show();
          }
          if (data.responseJSON.errors.last_name) {
            $('.print-error-msg-last-name').html(data.responseJSON.errors.last_name);
            $('.print-error-msg-last-name').show();
          }
          if (data.responseJSON.errors.phone) {
            $('.print-error-msg-phone').html(data.responseJSON.errors.phone);
            $('.print-error-msg-phone').show();
          }
        }
      },
      dataType: "json",
    });
  });
});