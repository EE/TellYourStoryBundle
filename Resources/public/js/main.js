$(function () {
    $('body').tooltip({
        selector: 'a[rel="tooltip"], [data-toggle="tooltip"]'
    });
    $('input[type=file]').bootstrapFileInput();
});