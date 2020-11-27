$(document).ready( function()
{
	$('#registration_cp').attr('disabled', 'disabled');
	var validations = [
		{
			fields: 'input[type="email"]',
			rules : [
				{
					regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
					message: "Veuillez entrer un email valide."
				}
			]
		},
		{
			fields: '#registration_phone',
			rules : [
				{
					regex: /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/,
					message: "Veuillez entrer un numero de téléphone valide."
				}
			]
		}
	];

	validations.forEach( function(element, i) 
	{
		element.rules.forEach( function(rule, j)
		{
			var errorField = `<div id="feedback-${i}-${j}" class="invalid-feedback">${rule.message}</div>`;
			$(errorField).insertAfter( $(element.fields) );
		})

		$(element.fields).change( function()
		{
			if( $(this).val().trim().match(element.rules[0].regex) )
			{
				$(this).parent().children('.invalid-feedback').removeClass('d-block');
				$(this).removeClass('is-invalid');
				$(this).addClass('is-valid');
			}
			else
			{                    
				$(this).parent().children('.invalid-feedback').addClass('d-block');
				$(this).addClass('is-invalid');
				$(this).removeClass('is-valid');
			}
		});
	})

	$('input:not([type="file"])').keyup( function()
	{
		$('#registration_form').removeClass('was-validated');
	})
	
	$('#btn-submit').click( function(e)
	{
		e.preventDefault();
		$('input:not([type=file])').trigger('change');
		if( $('input.is-invalid').length > 0 )
		{
			// $('#registration_form').addClass('was-validated');
		}
		else
		{
			var error = false;
			
			for (const element of $('input[required]') ) 
			{
				if( $(element).val().trim() == '' )
				{
					error = true;
					$(element).addClass('is-invalid')
					break;
				}
			}

			if( !error )
			{
				$('#registration_cp').removeAttr('disabled');
				$('#registration_form').submit();
			}
		}
	})

	$(`<ul id="city-list" class="d-none"></ul>`).insertAfter( $('#city') );
	$('#city').keyup(function(event)
	{
		var url = "https://datanova.legroupe.laposte.fr/api/records/1.0/search/?dataset=laposte_hexasmal&facet=nom_de_la_commune&facet=code_postal&q=" + $(this).val().trim();
		$.get(url, function(data)
		{
			$('#city-list li').remove();
			data.records.forEach( function(element, i)
			{
				$('#city-list').append(`
					<li class="px-2 py-1" data-cp="${ element.fields.code_postal }" data-city="${ element.fields.nom_de_la_commune }">${ element.fields.code_postal } - ${ element.fields.nom_de_la_commune }</li>
				`);
			});
		})
	});

	$('#city').focusin( function()
	{
		$('#city-list').removeClass('d-none');
	})

	$('#city').focusout( function()
	{
		setTimeout( function()
		{
			$('#city-list').addClass('d-none');
		}, 300)
	})

	$(document).on('click', '#city-list li', function()
	{
		$('#code_postal').removeClass('is-invalid');
		var city = $(this).attr('data-city');
		var cp   = $(this).attr('data-cp');
		$('#city').val(city);
		$('#code_postal').val(cp);
		$('#code_postal').trigger("change");
	})

	$(document).on('change', '.input-image', function(e) 
	{
		var input = this;
		var reader = new FileReader();
		
		reader.onload = function(e) 
		{
			if( e.total < 64000 )
			{
				$('.error-image').addClass('d-none');
				$('.output-image').attr('src', e.target.result);
			}
			else
			{
				$('.output-image').attr('src', "/image/img-add.png");
				$('.error-image').html('Image trop grande.');
				$('.error-image').removeClass('d-none');
				$(input).val(null);
			}
		};
		
		reader.readAsDataURL(input.files[0]); // convert to base64 string
	});

})