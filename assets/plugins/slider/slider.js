jQuery(document).ready(function ($) {

  "use strict";

  let current = 0;
  const slides = $(".jialifn-slides");
  const total = slides.length;
  const duration = 4000; // 4 seconds per slide
  const progressBar = $(".jialifn-progress-bar");

  function nextSlide() {
    slides.eq(current).removeClass("active");
    current = (current + 1) % total;
    slides.eq(current).addClass("active");

    progressBar.stop(true, true).css("width", "0").animate(
      { width: "100%" },
      duration,
      "linear",
      nextSlide
    );
  }

  // Start slider
  slides.eq(0).addClass("active");
  progressBar.animate({ width: "100%" }, duration, "linear", nextSlide);

});
