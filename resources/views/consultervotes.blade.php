<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter les Votes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/ges_user.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <!-- Content -->
                <div class="col-lg-10 content" id="content">
                <!-- En-tête -->
                <div class="py-3 sticky-top bg-white" id="head">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <div class="d-flex align-items-center">
                            <button class="btn d-lg-none me-2" id="sidebarToggle">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <h5 class="mb-0">Consulter les votes</h5>
                        </div>
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
                                <button id="btn-departement" class="btn-filter {{ $vue_active === 'departement' ? 'active' : '' }}">DÉPARTEMENTS</button>
                                <button id="btn-conseil" class="btn-filter {{ $vue_active === 'conseil' ? 'active' : '' }}">CONSEIL</button>
                                <button id="btn-statistiques" class="btn-filter {{ $vue_active === 'statistiques' ? 'active' : '' }}">STATISTIQUES</button>
                            </div>
                        </div>
                    </div>

                    <!-- Vue Départements -->
                    <div id="vue-departement" class="vue-content" style="{{ $vue_active !== 'departement' ? 'display: none;' : '' }}">
                        <form method="GET" action="">
                            <input type="hidden" name="vue" value="departement">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="departement" class="form-select auto-submit">
                                        <option value="all" {{ $filters['departement'] === 'all' ? 'selected' : '' }}>Tous les départements</option>
                                        @foreach($departements as $dept)
                                            <option value="{{ $dept }}" {{ $filters['departement'] === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="niveau" class="form-select auto-submit">
                                        <option value="all" {{ $filters['niveau'] === 'all' ? 'selected' : '' }}>Tous les niveaux</option>
                                        @foreach($niveaux as $niv)
                                            <option value="{{ $niv }}" {{ $filters['niveau'] === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Liste</th>
                                        <th>Candidat</th>
                                        <th>Département</th>
                                        <th>Niveau</th>
                                        <th>Votes</th>
                                        <th>Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($votes_departement ?? [] as $vote)
                                    <tr>
                                        <td>{{ $vote['liste'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['candidat'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['departement'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['niveau'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['votes'] ?? 0 }}</td>
                                        <td>{{ $vote['pourcentage'] ?? 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vue Conseil -->
                    <div id="vue-conseil" class="vue-content" style="{{ $vue_active !== 'conseil' ? 'display: none;' : '' }}">
                        <form method="GET" action="">
                            <input type="hidden" name="vue" value="conseil">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="niveau" class="form-select auto-submit">
                                        <option value="all" {{ $filters['niveau'] === 'all' ? 'selected' : '' }}>Tous les niveaux</option>
                                        @foreach($niveaux as $niv)
                                            <option value="{{ $niv }}" {{ $filters['niveau'] === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Liste</th>
                                        <th>Candidat</th>
                                        <th>Niveau</th>
                                        <th>Votes</th>
                                        <th>Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($votes_conseil ?? [] as $vote)
                                    <tr>
                                        <td>{{ $vote['liste'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['candidat'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['niveau'] ?? 'N/A' }}</td>
                                        <td>{{ $vote['votes'] ?? 0 }}</td>
                                        <td>{{ $vote['pourcentage'] ?? 0 }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vue Statistiques -->
                    <div id="vue-statistique" class="vue-content" style="{{ $vue_active !== 'statistiques' ? 'display: none;' : '' }}">
                        @if(isset($stats) && !isset($stats['error']))
                        <div class="row">
                            <!-- Participation Globale -->
                            <div class="col-md-6 mb-4">
                                <div class="stat-card">
                                    <h6>Participation globale</h6>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: {{ $stats['participation']['percentage'] ?? 0 }}%"
                                            aria-valuenow="{{ $stats['participation']['percentage'] ?? 0 }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="mt-2">{{ $stats['participation']['text'] ?? '0% (0/0)' }}</p>
                                </div>
                            </div>

                            <!-- Répartition par Départements -->
                            <div class="col-md-6 mb-4">
                                <div class="stat-card">
                                    <h6>Répartition par départements</h6>
                                    <canvas id="departementChart" height="200"></canvas>
                                </div>
                            </div>

                            <!-- Répartition par Niveau -->
                            <div class="col-md-6 mb-4">
                                <div class="stat-card">
                                    <h6>Répartition par niveau</h6>
                                    <canvas id="niveauChart" height="200"></canvas>
                                </div>
                            </div>

                            <!-- Performance des Listes -->
                            <div class="col-md-6 mb-4">
                                <div class="stat-card">
                                    <h6>Performance des listes</h6>
                                    <canvas id="listeChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Script pour les graphiques -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Données des statistiques converties en JSON
                                const stats = @json($stats ?? []);

                                // Graphique Départements
                                new Chart(document.getElementById('departementChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: Object.keys(stats.departements ?? {}),
                                        datasets: [{
                                            label: 'Participation (%)',
                                            data: Object.values(stats.departements ?? {}).map(d => d.percentage),
                                            backgroundColor: 'rgba(54, 162, 235, 0.7)'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                max: 100
                                            }
                                        }
                                    }
                                });

                                // Graphique Niveaux (similaire)
                                new Chart(document.getElementById('niveauChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: Object.keys(stats.niveaux ?? {}),
                                        datasets: [{
                                            label: 'Participation (%)',
                                            data: Object.values(stats.niveaux ?? {}).map(n => n.percentage),
                                            backgroundColor: 'rgba(255, 99, 132, 0.7)'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                max: 100
                                            }
                                        }
                                    }
                                });

                                // Graphique Listes
                                new Chart(document.getElementById('listeChart'), {
                                    type: 'bar',
                                    data: {
                                        labels: stats.listes?.map(l => l.liste) || [],
                                        datasets: [{
                                            label: 'Nombre de votes',
                                            data: stats.listes?.map(l => l.votes) || [],
                                            backgroundColor: 'rgba(75, 192, 192, 0.7)'
                                        }]
                                    },
                                    options: {
                                        responsive: true
                                    }
                                });
                            });
                        </script>
                        @elseif(isset($stats['error']))
                            <div class="alert alert-danger">
                                {{ $stats['error'] }}
                                @if(isset($stats['details']))
                                    <br><small>{{ $stats['details'] }}</small>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                <p class="mt-2">Chargement des statistiques...</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            // Gestion de la sidebar mobile
            $("#sidebarToggle").click(function() {
                $("#sidebar").toggleClass("sidebar-visible");
            });
            
            $("#closeSidebar").click(function() {
                $("#sidebar").removeClass("sidebar-visible");
            });

            // Gestion des onglets
            $('.btn-filter').click(function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');
                
                const target = $(this).attr('id');
                $('.vue-content').hide();
                
                switch(target) {
                    case 'btn-departement':
                        $('#vue-departement').show();
                        break;
                    case 'btn-conseil':
                        $('#vue-conseil').show();
                        break;
                    case 'btn-statistiques':
                        $('#vue-statistique').show();
                        renderCharts();
                        break;
                }
            });

            // Soumission automatique des filtres
            $('.auto-submit').change(function() {
                $(this).closest('form').submit();
            });

            // Initialisation des graphiques si on arrive directement sur les stats
            @if(request('vue') === 'statistiques')
                renderCharts();
            @endif
        });

        function renderCharts() {
            // Données pour les graphiques (passées depuis le contrôleur)
            const stats = @json($stats ?? []);

            // Graphique des départements
            new Chart(
                document.getElementById('departement-chart'),
                {
                    type: 'bar',
                    data: {
                        labels: Object.keys(stats.departements || {}),
                        datasets: [{
                            label: 'Participation par département (%)',
                            data: Object.values(stats.departements || {}).map(d => d.percentage),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)'
                        }]
                    }
                }
            );

            // Graphique des niveaux
            new Chart(
                document.getElementById('niveau-chart'),
                {
                    type: 'bar',
                    data: {
                        labels: Object.keys(stats.niveaux || {}),
                        datasets: [{
                            label: 'Participation par niveau (%)',
                            data: Object.values(stats.niveaux || {}).map(n => n.percentage),
                            backgroundColor: 'rgba(255, 99, 132, 0.5)'
                        }]
                    }
                }
            );

            // Graphique des listes
            new Chart(
                document.getElementById('liste-chart'),
                {
                    type: 'bar',
                    data: {
                        labels: stats.listes?.map(l => l.liste) || [],
                        datasets: [{
                            label: 'Nombre de votes',
                            data: stats.listes?.map(l => l.votes) || [],
                            backgroundColor: 'rgba(75, 192, 192, 0.5)'
                        }]
                    }
                }
            );
        }
    </script>
</body>
</html>
