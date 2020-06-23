$(document).ready(function () {
    const sPathname = window.location.pathname;
    const oSelector = $(`a[href="${sPathname}"]`);
    const setActive = function(oElement) {
        oElement.addClass('active');
        oElement.closest('ul').css('display:block;');
        oElement.closest('ul').closest('li').addClass('menu-open');
    }

    setActive(oSelector);
});