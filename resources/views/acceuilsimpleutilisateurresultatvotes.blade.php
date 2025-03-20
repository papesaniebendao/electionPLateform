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
            <a class="navbar-brand fw-bold" href="#">FALLOU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAcceuilSimple')}}">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{ route('pageAcceuilSimpleCandidats')}}">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold active" href="{{  route('pageAcceuilSimpleResultats') }}">Résultats</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="#">Contacts</a></li>
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

    <footer class="footer text-center py-3 mt-4  text-white">
        <p>&copy; 2025 FALLOU - Tous droits réservés</p>
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
        $(document).ready(function() {
        $(document).on('submit', '.voteForm', function(e) {
            e.preventDefault(); // Empêche le rechargement de la page

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Toastify({
                            text: "✅ Votre vote a été enregistré avec succès !",
                            duration: 3000,
                            close: true,
                            gravity: "top", 
                            position: "right",
                            backgroundColor: "#28a745"
                        }).showToast();
                    } else {
                        Toastify({
                            text: "⚠️ Vous avez déjà voté.",
                            duration: 3000,
                            close: true,
                            gravity: "top", 
                            position: "right",
                            backgroundColor: "#dc3545"
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "❌ Une erreur est survenue.",
                        duration: 3000,
                        close: true,
                        gravity: "top", 
                        position: "right",
                        backgroundColor: "#dc3545"
                    }).showToast();
                }
            });
        });
    });

</script>
</body>
</html>
