/**
 * DATETIMEPICKER
 */
$(document).ready(function () {
    //console.log(locations);
    const referenceDays = [];
    const hoursTaken = [];

    for (const location of locations) {
        let beginningDay = location.debut.split(' ')[0];
        let beginningHour = location.debut.split(' ')[1];
        let endHour = location.fin.split(' ')[1];
        referenceDays.push(beginningDay);
        hoursTaken.push([beginningHour, endHour]);
        //console.log(`${beginningDay} ${beginningHour} ${endHour}`)
    }

    //console.log(referenceDays);
    //console.log(hoursTaken);
 
    $.datetimepicker.setLocale('fr');

    $('#dateTimePicker').datetimepicker({
        format: 'd/m/Y H:i',
        locale: 'fr',
        onGenerate:function(ct,$i){
            let selectedDate = getFormattedDate(ct);

            let indexes = getAllIndexes(referenceDays, selectedDate);
            console.log(indexes);

            $('.xdsoft_time_variant .xdsoft_time').show();

            for (const index of indexes) {
                if(index !== -1) {
                    $('.xdsoft_time_variant .xdsoft_time').each(function(indication){
                        if(hoursTaken[index].indexOf($(this).text()) !== -1) {
                            $(this).addClass('disabled');
                            $(this).fadeTo("fast",.3);
                            $(this).prop('disabled',true);      
                        }
                    });
                }  
            }

        }
    });

    $('#dateTimePicker1').datetimepicker({
        format: 'd/m/Y H:i',
        locale: 'fr',
        onGenerate:function(ct,$i){
            let selectedDate = getFormattedDate(ct);

            let indexes = getAllIndexes(referenceDays, selectedDate);
            console.log(indexes);

            $('.xdsoft_time_variant .xdsoft_time').show();

            for (const index of indexes) {
                if(index !== -1) {
                    $('.xdsoft_time_variant .xdsoft_time').each(function(indication){
                        if(hoursTaken[index].indexOf($(this).text()) !== -1) {
                            $(this).addClass('disabled');
                            $(this).fadeTo("fast",.3);
                            $(this).prop('disabled',true);      
                        }
                    });
                }  
            }

        }
    });
});
/**
 * FIN DATETIMEPICKER
 */

 /**
  * Vérification de la disponibilité
  */
$('#reserver1').click(function () {
    // Demande de reservation de l'utilisateur
    let date_debut = $('#dateTimePicker').val();
    let date_fin = $('#dateTimePicker1').val();
    
    //split début
    let debut_split = date_debut.split(" ");
    let debut_split1 = debut_split[0].split("/");
    let debut_split2 = debut_split[1].split(":");

    //split fin
    let fin_split = date_fin.split(" ");
    let fin_split1 = fin_split[0].split("/");
    let fin_split2 = fin_split[1].split(":");

    //Création des objets Date()
    let demande_debut = new Date(debut_split1[2], debut_split1[1] -1, debut_split1[0], debut_split2[0], debut_split2[1]);
    let demande_fin = new Date(fin_split1[2], fin_split1[1] -1, fin_split1[0], fin_split2[0], fin_split2[1]);

    console.log(`${demande_debut} 
        ${debut_split[0]}
        ${debut_split[1]}
        ${debut_split1[0]} ${debut_split1[1]} ${debut_split1[2]}
        ${debut_split2[0]} ${debut_split2[1]}
        ${demande_debut}
        ${demande_fin}
    `);

    if (demande_debut == "" || demande_fin == "") {
        $('#reservationModal .liste-reservation').addClass("d-none");
        $('#reservationModal .alert-indisponible').addClass("d-none");
        $('#reservationModal .alert-selection').removeClass("d-none");
        $('#reservationModal .modal-footer').addClass("d-none");
    }
    else {
        console.log(`${demande_debut} - ${demande_fin}`);
        let demandes = getFreeDates(demande_debut, demande_fin, locations);
        console.log(demandes);

        if (demandes.length == 0) {
            $('#reservationModal .liste-reservation').addClass("d-none");
            $('#reservationModal .alert-selection').addClass("d-none");
            $('#reservationModal .modal-footer').addClass("d-none");
            $('#reservationModal .alert-indisponible').removeClass("d-none");
        }
        else {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour:'2-digit', minute:'2-digit' };
            let listes = '';
            for (const demande of demandes) {
                
                if (demande.debut == demande.fin) {
                    let fin = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                    listes += `<li class="list-group-item py-1 border-0">Le <strong>${fin}</strong></li>`
                }
                else {
                    let debut = (new Date(demande.debut)).toLocaleDateString('fr-FR', options);
                    let fin = (new Date(demande.fin)).toLocaleDateString('fr-FR', options);

                    console.log(`${demande.debut} ${demande.fin}
                    ${debut} ${fin}`);
                    
                    listes += `<li class="list-group-item py-1 border-0">Le <strong>${debut}</strong> jusqu'au <strong>${fin}</strong></li>`
                }

            console.log(JSON.stringify(demandes));

            }
            $('#reservationModal .liste-reservation .list-group').html(listes);
            $('#reservationModal .liste-reservation').removeClass("d-none");
            $('#reservationModal .modal-footer').removeClass("d-none");
            $('#reservationModal .alert').addClass("d-none");

            $('#input-reservation').val(JSON.stringify(demandes));
        }
    }
});
/**
  * Vérification de la disponibilité
  */

//Les différentes fonctions utilisées
const getFreeDates = (demande_debut, demande_fin, locations) => {
    let demandes = [];
    let debut = demande_debut;
    let fin = demande_fin;

    let totalement_reserve = false;

    for (const location of locations) {
        // Date déja reservé
        let reserve_debut = location.debut.slice(0, 10);
        let reserve_fin = location.fin.slice(0, 10);

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
                console.log(temp_fin);
                demandes.push({ debut: debut, fin: fin });

                temp = new Date(reserve_fin);
                temp.setDate(temp.getDate() + 1);

                debut = temp.toISOString().slice(0, 10);
            }
        }
    };

    if (debut <= fin && !totalement_reserve)
        demandes.push({ debut: debut, fin: fin });

    return demandes;
};

function getFormattedDate(date) {
    let day = ('0' + date.getDate()).slice(-2);
    let month = ('0' + (date.getMonth()+1)).slice(-2);
    let year = date.getFullYear().toString();

    return `${day}/${month}/${year}`;
}

function getAllIndexes(arr, val) {
    let indexes = [], i = -1;
    while ((i = arr.indexOf(val, i+1)) != -1){
        indexes.push(i);
    }
    return indexes;
}