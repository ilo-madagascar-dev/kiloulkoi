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
        
        console.log(endHour);
        //formattage de la date de fin
        let endHourHour =  parseInt(endHour.split(':')[0]) - 1;
        let endHourMinutes =  59;
        
        endHour = new Date();
        endHour.setHours(endHourHour, endHourMinutes);  
        endHour = getFormattedHoursMinutes(endHour);
        //fin du formattage de la date de fin

        referenceDays.push(beginningDay);
        hoursTaken.push([beginningHour, endHour]);
        //console.log(`${beginningDay} ${beginningHour} ${endHour}`)
    }

    //console.log(referenceDays);
    console.log(`hours taken : ${hoursTaken}`);
 
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

    console.log(`${demande_debut} ${demande_fin}`);

    for (const location of locations) {
        // Date déja reservé
        let reserve_debut = location.debut.slice();
        let reserve_fin = location.fin.slice();

        console.log(`${reserve_debut} ${reserve_fin}`);
        //split début des dates réservées
        let reserve_debut_split = reserve_debut.split(" ");
        let reserve_debut_split1 = reserve_debut_split[0].split("/");
        let reserve_debut_split2 = reserve_debut_split[1].split(":");

        //split fin des dates réservées
        let reserve_fin_split = reserve_fin.split(" ");
        let reserve_fin_split1 = reserve_fin_split[0].split("/");
        let reserve_fin_split2 = reserve_fin_split[1].split(":");

        //Création des objets Date()
        reserve_debut = new Date(reserve_debut_split1[2], reserve_debut_split1[1] -1, reserve_debut_split1[0], reserve_debut_split2[0], reserve_debut_split2[1]);
        reserve_fin = new Date(reserve_fin_split1[2], reserve_fin_split1[1] -1, reserve_fin_split1[0], reserve_fin_split2[0], reserve_fin_split2[1]);
        console.log(reserve_debut);
        console.log(reserve_fin);

        
        if (reserve_debut <= debut &&  fin <= reserve_fin) {
            totalement_reserve = true;
            break;
        }
    };

    //alert(totalement_reserve);
    
    if (totalement_reserve != true){
        demandes.push({ debut: debut, fin: fin });
    }
    
    return demandes;
};

function getFormattedDate(date) {
    let day = ('0' + date.getDate()).slice(-2);
    let month = ('0' + (date.getMonth()+1)).slice(-2);
    let year = date.getFullYear().toString();

    return `${day}/${month}/${year}`;
}

function getFormattedHoursMinutes(date) {
    console.log(date);
    let hour = ('0' + date.getHours()).slice(-2);
    let minutes = ('0' + (date.getMinutes())).slice(-2);

    return `${hour}:${minutes}`;
}

function getAllIndexes(arr, val) {
    let indexes = [], i = -1;
    while ((i = arr.indexOf(val, i+1)) != -1){
        indexes.push(i);
    }
    return indexes;
}