<?php
    /*
     * This file contains functions hooked to the plugin's own hooks
     */

    /**
     * Do stuff afer manual activation by admin
     *
     * @param $user_id
     */
    function b3_do_stuff_after_new_user_activated_by_admin( $user_id ) {
        // Do stuff when user is activated by admin
        $user_object = get_userdata( $user_id );
        $user_object->set_role( get_option( 'default_role' ) );
        $from_name   = get_option( 'blogname' ); // @TODO: add filter for sender name
        $from_email  = get_option( 'admin_email' ); // @TODO: add filter for sender email
        $to          = $user_object->user_email;
        $subject     = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
        $message     = apply_filters( 'b3_get_account_approved_message', b3_get_account_approved_message() );
        $message     = b3_replace_template_styling( $message );
        $message     = strtr( $message, b3_replace_email_vars( [] ) );
        $message     = htmlspecialchars_decode( stripslashes( $message ) );
        $headers     = array(
            'From: ' . $from_name . ' <' . $from_email . '>',
            'Content-Type: text/html; charset=UTF-8',
        );

        wp_mail( $to, $subject, $message, $headers );
    }
    add_action( 'b3_new_user_activated_by_admin', 'b3_do_stuff_after_new_user_activated_by_admin' );


    /**
     * Do stuff after user activated
     *
     * @param $user_id
     */
    function b3_after_user_activated( $user_id ) {
        if ( 1 != get_option( 'b3_disable_admin_notification_new_user', false ) ) {
            wp_new_user_notification( $user_id, null, 'admin' );
        }
        // send activate email to user
        if ( 'email_activation' == get_option( 'b3_registration_type', false ) ) {
            $from_name  = get_option( 'blogname' ); // @TODO: add filter for sender name
            $from_email = get_option( 'admin_email' ); // @TODO: add filter for sender email
            $user       = get_userdata( $user_id );
            $to         = $user->user_email;
            $subject    = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
            $message    = apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() );
            $message    = b3_replace_template_styling( $message );
            $message    = strtr( $message, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $message    = htmlspecialchars_decode( stripslashes( $message ) );

            $headers = array(
                'From: ' . $from_name . ' <' . $from_email . '>',
                'Content-Type: text/html; charset=UTF-8',
            );
            wp_mail( $to, $subject, $message, $headers );
            // if ( $styling && $template ) {
            // }
        }
    }
    add_action( 'b3_new_user_activated', 'b3_after_user_activated' );


    /**
     * Add reCAPTCHA check
     *
     * @TODO: hook to action
     *
     * @param $recaptcha_public
     * @param string $form_type
     */
    function b3_add_captcha_registration( $recaptcha_public, $form_type = 'register' ) {

        $recaptcha_version = get_option( 'b3_recaptcha_version', '2' );
        do_action( 'b3_before_recaptcha_' . $form_type );
        if ( '2' == $recaptcha_version ) {
            ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
            </div>
            <p></p>
            <?php
        }
        do_action( 'b3_after_recaptcha_' . $form_type );
    }

    /**
     * Add field for subdomain when WPMU is active
     *
     * @TODO: hook to action
     */
    function b3_add_subdomain_field() {

        if ( 'all' == get_site_option( 'registration' ) && in_array( get_option( 'b3_registration_type', false ) , [ 'request_access_subdomain', 'ms_register_site_user' ] ) ) {
            ob_start();
            ?>
            <?php // @TODO: add more fields for Multisite ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_subdomain"><?php esc_html_e( 'Desired (sub) domain', 'b3-onboarding' ); ?></label>
                <input name="b3_subdomain" id="b3_subdomain" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-onboarding' ); ?>        .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>" required />
            </div>
            <?php
            $output = ob_get_clean();

            echo $output;
        }

    }

    /**
     * Output for first/last name fields
     *
     * @TODO: hook this (properly) to an action
     */
    function b3_first_last_name_fields() {

        ob_start();
        ?>
        <?php $required = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false; ?>
        <div class="b3_form-element b3_form-element--register">
            <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
            <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo ( defined( 'WP_TESTING' ) ) ? 'First name' : false; ?>"<?php echo $required; ?>>
        </div>
        <div class="b3_form-element b3_form-element--register">
            <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><strong>*</strong><?php } ?></label>
            <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo ( defined( 'WP_TESTING' ) ) ? 'Last name' : false; ?>"<?php echo $required; ?>>
        </div>
        <?php
        $output = ob_get_clean();

        echo $output;
    }

