<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Accueil Central</title>
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
                    <li class="nav-item"><a class="nav-link fw-bold " href="#">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAuth')}}">Connecter</a></li>

                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section container d-flex flex-column flex-lg-row">
        <div class="hero-text text-center text-lg-start">
            <span style = "background: #3f51b5;" class="badge  mb-3">Votez pour votre choix</span>
            <h1>Participez à l'élection et faites entendre votre voix</h1>
            <p>Votre opinion compte ! Prenez part à ce vote et contribuez à façonner l'avenir.</p>
            <a href = "{{ route('pageIns')}}" style = "background: #3f51b5; color: white;" class="btn" >S'inscrire</a>
        </div>
        <div class="hero-image mt-4 mt-lg-0">
            <img src="{{ asset('assets/acceuil.jpg') }}" alt="Illustration élections">
        </div>
    </section>

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



