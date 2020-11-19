
$(document).ready( function()
{
    if( $('#messageBody').length )
        $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 0);

    $('#message-submit').click( function(event)
    {
        event.preventDefault();
        var content = $('#message-form .message-content').val();
        var url     = $('#message-form').attr('action');

        $('#message-submit').attr('disabled', 'disabled');
        $('#message-submit i'   ).addClass('d-none');
        $('#message-submit span').removeClass('d-none');

        $.post( url, { contenue: content }, function(response)
        {
            var message = JSON.parse(response);
            var template = `
                <div class="media w-50 ml-auto">
                    <div class="media-body mb-2">
                        <div class="bg-primary rounded py-2 px-3">
                            <p class="text-small mb-0 text-white">${ message.content }</p>
                        </div>
                        <small class="small text-muted">${ message.date }</small>
                    </div>
                </div>
            `;

            $('#message-submit i'   ).removeClass('d-none');
            $('#message-submit span').addClass('d-none');
            $('#message-submit'     ).removeAttr('disabled');

            $(`#conversation-${ message.conversation } .conversation-date`).html(message.date);
            $(`#conversation-${ message.conversation } .conversation-content`).html(`<small style="font-weight: bold;">Moi: </small><small>${ message.content }</small>`);

            $("#messageBody").append( $(template) );
            $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 1000);
            
            $('#message-form .message-content').val('');
        })
    })

    $('.message-content').keydown( function(e)
    {
        if(event.which == 13 && !event.shiftKey)
        {
            $('#message-submit').trigger('click');
        };
    })
    
    // URL is a built-in JavaScript class to manipulate URLs
    const url = new URL('http://127.0.0.1:3000/.well-known/mercure');

    url.searchParams.append('topic',  "http://127.0.0.1:8080/message/{id}");

    const eventSource = new EventSource(url, { withCredentials: true });
    eventSource.onmessage = event => {
        var message = JSON.parse(event.data);
    
        var template = `
            <div class="media w-50">
                <img src="${ message.user.avatar }" alt="user" width="50" height="50" class="rounded-circle">
                <div class="media-body mb-2 ml-3">
                    <div class="bg-light rounded py-2 px-3">
                        <p class="text-small mb-0 text-muted">${ message.content }</p>
                    </div>
                    <small class="small text-muted">${ message.date }</small>
                </div>
            </div>
        `;

        if( $(`#conversation-${ message.conversation }`).length == 0 )
        {
            var conversation = `
                <a id="conversation-${ message.conversation }" href="${ message.path }" class="list-group-item list-group-item-action text-muted rounded-0 list-group-item-info'">
                    <div class="media">
                        <img src="${ message.user.avatar }" alt="user" width="50" height="50" class="rounded-circle">
                        <div class="media-body ml-3">
                            <div class="d-flex align-items-center justify-content-between mb-0">
                                <h6 class="mb-0" style="font-weight: bold;">${ message.user.fullName }</h6>
                                <small class="conversation-date" style="font-size: 60%;">${ message.date }</small>
                            </div>
                            <p class="font-italic mb-0 text-small conversation-content">
                                <small>${ message.content }</small>
                            </p>
                        </div>
                    </div>
                </a>
            `;

            $('#conversations').prepend( $(conversation) );
        }
        else
        {
            $(`#conversation-${ message.conversation }`).removeClass('text-muted list-group-item-light');
            $(`#conversation-${ message.conversation }`).addClass('list-group-item-info');
            $(`#conversation-${ message.conversation } .conversation-date`).html(message.date);
            $(`#conversation-${ message.conversation } .conversation-content`).html(message.content);

            $('#conversations').prepend( $(`#conversation-${ message.conversation }`) );
            if( $('#messageBody').attr('data') == `chat-${ message.conversation }` )
            {
                $("#messageBody").append( $(template) );
                $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 1000);
            }
        }
    
    }
})
