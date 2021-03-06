$(document).ready( function()
{
    $('#code_postal').attr('disabled', 'disabled');

    var validations = [
        {
            fields: 'input[type="email"]',
            rules : [
                {
                    regex: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    message: "Veuillez entrer un email valide."
                },
                {
                    unique: true,
                    id: 'Email',
                    message: "Email déjà utilisé."
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
        },
        {
            fields: '#registration_form_password_first',
            rules : [
                {
                    regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/,
                    message: "Doit contenir au moins 10 caractères, un majuscule, un minuscule et un caractère spécial."
                }
            ]
        },
        {
            fields: '#registration_siret',
            rules : [
                {
                    regex: /^[0-9]{14}$/,
                    message: "Veuillez entrer un numero de siret valide."
                },
                {
                    unique: true,
                    id: 'Siret',
                    message: "Siret déjà utilisé."
                }
            ]
        },
        {
            fields: '#datenaissance',
            rules : [
                {
                    regex: /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|(([1][26]|[2468][048]|[3579][26])00))))$/,
                    message: "Veuillez entrer une date valide."
                }
            ]
        },
    ];

    validations.forEach( function(element, i)
    {
        element.rules.forEach( function(rule, j)
        {
            var errorField = `<div id="feedback-${i}-${j}" class="invalid-feedback">${rule.message}</div>`;
            $(errorField).insertAfter( $(element.fields) );

            if(j == 1)
            {
                $(`<div class="invalid-feedback verification">Vérification....</div>`).insertAfter( $(element.fields) );
            }
        })

        $(element.fields).change( function()
        {
            if( $(this).val().trim().match(element.rules[0].regex) )
            {
                $(this).parent().children('.invalid-feedback').removeClass('d-block');
                if( !element.rules[1] )
                {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                }
            }
            else
            {
                $(this).parent().children('.invalid-feedback').addClass('d-block');
                $(this).addClass('is-invalid');
                $(this).removeClass('is-valid');

                $(`#feedback-${i}-${1}`).removeClass('d-block');
                $(`#feedback-${i}-${1}`).addClass('d-none');

                $(`#feedback-${i}-${1}`).removeClass('d-block');
                $(`#feedback-${i}-${1}`).addClass('d-none');

                $(this).parent().children('.invalid-feedback.verification').addClass('d-none');
                $(this).parent().children('.invalid-feedback.verification').removeClass('d-block');
            }
        });

        if( element.rules[1] )
        {
            $(element.fields).focusout( function()
            {
                var that = this;
                if( $(this).val().trim().match(element.rules[0].regex) )
                {
                    $(this).parent().children('.invalid-feedback').addClass('d-none');
                    $(this).parent().children('.invalid-feedback').removeClass('d-block');
                    $(this).parent().children('.invalid-feedback.verification').addClass('d-block');

                    var value = $(this).val();

                    $.post('/check' + element.rules[1].id, { data: value }, function(data)
                    {
                        if( data.trim() == 0 )
                        {
                            $(that).removeClass('is-invalid');
                            $(that).addClass('is-valid');
                        }
                        else
                        {
                            $(that).removeClass('is-valid');
                            $(that).addClass('is-invalid');

                            $(`#feedback-${i}-${1}`).removeClass('d-none');
                            $(`#feedback-${i}-${1}`).addClass('d-block');
                        }

                        $(that).parent().children('.invalid-feedback.verification').addClass('d-none');
                        $(that).parent().children('.invalid-feedback.verification').removeClass('d-block');
                    })
                }
            });
        }
    })

    $('input:not([type="file"])').keyup( function()
    {
        $('#registration_form').removeClass('was-validated');
    })

    $(`<div id="feedback-pass-2" class="invalid-feedback">Doit être le même que le champ "Mot de passe"</div>`).insertAfter( $("#registration_form_password_second") );
    $('#registration_form_password_second').change( function()
    {
        if( $(this).val() == $('#registration_form_password_first').val() && !$('#registration_form_password_first').hasClass('is-invalid') )
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

    $('#registration_form_password_first').change( function()
    {
        if( $('#registration_form_password_second').val() !== '' )
            $('#registration_form_password_second').trigger('change');
    });

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
                $('#code_postal').removeAttr('disabled');
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

    /*ville entreprise*/
    $(`<ul id="villeEntreprise-list" class="d-none"></ul>`).insertAfter( $('#villeEntreprise') );
    $('#villeEntreprise').keyup(function(event)
    {
        var url = "https://datanova.legroupe.laposte.fr/api/records/1.0/search/?dataset=laposte_hexasmal&facet=nom_de_la_commune&facet=code_postal&q=" + $(this).val().trim();
        $.get(url, function(data)
        {
            $('#villeEntreprise-list li').remove();
            data.records.forEach( function(element, i)
            {
                $('#villeEntreprise-list').append(`
                    <li class="px-2 py-1" data-codepoEntreprise="${ element.fields.code_postal }" data-villeEntreprise="${ element.fields.nom_de_la_commune }">${ element.fields.code_postal } - ${ element.fields.nom_de_la_commune }</li>
                `);
            });
        })
    });

    $('#villeEntreprise').focusin( function()
    {
        $('#villeEntreprise-list').removeClass('d-none');
    })

    $('#villeEntreprise').focusout( function()
    {
        setTimeout( function()
        {
            $('#villeEntreprise-list').addClass('d-none');
        }, 300)
    })

    $(document).on('click', '#villeEntreprise-list li', function()
    {
        $('#codepoEntreprise').removeClass('is-invalid');
        var city = $(this).attr('data-villeEntreprise');
        var cp   = $(this).attr('data-codepoEntreprise');
        $('#villeEntreprise').val(city);
        $('#codepoEntreprise').val(cp);
        $('#codepoEntreprise').trigger("change");
    })
    /*ville entreprise*/

    $(document).on('change', '#registration_gender', function()
    {
        if( $(".input-image").val() == "" )
        {
            if( $(this).val() == 1 )
            {
                $('.output-image').attr('src', "/uploads/avatar/default-men.png");
            }
            else
            {
                $('.output-image').attr('src', "/uploads/avatar/default-women.png");
            }
        }
    })

    $('.input-image').val(null);
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
});
