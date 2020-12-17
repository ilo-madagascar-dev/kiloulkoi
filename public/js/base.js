$(document).ready( function () 
{
	var words = $('.categorie-parent').map( function(i, e) { return $(e).text().trim() });
	var index = 0;
	
	var show = function() 
	{
		setTimeout(function () {
			$('.slogan-animation').fadeOut(1000, function() 
			{
				$('.slogan-animation').text(words[index]);
				index++;
				if( !words[index] )
				index = 0;
				
				$('.slogan-animation').fadeIn(1500, show);
			});
		}, 1000)
	}
	show();
})