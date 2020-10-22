$(document).ready( function()
{
    var reservations = [];
    var date = new Date();

    const n_ = (n) => {
        return n > 9 ? "" + n: "0" + n;
    }

    const renderCalendar = () => {
        date.setDate(1);

        const monthDays = document.querySelector(".days");

        const lastDay = new Date(
            date.getFullYear(),
            date.getMonth() + 1,
            0
        ).getDate();

        const prevLastDay = new Date(
            date.getFullYear(),
            date.getMonth(),
            0
        ).getDate();

        const firstDayIndex = date.getDay();

        const lastDayIndex = new Date(
            date.getFullYear(),
            date.getMonth() + 1,
            0
        ).getDay();

        const nextDays = 7 - lastDayIndex - 1;
        const months   = ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "July", "Août", "Septembre", "Octobre", "Novembre", "Decembre"];
        
        document.querySelector(".date h3").innerHTML = months[date.getMonth()];
        document.querySelector(".date p").innerHTML  = new Date().toDateString();

        let days = "";

        for (let x = firstDayIndex; x > 0; x--)
        {
            let day        = n_(prevLastDay - x + 1);
            let span_date  = `${ date.getFullYear() }-${ n_(date.getMonth()) }-${ day }`;
            days += `<span data="${span_date}" class="prev-date text-muted">${day}</span>`;
        }

        for (let i = 1; i <= lastDay; i++) 
        {
            let day        = n_(i);
            let span_date  = `${ date.getFullYear() }-${ n_(date.getMonth() + 1) }-${ day }`;
    
            if (i === new Date().getDate() && date.getMonth() === new Date().getMonth())
            {
                days += `<span data="${span_date}" class="today">${i}</span>`;
            } else {
                days += `<span data="${span_date}" >${i}</span>`;
            }
        }

        for (let j = 1; j <= nextDays; j++)
        {
            let day        = n_(j);
            let span_date  = `${ date.getFullYear() }-${ n_(date.getMonth() + 2) }-${ day }`;
    
            days += `<span data="${span_date}" class="next-date  text-muted">${j}</span>`;
        }
        monthDays.innerHTML = days;

        locations.forEach(location => {
            let debut = location.debut.date.slice(0, 10);;
            let fin   = location.fin.date.slice(0, 10);
            let statut = '';

            mois_debut = parseInt( debut.slice(5, 7) );
            mois_fin   = parseInt( fin.slice(5, 7) );

            switch( location.statut )
            {
                case 1: statut = 'enAttente';break;
                case 2: statut = 'enCours';break;
                case 3: statut = 'effectue';break;
                case 4: statut = 'interrompu';break;
            }

            if( mois_debut == date.getMonth() + 1 || mois_fin == date.getMonth() + 1 )
            {
                $(`.calendar span[data]`).each( (i, element) => {
                    if( debut <= $(element).attr('data') && $(element).attr('data') <= fin )
                    {
                        $(element).addClass(statut);
                    }
                });
            }
        });
    }; // render calendar end
    
    const getFreeDates = (demande_debut, demande_fin, locations) => 
    {
        let demandes = [];
        let debut    = demande_debut;
        let fin      = demande_fin;

        let totalement_reserve = false;

        for (const location of locations) 
        {
            // Date déja reservé
            let reserve_debut = location.debut.date.slice(0, 10);;
            let reserve_fin   = location.fin.date.slice(0, 10);

            // La partie déja reservée se trouve à gauche
            if( reserve_fin < debut )
            {
                continue;
            }

            // La partie déja reservée se trouve à droite
            else if( fin < reserve_debut )
            {
                // demandes.push({debut: debut, fin: fin});
                break;
            }

            // La demande est totalement inclus dans la partie déja reservée
            else if( reserve_debut <= debut && fin <= reserve_fin )
            {
                console.log('Totalement impossible');
                totalement_reserve = true;
                break;
            }
            else
            {
                if( reserve_debut <= debut && reserve_fin < fin )
                {
                    let temp = new Date(reserve_fin);
                    temp.setDate(temp.getDate() + 1);
    
                    debut = temp.toISOString().slice(0, 10);
                }
                else if( reserve_debut < fin && fin <= reserve_fin )
                {
                    let temp = new Date(reserve_debut);
                    temp.setDate(temp.getDate() - 1);
    
                    fin = temp.toISOString().slice(0, 10);
                }
                else
                {
                    let temp = new Date(reserve_debut);
                    temp.setDate(temp.getDate() - 1);
    
                    let temp_fin = temp.toISOString().slice(0, 10);

                    demandes.push({debut: debut, fin: temp_fin});
                    
                    temp = new Date(reserve_fin);
                    temp.setDate(temp.getDate() + 1);
    
                    debut = temp.toISOString().slice(0, 10);
                }
            }
        };

        if( debut <= fin && !totalement_reserve )
            demandes.push({debut: debut, fin: fin});

        return demandes;
    }

    document.querySelector(".prev").addEventListener("click", () => {
        let current_month = (new Date()).getMonth();
        let current_year  = (new Date()).getFullYear();

        if( current_month > date.getMonth() && current_year >= date.getFullYear() )
        {
            $('.prev').addClass('d-none');
        }
        else
        {
            date.setMonth(date.getMonth() - 1);
            renderCalendar();
        }
    });

    document.querySelector(".next").addEventListener("click", () => {
        date.setMonth(date.getMonth() + 1);
        renderCalendar();

        $('.prev').removeClass('d-none');
    });

    // $(document).on('click', '.days span', function()
    // {
    //     var data = $(this).attr('data');

    //     if( $(this).hasClass('selected') )
    //     {
    //         $(this).removeClass('selected');

    //         const index = reservations.indexOf(data);
    //         if (index > -1)
    //             reservations.splice(index, 1);
    //     }
    //     else
    //     {
    //         $(this).addClass('selected');
    //         reservations.push(data);
    //         reservations.sort()
    //     }

    //     console.log(reservations);
    // });


    $('#reserver').click( function()
    {
        // Demande de reservation de l'utilisateur
        let demande_debut = $('#date-debut').val();
        let demande_fin   = $('#date-fin  ').val();

        if( demande_debut == "" || demande_fin == "" )
        {
            $('#reservationModal .liste-reservation' ).addClass("d-none");
            $('#reservationModal .alert-indisponible').addClass("d-none");
            $('#reservationModal .alert-selection'   ).removeClass("d-none");
            $('#reservationModal .modal-footer'      ).addClass("d-none");
        }
        else
        {
            let demandes = getFreeDates(demande_debut, demande_fin, locations);
    
            if( demandes.length == 0 )
            {
                $('#reservationModal .liste-reservation' ).addClass("d-none");
                $('#reservationModal .alert-selection'   ).addClass("d-none");
                $('#reservationModal .modal-footer'      ).addClass("d-none");
                $('#reservationModal .alert-indisponible').removeClass("d-none");
            }
            else
            {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                let listes = '';
                for (const demande of demandes)
                {
                    if( demande.debut == demande.fin )
                    {
                        let fin   = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                        listes += `<li class="list-group-item py-1 border-0">Le <strong>${ fin }</strong></li>`
                    }
                    else
                    {
                        let debut = (new Date(demande.debut)).toLocaleDateString('fr-FR', options);
                        let fin   = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                        listes += `<li class="list-group-item py-1 border-0">Le <strong>${ debut }</strong> jusqu'au <strong>${ fin }</strong></li>`
                    }
                }
                $('#reservationModal .liste-reservation .list-group').html(listes);
                $('#reservationModal .liste-reservation').removeClass("d-none");
                $('#reservationModal .modal-footer').removeClass("d-none");
                $('#reservationModal .alert').addClass("d-none");

                $('#input-reservation').val( JSON.stringify(demandes) );
            }
        }
    });

    renderCalendar();
});
