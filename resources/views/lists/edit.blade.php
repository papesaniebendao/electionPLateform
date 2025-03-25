<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la liste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lien vers le fichier CSS externe -->
    <link rel="stylesheet" href="{{ asset('nativecss/edit.css') }}">
</head>
<body>
    <!-- Header -->
    <div class="col-md-12" id="head">
        <nav class="row">
            <div class="col-md-6 col-6">
                <h5>Modification de liste</h5>
            </div>
            <!-- Bulle de l'admin -->
            <div class="col-md-6 col-6 text-end">
                <div class="admin-bubble">
                    <span class="admin-name">May</span>
                </div>
            </div>
        </nav>
    </div>

    <!-- Formulaire de modification -->
    <div class="container">
        <h1>Modifier la liste</h1>
        <form action="{{ route('liste.update', $list->id) }}" method="POST">
            @csrf <!-- Token CSRF -->
            @method('PUT') <!-- Méthode HTTP PUT pour la mise à jour -->

            <!-- Nom de la liste -->
            <div class="mb-3">
                <label for="nom_liste" class="form-label">Nom de la liste</label>
                <input type="text" class="form-control" id="nom_liste" name="nom_liste" value="{{ $list->nom_liste }}" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $list->description }}</textarea>
            </div>

            <!-- Statut (Actif/Inactif) -->
            <div class="mb-3">
                <label for="is_active" class="form-label">Statut</label>
                <select class="form-select" id="is_active" name="is_active" required>
                    <option value="1" {{ $list->is_active ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ !$list->is_active ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>
</body>
</html>