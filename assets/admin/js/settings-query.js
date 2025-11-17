jQuery(document).ready(function ($) {
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

    // Initialize Select2
    $('.jialifn-select2').select2({
      theme: classic
      // placeholder: "Select Type",
      // allowClear: true
    });

  })
});
