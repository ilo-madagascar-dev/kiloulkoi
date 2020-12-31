$(document).ready( function ()
{
	var words = [
		"un appartement",
		"une toile de tente",
		"une tondeuse",
		"un véhicule",
		"une perceuse",
		"la mode",
		"une poussette",
		"l'iTech",
		"l'électromenager",
		"un meuble",
		"le reste",
	];
	var index = 0;

	var show = function()
	{
		setTimeout(function ()
		{
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

var copyUrl = function()
{
	var dummy = document.createElement('input'),
	text = window.location.href;

	document.body.appendChild(dummy);
	dummy.value = text;
	dummy.select();
	document.execCommand('copy');
	document.body.removeChild(dummy);
}
