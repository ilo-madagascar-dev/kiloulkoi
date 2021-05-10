/**
 * DATETIMEPICKER
 */
$(document).ready(function () {
    console.log(locations);
    const referenceMonth = [];
    const daysPairsTaken = [];
    //const check_in = [["2021-05-15", "2021-05-21"], ["2021-06-15", "2021-06-16"]]; //Array pour un simple essai


    for (const location of locations) {
        let beginningDay = location.debut.date.split(' ')[0];
        let endingDay = location.fin.date.split(' ')[0];

        let beginningAndEndDays = [beginningDay, endingDay];
        //Insertion des différentes dates de début et de fin
        daysPairsTaken.push(beginningAndEndDays);
    }

    console.log(daysPairsTaken);

    $('#dateTimePicker').datepicker({
		dateFormat: "dd/mm/yy",
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
		firstDay: 1,
        beforeShowDay: function(date) {
            let string = jQuery.datepicker.formatDate('yy-mm-dd', date);

            for (var i = 0; i < daysPairsTaken.length; i++) {
                if (Array.isArray(daysPairsTaken[i])) {
                    let from = new Date(daysPairsTaken[i][0]);
                    let to = new Date(daysPairsTaken[i][1]);
                    let current = new Date(string);
                    if (current >= from && current <= to) return false;
                }
            }

            return [daysPairsTaken.indexOf(string) == -1];
        }
	});

    $('#dateTimePicker1').datepicker({
		dateFormat: "dd/mm/yy",
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
		firstDay: 1,
        beforeShowDay: function(date) {
            let string = jQuery.datepicker.formatDate('yy-mm-dd', date);

            for (var i = 0; i < daysPairsTaken.length; i++) {
                if (Array.isArray(daysPairsTaken[i])) {
                    let from = new Date(daysPairsTaken[i][0]);
                    let to = new Date(daysPairsTaken[i][1]);
                    let current = new Date(string);
                    if (current >= from && current <= to) return false;
                }
            }

            return [daysPairsTaken.indexOf(string) == -1];
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
    //let debut_split2 = debut_split[1].split(":");

    //split fin
    let fin_split = date_fin.split(" ");
    let fin_split1 = fin_split[0].split("/");
    //let fin_split2 = fin_split[1].split(":");

    //Création des objets Date()
    let demande_debut = new Date(debut_split1[2], debut_split1[1] -1, debut_split1[0]);
    let demande_fin = new Date(fin_split1[2], fin_split1[1] -1, fin_split1[0]);

    console.log(`${demande_debut}
        ${debut_split1[0]} ${debut_split1[1]} ${debut_split1[2]}
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
            const options = { year: 'numeric', month: 'long', day: 'numeric'};
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

//Obtention des différentes dates libres
const getFreeDates = (demande_debut, demande_fin, locations) => {
    let demandes = [];
    let debut = demande_debut;
    let fin = demande_fin;
    let totalement_reserve = false;

    for (const location of locations) {
        // Date déja reservé
        let reserve_debut = location.debut.date.split(' ')[0];
        let reserve_fin = location.fin.date.split(' ')[0]

        //split début des dates réservées
        let reserve_debut_split = reserve_debut.split(" ");
        let reserve_debut_split1 = reserve_debut_split[0].split("/");
        //let reserve_debut_split2 = reserve_debut_split[1].split(":");

        //split fin des dates réservées
        let reserve_fin_split = reserve_fin.split(" ");
        let reserve_fin_split1 = reserve_fin_split[0].split("/");
        //let reserve_fin_split2 = reserve_fin_split[1].split(":");

        //Création des objets Date()
        reserve_debut = new Date(reserve_debut_split1[2], reserve_debut_split1[1] -1, reserve_debut_split1[0]);
        reserve_fin = new Date(reserve_fin_split1[2], reserve_fin_split1[1] -1, reserve_fin_split1[0]);

        console.log(`Réservation : début et fin ${reserve_debut} ${reserve_fin}`);

        if (reserve_debut <= debut &&  fin <= reserve_fin) {
            totalement_reserve = true;
            break;
        } else if(reserve_debut <= debut &&  debut < reserve_fin){
            totalement_reserve = true;
            break;
        } else if(reserve_debut < fin &&  fin < reserve_fin){
            totalement_reserve = true;
            break;
        } else if(reserve_debut.getTime() == debut.getTime()){ //doit probablement être retiré lors de la gestion des dates seules
            totalement_reserve = true;
            break;
        } else if(debut < reserve_debut &&  reserve_fin <= fin){
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