$(document).ready( function () 
{
	var words = $('.categorie-parent').map( function(i, e) { return $(e).text().trim() });
	console.log( words );
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

	
    $('#datepicker').datepicker({
        format: 'dd/mm/yyyy',
        uiLibrary: 'bootstrap4',
        altField: "#datepicker",
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['Di', 'Lu', 'Ma', 'Mer', 'Je', 'Ve', 'Sa'],
        weekHeader: 'Sem.',
        firstDay: 1
    });
})