jQuery(document).ready(function ($) {

  "use strict";

  function showToast(content, duration = 3000) {
    const $toast = $('.jialifn-toast');
    const $content = $toast.find('.jialifn-toast-content');

    // Allow HTML content
    if (typeof content === 'string' || content instanceof String) {
      $content.html(content);
    } else {
      $content.empty().append(content);
    }

    // If already showing, restart
    if ($toast.hasClass('show')) {
      $toast.removeClass('show');
      clearTimeout($toast.data('timeout'));
      setTimeout(() => showToast(content, duration), 200);
      return;
    }

    // Show toast
    $toast.css('display', 'block').addClass('show');

    // Auto-hide
    // const timeout = setTimeout(() => {
    //   $toast.removeClass('show');
    //   setTimeout(() => $toast.hide(), 300);
    // }, duration);

    // $toast.data('timeout', timeout);
  }

  showToast(`
    <div class="jialifn-slider">
        <div class="jialifn-slides active">
            <img src="https://picsum.photos/id/1018/800/400" alt="Slide 1">
        </div>
        <div class="jialifn-slides">
            <img src="https://picsum.photos/id/1025/800/400" alt="Slide 2">
        </div>
        <div class="jialifn-slides">
            <img src="https://picsum.photos/id/1039/800/400" alt="Slide 3">
        </div>

        <div class="jialifn-progress">
            <div class="jialifn-progress-bar"></div>
        </div>
    </div>
  `);

});