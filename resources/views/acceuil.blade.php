<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Accueil Central</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/style3.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

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
                    <li class="nav-item"><a class="nav-link fw-bold " href="#">Accueil</a></li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal">
                            Contacts
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-bold" href = "{{ route('pageAuth')}}">Connecter</a></li>
 
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section container d-flex flex-column flex-lg-row">
        <div class="hero-text text-center text-lg-start">
            <span style = "background: #345afb;" class="badge  mb-3">Votez pour votre choix</span>
            <h1>Participez à l'élection et faites entendre votre voix</h1>
            <p>Votre opinion compte ! Prenez part à ce vote et contribuez à façonner l'avenir.</p>
            <a href = "{{ route('pageIns')}}" style = "background: #345afb; color: white;" class="btn" >S'inscrire</a>
        </div>
        <div class="hero-image mt-4 mt-lg-0">
            <img src="{{ asset('assets/acceuil.jpg') }}" alt="Illustration élections">
        </div>
    </section>

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



