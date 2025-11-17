jQuery(document).ready(function ($) {

  "use strict";
  
  // Initialize Select2
  $('#example-select').select2();


  $('.jialifn-source').on('change', function() {
    const value = $(this).val();

    if( value === 'manual_ids' ) {
      $('.jialifn-includeby-wrapper').hide();
      $('.jialifn-excludeby-wrapper').hide();
      $('.jialifn-date-range-wrapper').hide();
    } else {
      $('.jialifn-includeby-wrapper').show();
      $('.jialifn-excludeby-wrapper').show();
      $('.jialifn-date-range-wrapper').show();
    }

  })
});
