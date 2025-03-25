<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Étudiant</title>
    <link rel="stylesheet" href="{{ asset('nativecss/style2.css') }}">
    <script src="{{ asset('nativejs/jquery.min.js') }}"></script>
    <script src="{{ asset('nativejs/scriptIns.js') }}"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-image"></div>
        
        <div class="login-form">
            <div class="form-card">
                <h2>Inscription</h2>
                <form id="inscriptionForm" action="{{ route('inscription.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" id="nom" name="nom" required>
                            <div class="error-message"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email UGB</label>
                        <input type="email" 
                            id="email" 
                            name="email" 
                            placeholder="nom.prenom-deuxiemeprenom.nom@ugb.edu.sn" 
                            required>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label>Code Étudiant (Ex: P 31 2062 ou P 31 876)</label>
                        <input type="text" 
                            id="codeEtudiant" 
                            name="codeEtudiant" 
                            pattern="P\s\d{2}\s\d{3,4}"
                            placeholder="P 00 0000" 
                            required>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" id="password" name="password" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmation</label>
                            <input type="password" id="confirmPassword" name="password_confirmation" required>
                            <div class="error-message"></div>
                        </div>
                    </div>

                    <!-- Nouveau champ : Départment -->
                    <div class="form-group">
                        <label>Département</label>
                        <select id="departement" name="departement" required>
                            <option value="">Sélectionnez votre département</option>
                            <option value="Mathematique">Mathématique</option>
                            <option value="Informatique">Informatique</option>
                            <option value="Physique">Physique</option>
                        </select>
                        <div class="error-message"></div>
                    </div>

                    <!-- Nouveau champ : Niveau -->
                    <div class="form-group">
                        <label>Niveau</label>
                        <select id="niveau" name="niveau" required>
                            <option value="">Sélectionnez votre niveau</option>
                            <option value="Licence">Licence</option>
                            <option value="Master">Master</option>
                        </select>
                        <div class="error-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <div class="error-message"></div>
                    </div>


                    <button type="submit">S'inscrire</button>
                    
                    <div class="form-links">
                        <span>Déjà inscrit ? <a href="{{ route('pageAuth') }}">Connectez-vous</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
