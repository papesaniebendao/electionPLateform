
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
                        <li class="{{ request()->is('admin/parametres') ? 'active' : '' }}">
                            <a href="/admin/parametres">
                                <img src="{{ asset('assets/icons8-paramètres-24.png') }}" alt="Settings">
                                <span>Paramètres</span>
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
                            <h5 class="mb-0">Consulter les votes</h5>
                        </div>
                        <div class="admin-bubble">
                            <span class="admin-name">{{ Auth::guard('admin')->user()->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 px-3" id="contenu">
                    <!-- Filtres -->
                    <div class="row mb-3">
                        <div class="col-md-9 col-sm-8 col-12 mb-2 mb-sm-0">
                            <div class="d-flex flex-wrap gap-2">
                                <button id="btn-departement" type="button" class="btn-filter {{ $vue_active === 'departement' ? 'active' : '' }}">DÉPARTEMENTS</button>
                                <button id="btn-conseil" type="button" class="btn-filter {{ $vue_active === 'conseil' ? 'active' : '' }}">CONSEIL</button>
                                <button id="btn-statistiques" type="button" class="btn-filter {{ $vue_active === 'statistiques' ? 'active' : '' }}">STATISTIQUES</button>
                            </div>
                        </div>
                    </div>

                    <!-- Vue Départements -->
                    <div id="vue-departement" class="vue-content" style="{{ $vue_active !== 'departement' ? 'display: none;' : '' }}">
                        <form method="GET" action="/consulter-votes">
                            <input type="hidden" name="vue" value="departement">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="departement" class="form-select auto-submit">
                                        <option value="all" {{ $filters['departement'] === 'all' ? 'selected' : '' }}>Tous les départements</option>
                                        @foreach($departements as $dept)
                                            @if($dept != 'all')
                                                <option value="{{ $dept }}" {{ $filters['departement'] === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="niveau" class="form-select auto-submit">
                                        <option value="all" {{ $filters['niveau'] === 'all' ? 'selected' : '' }}>Tous les niveaux</option>
                                        @foreach($niveaux as $niv)
                                            @if($niv != 'all')
                                                <option value="{{ $niv }}" {{ $filters['niveau'] === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                                            @endif
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
                        <form method="GET" action="/consulter-votes">
                            <input type="hidden" name="vue" value="conseil">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="niveau" class="form-select auto-submit">
                                        <option value="all" {{ $filters['niveau'] === 'all' ? 'selected' : '' }}>Tous les niveaux</option>
                                        @foreach($niveaux as $niv)
                                            @if($niv != 'all')
                                                <option value="{{ $niv }}" {{ $filters['niveau'] === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                                            @endif
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
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des statistiques...</p>
                        </div>
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
            $('#btn-departement').click(function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');
                $('.vue-content').hide();
                $('#vue-departement').show();
                
                // Update URL without page reload
                window.history.pushState({}, '', '/consulter-votes?vue=departement');
            });

            $('#btn-conseil').click(function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');
                $('.vue-content').hide();
                $('#vue-conseil').show();
                
                // Update URL without page reload
                window.history.pushState({}, '', '/consulter-votes?vue=conseil');
            });

            $('#btn-statistiques').click(function() {
                $('.btn-filter').removeClass('active');
                $(this).addClass('active');
                $('.vue-content').hide();
                $('#vue-statistique').show();
                
                // Update URL without page reload
                window.history.pushState({}, '', '/consulter-votes?vue=statistiques');
                
                // Load statistics data via AJAX
                if (!window.statsLoaded) {
                    $('#vue-statistique').html(`
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des statistiques...</p>
                        </div>
                    `);
                    
                    $.ajax({
                        url: '{{ route("votes.statistiques") }}',
                        method: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log("Statistiques chargées avec succès:", data);
                            window.statsData = data;
                            window.statsLoaded = true;
                            
                            // Rebuild the statistics view with the fetched data
                            renderStatisticsView(data);
                            initCharts();
                        },
                        error: function(xhr, status, error) {
                            console.error("Erreur lors du chargement des statistiques:", xhr, status, error);
                            $('#vue-statistique').html(`
                                <div class="alert alert-danger">
                                    Erreur lors du chargement des statistiques.
                                    <br><small>${xhr.responseText || error}</small>
                                </div>
                            `);
                        }
                    });
                } else if (window.statsData) {
                    renderStatisticsView(window.statsData);
                    initCharts();
                }
            });

            // Soumission automatique des filtres
            $('.auto-submit').change(function() {
                $(this).closest('form').submit();
            });

            // Si on est sur la vue statistiques au chargement de la page
            @if($vue_active === 'statistiques')
                $('#btn-statistiques').trigger('click');
            @endif
        });

        function renderStatisticsView(stats) {
            if (!stats || stats.error) {
                $('#vue-statistique').html(`
                    <div class="alert alert-danger">
                        ${stats?.error || 'Une erreur est survenue lors du chargement des statistiques'}
                        ${stats?.details ? '<br><small>' + stats.details + '</small>' : ''}
                    </div>
                `);
                return;
            }
            
            // Build HTML for the statistics view
            const html = `
            <div class="row">
                <!-- Participation Globale -->
                <div class="col-md-6 mb-4">
                    <div class="stat-card">
                        <h6>Participation globale</h6>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" 
                                style="width: ${stats.participation.percentage || 0}%"
                                aria-valuenow="${stats.participation.percentage || 0}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <p class="mt-2">${stats.participation.text || '0% (0/0)'}</p>
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
            `;
            
            $('#vue-statistique').html(html);
        }

        function initCharts() {
            // Use the globally stored stats data
            const stats = window.statsData;
            
            if (!stats || stats.error) {
                console.error("Données de statistiques non disponibles");
                return;
            }

            // Vérifier que les éléments canvas existent
            const depChartEl = document.getElementById('departementChart');
            const nivChartEl = document.getElementById('niveauChart');
            const listChartEl = document.getElementById('listeChart');
            
            if (!depChartEl || !nivChartEl || !listChartEl) {
                console.error("Les éléments canvas ne sont pas disponibles");
                return;
            }

            // Try to destroy existing charts to prevent duplicates
            try {
                if (window.depChart) window.depChart.destroy();
                if (window.nivChart) window.nivChart.destroy();
                if (window.listChart) window.listChart.destroy();
            } catch (e) {
                console.warn("Erreur lors de la destruction des graphiques existants:", e);
            }

            // Graphique des départements
            if (stats.departements) {
                window.depChart = new Chart(depChartEl, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(stats.departements),
                        datasets: [{
                            label: 'Participation par département (%)',
                            data: Object.values(stats.departements).map(d => d.percentage),
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
            }

            // Graphique des niveaux
            if (stats.niveaux) {
                window.nivChart = new Chart(nivChartEl, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(stats.niveaux),
                        datasets: [{
                            label: 'Participation par niveau (%)',
                            data: Object.values(stats.niveaux).map(n => n.percentage),
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
            }

            // Graphique des listes
            if (stats.listes && stats.listes.length > 0) {
                window.listChart = new Chart(listChartEl, {
                    type: 'bar',
                    data: {
                        labels: stats.listes.map(l => l.liste),
                        datasets: [{
                            label: 'Nombre de votes',
                            data: stats.listes.map(l => l.votes),
                            backgroundColor: 'rgba(75, 192, 192, 0.7)'
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        }
    </script>
</body>
</html>