<?php
    /**
     * Render recaptcha tab
     *
     * @since 2.0.0
     *
     * @return false|string
     */
    function b3_render_recaptcha_tab() {

        ob_start();
        $public_key        = get_option( 'b3_recaptcha_public', false );
        $recaptcha_version = get_option( 'b3_recaptcha_version', 2 );
        $secret_key        = get_option( 'b3_recaptcha_secret', false );
        $recaptcha_login   = get_option( 'b3_recaptcha_login', false );
        $recaptcha_on      = get_option( 'b3_recaptcha_on', [] );
        ?>
        <h2>
            <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can set the v2 reCaptcha settings, v3 is not working (yet).', 'b3-onboarding' ); ?>
            <br />
            <?php esc_html_e( 'Both keys must be entered, for reCaptcha to work.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=recaptcha" method="post">
            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_on"><?php esc_html_e( 'Add reCaptcha on:', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox b3_settings-input--recaptcha">
                    <input type="checkbox" id="b3_recaptcha_registration" name="b3_recaptcha_on[]" value="register" <?php if ( in_array( 'register', $recaptcha_on ) ) { ?>checked="checked"<?php } ?>/> <label for="b3_recaptcha_registration"><?php esc_html_e( 'Registration form', 'b3-onboarding' ); ?></label>
                    <input type="checkbox" id="b3_recaptcha_login" name="b3_recaptcha_on[]" value="login" <?php if ( in_array( 'login', $recaptcha_on ) ) { ?>checked="checked"<?php } ?>/> <label for="b3_recaptcha_login"><?php esc_html_e( 'Login form', 'b3-onboarding' ); ?></label>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(1); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_login"><?php esc_html_e( 'Add reCaptcha on login page', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_recaptcha_login" name="b3_recaptcha_login" value="1" <?php if ( $recaptcha_login ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to add reCaptcha on the custom login form.', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_public"><?php esc_html_e( 'Public key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_public" name="b3_recaptcha_public" class="b3_recaptcha_input" value="<?php if ( $public_key ) { echo $public_key; } ?>" />
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_secret"><?php esc_html_e( 'Secret key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_secret" name="b3_recaptcha_secret" class="b3_recaptcha_input" value="<?php if ( $secret_key ) { echo $secret_key; } ?>" />
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open( 1 ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_version"><?php esc_html_e( 'reCaptcha version', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <select name="b3_recaptcha_version" id="b3_recaptcha_version">
                        <option value=""><?php esc_html_e( 'Choose', 'b3-onboarding' ); ?></option>
                        <?php $versions = [ 2, 3 ]; ?>
                        <?php foreach( $versions as $version ) { ?>
                            <option value="<?php echo $version; ?>"<?php echo ( $recaptcha_version == $version ) ? ' selected="selected"' : false; ?>>v<?php echo $version; ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php b3_get_close(); ?>

            <p>
                <?php echo sprintf( __( 'Get your (free) reCaptcha keys <a href="%s" target="_blank" rel="noopener">here</a>.', 'b3-onboarding' ), esc_url( 'https://www.google.com/recaptcha/admin#list' ) ); ?>
            </p>

            <?php b3_get_submit_button( __( 'Save reCaptcha', 'b3-onboarding' ), false ); ?>

        </form>

        <?php if ( defined( 'LOCALHOST' ) && true == LOCALHOST ) { ?>

            <?php b3_get_settings_field_open(); ?>
            <?php
            $modules = [
                [
                    'id'   => 'mailchimp',
                    'name' => 'Mailchimp',
                    'logo' => 'logo-mailchimp.png',
                    'link' => '#',
                ],
                [
                    'id'   => 'salesforce',
                    'name' => 'Salesforce',
                    'logo' => 'logo-salesforce.png',
                    'link' => '#',
                ],
                [
                    'id'   => 'aweber',
                    'name' => 'AWeber',
                    'logo' => 'logo-aweber.png',
                    'link' => '#',
                ],
            ];
            ?>
            <div class="integrations">
                <h3>
                    <?php esc_html_e( 'More integrations', 'b3-onboarding' ); ?>
                </h3>
                <p>
                    <?php esc_html_e( 'We understand there might be a need for more integrations.', 'b3-onboarding' ); ?>
                    <br />
                    <?php esc_html_e( "If we'll add more, the ones below are the first ones wer're gonna explore.", 'b3-onboarding' ); ?>
                </p>

                <ul class="b3_integrations--list"><!--
                    <?php foreach( $modules as $module ) { ?>
                    --><li class="b3_integrations--list-item b3_integrations--list-item--<?php echo $module[ 'id' ]; ?>">
                        <div class="b3_integration__container">
                            <div class="b3_integration__image">
                                <img
                                    src="<?php echo B3_PLUGIN_URL . 'assets/images/'; ?><?php echo $module[ 'logo' ]; ?>"
                                    alt="<?php echo $module[ 'name' ]; ?>"/>
                            </div>
                            <div class="b3_integration__name">
                                <?php echo $module[ 'name' ]; ?>
                            </div>
                        </div>
                    </li><!--
                    <?php } ?>
                --></ul>
            </div>
            <?php b3_get_close(); ?>
        <?php } ?>

        <?php
        $result = ob_get_clean();

        return $result;

    }
