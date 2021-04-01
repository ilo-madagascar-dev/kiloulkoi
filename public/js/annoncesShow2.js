$("#button-ads-show").click(function () {
    $(".kiloukoi-show-icons").toggleClass("display-inline-anonces");
});

$(document).ready(function () {
    $.datetimepicker.setLocale('fr');

    $('#dateTimePicker').datetimepicker({
        format: 'm/d/Y H:i',
        locale: 'fr'
    });

    $('#dateTimePicker1').datetimepicker({
        format: 'm/d/Y H:i',
        lang: 'fr'
    });
});