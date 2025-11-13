jQuery(document).ready(function ($) {

  "use strict";

  function showToast(html = '', type = 'info', duration = 3000) {
    // Remove old toast if exists
    $('.jialifn-toast').remove();

    // // Create toast element
    // const element = `
    //   <div class="jialifn-toast">
    //     <div class="jialifn-toast-content">
    //       ${html}
    //     </div>
    //   </div>`;

    // const $toast = $(element)
    //   .addClass(type)
    //   .appendTo('body');

    // Trigger show animation
    setTimeout(() => $toast.addClass('show'), 50);

  }

});