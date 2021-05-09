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

        var $validation_note = '.b3_settings-input-description--validation';
        $('#b3_activate_filter_validation').change(function() {
            if (document.getElementById('b3_activate_filter_validation').checked) {
                $($validation_note).removeClass('hidden');
            } else {
                $($validation_note).addClass('hidden');
            }
        });

        var $register_email_only = '.b3-name-fields';
        $('#b3_register_email_only').change(function() {
            if (document.getElementById('b3_register_email_only').checked) {
                $($register_email_only).addClass('hidden');
            } else {
                $($register_email_only).removeClass('hidden');
            }
        });

        var $first_last = '.b3_settings-field--first-last-required';
        $('#b3_activate_first_last').change(function() {
            if (document.getElementById('b3_activate_first_last').checked) {
                $($first_last).removeClass('hidden');
            } else {
                $($first_last).addClass('hidden');
            }
        });

        var $privacy_fields = '.b3_settings-field--privacy';
        $('#b3_privacy').change(function() {
            if (document.getElementById('b3_privacy').checked) {
                $($privacy_fields).removeClass('hidden');
            } else {
                $($privacy_fields).addClass('hidden');
            }
        });

        var $recaptcha_info = '.b3_settings-input-description--recaptcha';
        $('#b3_activate_recaptcha').change(function() {
            if (document.getElementById('b3_activate_recaptcha').checked) {
                $($recaptcha_info).removeClass('hidden');
            } else {
                $($recaptcha_info).addClass('hidden');
            }
        });

        var $custom_email_styling  = '.metabox-handler--email_styling';
        var $custom_email_template = '.metabox-handler--email_template';
        $('#b3_activate_custom_emails').change(function() {
            if (document.getElementById('b3_activate_custom_emails').checked) {
                $($custom_email_styling).removeClass('hidden');
                $($custom_email_template).removeClass('hidden');
            } else {
                $($custom_email_styling).addClass('hidden');
                $($custom_email_template).addClass('hidden');
            }
        });

        var $front_end_approval = '.b3_settings-input-description--approval';
        $('#b3_activate_frontend_approval').change(function() {
            if (document.getElementById('b3_activate_frontend_approval').checked) {
                $($front_end_approval).removeClass('hidden');
            } else {
                $($front_end_approval).addClass('hidden');
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

