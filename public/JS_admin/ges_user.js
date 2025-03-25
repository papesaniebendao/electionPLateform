$(document).ready(function () {
    let modificationActive = false;

    // Écouteur pour le bouton "Modifier"
    $('.modifier').click(function () {
        modificationActive = !modificationActive; // Inverser l'état de modification
        $('table.my-table tbody input[type="checkbox"]').prop('disabled', !modificationActive);

        if (modificationActive) {
            $(this).text('Valider');
        } else {
            $(this).text('Modifier');
            // Envoyer les modifications au serveur et afficher un message pop-up
            validerModifications();
        }
    });

    function validerModifications() {
        const utilisateursModifies = [];
    
        // Récupérer les utilisateurs modifiés
        $('table.my-table tbody tr').each(function () {
            const id = $(this).find('input[type="checkbox"]').attr('id').replace('checkbox', '');
            const isActive = $(this).find('input[type="checkbox"]').is(':checked'); // Retourne un booléen
    
            // Ajouter l'utilisateur modifié au tableau
            utilisateursModifies.push({
                id: parseInt(id), // Convertir l'ID en entier
                is_active: isActive, // Booléen (true ou false)
            });
        });
    
        // Envoyer les modifications au serveur via AJAX
        $.ajax({
            url: validerModificationsUrl, // Utilise la variable définie dans la vue
            method: 'POST',
            data: {
                utilisateurs: utilisateursModifies,
                _token: $('meta[name="csrf-token"]').attr('content'), // Récupérer le token CSRF
            },
            success: function (response) {
                alert('Modifications enregistrées avec succès !');
                location.reload();
            },
            error: function (xhr) {
                console.error('Erreur lors de l\'enregistrement des modifications:', xhr.responseText);
                alert('Une erreur est survenue lors de l\'enregistrement des modifications.');
            }
        });
    }

    // Écouteur pour le bouton "TOUS"
    $('#btn-tous').click(function () {
        filtrerUtilisateurs('tous');
    });

    // Écouteur pour le bouton "ACTIFS"
    $('#btn-actifs').click(function () {
        filtrerUtilisateurs('actifs');
    });

    // Écouteur pour le bouton "DESACTIVES"
    $('#btn-desactives').click(function () {
        filtrerUtilisateurs('desactives');
    });

    // Fonction pour envoyer la requête AJAX
    function filtrerUtilisateurs(filtre) {
        $.ajax({
            url: "/filtrer-utilisateurs", // Route pour filtrer les utilisateurs
            method: 'POST',
            data: {
                filtre: filtre,
                _token: $('meta[name="csrf-token"]').attr('content'), // Récupérer le token CSRF
            },
            success: function (response) {
                // Mettre à jour le tableau avec la réponse
                $('table.my-table tbody').html(response);

                // Masquer ou afficher le bouton "Modifier" en fonction du filtre
                if (filtre === 'tous') {
                    $('.modifier').show();
                } else {
                    $('.modifier').hide();
                }
            },
            error: function (xhr) {
                console.error('Erreur lors du filtrage des utilisateurs:', xhr.responseText);
            }
        });
    }
});