<?php

    /**
     * Content for the 'settings page'
     */
    function b3_login_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html( __( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-login' ) ) );
        }
        $site_key   = get_option( 'b3-login-recaptcha-site-key', '' );
        $secret_key = get_option( 'b3-login-recaptcha-site-key', '' );

        if ( function_exists( 'b3_show_error_messages' ) ) {
            b3_show_error_messages();
        }
        ?>

        <div class="wrap">

            <h1>Login settings</h1>

            <?php // @TODO: check for recaptcha settings ?>
            <div>
                Set the reCaptcha keys here.
            </div>

            <form name="" action="" method="post">
                <input name="b3_login_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-login-settings-nonce' ); ?>" />

                <h3>Public key</h3>
                <div>
                    <label for="b3-login-recaptcha-site-key" class="screen-reader-text">Site key</label>
                    <input type="text" id="b3-login-recaptcha-site-key" name="b3-login-recaptcha-site-key" value="<?php echo $site_key; ?>" />
                </div>

                <h3>Private key</h3>
                <div>
                    <label for="b3-login-recaptcha-secret-key" class="screen-reader-text">Private key</label>
                    <input type="text" id="b3-login-recaptcha-secret-key" name="b3-login-recaptcha-secret-key" value="<?php echo $secret_key; ?>" />
                </div>

                <h3>Custom passwords</h3>
                <div>
                    <label for="b3-login-custom-passwords" class="screen-reader-text">Custom passwords</label>
                    <input type="checkbox" id="b3-login-custom-passwords" name="b3-login-custom-passwords" value="<?php _e( '1', 'b3-login' ); ?>" /> Activate custom passwords
                </div>

                <br />
                <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
            </form>

        </div><!-- end .wrap -->
    <?php }
