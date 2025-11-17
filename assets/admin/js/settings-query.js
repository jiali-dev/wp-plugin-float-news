jQuery(document).ready(function ($) {

  "use strict";
  
  // Initialize Select2
  $('.jialifn-includeby').select2({
      width: '25%',
      placeholder: 'Select ...',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );

  $('.jialifn-includedterms').select2({
      width: '25%',
      placeholder: 'Please enter 1 or more characters',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr',
      ajax: {
        url: jialifn_ajax.ajaxurl,
        dataType: 'json',
        delay: 250,
        type: 'POST',
        data: function(params) {

            const postType = $('.jialifn-source').val();
            const nonce = jialifn_ajax.nonce;

            return {
              action: 'jialifn_get_terms',
              search: params.term,
              post_type: postType,
              nonce: nonce,
            };
        },
        processResults: function(data) {
            return {
              results: data
            };
        }
      }
    }
  );

  $('.jialifn-includedauthors').select2({
      width: '25%',
      placeholder: 'Please enter 1 or more characters',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );


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
