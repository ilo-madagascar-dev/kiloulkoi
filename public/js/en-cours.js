$(document).ready( function()
{
    $('.btn-message').click( function()
    {
        var userName = $(this).attr('data-user');
        var url      = $(this).attr('data-url');

        $('#message-receiver').html(userName);
        $('#message-modal form').attr('action', url);
    })

    $('.btn-note').click( function()
    {
        var url = $(this).attr('data-url');
        $('#note-modal form').attr('action', url);
    })

});
