(function($) {
    $(document).ready(function() {

        var content = ".foldout__content";
        $(content).hide();

        $(".foldout__toggle").click(function() {
            if ($(content).hasClass('hidden')){
                $(content).removeClass('hidden');
            }
            $(this).next(content).toggle('fast');
            if ($(this).hasClass('open')){
                $(this).removeClass('open');
            } else {
                $(this).addClass('open');
            }

            if ($(this).find('i.dashicons').hasClass('dashicons-plus')){
                $(this).find('i.dashicons').removeClass('dashicons-plus');
                $(this).find('i.dashicons').addClass('dashicons-no');
            } else if ($(this).find('i.dashicons').hasClass('dashicons-no')){
                $(this).find('i.dashicons').removeClass('dashicons-no');
                $(this).find('i.dashicons').addClass('dashicons-plus');
            }
        });

        $('#b3_activate_filter_validation').change(function() {
            $('.b3_settings-input-description--validation').toggle();
        });

        $('#b3_register_email_only').change(function() {
            $('.b3-name-fields').toggle();
        });

        $('#b3_activate_first_last').change(function() {
            $('.b3_settings-field--first-last-required').toggle();
        });

        $('#b3_privacy').change(function() {
            $('.b3_settings-field--privacy').toggle();
        });

        $('#b3_activate_recaptcha').change(function() {
            $('.b3_settings-input-description--recaptcha').toggle();
        });

        $('#b3_logo_in_email').change(function() {
            $('.b3_settings-input-description--logo').toggle();
        });

        $('#b3_activate_frontend_approval').change(function() {
            $($front_end_approval).toggle();
        });

        // @TODO: check if still used
        var $redirect_after_register = '.b3_settings-field--redirect';
        $('#b3_activate_custom_passwords').change(function() {
            if (document.getElementById('b3_activate_custom_passwords').checked) {
                $($redirect_after_register).addClass('hidden');
            } else {
                $($redirect_after_register).removeClass('hidden');
            }
        });

        var $custom_passwords = '.b3_settings-field--custom-passwords';
        $('input[name="b3_registration_type"]').change(function() {
            if (document.getElementById('b3_registration_type_request_access').checked) {
                $($custom_passwords).addClass('hidden');
            } else {
                $($custom_passwords).removeClass('hidden');
            }
        });

        $('#b3_domain_restrictions').change(function () {
            $('.b3_settings-field--domain-restrictions').toggle();
        });

        $('#b3_activate_welcome_page').change(function () {
            $('.b3_settings-input-description--welcome').toggle();
        });

    });
})(jQuery);

// https://www.w3schools.com/howto/howto_js_tabs.asp
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tabbutton;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("b3_tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tabbutton" and remove the class "active"
    tabbutton = document.getElementsByClassName("b3_tab-button");
    for (i = 0; i < tabbutton.length; i++) {
        tabbutton[i].className = tabbutton[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

