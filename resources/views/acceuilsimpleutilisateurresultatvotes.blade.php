<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Resultats</title>
    <link href="{{ asset('nativecss/style4.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Importation de Toastify (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            margin-bottom: 95px;
        }
        .candidate-info {
            flex-grow: 1;
        }
        .candidate-name {
            font-weight: bold;
            color: #3f51b5;
        }
        .vote-button {
            background-color: #3f51b5;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .vote-button:hover {
            background-color: #3f51b5;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
        <div class="container-fluid d-flex justify-content-between">
            <a class="navbar-brand fw-bold" href="#">FALOU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAcceuilSimple')}}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pageAcceuilSimpleCandidats')}}">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold active" href="{{  route('pageAcceuilSimpleResultats') }}">Résultats</a></li>
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
                $(this).removeClass("btn-light").css({ "background-color": "#3f51b5", "color": "white" });

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
            url: '{{ route('charger.resultats') }}', // Utilisation de la route Laravel
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
function voteForCandidate(button) {
    var candidatId = $(button).data('candidat-id');

    $.ajax({
        url: '{{ route('vote.submit') }}',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            candidat_id: candidatId
        },
        dataType: "json", // Assurez-vous que la réponse est bien interprétée comme JSON
        success: function(response) {
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
            }
        },
        error: function(xhr) {
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
