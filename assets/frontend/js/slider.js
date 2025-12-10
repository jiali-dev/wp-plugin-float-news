jQuery(function ($) {
  "use strict";

  const SLIDE_DURATION = parseInt(jialifn_style_settings.slideDuration) || 4000; // 4 seconds per slide
console.log("Slider duration:", SLIDE_DURATION);
  function initSlider($container) {
    const $slides = $container.find(".jialifn-slides");
    const $progress = $container.find(".jialifn-progress-bar");
    if (!$slides.length) return; // no slides, skip

    let current = 0;
    const total = $slides.length;

    function nextSlide() {
      $slides.eq(current).removeClass("active");
      current = (current + 1) % total;
      $slides.eq(current).addClass("active");

      $progress.stop(true, true)
        .css("width", "0")
        .animate({ width: "100%" }, SLIDE_DURATION, "linear", nextSlide);
    }

    // initialize
    $slides.removeClass("active").eq(0).addClass("active");
    $progress.css("width", "0")
      .animate({ width: "100%" }, SLIDE_DURATION, "linear", nextSlide);
  }

  // ✅ Initialize immediately if slides already exist
  $(".jialifn-slider").each(function () {
    initSlider($(this));
  });

  // ✅ Also auto-initialize dynamically added sliders
  const observer = new MutationObserver(() => {
    $(".jialifn-slider").each(function () {
      if (!$(this).data("initialized")) {
        $(this).data("initialized", true);
        initSlider($(this));
      }
    });
  });

  observer.observe(document.body, { childList: true, subtree: true });
});