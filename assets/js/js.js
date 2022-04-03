jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){
    var radio_button = $('.b3_form-element--signup-for input[type="radio"]');

    radio_button.change( function() {
        $site_fields = '.b3_site-fields';
        if ( 'user' === $(this).val() ) {
            $($site_fields).addClass('hidden');
        } else {
            $($site_fields).removeClass('hidden');
        }
    });

    $('span.error__close').click(function(e){
        $('p.b3_message').fadeOut(750);
    });

    $theme = 'light';
    if (typeof(b3ob_vars) != "undefined" && b3ob_vars !== null) {
        $theme = b3ob_vars.recaptcha_theme;
    }
    $( '.g-recaptcha' ).attr( 'data-theme', $theme );
});
