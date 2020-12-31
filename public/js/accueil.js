
$(document).ready( function ()
{
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

	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 10000,
		values: [ 0, 3000 ],
		slide: function( event, ui )
		{
			$( "#amount" ).val( ui.values[ 0 ] + "€ - " + ui.values[ 1 ] + "€" );
		}
	});
	$( "#amount" ).val( $( "#slider-range" ).slider( "values", 0 ) + "€ - " + $( "#slider-range" ).slider( "values", 1 ) + "€" );

	$('#input-categorie').val(null);
	$('#input-show-categorie').click( function(e)
	{
		$(this).toggleClass('not-show-all');
		$('.categorie-container').toggleClass('d-none');
	})

	$('.categorie-enfant').click( function ()
	{
		selectCategorie( $(this) );
	})

	$('.categorie-parent').click( function ()
	{
		if( $(this).attr('data-class') == 'Service' || $(this).attr('data-class') == 'Divers' )
		{
			$('#prix-unite option[value=1]').removeClass('d-none');
			selectCategorie( $(this) );
		}
		else
		{
			$('#prix-unite option[value=1]').addClass('d-none');
			$('#prix-unite').val('2');
		}
	})

	$(document).on('click', '#input-show-categorie span i', function (event)
	{
		try {
			var values = JSON.parse( $('#input-categorie').val() );
		} catch (e) {
			var values = [];
		}

		if( values.length <= 1 )
		{
			$('.categorie-placeholder').removeClass('d-none');
		}

		var val = $(this).parent().attr('data');
		values  = values.filter( function(ele){
			return ele != val;
		});
		values = JSON.stringify( values );
		$('#input-categorie').val( values );
		$(this).parent().remove();
	})

	var selectCategorie = function (categorie)
	{
		try {
			var values = JSON.parse( $('#input-categorie').val() );
		} catch (e) {
			var values = [];
		}

		$('.categorie-placeholder').addClass('d-none');

		var forChild = categorie.parent().prev().children('i').attr('class');
		var icon = forChild ? forChild : categorie.children('i').attr('class');
		var val  = categorie.attr('data');
		if( !values.includes(val) )
		{
			values.push(val);
			values = JSON.stringify( values );

			var selected = `
				<span data="${ categorie.attr('data') }">
					<i class="delete">x</i>
					<i class="fa-icon ${ icon } mx-1"></i>
					<small>${ categorie.text().trim() }</small>
				</span>
			`;
			$('#input-show-categorie').append( selected );
			$('#input-categorie').val( values );
		}
	}

	$('#city').select2({
		language: 'fr',
		placeholder: 'Choisissez une ville',
		templateSelection: function (params) {
			return params.text;
		},
		ajax: {
			url: function (params) {
				return "https://datanova.legroupe.laposte.fr/api/records/1.0/search/?dataset=laposte_hexasmal&facet=nom_de_la_commune&facet=code_postal&q=" + params.term;
			},
			processResults: function (data) {
				// Transforms the top-level key of the response object from 'items' to 'results'
				var response = data.records;
				var options  = $.map(response, function (obj) {
					return {
						text: obj.fields.code_postal + ' - ' + obj.fields.nom_de_la_commune,
						id:  obj.fields.code_postal + ' - ' + obj.fields.nom_de_la_commune,
					};
				});

				return { results: options };
			}
		}
	});

	$(document).click( function(event)
	{
		//check if the clicked area is dropDown or not
		if ( $(".categorie-container *").has(event.target).length === 0 && $("#input-show-categorie *").has(event.target).length === 0 && "input-show-categorie" !== $(event.target).attr('id') )
		{
			$(".categorie-container").addClass("d-none");
			$("#input-show-categorie").addClass("not-show-all");
		}
    });
})
