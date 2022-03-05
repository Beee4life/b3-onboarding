<?php
    /**
     * Render recaptcha tab
     *
     * @since 2.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_recaptcha_tab() {
        $public_key        = get_option( 'b3_recaptcha_public' );
        $recaptcha_version = get_option( 'b3_recaptcha_version', 2 );
        $secret_key        = get_option( 'b3_recaptcha_secret' );

        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Recaptcha', 'b3-onboarding' ) );
        ?>
        <p>
            <?php esc_html_e( 'Here you can set the reCaptcha settings.', 'b3-onboarding' ); ?>
            <br>
            <?php esc_html_e( 'Both keys must be entered, for reCaptcha to work.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=recaptcha" method="post">
            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_version"><?php esc_html_e( 'reCaptcha version', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--radio">
                    <label>
                        <input type="radio" name="b3_recaptcha_version" value="2" <?php if ( 2 == $recaptcha_version ) { echo ' checked="checked"'; } ?> /> 2
                        <input type="radio" name="b3_recaptcha_version" value="3" <?php if ( 3 == $recaptcha_version ) { echo ' checked="checked"'; } ?> /> 3
                    </label>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_public"><?php esc_html_e( 'Public key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_public" name="b3_recaptcha_public" class="b3_recaptcha_input" value="<?php if ( $public_key ) { echo esc_attr( $public_key ); } ?>" />
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_secret"><?php esc_html_e( 'Secret key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_secret" name="b3_recaptcha_secret" class="b3_recaptcha_input" value="<?php if ( $secret_key ) { echo esc_attr( $secret_key ); } ?>" />
                </div>
            <?php b3_get_close(); ?>

            <?php echo sprintf( '<p>%s</p>', sprintf( esc_html__( 'Get your (free) reCaptcha keys %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( 'https://www.google.com/recaptcha/admin#list' ), esc_html__( 'here', 'b3-onboarding' ) ) ) ); ?>

            <?php b3_get_submit_button( esc_attr__( 'Save reCaptcha', 'b3-onboarding' ) ); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
