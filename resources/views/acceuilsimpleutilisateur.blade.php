<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/style3.css') }}">
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
                    <li class="nav-item"><a class="nav-link fw-bold active" href="#">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAcceuilSimpleCandidats')}}">Candidats</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href="{{  route('pageAcceuilSimpleResultats') }}">Résultats</a></li>
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

    <section class="hero-section container d-flex flex-column flex-lg-row"> 
        <div class="hero-text text-center text-lg-start">
            <span style = "background: #3f51b5;" class="badge  mb-3">Votez pour votre choix</span>
            <h1>Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }} !</h1>
            <p>Votre opinion compte ! Prenez part à ce vote et contribuez à façonner l'avenir.</p>
            <p>Vous pouvez aussi suivre les resultats du votes.</p>
            <a href = "{{ route('pageAcceuilSimpleCandidats')}}" style = "background: #3f51b5; color: white;" class="btn" >Voter maintenant</a>
        </div>
        <div class="hero-image mt-4 mt-lg-0">
            <img src="{{ asset('assets/acceuil.jpg') }}" alt="Illustration élections">
        </div>
    </section>


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

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 FALOU - Tous droits réservés</p>
        <p>
            <a href="#">Mentions légales</a> | 
            <a href="#">Politique de confidentialité</a> | 
            <a href="#">Contact</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



