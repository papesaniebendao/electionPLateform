<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="{{ asset('nativecss/style1.css') }}">
    <script src="{{ asset('nativejs/jquery.min.js') }}"></script>
    <script src="{{ asset('nativejs/scriptAuth.js') }}"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-image"></div>
        
        <div class="login-form">
            <div class="form-card">
                <h1>Espace Administrateur</h1>
                <h2>Connexion</h2>
                <form action="{{ route('authentificationadmin.submit') }}" method="GET">
                    <!-- Ajoutez les conteneurs d'erreur -->
                    <div class="form-group">
                        <label>Email UGB</label>
                        <input type="email" id="email" name="email" placeholder="ndao.prenom-deuxiemeprenom.nom@ugb.edu.sn" required>
                        <div class="error-message"></div> <!-- Ajouté -->
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                        <div class="error-message"></div> <!-- Déplacé ici -->
                    </div>

                    <button type="submit">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
