
$(document).ready( function()
{
	$.get(notif_url, function(response)
	{
		try {
			var data = JSON.parse(response);
		} catch (e) {
			var data = {
				message: 0
			};
		}

		if( parseInt(data.messages.unread) > 0 )
		{
			$('.messages .unread').html(data.messages.unread);
			$('.messages .unread').removeClass('d-none');
		}

		if( parseInt(data.notifications.unread) > 0 )
		{
			$('.notifications-container .unread').html(data.notifications.unread);
			$('.notifications-container .unread').removeClass('d-none');
		}

		listenToNotifications();
	})

	$('.btn-heart').click( function()
	{
		var id  = $(this).attr('data');
		var url = heart_url.replace('0', id);
		var that = $(this);
		$.get( url, function(data)
		{
			var heart = that.children('i');
			if( parseInt(data) == 1 )
			{
				heart.removeClass('far fa-heart');
				heart.addClass('fa fa-heart text-danger');
			}
			else
			{
				heart.removeClass('fa fa-heart text-danger');
				heart.addClass('far fa-heart');
			}
		})
	})

	var listenToNotifications = function()
	{
		const url = new URL('http://127.0.0.1:3000/.well-known/mercure');
		url.searchParams.append('topic', "http://127.0.0.1:8080/event/{id}");

        ( new EventSource(url, { withCredentials: true }) ).onmessage = event => {

			try {
				var data = JSON.parse( event.data );
			} catch (e) {
				var data = {
					type: 'error'
				};
			}
			
			if( data.type == 'notification' )
			{

				// var notification = data;
				var html    = `
					<div role="alert" aria-live="assertive" aria-atomic="true">
						<div class="toast-header">
							<i class="fa fa-envelope mr-2"></i>${ data.date }
							<button type="button" class="ml-auto close" data-dismiss="toast" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="toast-body">
							${ data.content }, <a href="${ data.lien }">Voir</a>!
						</div>
					</div>
				`;

			 	alertify.set('notifier','position', 'bottom-left');
				alertify.notify( html, 'success', 100, function(){  console.log('notified'); });
				// $('#pop-up').prepend(html);
				// $('#pop-up-' + data.id).toast('show');
			}
		}
	}
});