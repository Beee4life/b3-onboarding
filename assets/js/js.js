jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){

    $("span.error__close").click(function(e){
        $(".b3_message").fadeOut(750);
    });

    var $radio_button = $('.b3_form-element--signup-for input[type="radio"]');
    $radio_button.change( function() {
        $subdomain_field = '.b3_form-element--site-fields';
        if ( 'user' === $(this).val() ) {
            $($subdomain_field).addClass('hidden');
        } else {
            $($subdomain_field).removeClass('hidden');
        }
    });
});
