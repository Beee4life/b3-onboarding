(function($) {
    $(document).ready(function() {

        // Fold-outs
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

        // click admin tab
        $('#b3_tab-button--template').click(function() {
            invokeCM(window.cm_invoked);
        });

        // direct page
        var get_var = get_query();
        if ( 'template' === get_var.tab ) {
            invokeCM(window.cm_invoked);
        }

        // Toggle fields
        $('#b3_activate_filter_validation').change(function() {
            $('.b3_settings-input-description--validation').toggle();
        });

        $('#b3_register_email_only').change(function() {
            $('.b3-name-fields').toggle();
        });

        $('#b3_activate_custom_passwords').change(function() {
            $('.b3_settings-field--one-time-password').toggle();
            $('.b3_settings-field--redirect').toggle();
        });

        $('#b3_use_one_time_password').change(function() {
            $('.b3_settings-field--custom-passwords').toggle();
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
            $('.b3_settings-input-description--approval').toggle();
        });

        $('#b3_restrict_usernames').change(function () {
            $('.b3_settings-field--username-restrictions').toggle();
        });

        $('#b3_domain_restrictions').change(function () {
            $('.b3_settings-field--domain-restrictions').toggle();
        });

        $('#b3_activate_welcome_page').change(function () {
            $('.b3_settings-input-description--welcome').toggle();
        });

    });
})(jQuery);

/**
 * Invoke CodeMirror (if not invoked already)
 *
 * @param invoked
 */
function invokeCM(invoked) {
    if (! invoked) {
        var styling_id = '#b3__input--email_styling';
        var template_id = '#b3__input--email_template';

        if (jQuery(styling_id).length ) {
            wp.codeEditor.initialize(jQuery(styling_id), b3cm_settings);
        }
        if (jQuery(template_id).length ) {
            wp.codeEditor.initialize(jQuery(template_id), b3cm_settings);
        }
        window.cm_invoked = true;
    }
}

/**
 * Open tabs
 *
 * @src: https://www.w3schools.com/howto/howto_js_tabs.asp
 *
 * @param evt
 * @param tabName
 */
function openTab(evt, tabName) {
    // Declare variables
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

/**
 * Get $_GET query/vars
 *
 * @returns {{}}
 */
function get_query(){
    var url = document.location.href;
    var query_string = url.substring(url.indexOf('?') + 1).split('&');
    for(var i = 0, result = {}; i < query_string.length; i++){
        query_string[i] = query_string[i].split('=');
        result[query_string[i][0]] = decodeURIComponent(query_string[i][1]);
    }

    return result;
}
