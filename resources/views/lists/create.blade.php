<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une nouvelle liste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('nativecss/create.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="col-md-12" id="head">
        <nav class="row">
            <div class="col-md-6 col-6">
                <h5>Création de liste</h5>
            </div>
            <div class="col-md-6 col-6 text-end">
                <div class="admin-bubble">
                    <span class="admin-name">May</span>
                </div>
            </div>
        </nav>
    </div>

    <div class="container">
        <h1 class="my-4">Créer une nouvelle liste</h1>

        <!-- Formulaire de création -->
        <form id="createListForm" action="{{ route('liste.store') }}" method="POST">
            @csrf
            
            <!-- Messages d'erreur généraux -->
            <div id="form-errors" class="alert alert-danger d-none mb-3">
                Des erreurs ont été trouvées. Veuillez vérifier le formulaire.
            </div>

            <!-- Nom de la liste -->
            <div class="mb-3">
                <label for="nom_liste" class="form-label">Nom de la liste</label>
                <input type="text" class="form-control" id="nom_liste" name="nom_liste" required>
                <div class="invalid-feedback" id="error-nom_liste"></div>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                <div class="invalid-feedback" id="error-description"></div>
            </div>

            <!-- Statut (Actif/Inactif) -->
            <div class="mb-3">
                <label for="is_active" class="form-label">Statut</label>
                <select class="form-select" id="is_active" name="is_active" required>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
                <div class="invalid-feedback" id="error-is_active"></div>
            </div>

            <!-- Codes étudiants pour chaque poste -->
            <div class="mb-3">
                <label for="code_etu_info_licence" class="form-label">Code étudiant (Info Licence)</label>
                <input type="text" class="form-control" id="code_etu_info_licence" name="code_etu_info_licence" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_info_licence"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_info_master" class="form-label">Code étudiant (Info Master)</label>
                <input type="text" class="form-control" id="code_etu_info_master" name="code_etu_info_master" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_info_master"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_math_licence" class="form-label">Code étudiant (Math Licence)</label>
                <input type="text" class="form-control" id="code_etu_math_licence" name="code_etu_math_licence" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_math_licence"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_math_master" class="form-label">Code étudiant (Math Master)</label>
                <input type="text" class="form-control" id="code_etu_math_master" name="code_etu_math_master" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_math_master"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_phys_licence" class="form-label">Code étudiant (Phys Licence)</label>
                <input type="text" class="form-control" id="code_etu_phys_licence" name="code_etu_phys_licence" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_phys_licence"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_phys_master" class="form-label">Code étudiant (Phys Master)</label>
                <input type="text" class="form-control" id="code_etu_phys_master" name="code_etu_phys_master" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_phys_master"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_conseil_licence" class="form-label">Code étudiant (Conseil Licence)</label>
                <input type="text" class="form-control" id="code_etu_conseil_licence" name="code_etu_conseil_licence" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_conseil_licence"></div>
            </div>

            <div class="mb-3">
                <label for="code_etu_conseil_master" class="form-label">Code étudiant (Conseil Master)</label>
                <input type="text" class="form-control" id="code_etu_conseil_master" name="code_etu_conseil_master" pattern="P\s\d{2}\s\d{3,4}" placeholder="P 00 0000" required>
                <div class="invalid-feedback" id="error-code_etu_conseil_master"></div>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
    <footer>
        &copy; 2025 FALOU. Tous droits réservés.
    </footer>

    <script>
      $(document).ready(function () {
        $('#createListForm').on('submit', function (e) {
            e.preventDefault(); // Empêcher la soumission normale du formulaire

            // Nettoyer les erreurs précédentes
            $('#form-errors').addClass('d-none');
            $('.invalid-feedback').empty();
            $('.is-invalid').removeClass('is-invalid');

            // Envoyer les données via AJAX
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    // Rediriger en cas de succès
                    if (response.success) {
                        window.location.href = "{{ route('gestion.listes') }}";
                    }
                },
                error: function (xhr) {
                    console.log("Erreur reçue:", xhr.responseJSON); // Pour déboguer
                    
                    // Afficher les erreurs de validation
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Afficher le message d'erreur général
                        $('#form-errors').removeClass('d-none');
                        
                        const errors = xhr.responseJSON.errors;
                        
                        // Afficher les erreurs spécifiques à chaque champ
                        for (const field in errors) {
                            // Trouver l'élément de champ
                            const input = $(`#${field}`);
                            if (input.length) {
                                // Ajouter la classe d'erreur et le message
                                input.addClass('is-invalid');
                                $(`#error-${field}`).text(errors[field][0]);
                            } else {
                                // Essayer avec le nom du champ si l'ID ne correspond pas
                                const inputByName = $(`[name="${field}"]`);
                                if (inputByName.length) {
                                    inputByName.addClass('is-invalid');
                                    // Trouver ou créer un conteneur pour le message d'erreur
                                    let errorContainer = $(`#error-${field}`);
                                    if (!errorContainer.length) {
                                        inputByName.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                                    } else {
                                        errorContainer.text(errors[field][0]);
                                    }
                                }
                            }
                        }
                    } else {
                        // Afficher une erreur générale si aucune erreur spécifique n'est retournée
                        $('#form-errors').removeClass('d-none').text("Une erreur s'est produite lors de la création de la liste.");
                    }
                }
            });
        });
      });
    </script>
</body>
</html>