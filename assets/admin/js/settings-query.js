jQuery(document).ready(function ($) {

  "use strict";
  
  // Get source
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

  // Get manual sources
  $('.jialifn-manual-sources').select2({
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
              action: 'jialifn_get_manual_sources',
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

  // Initialize include by field
  $('.jialifn-include-by').select2({
      width: '25%',
      placeholder: 'Select ...',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );

  // Initialize exclude by field
  $('.jialifn-exclude-by').select2({
      width: '25%',
      placeholder: 'Select ...',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );

  // Get included terms
  $('.jialifn-included-terms').select2({
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

  // Get included authors
  $('.jialifn-included-authors').select2({
      width: '25%',
      placeholder: 'Please enter 1 or more characters',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr',
      ajax: {
        url: jialifn_ajax.ajaxurl,
        dataType: 'json',
        delay: 250,
        type: 'POST',
        data: function(params) {

            const nonce = jialifn_ajax.nonce;

            return {
              action: 'jialifn_get_authors',
              search: params.term,
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

    // Get excluded terms
  $('.jialifn-excluded-terms').select2({
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

  // Get excluded authors
  $('.jialifn-excluded-authors').select2({
      width: '25%',
      placeholder: 'Please enter 1 or more characters',
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr',
      ajax: {
        url: jialifn_ajax.ajaxurl,
        dataType: 'json',
        delay: 250,
        type: 'POST',
        data: function(params) {

            const nonce = jialifn_ajax.nonce;

            return {
              action: 'jialifn_get_authors',
              search: params.term,
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

  // Get manual sources
  $('.jialifn-manual-excluded-sources').select2({
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
              action: 'jialifn_get_manual_sources',
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
});
