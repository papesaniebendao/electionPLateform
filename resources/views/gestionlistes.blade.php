<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Listes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/ges_user.css') }}">
    <!-- Ajouter le token CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar" id="sidebar">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">FALOU</h2>
                    <button id="closeSidebar" class="btn d-lg-none">
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
                <div class="sticky-top" id="head">
                    <nav class="d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <button id="sidebarToggle" class="btn d-lg-none me-2">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <h5 class="mb-0">Gestion des Listes</h5>
                        </div>
                        <!-- Bulle de l'admin -->
                        <div>
                            <div class="admin-bubble">
                                <span class="admin-name">May</span>
                            </div>
                        </div>
                    </nav>
                </div>

                <div id="contenu">
                    <!-- Bouton pour créer une nouvelle liste -->
                    <div class="text-end mb-3">
                        <a href="{{ route('liste.create') }}" class="btn btn-primary">Créer une nouvelle liste</a>
                    </div>

                    <!-- Tableau des listes -->
                    <div class="table-responsive">
                        <table class="my-table">
                            <thead>
                                <tr>
                                    <th>Nom de la liste</th>
                                    <th>Description</th>
                                    <th>Date de création</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lists as $list)
                                    <tr>
                                        <td>{{ $list->nom_liste }}</td>
                                        <td>{{ Str::limit($list->description, 50) }}</td>
                                        <td>{{ $list->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <span class="badge {{ $list->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $list->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('liste.edit', $list->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                                <form action="{{ route('liste.destroy', $list->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> <!-- Fin contenu -->
            </div> <!-- Fin content -->
        </div> <!-- Fin row -->
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script pour la gestion du sidebar responsive -->
    <script>
        $(document).ready(function() {
            // Toggle sidebar sur mobile
            $("#sidebarToggle").click(function() {
                $("#sidebar").toggleClass("sidebar-visible");
            });
            
            // Fermer sidebar quand on clique sur le bouton de fermeture
            $("#closeSidebar").click(function() {
                $("#sidebar").removeClass("sidebar-visible");
            });
            
            // Fermer sidebar quand on clique sur un lien (sur mobile)
            $("#liste_menu a").click(function() {
                if (window.innerWidth < 992) {
                    $("#sidebar").removeClass("sidebar-visible");
                }
            });
        });
    </script>
    
    <!-- Inclure le fichier JavaScript externe -->
    <script src="{{ asset('JS_admin/ges_listes.js') }}"></script>
    
    <footer>
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>
</body>
</html>