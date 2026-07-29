jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){
    var password_form = $('#b3-login');
    var magiclink_form = $('#b3-resetpass');
    password_form.hide();

    $(document).on('click', '.button-submit--use-magiclink', function (e) {
        e.preventDefault();
        password_form.hide();
        magiclink_form.show();

        $('#b3-resetpass #b3_magic-password_nonce')
            .attr('name', 'b3_login_nonce')
            .val(b3ob_vars.login_nonce)
            .attr('id', 'b3_login_nonce');
    });

    $(document).on('click', '.button-submit--use-password', function (e) {
        e.preventDefault();
        magiclink_form.hide();
        password_form.show();

        var $active_submit = $('#loginform #b3-submit');
        $active_submit.val('Login');

        var $button2 = $('#loginform #button2');
        $button2.val('Get magic link');

        $button2.removeClass('button-submit--use-password')
            .addClass('button-submit--use-magiclink');

        $('#b3-login #b3_magiclink_nonce')
            .attr('name', 'b3_login_nonce')
            .val(b3ob_vars.login_nonce)
            .attr('id', 'b3_login_nonce');
    });
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
