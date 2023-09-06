@if (isset($visiterProspectionVideo->video))
    <video id="video" width="100%" height="100%" controls class="video-play" data-status="true" data-id="{{ $visiterProspectionVideo->id }}" data-visited-id="{{ $videoVisitedId }}">
        <source src="{{  App\Classes\Helper\CommonUtil::getUrl($visiterProspectionVideo->video) }}" type="video/mp4">
        <span id="time">0.00</span>
        Your browser does not support the video tag.
    </video>
@endif


<script type="text/javascript">
    var videoVisiterVisitRoute = "{{ route('visiter.video.visit') }}";
    var prospectionVideoSurveyRoute = "{{ route('frontend.prospection.survey',['','','']) }}";
    var vid = document.getElementById("video");
    var time = document.getElementById("time");
    var video_visiter_id = localStorage.getItem('video_visiter_id');
    let auth_user_id = "{{ Auth::User() ? Auth::User()->id : null }}";

    /**
     * Play video
     */
    if(vid) {
      $(vid).on('play', function(e) {
        var type = "play";
        var visitedVideoId = $(this).data('visited-id');
        var videoStatus = $(this).data('status');
        var user_data = JSON.parse(localStorage.getItem('user_data'));

        mins = Math.floor(vid.currentTime / 60);
        secs = Math.floor(vid.currentTime % 60);

        if (secs < 10) {
          secs = '0' + String(secs);
        }
        var time = mins + ':' + secs;

        if(auth_user_id != user_data.id || !videoStatus) {
          video(video_visiter_id, visitedVideoId, type,time);
        }
      });

      $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
          return null;
        }
        return decodeURI(results[1]) || 0;
      }

     /**
     * End video
     */
      $(vid).on('ended', function(e) {
        var type = "end";
        var visitedVideoId = $(this).data('visited-id');
        var user_data = JSON.parse(localStorage.getItem('user_data'));

        mins = Math.floor(vid.currentTime / 60);
        secs = Math.floor(vid.currentTime % 60);

        if (secs < 10) {
          secs = '0' + String(secs);
        }
        var time = mins + ':' + secs;
        if(auth_user_id != user_data.id) {
          video(video_visiter_id, visitedVideoId, type,time);
        }
      });

      /**
       * Paus video
       */
      $(vid).on('pause', function(e) {
        var type = "pause";
        var visitedVideoId = $(this).data('visited-id');
        var user_data = JSON.parse(localStorage.getItem('user_data'));

        mins = Math.floor(vid.currentTime / 60);
        secs = Math.floor(vid.currentTime % 60);

        if (secs < 10) {
          secs = '0' + String(secs);
        }
        var time = mins + ':' + secs;

        if(auth_user_id != user_data.id) {
          video(video_visiter_id, visitedVideoId, type,time);
        }
      });

      /**
       * Video email
       */
      function video(video_visiter_id, visitedVideoId, type,time) {
        var dateTime =  moment().format('DD/MM/YYYY HH:mm:ss');
        var formData = {
          video_visiter_id : video_visiter_id,
          user_data: localStorage.getItem('user_data'),
          type : type,
          visited_video_id: visitedVideoId,
          date: "{{ getTodayDayForUser() }}",
          referral: $.urlParam('referral'),
          current_date_time: dateTime,
          time : time
        }
        $.ajax({
          type:'POST',
          url: videoVisiterVisitRoute,
          data: formData,
          dataType: "json",
          success:function(data) {
            if(data.success) {
              if(data.redirect_url) {
                window.location.replace(data.redirect_url);
              }
            }
          }
        });
      }
    }
    
    function addAjaxLoader(jqueryElement, height) {
        let html = `<div class="ajax-loader-main" style="min-height: ${height}px"><div class="ajax-loader"><i class="feather-loader"></i></div></div>`;
        jqueryElement.append(html);
    }
</script>
