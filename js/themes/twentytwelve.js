jQuery(document).ready(function() {
    if (typeof Ecwid == 'undefined') return;

    Ecwid.OnPageLoaded.add(function () {
        document.activeElement.blur();
        jQuery('.nav-menu,.nav-menu *.focus').removeClass('focus');
    });
}
);