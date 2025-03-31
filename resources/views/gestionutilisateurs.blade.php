<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/ges_user.css') }}">
    <!-- Ajouter le token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Ajout des styles spécifiques pour le toggle de la sidebar */
        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                z-index: 1051;
                width: 250px;
                height: 100vh;
                overflow-y: auto;
                transition: left 0.3s ease;
            }
            
            .sidebar-visible {
                left: 0 !important;
            }
            
            #closeSidebar {
                display: block;
            }
            
            .content {
                width: 100%;
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar" id="sidebar">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">FALOU</h2>
                    <button class="btn d-lg-none" id="closeSidebar">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="sidebar-menu">
                    <ul id="liste_menu">
                        <!-- Tableau de bord -->
                        <li class="{{ request()->is('pageAcceuilAdmin') ? 'active' : '' }}">
                            <a href="/pageAcceuilAdmin">
                                <img src="{{ asset('assets/icons8-disposition-du-tableau-de-bord-48.png') }}" alt="Dashboard">
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <!-- Gestion utilisateurs -->
                        <li class="{{ request()->is('gestion-utilisateurs') ? 'active' : '' }}">
                            <a href="/gestion-utilisateurs">
                                <img src="{{ asset('assets/icons8-contact-24.png') }}" alt="Users">
                                <span>Gestion Utilisateurs</span>
                            </a>
                        </li>

                        <!-- Gestion listes -->
                        <li class="{{ request()->is('gestion-listes') ? 'active' : '' }}">
                            <a href="/gestion-listes">
                                <img src="{{ asset("assets/icons8-groupe-d'utilisateurs-50.png") }}" alt="Lists">
                                <span>Gestion Listes</span>
                            </a>
                        </li>

                        <!-- Consultation votes -->
                        <li class="{{ request()->is('consulter-votes') ? 'active' : '' }}">
                            <a href="/consulter-votes">
                                <img src="{{ asset('assets/icons8-bulletin-de-vote-bleu.png') }}" alt="Votes">
                                <span>Consulter votes</span>
                            </a>
                        </li>

                        <!-- Paramètres -->
                        <li>
                            <a href="{{ route('deconnexionadmin.submit') }}">
                                <img src="assets/icons8-sortie-48.png" alt="Settings">
                                <span>Se deconnecter</span> 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-10 content" id="content">
                <!-- En-tête -->
                <div class="py-3 sticky-top bg-white" id="head">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <div class="d-flex align-items-center">
                            <button class="btn d-lg-none me-2" id="sidebarToggle">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <h5 class="mb-0">Gestion des users</h5>
                        </div>
                        <!-- Bulle de l'admin -->
                        <div class="admin-bubble">
                            <span class="admin-name">Admin</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 px-3" id="contenu">
                    <!-- Filtres -->
                    <div class="row mb-3">
                        <div class="col-md-9 col-sm-8 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-wrap gap-2">
                                <button id="btn-tous" class="tous btn">TOUS</button>
                                <button id="btn-actifs" class="actifs btn">ACTIFS</button>
                                <button id="btn-desactives" class="desactives btn">DESACTIVES</button>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-12 text-md-end">
                            @if($filtre === 'tous')
                                <button class="modifier btn">Modifier</button>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="my-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nom</th>
                                    <th>Mail</th>
                                    <th>Departement</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('partials.utilisateurs_table', ['utilisateurs' => $utilisateurs, 'filtre' => $filtre, 'modificationActive' => false])
                            </tbody>
                        </table>
                    </div>
                </div> <!-- Fin contenu -->
            </div> <!-- Fin content -->
        </div> <!-- Fin row -->
    </div> <!-- Fin container-fluid -->

    <footer class="mt-auto">
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script pour gérer la sidebar responsive -->
    <script>
        const validerModificationsUrl = "{{ route('valider.modifications') }}";
        
        $(document).ready(function() {
            // Toggle sidebar sur mobile
            $("#sidebarToggle").click(function() {
                $("#sidebar").addClass("sidebar-visible");
                $("body").append('<div id="sidebar-overlay"></div>');
                $("#sidebar-overlay").css({
                    "position": "fixed",
                    "top": 0,
                    "left": 0,
                    "right": 0,
                    "bottom": 0,
                    "background-color": "rgba(0,0,0,0.5)",
                    "z-index": 1050
                }).click(function() {
                    $("#sidebar").removeClass("sidebar-visible");
                    $(this).remove();
                });
            });
            
            // Fermer sidebar quand on clique sur le bouton de fermeture
            $("#closeSidebar").click(function() {
                $("#sidebar").removeClass("sidebar-visible");
                $("#sidebar-overlay").remove();
            });
            
            // Fermer sidebar quand on clique sur un lien (sur mobile)
            $("#liste_menu a").click(function() {
                if (window.innerWidth < 992) {
                    $("#sidebar").removeClass("sidebar-visible");
                    $("#sidebar-overlay").remove();
                }
            });
        });
    </script>
    <!-- Inclure le fichier JavaScript externe -->
    <script src="{{ asset('JS_admin/ges_user.js') }}"></script>
</body>
</html>
