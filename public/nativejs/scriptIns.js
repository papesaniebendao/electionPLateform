$(document).ready(function() {
    // Validation en temps réel
    $('input, select').on('input change', function() {
        validateField($(this));
    });

    $('#inscriptionForm').submit(function(e) {
        let isValid = true;
        
        $('input, select').each(function() {
            if(!validateField($(this))) isValid = false;
        });

        // Vérification mot de passe
        if($('#password').val() !== $('#confirmPassword').val()) {
            showError($('#confirmPassword'), 'Les mots de passe ne correspondent pas');
            isValid = false;
        }

        if(!isValid) e.preventDefault();
    });

    function validateField(field) {
        const value = field.val().trim();
        const errorDiv = field.next('.error-message');
        
        errorDiv.text('');
        field.removeClass('error-border');

        if(field.prop('required') && value === '') {
            showError(field, 'Ce champ est obligatoire');
            return false;
        }

        // Validation spécifique pour le code promotion
        if(field.attr('id') === 'codePromotion' && !/^P\s\d{2}\s\d{4}$/.test(value)) {
            showError(field, 'Format invalide (Ex: P 31 2062)');
            return false;
        }

                // Dans la fonction validateField()
        if (field.attr('id') === 'email') {
            const emailPattern = /^[a-zA-Z0-9._-]+@ugb\.edu\.sn$/;
            if (!emailPattern.test(value)) {
                showError(field, 'Email UGB invalide (ex email valide: nom.prenom-deuxiemeprenom.nom@ugb.edu.sn)');
                return false;
            }
        }

        if(field.attr('type') === 'password' && value.length < 8) {
            showError(field, 'Minimum 8 caractères');
            return false;
        }

        return true;
    }

    function showError(field, message) {
        field.addClass('error-border');
        field.next('.error-message').text(message);
    }
});