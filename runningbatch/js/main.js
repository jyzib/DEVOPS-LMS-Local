define(['jquery'], function($) {
    function init() {
      $(document).on('click', '.video-link', function(e) {
        e.preventDefault();
        var videoUrl = $(this).data('video-url');
        showVideoPopup(videoUrl);
      });
    }
  
    function showVideoPopup(videoUrl) {
      var $videoContainer = $('<div id="video-popup"></div>');
      var $video = $('<video controls autoplay><source src="' + videoUrl + '" type="video/mp4"></video>');
      var $controls = $('<div id="video-popup-controls"><button class="play-pause">&#9658;</button><button class="fullscreen">&#10064;</button></div>');
  
      $videoContainer.append($video);
      $videoContainer.append($controls);
      $('#video-popup-container').html($videoContainer).fadeIn();
  
      var videoElement = $video[0];
  
      $controls.find('.play-pause').on('click', function() {
        if (videoElement.paused) {
          videoElement.play();
          $(this).html('&#10074;&#10074;');
        } else {
          videoElement.pause();
          $(this).html('&#9658;');
        }
      });
  
      $controls.find('.fullscreen').on('click', function() {
        if (videoElement.requestFullscreen) {
          videoElement.requestFullscreen();
        } else if (videoElement.mozRequestFullScreen) {
          videoElement.mozRequestFullScreen();
        } else if (videoElement.webkitRequestFullscreen) {
          videoElement.webkitRequestFullscreen();
        } else if (videoElement.msRequestFullscreen) {
          videoElement.msRequestFullscreen();
        }
      });
    }
  
    return {
      init: init
    };
  });
  