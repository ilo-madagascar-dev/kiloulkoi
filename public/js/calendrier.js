$(document).ready(function () {
    moment.locale('fr');

    var input_debut = "";
    var input_fin = "";

    var date = new Date();
    var selected1 = '';
    var selected2 = '';

    // S4G!?!M4d4

    const monthDiff = function (d1, d2) {
        var months;
        months = (d2.getFullYear() - d1.getFullYear()) * 12;
        months -= d1.getMonth();
        months += d2.getMonth();
        return months <= 0 ? 0 : months;
    }

    const n_ = (n) => {
        return n > 9 ? "" + n : "0" + n;
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
        const months = ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "July", "Août", "Septembre", "Octobre", "Novembre", "Decembre"];

        document.querySelector(".date .month-name").innerHTML = months[date.getMonth()];
        // document.querySelector(".date p").innerHTML  = new Date().toDateString();
        document.querySelector(".date p").innerHTML = date.getFullYear();

        let days = "";

        let yearPrev = (date.getMonth() == 0) ? date.getFullYear() - 1 : date.getFullYear();
        let monPrev = (date.getMonth() == 0) ? 12 : date.getMonth();

        var now = new Date();
        var today = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();

        for (let x = firstDayIndex; x > 0; x--) {
            let day = n_(prevLastDay - x + 1);
            let span_date = `${yearPrev}-${n_(monPrev)}-${day}`;
            days += `<span data="${span_date}" class="prev-date text-muted">${day}</span>`;
        }

        for (let i = 1; i <= lastDay; i++) {
            let day = n_(i);
            let span_date = `${date.getFullYear()}-${n_(date.getMonth() + 1)}-${day}`;
            days += `<span data="${span_date}" >${i}</span>`;
        }

        let yearNext = (date.getMonth() == 11) ? date.getFullYear() + 1 : date.getFullYear();
        let monNext = (date.getMonth() == 11) ? 1 : date.getMonth() + 2;
        for (let j = 1; j <= nextDays; j++) {
            let day = n_(j);
            let span_date = `${yearNext}-${n_(monNext)}-${day}`;

            days += `<span data="${span_date}" class="next-date  text-muted">${j}</span>`;
        }
        monthDays.innerHTML = days;

        locations.forEach(location => {
            let debut = location.debut.date.slice(0, 10);;
            let fin = location.fin.date.slice(0, 10);
            let statut = '';

            mois_debut = parseInt(debut.slice(5, 7));
            mois_fin = parseInt(fin.slice(5, 7));

            switch (location.statut) {
                case 1: statut = 'enAttente'; break;
                case 2: statut = 'enCours'; break;
                case 3: statut = 'effectue'; break;
                case 4: statut = 'interrompu'; break;
            }

            if (mois_debut == date.getMonth() + 1 || mois_fin == date.getMonth() + 1) {
                $(`.calendar span[data]`).each((i, element) => {
                    if (debut <= $(element).attr('data') && $(element).attr('data') <= fin) {
                        $(element).addClass(statut);
                    }
                });
            }
        });

        try {
            $(`.calendar .days span[data=${selected1}]`).addClass('selected');
        } catch (e) { }

        try {
            $(`.calendar .days span[data=${selected2}]`).addClass('selected');
        } catch (e) { }

        try {
            $(`.calendar .days span[data=${today}]`).addClass('today');
        } catch (e) { }

        if (selected1 != '' && selected2 != '') {
            $('.calendar .days span').each(function (i, span) {
                var data = $(span).attr('data');

                if ((selected1 > data && data > selected2) || (selected1 < data && data < selected2)) {
                    if (!$(span).hasClass('enCours'))
                        $(span).addClass('bg-secondary');
                }
            });
        }

        $('.calendar .days span').each(function (i, span) {
            var data = $(span).attr('data');

            if (today <= data) return false;

            var user_eligibility = $('.user_eligibility').data('userEligibility');

            console.log(user_eligibility);

            if (user_eligibility != 'REGULAR') {
                $(span).addClass('interdit');
            }
        });


    }; // render calendar end

    document.querySelector(".prev").addEventListener("click", () => {
        date.setMonth(date.getMonth() - 1);
        renderCalendar();

        if (monthDiff((new Date()), date) == 0) {
            $('.prev').addClass('d-none');
        }
        $('.next').removeClass('d-none');
    });

    document.querySelector(".next").addEventListener("click", () => {
        date.setMonth(date.getMonth() + 1);
        renderCalendar();

        if (monthDiff((new Date()), date) > 2) {
            $('.next').addClass('d-none');
        }

        $('.prev').removeClass('d-none');
    });

    $(document).on('click', '.days span', function () {
        if (!$(this).hasClass('interdit')) {
            if (selected1 !== '' && selected2 == '') {
                selected2 = $(this).attr('data');
                var before = $('.calendar span.selected').attr('data');
                var after = $(this).attr('data');
                $(this).addClass('selected');

                if (before > after) {
                    input_debut = after;
                    input_fin = before;

                    $('#date-debut').val(moment(after).format("Do MMMM YYYY"));
                    $('#date-fin').val(moment(before).format("Do MMMM YYYY"));

                    var toColor = $(this).next();
                    while (!toColor.hasClass('selected') && toColor.length > 0) {
                        if (!toColor.hasClass('enCours')) {
                            toColor.addClass('bg-secondary');
                        }
                        toColor = toColor.next();
                    }
                }
                else if (before == after) {
                    $('#date-debut').val(moment(before).format("Do MMMM YYYY"));
                    $('#date-fin').val(moment(after).format("Do MMMM YYYY"));
                }
                else {
                    input_debut = before;
                    input_fin = after;

                    $('#date-debut').val(moment(before).format("Do MMMM YYYY"));
                    $('#date-fin').val(moment(after).format("Do MMMM YYYY"));

                    var toColor = $(this).prev();
                    while (!toColor.hasClass('selected') && toColor.length > 0) {
                        if (!toColor.hasClass('enCours')) {
                            toColor.addClass('bg-secondary');
                        }
                        toColor = toColor.prev();
                    }
                }
            }
            else if (selected1 !== '' && selected2 !== '') {
                selected1 = $(this).attr('data');;
                selected2 = '';
                $('.calendar span.selected').removeClass('selected');
                $('.calendar span.bg-secondary').removeClass('bg-secondary');
                $(this).addClass('selected');
            }
            else {
                selected1 = $(this).attr('data');
                $(this).addClass('selected');
            }
        }
    });

    $('#reserver').click(function () {
        // Demande de reservation de l'utilisateur
        let demande_debut = input_debut;
        let demande_fin = input_fin;

        if (demande_debut == "" || demande_fin == "") {
            $('#reservationModal .liste-reservation').addClass("d-none");
            $('#reservationModal .alert-indisponible').addClass("d-none");
            $('#reservationModal .alert-selection').removeClass("d-none");
            $('#reservationModal .modal-footer').addClass("d-none");
        }
        else {
            let demandes = getFreeDates(demande_debut, demande_fin, locations);

            if (demandes.length == 0) {
                $('#reservationModal .liste-reservation').addClass("d-none");
                $('#reservationModal .alert-selection').addClass("d-none");
                $('#reservationModal .modal-footer').addClass("d-none");
                $('#reservationModal .alert-indisponible').removeClass("d-none");
            }
            else {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                let listes = '';
                for (const demande of demandes) {
                    if (demande.debut == demande.fin) {
                        let fin = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                        listes += `<li class="list-group-item py-1 border-0">Le <strong>${fin}</strong></li>`
                    }
                    else {
                        let debut = (new Date(demande.debut)).toLocaleDateString('fr-FR', options);
                        let fin = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                        listes += `<li class="list-group-item py-1 border-0">Le <strong>${debut}</strong> jusqu'au <strong>${fin}</strong></li>`
                    }
                }
                $('#reservationModal .liste-reservation .list-group').html(listes);
                $('#reservationModal .liste-reservation').removeClass("d-none");
                $('#reservationModal .modal-footer').removeClass("d-none");
                $('#reservationModal .alert').addClass("d-none");

                $('#input-reservation').val(JSON.stringify(demandes));
            }
        }
    });

    const getFreeDates = (demande_debut, demande_fin, locations) => {
        let demandes = [];
        let debut = demande_debut;
        let fin = demande_fin;

        let totalement_reserve = false;

        for (const location of locations) {
            // Date déja reservé
            let reserve_debut = location.debut.date.slice(0, 10);;
            let reserve_fin = location.fin.date.slice(0, 10);

            // La partie déja reservée se trouve à gauche
            if (reserve_fin < debut) {
                continue;
            }

            // La partie déja reservée se trouve à droite
            else if (fin < reserve_debut) {
                // demandes.push({debut: debut, fin: fin});
                break;
            }

            // La demande est totalement inclus dans la partie déja reservée
            else if (reserve_debut <= debut && fin <= reserve_fin) {
                console.log('Totalement impossible');
                totalement_reserve = true;
                break;
            }
            else {
                if (reserve_debut <= debut && reserve_fin < fin) {
                    let temp = new Date(reserve_fin);
                    temp.setDate(temp.getDate() + 1);

                    debut = temp.toISOString().slice(0, 10);
                }
                else if (reserve_debut < fin && fin <= reserve_fin) {
                    let temp = new Date(reserve_debut);
                    temp.setDate(temp.getDate() - 1);

                    fin = temp.toISOString().slice(0, 10);
                }
                else {
                    let temp = new Date(reserve_debut);
                    temp.setDate(temp.getDate() - 1);

                    let temp_fin = temp.toISOString().slice(0, 10);

                    demandes.push({ debut: debut, fin: temp_fin });

                    temp = new Date(reserve_fin);
                    temp.setDate(temp.getDate() + 1);

                    debut = temp.toISOString().slice(0, 10);
                }
            }
        };

        if (debut <= fin && !totalement_reserve)
            demandes.push({ debut: debut, fin: fin });

        return demandes;
    }

    renderCalendar();
});
