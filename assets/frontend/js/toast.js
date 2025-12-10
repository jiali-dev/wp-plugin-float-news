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

  // Build Query URL
  function buildQueryUrl(baseUrl, params) {
    const query = new URLSearchParams();

    Object.keys(params).forEach(key => {
        const value = params[key];

        if (Array.isArray(value)) {
            value.forEach(v => query.append(key + "[]", v));
        } else if (value !== "" && value !== null && value !== undefined) {
            query.append(key, value);
        }
    });

    return baseUrl + "?" + query.toString();
  }
  
  const query_url = buildQueryUrl(jialifn_ajax.api_url, jialifn_ajax.settings);
  console.log("Query URL:", query_url);

  fetch(query_url)
    .then(res => res.json())
    .then(posts => {

        if (!posts.length) return;

        let html = `
            <div class="jialifn-slider">
                ${posts.map((item, i) => `
                    <a class="jialifn-slides ${i === 0 ? 'active' : ''}" href="${item.link}">
                        <h3 class="jialifn-slides__title">${item.title}</h3>
                        <img class="jialifn-slides__img" src="${item.image || ''}">
                    </a>
                `).join('')}
                <div class="jialifn-progress"><div class="jialifn-progress-bar"></div></div>
            </div>
        `;

        $(".jialifn-toast .jialifn-toast-content").html(html);
        $(".jialifn-toast").addClass("show");
    })
    .catch(console.error);
});