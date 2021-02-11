
$(document).ready(function () {
    var nl2br = function (str, isXhtml) {
        if (typeof str === 'undefined' || str === null) {
            return ''
        }
        // Adjust comment to avoid issue on locutus.io display
        const breakTag = (isXhtml || typeof isXhtml === 'undefined') ? '<br ' + '/>' : '<br>'
        return (str + '')
            .replace(/(\r\n|\n\r|\r|\n)/g, breakTag + '$1')
    }

    if ($('#messageBody').length)
        $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 0);

    $('#message-submit').click(function (event) {
        event.preventDefault();
        var content = $('#message-form .message-content').val();
        var url = $('#message-form').attr('action');

        $('#message-submit').attr('disabled', 'disabled');
        $('#message-submit i').addClass('d-none');
        $('#message-submit span').removeClass('d-none');

        $.post(url, { contenue: content }, function (response) {
            var message = JSON.parse(response);
            var content_m = message.content.substring(0, 30) + ((message.content.length > 30) ? '...' : '');
            var template = `
                <div class="media w-50 ml-auto">
                    <div class="media-body mb-2">
                        <div class="bg-primary rounded py-2 px-3 shadow">
                            <p class="text-small mb-0 text-white">${nl2br(message.content)}</p>
                        </div>
                        <small class="small">${message.date}</small>
                    </div>
                </div>
            `;

            $('#message-submit i').removeClass('d-none');
            $('#message-submit span').addClass('d-none');
            $('#message-submit').removeAttr('disabled');

            $(`#conversation-${message.conversation} .conversation-date`).html(message.date);
            $(`#conversation-${message.conversation} .conversation-content`).html(`<small style="font-weight: bold;">Moi: </small><small>${content_m}</small>`);

            $("#messageBody").append($(template));
            $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 1000);

            $('#message-form .message-content').val('');
        })
    })

    $('.message-content').keydown(function (e) {
        if (event.which == 13 && !event.shiftKey) {
            e.preventDefault();
            $('#message-submit').trigger('click');
        };
    })

    // URL is a built-in JavaScript class to manipulate URLs
    const url = new URL('http://localhost:3000/.well-known/mercure');

    url.searchParams.append('topic', "http://127.0.0.1:8080/event/{id}");

    const eventSource = new EventSource(url, { withCredentials: true });
    eventSource.onmessage = event => {
        var data = JSON.parse(event.data);
        if (data.type == 'message') {
            var message = data;
            var template = `
                <div class="media w-50">
                    <img src="${message.user.avatar}" alt="user" width="40" height="40" class="rounded-circle bg-white">
                    <div class="media-body mb-2 ml-3">
                        <div class="bg-light rounded py-2 px-3 shadow">
                            <p class="text-small mb-0 text-muted">${nl2br(message.content)}</p>
                        </div>
                        <small class="small">${message.date}</small>
                    </div>
                </div>
            `;

            if ($(`#conversation-${message.conversation}`).length == 0) {
                var content_m = message.content.substring(0, 30) + (message.content.length > 30) ? '...' : '';
                var conversation = `
                    <a id="conversation-${message.conversation}" href="${message.path}" class="list-group-item list-group-item-action text-muted rounded-0 list-group-item-info'">
                        <div class="media">
                            <img src="${message.user.avatar}" alt="user" width="40" height="40" class="rounded-circle">
                            <div class="media-body ml-3">
                                <div class="d-flex align-items-center justify-content-between mb-0">
                                    <h6 class="mb-0" style="font-weight: bold;">${message.user.fullName}</h6>
                                    <small class="conversation-date" style="font-size: 60%;">${message.date}</small>
                                </div>
                                <p class="font-italic mb-0 text-small conversation-content">
                                    <small>${content_m}</small>
                                </p>
                            </div>
                        </div>
                    </a>
                `;

                $('#conversations').prepend($(conversation));
            }
            else {
                $(`#conversation-${message.conversation}`).removeClass('text-muted list-group-item-light');
                $(`#conversation-${message.conversation}`).addClass('list-group-item-info');
                $(`#conversation-${message.conversation} .conversation-date`).html(message.date);
                $(`#conversation-${message.conversation} .conversation-content`).html(nl2br(message.content));

                $('#conversations').prepend($(`#conversation-${message.conversation}`));
                if ($('#messageBody').attr('data') == `chat-${message.conversation}`) {
                    $("#messageBody").append($(template));
                    $('#messageBody').animate({ scrollTop: $('#messageBody')[0].scrollHeight }, 1000);
                }
            }
        }
    }
})
