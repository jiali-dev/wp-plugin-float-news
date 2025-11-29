jQuery(document).ready(function ($) {
  "use strict";

  // Check source field dependencies to show
  function handleSourceDependencies(value) {
    const hide = value === "manual_selection";
    $(".jialifn-query-exception-section-wrapper").toggle(!hide);
    $(".jialifn-query-date-section-wrapper").toggle(!hide);
    $(".jialifn-query-order-section-wrapper").toggle(!hide);
    $(".jialifn-manual-sources-wrapper").toggle(hide);
  }

  // Check include by field dependencies to show
  function handleIncludeByDependencies(values) {
    const showByTerm = values.includes("term");
    const showByAuthor = values.includes("author");
    $(".jialifn-included-terms-wrapper").toggle(showByTerm);
    if( showByTerm ) {
      $(".jialifn-included-terms").val(null).trigger('change');
    }
    $(".jialifn-included-authors-wrapper").toggle(showByAuthor);
    if( showByAuthor ) {
      $(".jialifn-included-authors").val(null).trigger('change');
    }
  }

  // Check exclude by field dependencies to show
  function handleExcludeByDependencies(values) {
    const showByTerm = values.includes("term");
    const showByAuthor = values.includes("author");
    const showManualSelection = values.includes("manual_selection");
    $(".jialifn-excluded-terms-wrapper").toggle(showByTerm);
    $(".jialifn-excluded-authors-wrapper").toggle(showByAuthor);
    $(".jialifn-manual-excluded-sources-wrapper").toggle(showManualSelection);
  }

  // Check date range field dependencies to show
  function handleDateRangeDependencies(value) {
    const show = value === "custom";
    $(".jialifn-date-before-wrapper").toggle(show);
    $(".jialifn-date-after-wrapper").toggle(show);
  }

  // Run on page load
  handleSourceDependencies($(".jialifn-source").val());
  handleIncludeByDependencies($(".jialifn-include-by").val() || []);
  handleExcludeByDependencies($(".jialifn-exclude-by").val() || []);
  handleDateRangeDependencies($(".jialifn-date-range").val());

  // Get source
  $(".jialifn-source").on("change", function () {
    const value = $(this).val();
    handleSourceDependencies(value);
    $(".jialifn-include-by").val(null).trigger('change');
    $(".jialifn-included-terms").val(null).trigger('change');
    $(".jialifn-included-authors").val(null).trigger('change');
    $(".jialifn-exclude-by").val(null).trigger('change');
    $(".jialifn-excluded-terms").val(null).trigger('change');
    $(".jialifn-excluded-authors").val(null).trigger('change');
    $(".jialifn-manual-excluded-sources").val(null).trigger('change');
  });

  // Get manual sources
  $(".jialifn-manual-sources").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_manual_sources",
          search: params.term,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Initialize include by field
  $(".jialifn-include-by").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.select,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
  });

  // Get include by
  $(".jialifn-include-by").on("change", function () {
    const values = $(this).val() || [];
    handleIncludeByDependencies(values);
  });

  // Get included terms
  $(".jialifn-included-terms").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const postType = $(".jialifn-source").val();
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_terms",
          search: params.term,
          post_type: postType,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Get included authors
  $(".jialifn-included-authors").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_authors",
          search: params.term,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Initialize exclude by field
  $(".jialifn-exclude-by").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.select,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
  });

  // Get exclude by
  $(".jialifn-exclude-by").on("change", function () {
    const values = $(this).val() || [];
    handleExcludeByDependencies(values);
  });

  // Get excluded terms
  $(".jialifn-excluded-terms").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const postType = $(".jialifn-source").val();
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_terms",
          search: params.term,
          post_type: postType,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Get excluded authors
  $(".jialifn-excluded-authors").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_authors",
          search: params.term,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Get manual sources
  $(".jialifn-manual-excluded-sources").select2({
    width: "25%",
    placeholder: jialifn_translate_handler.enter_characters,
    dir: $("body").hasClass("rtl") ? "rtl" : "ltr",
    ajax: {
      url: jialifn_ajax.ajaxurl,
      dataType: "json",
      delay: 250,
      type: "POST",
      data: function (params) {
        const nonce = jialifn_ajax.nonce;

        return {
          action: "jialifn_get_manual_sources",
          search: params.term,
          nonce: nonce,
        };
      },
      processResults: function (data) {
        return {
          results: data,
        };
      },
    },
  });

  // Get Date Range
  $(".jialifn-date-range").on("change", function () {
    const value = $(this).val();
    handleDateRangeDependencies(value);
  });

  // Date before
  flatpickr(".jialifn-date-before", {
    placeholder: jialifn_translate_handler.enter_characters,
    enableTime: true,
    noCalendar: false, // keep the calendar visible
    dateFormat: "Y-m-d H:i", // include hours & minutes in the output
    time_24hr: true, // 24-hour format (optional)
  });

  // Date after
  flatpickr(".jialifn-date-after", {
    enableTime: true,
    noCalendar: false, // keep the calendar visible
    dateFormat: "Y-m-d H:i", // include hours & minutes in the output
    time_24hr: true, // 24-hour format (optional)
  });
});
