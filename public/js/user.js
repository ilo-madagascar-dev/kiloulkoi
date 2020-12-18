
$(document).ready( function()
{
	$.get(init_url, function(response)
	{
		try {
			var data = JSON.parse(response);
		} catch (e) {
			var data = {
				messages: 0,
				notifications: 0,
			};
		}

		listenToNotifications();
		showNotifications( data.notifications );		

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
				var url  = notif_url.replace('0', data.id);
				var html = `
					<div>
						<div class="toast-header">
							<i class="fa fa-bell mr-2"></i>${ data.date }
							<button type="button" class="ml-auto close" data-dismiss="toast" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="toast-body" style="cursor: pointer;" onclick="location.href='${ url }'">
							${ data.content }.
						</div>
					</div>
				`;

				if( parseInt(data.unread) > 0 )
				{
					$('.notifications-container .unread').html(data.unread);
					$('.notifications-container .unread').removeClass('d-none');
				}

				showNotification(data);
			 	alertify.set('notifier','position', 'bottom-left');
				alertify.notify( html, 'success', 100, function(){  console.log('notified'); });
			}
			else if( data.type == 'message' )
			{
				var alertifyHtml = `
						<div>
							<div class="toast-header text-primary border-bottom border-primary">
								<i class="fa fa-envelope mr-2"></i>${ data.user.fullName }
								<button type="button" class="ml-auto close" data-dismiss="toast" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="toast-body" style="cursor: pointer;" onclick="location.href='${ data.path }'">
								${ data.content }.
							</div>
						</div>
				`;
				alertify.set('notifier','position', 'bottom-left');
				alertify.notify( alertifyHtml, 'custom', 100, function(){  console.log('notified'); });
			}
		}
	}

	var showNotifications = function(notifications)
	{
		notifications.all.forEach(notif => {
			showNotification(notif);
		});
	}

	var showNotification = function(notif)
	{
		var url  = notif_url.replace('0', notif.id);
		var html = `
			<a href="${ url }" class="list-group-item list-group-item-action px-0 py-2" >
				<div class="row">
					<div class="col-3 text-center d-flex align-items-center justify-content-center pr-0">
						<img src="${ notif.photo }" class="w-80 rounded-circle">
					</div>
					<div class="col-9 notification-content">
						<div style="font-size: 85%">
							${ notif.content }
						</div>
						<small class="text-muted">${ notif.date }</small>
					</div>
				</div>
			</a>			
		`;

		$('.notifications-container .list-group').append(html);
	}
});