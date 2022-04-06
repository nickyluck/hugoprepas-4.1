$(document).ready(function () {
    $('span.classe').click(function (event) {
        event.preventDefault();
        var $ul = $(this).next();
        $ul.toggle('fast');
    });
});
