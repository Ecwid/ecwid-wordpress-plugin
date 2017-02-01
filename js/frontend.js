window.ec = window.ec || {};
window.ec.config = window.ec.config || {};
window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};

window.ec.config.storefrontUrls.cleanUrls = true;
window.ec.config.baseUrl = 'wordpress/461/store';

jQuery(document).ready(function() {
  jQuery('.ecwid-store-with-categories a').click(function() {jQuery(':focus').blur()});
})
