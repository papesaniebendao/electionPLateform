<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Candidats</title>
    <link href="{{ asset('nativecss/style4.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Importation de Toastify (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .filter-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .filter-buttons .btn {
            border-radius: 5px;
            margin-left: 5px;
            height: 35px;
        }
        .candidate-card {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 15px;
            background: white;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            max-width: 300px;
        }
        .candidate-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            margin-bottom: 180px;
        }
        .candidate-info {
            flex-grow: 1;
        }
        .candidate-name {
            font-weight: bold;
            color: #345afb;
        }
        .vote-button {
            background-color: #345afb;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .vote-button:hover {
            background-color: #345afb;
        }
        
        /* Style de la bannière flottante */
        .floating-banner {
            position: fixed;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            z-index: 1000;
            width: auto;
            max-width: 250px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .floating-banner .message {
            font-weight: bold;
        }

        .floating-banner .close {
            background-color: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .floating-banner .close:hover {
            color: #f1c40f;
        }
    </style>
</head>
<body>

    <!-- Fenêtre modale -->
    <!-- Bannière flottante -->
    <div id="floatingBanner" class="floating-banner">
        <p class="message">"Vous pouvez voter une seule fois par poste de candidat, donc au total, vous aurez 8 votes pour les 8 postes disponibles.</p>
        <button class="close">&times;</button>
    </div>

    <script>
        // Fonction pour afficher et fermer la bannière flottante
        window.onload = function() {
            var banner = document.getElementById("floatingBanner");
            var closeBtn = document.querySelector(".close");

            // Fonction pour fermer la bannière
            closeBtn.onclick = function() {
                banner.style.display = "none";
            }

            // Afficher la bannière dès le chargement
            banner.style.display = "flex";
        }
    </script>

    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand fw-bold" href="#">FALOU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAcceuilSimple')}}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold active" href="#">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{  route('pageAcceuilSimpleResultats') }}">Résultats</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">
                            Contacts
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                            Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="responseMessage"></div>

    <div class="container mt-4">
        <div class="filter-buttons">
            <button id="departement-btn" class="btn btn-light">Département</button>
            <button id="conseil-btn" class="btn btn-light">Conseil</button>
        </div>

        <div id="candidates-list" class="row g-4">
            <!-- Les candidats seront chargés ici dynamiquement -->
        </div>
    </div>

        <!-- modal pour le lien Profil  -->
        <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Votre Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/icone.jpg') }}" 
                        alt="Photo de profil" class="rounded-circle mb-2" width="100" height="100">
                        <p class="mb-1"><strong>Nom complet :</strong><br>
                        {{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                        
                        <p class="mb-1"><strong>Email :</strong><br>
                        {{ Auth::user()->email }}</p>
                        
                        <p class="mb-1"><strong>Statut :</strong> 
                            @if(Auth::user()->is_voted)
                                <span class="badge bg-success">A voté</span>
                            @else
                                <span class="badge bg-danger">N'a pas voté</span>
                            @endif
                        </p>
                        
                        <p class="mb-0"><strong>Membre depuis :</strong><br>
                        {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="{{ route('deconnexion.submit') }}" class="btn btn-danger">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal pour le lien Contact -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-body">
                    <div class="contact-infos">
                        <!-- Carte Adresse -->
                        <div class="contact-card mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h6>Siège social</h6>
                                    <p class="mb-0 text-muted">Sanar<br>Saint-Louis, Sénégal</p>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Téléphone -->
                        <div class="contact-card mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div>
                                    <h6>Appelez-nous</h6>
                                    <p class="mb-0">
                                        <a href="tel:+221338765432" class="text-decoration-none">+221 33 876 54 32</a><br>
                                        <a href="tel:+221338765433" class="text-decoration-none">+221 33 876 54 33</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Carte Email -->
                        <div class="contact-card">
                            <div class="d-flex align-items-center gap-3">
                                <div class="contact-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div>
                                    <h6>Écrivez-nous</h6>
                                    <p class="mb-0">
                                        <a href="mailto:contact@fallou.sn" class="text-decoration-none">papesndao@fallou.sn</a><br>
                                        <a href="mailto:support@fallou.sn" class="text-decoration-none">adjimndiaye@fallou.sn</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="social-section text-center mt-4 pt-3 border-top">
                        <h6 class="mb-3">Suivez-nous</h6>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 


    <footer class="footer text-center py-3 mt-4  text-white">
        <p>&copy; 2025 FALOU - Tous droits réservés</p>
        <p>
            <a href="#" class="text-white">Mentions légales</a> | 
            <a href="#" class="text-white">Politique de confidentialité</a> | 
            <a href="#" class="text-white">Contact</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Changer la couleur du bouton lors du clic
            $('#departement-btn, #conseil-btn').click(function() {
                // Réinitialiser les deux boutons
                $("#departement-btn, #conseil-btn").css({ "background-color": "", "color": "" }).addClass("btn-light");

                // Appliquer la couleur uniquement au bouton sélectionné
                $(this).removeClass("btn-light").css({ "background-color": "#345afb", "color": "white" });

                // Récupérer la catégorie sélectionnée et charger les candidats dynamiquement
                const category = $(this).attr('id') === 'departement-btn' ? 'departement' : 'conseil';
                loadCandidates(category);
            });


            $("#departement-btn").addClass("active-btn");
            loadCandidates('departement'); // Charge les candidats pour le département par défaut
    });

    // Fonction pour charger les candidats en fonction de la catégorie sélectionnée
    function loadCandidates(category) {
        $.ajax({
            url: '{{ route('charger.candidats') }}', // Utilisation de la route Laravel
            method: 'GET',
            data: { category: category },
            success: function(response) {
                $('#candidates-list').html(response);
            },
            error: function() {
                alert('Une erreur est survenue lors du chargement des candidats.');
            }
        });
    }

    </script>

<script>
function voteForCandidate(event, button) {
    event.preventDefault();  // Empêche la soumission classique du formulaire
    var candidatId = $(button).data('candidat-id');
    console.log("Candidat ID : ", candidatId);

    $.ajax({
        url: '{{ route('vote.submit') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            candidat_id: candidatId
        },
        success: function(response) {
            console.log("Réponse du serveur : ", response);
            Toastify({
                text: response.message,
                duration: 3000,
                close: true,
                gravity: "top", 
                position: "center", 
                backgroundColor: response.success ? "green" : "red"
            }).showToast();

            if (response.success) {
                $(button).prop('disabled', true).text('Voté');

                var votesCountElement = $(button).closest('.candidate-card').find('.votes-count');
                votesCountElement.text(response.votes_count);

                $("#candidat-prenom").text(response.nom_candidat);
                $("#candidat-poste").text(response.poste);

                $(button).prop('disabled', true).text('Voté').css('background-color', 'green');
            }
        },
        error: function(xhr, status, error) {
            console.log("Erreur AJAX : ", error); 
            let errorMessage = "Une erreur est survenue lors du vote.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Toastify({
                text: errorMessage,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "red"
            }).showToast();
        }
    });
}

</script>



</body>
</html>
