$(document).ready(function() {
    // Gestion de la sidebar mobile
    $("#sidebarToggle").click(function() {
        $("#sidebar").addClass("sidebar-visible");
        $("body").append('<div id="sidebar-overlay"></div>');
        $("#sidebar-overlay").click(function() {
            $("#sidebar").removeClass("sidebar-visible");
            $(this).remove();
        });
    });

    $("#closeSidebar").click(function() {
        $("#sidebar").removeClass("sidebar-visible");
        $("#sidebar-overlay").remove();
    });

    // Soumission automatique des formulaires de filtre
    $('.auto-submit').change(function() {
        $(this).closest('form').submit();
    });

    // Empêche le comportement par défaut des liens de filtre
    // (optionnel - pour une meilleure expérience utilisateur)
    $('.btn-filter').click(function(e) {
        // Ajoute un effet visuel pendant le chargement
        $('#contenu').css('opacity', '0.5');
        setTimeout(() => {
            $('#contenu').css('opacity', '1');
        }, 300);
    });
});