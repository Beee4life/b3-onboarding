jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){

    $("span.b3__message-close").click(function(e){

        $(".b3__message").fadeOut(750);

    });

});
