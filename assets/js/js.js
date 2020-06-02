jQuery(document).ready(function () {
    jQuery('html').addClass('js').removeClass('no-js');
});

jQuery(function($){

    $("span.error__close").click(function(e){
        console.log('CLICK');
        $(".b3_message").fadeOut(750);

    });

});
