$(document).ready(function() {
    // Sélection des éléments
    const $form = $('#inscriptionForm');
    const $emailInput = $('input[type="email"]');
    const $passwordInput = $('input[type="password"]');
    
    // Configuration des regex
    const emailRegex = /^[a-zA-Z0-9._-]+@ugb\.edu\.sn$/;
    const minPasswordLength = 8;

    // Validation en temps réel
    $emailInput.on('input', validateEmail);
    $passwordInput.on('input', validatePassword);

    // Validation à la soumission
    $form.submit(function(e) {
        let isValid = true;
        
        if(!validateEmail()) isValid = false;
        if(!validatePassword()) isValid = false;

        if(!isValid) {
            e.preventDefault();
            showGlobalError("Veuillez corriger les erreurs avant de soumettre");
        }
    });

    function validateEmail() {
        const value = $emailInput.val().trim();
        const $errorDiv = $emailInput.next('.error-message');
        
        $errorDiv.text('');
        $emailInput.removeClass('error-border');

        if(value === '') {
            showError($emailInput, "L'email est obligatoire");
            return false;
        }

        if(!emailRegex.test(value)) {
            showError($emailInput, "Format UGB requis : nom.prenom-deuxiemeprenom.nom@ugb.edu.sn");
            return false;
        }

        return true;
    }

    function validatePassword() {
        const value = $passwordInput.val();
        const $errorDiv = $passwordInput.nextAll('.error-message').first();

        $errorDiv.text('');
        $passwordInput.removeClass('error-border');

        if(value.length < minPasswordLength) {
            showError($passwordInput, `Minimum ${minPasswordLength} caractères`);
            return false;
        }

        return true;
    }

    function showError($input, message) {
        $input.addClass('error-border');
        $input.next('.error-message').text(message);
    }

    function showGlobalError(message) {
        $('<div class="global-error">')
            .text(message)
            .insertBefore($form)
            .delay(3000).fadeOut();
    }
});