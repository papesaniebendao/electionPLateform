<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/admin_user.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 col-sm-12 sidebar">
                <div class="sidebar-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">FALOU</h2>
                    <button class="d-md-none btn btn-link menu-toggle" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="sidebar-content">
                    <ul id="liste_menu" class="collapse-md">
                        <!-- Lien vers le tableau de bord -->
                        <li class="{{ request()->routeIs('pageAcceuilAdmin') ? 'active' : '' }}">
                            <a href="{{ route('pageAcceuilAdmin') }}">
                                <img src="../assets/icons8-disposition-du-tableau-de-bord-48.png" alt="Dashboard">
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <!-- Lien vers la gestion des utilisateurs -->
                        <li class="{{ request()->routeIs('gestion.utilisateurs') ? 'active' : '' }}">
                            <a href="{{ route('gestion.utilisateurs') }}">
                                <img src="{{ asset('assets/icons8-contact-24.png') }}" alt="Users">
                                <span>Gestion des Users</span>
                            </a>
                        </li>

                        <!-- Lien vers la gestion des listes -->
                        <li class="{{ request()->routeIs('gestion.listes') ? 'active' : '' }}">
                            <a href="{{ route('gestion.listes') }}">
                                <img src="{{ asset("assets/icons8-groupe-d'utilisateurs-50.png") }}" alt="Lists">
                                <span>Gestion des Listes</span>
                            </a>
                        </li>

                        <!-- Autres liens -->
                        <li>
                            <a href="{{ route('consulter.votes') }}">
                                    <img src="{{ asset('assets/icons8-bulletin-de-vote-bleu.png') }}" alt="Votes">
                                    <span>Consulter les votes</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('deconnexionadmin.submit') }}">
                                <img src="assets/icons8-sortie-48.png" alt="Settings">
                                <span>Se deconnecter</span> 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contenu Principal -->
            <div class="col-lg-10 col-md-9 col-sm-12 content">
                <!-- En-tête -->
                <div class="col-12" id="head">
                    <nav class="row">
                        <div class="col-6">
                            <h5>Tableau de bord</h5>
                        </div>
                        <!-- Bulle de l'admin -->
                        <div class="col-6 text-end">
                            <div class="admin-bubble">
                                <span class="admin-name">Admin</span>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Statistiques de Base -->
                <div class="col-12" id="contenu">
                    <div class="row">
                        <!-- Utilisateurs -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                            <div class="box">
                                <h4>Utilisateurs</h4>
                                <p>{{ $totalEtudiants }}</p>
                            </div>
                        </div>
                        <!-- Listes -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                            <div class="box">
                                <h4>Listes</h4>
                                <p>{{ $totalListes }}</p>
                            </div>
                        </div>
                        <!-- Candidats -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                            <div class="box">
                                <h4>Candidats</h4>
                                <p>{{ $totalCandidats }}</p>
                            </div>
                        </div>
                        <!-- Votes -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                            <div class="box">
                                <h4>Votes</h4>
                                <p>{{ $totalVotes }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activités Récentes -->
                  <!-- Activités Récentes -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Activités Récentes</h4>
                                <div class="activity-list">
                                    @foreach($activitesRecent as $activite)
                                        <div class="activity-item">
                                            <span class="activity-icon">{{ $activite['icon'] }}</span>
                                            <div class="activity-content">
                                                <span class="activity-text">
                                                    {{ $activite['description'] }}
                                                </span>
                                                <span class="activity-time">
                                                    {{ \Carbon\Carbon::parse($activite['date'])->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if(count($activitesRecent) == 0)
                                        <div class="activity-item">
                                            <span class="activity-icon">ℹ️</span>
                                            <div class="activity-content">
                                                <span class="activity-text">Aucune activité récente</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="mt-4">
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>

    <!-- Scripts Bootstrap et JS personnalisé -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle menu sur mobile
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebarContent = document.querySelector('.sidebar-content');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebarContent.classList.toggle('show');
                });
            }
            
            // Fermer le menu lors du clic sur un lien (en mode mobile)
            const menuLinks = document.querySelectorAll('#liste_menu li a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        sidebarContent.classList.remove('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
