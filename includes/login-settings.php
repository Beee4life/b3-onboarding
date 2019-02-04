<?php

    /**
     * Content for the 'settings page'
     */
    function sd_login_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html( __( 'Sorry, you do not have sufficient permissions to access this page.', 'sd-login' ) ) );
        }
        $site_key   = get_option( 'sd-login-recaptcha-site-key', '' );
        $secret_key = get_option( 'sd-login-recaptcha-site-key', '' );

        if ( function_exists( 'sd_show_error_messages' ) ) {
            sd_show_error_messages();
        }
        ?>

        <div class="wrap">

            <h1>Login settings</h1>

            <p>
                Set the reCaptcha keys here.
            </p>

            <?php
                // settings_fields( 'sd-login' );
                // do_settings_sections( 'sd-login' );
            ?>

            <form name="" action="" method="post">
                <input name="sd_login_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'sd-login-settings-nonce' ); ?>" />

                <h3>Public key</h3>
                <p>
                    <label for="sd-login-recaptcha-site-key" class="screen-reader-text">Site key</label>
                    <input type="text" id="sd-login-recaptcha-site-key" name="sd-login-recaptcha-site-key" value="<?php echo $site_key; ?>" />
                </p>

                <h3>Private key</h3>
                <p>
                    <label for="sd-login-recaptcha-secret-key" class="screen-reader-text">Private key</label>
                    <input type="text" id="sd-login-recaptcha-secret-key" name="sd-login-recaptcha-secret-key" value="<?php echo $secret_key; ?>" />
                </p>

                <h3>Custom passwords</h3>
                <p>
                    <label for="sd-login-custom-passwords" class="screen-reader-text">Custom passwords</label>
                    <input type="checkbox" id="sd-login-custom-passwords" name="sd-login-custom-passwords" value="<?php _e( '1', 'sd-login' ); ?>" /> Activate custom passwords
                </p>

                <br />
                <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
            </form>

        </div><!-- end .wrap -->
    <?php }

    /**
     * Content for the 'settings page'
     */
    function sd_login_settings2() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html( __( 'Sorry, you do not have sufficient permissions to access this page.', 'sd-login' ) ) );
        }
        if ( function_exists( 'sd_show_error_messages' ) ) {
            sd_show_error_messages();
        }

        register_setting( 'sd-login', 'sd-login-recaptcha-site-key' );
        register_setting( 'sd-login', 'sd-login-recaptcha-secret-key' );

        add_settings_field(
            'sd-login-recaptcha-site-key',
            '<label for="sd-login-recaptcha-site-key">' . __( 'reCAPTCHA site key' , 'sd-login' ) . '</label>',
            'render_recaptcha_site_key_field',
            'general'
        );

        add_settings_field(
            'sd-login-recaptcha-secret-key',
            '<label for="sd-login-recaptcha-secret-key">' . __( 'reCAPTCHA secret key' , 'sd-login' ) . '</label>',
            'render_recaptcha_secret_key_field',
            'general'
        );

        add_settings_section(
            'sd-login-settings',
            '',
            '',
            'general'
        )

        ?>

        <div class="wrap">

            <h1>Login settings</h1>

            <div class="">
                <form name="" action="" method="post">
                    <?php settings_field( 'sd-login' ); ?>
                    <?php do_settings_field( 'general' ); ?>
                </form>
            </div>


        </div><!-- end .wrap -->
    <?php }

