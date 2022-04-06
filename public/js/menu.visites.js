$(document).ready(function () {
    var url = $(location).attr('pathname');
    url = url.replace('/rubrique-', '');
    var id = url.replace('/article-', '');
    var ul = $('.menu_visites ul[rubrique=' + id + ']');
    while (ul.length) {
        ul.addClass('on');
        ul = ul.parents('ul');
    }
});