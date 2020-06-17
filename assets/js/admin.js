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

        if (jQuery(this).find('i.dashicons').hasClass('dashicons-plus')){
            jQuery(this).find('i.dashicons').removeClass('dashicons-plus');
            jQuery(this).find('i.dashicons').addClass('dashicons-no');
        } else if (jQuery(this).find('i.dashicons').hasClass('dashicons-no')){
            jQuery(this).find('i.dashicons').removeClass('dashicons-no');
            jQuery(this).find('i.dashicons').addClass('dashicons-plus');
        }
    });
});

(function($) {
    $(document).ready(function() {

        var $wordpress_setting = '.b3_settings-field--wp-forms';
        $('#b3_disable_wordpress_forms').change(function() {
            if (document.getElementById('b3_disable_wordpress_forms').checked) {
                $($wordpress_setting).addClass('hidden');
            } else {
                $($wordpress_setting).removeClass('hidden');
            }
        });

        var $wordpress_tab = '.b3_tab-button--wordpress';
        $('#b3_style_wordpress_forms').change(function() {
            if (document.getElementById('b3_style_wordpress_forms').checked) {
                $($wordpress_tab).removeClass('hidden');
            } else {
                $($wordpress_tab).addClass('hidden');
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

