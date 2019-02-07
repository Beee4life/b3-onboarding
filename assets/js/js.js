jQuery(document).ready(function () {
    // $('body').addClass('js').removeClass('no-js');
});

jQuery(document).ready(function() {
    var content = ".foldout__content";
    jQuery(content).hide();

    jQuery(".foldout__toggle").click(function() {
        if (jQuery(content).hasClass('hidden')){
            jQuery(content).removeClass('hidden');
        }
        jQuery(this).next(content).toggle('fast');
        if (jQuery(this).hasClass('open')){
            jQuery(this).removeClass('open');
        } else {
            jQuery(this).addClass('open');
        }

        var $icon = '.handlediv i';
        if (jQuery($icon).closest('i').hasClass('dashicons-plus')){
            jQuery($icon).closest('i').removeClass('dashicons-plus');
            jQuery($icon).closest('i').addClass('dashicons-no');
        } else if (jQuery($icon).closest('i').hasClass('dashicons-no')){
            jQuery($icon).closest('i').removeClass('dashicons-no');
            jQuery($icon).closest('i').addClass('dashicons-plus');
        }
    });
});
