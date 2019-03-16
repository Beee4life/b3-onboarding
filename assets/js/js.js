jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){

    $("span.b3_message-close").click(function(e){

        $(".b3_message").fadeOut(750);

    });

});
