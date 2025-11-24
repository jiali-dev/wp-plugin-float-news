jQuery(document).ready(function ($) {

  "use strict";
  
  // Check field dependencies to show
  function handleSourceDependencies(value) { 
    
    const hide = (value === 'manual_selection');

    $('.jialifn-query-exception-section-wrapper').toggle(!hide);
    $('.jialifn-query-date-section-wrapper').toggle(!hide);
    $('.jialifn-manual-sources-wrapper').toggle(hide);

  }

  // Run on page load
  handleSourceDependencies($('.jialifn-source').val());

  // Get source
  $('.jialifn-source').on('change', function() {
    
    const value = $(this).val();

    handleSourceDependencies(value);

  })

  // Get manual sources
  $('.jialifn-manual-sources').select2({
      width: '25%',
      placeholder: jialifn_translate_handler.enter_characters,
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
      placeholder: jialifn_translate_handler.select,
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );

  // Initialize exclude by field
  $('.jialifn-exclude-by').select2({
      width: '25%',
      placeholder: jialifn_translate_handler.select,
      dir: $('body').hasClass('rtl') ? 'rtl' : 'ltr'
    }
  );

  // Get included terms
  $('.jialifn-included-terms').select2({
      width: '25%',
      placeholder: jialifn_translate_handler.enter_characters,
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
      placeholder: jialifn_translate_handler.enter_characters,
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
      placeholder: jialifn_translate_handler.enter_characters,
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
      placeholder: jialifn_translate_handler.enter_characters,
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
      placeholder: jialifn_translate_handler.enter_characters,
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

  // Date before
  flatpickr(".jialifn-date-before", {
    placeholder: jialifn_translate_handler.enter_characters,
    enableTime: true,
    noCalendar: false,     // keep the calendar visible
    dateFormat: "Y-m-d H:i", // include hours & minutes in the output
    time_24hr: true        // 24-hour format (optional)
  });

  // Date after
  flatpickr(".jialifn-date-after", {
    enableTime: true,
    noCalendar: false,     // keep the calendar visible
    dateFormat: "Y-m-d H:i", // include hours & minutes in the output
    time_24hr: true        // 24-hour format (optional)
  });
});
