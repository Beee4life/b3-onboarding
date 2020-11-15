<?php
    /**
     * Render settings tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_settings_tab() {

        $activate_filter_validation = get_option( 'b3_activate_filter_validation', false );
        $disable_action_links       = get_option( 'b3_disable_action_links', false );
        $disable_wordpress_forms    = get_option( 'b3_disable_wordpress_forms', false );
        $debug_info                 = get_option( 'b3_debug_info', false );
        $hide_wordpress_checkbox    = ( 1 == get_option( 'b3_disable_wordpress_forms', false ) ) ? 'hidden' : false;
        $main_logo                  = get_option( 'b3_main_logo', false );
        $recaptcha                  = get_option( 'b3_activate_recaptcha', false );
        $registration_type          = get_option( 'b3_registration_type', false );
        $style_wordpress_forms      = get_option( 'b3_style_wordpress_forms', false );
        $use_popup                  = get_option( 'b3_use_popup', false );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Settings', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can set various global settings for the plugin.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding" method="post">
            <input name="b3ob_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3ob-settings-nonce' ); ?>" />

            <?php if ( is_multisite() && is_main_site() || ! is_multisite() ) { ?>
                <?php if ( ! is_multisite() ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_disable_wordpress_forms"><?php esc_html_e( 'Disable Wordpress forms', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_disable_wordpress_forms" name="b3_disable_wordpress_forms" value="1" <?php if ( $disable_wordpress_forms ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( "Check this box to disable WordPress' forms and force using yours.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( $hide_wordpress_checkbox, 'wp-forms' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_style_wordpress_forms"><?php esc_html_e( 'Style Wordpress forms', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_style_wordpress_forms" name="b3_style_wordpress_forms" value="1" <?php if ( $style_wordpress_forms ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( "Check this box to activate custom settings for WordPress' forms.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_use_popup"><?php esc_html_e( 'Use popup', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_use_popup" name="b3_use_popup" value="1" <?php if ( $use_popup ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to show the login form in a popup.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php if ( 'closed' != $registration_type ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php if ( $recaptcha ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate reCAPTCHA.', 'b3-onboarding' ); ?>
                            <?php $hide_recaptcha_note = ( 1 == get_option( 'b3_activate_recaptcha', false ) ) ? false : ' hidden'; ?>
                            <div class="b3_settings-input-description b3_settings-input-description--recaptcha<?php echo $hide_recaptcha_note; ?>">
                                <?php esc_html_e( 'See tab reCaptcha (after saving)', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php if ( $disable_action_links ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to hide the action links on custom forms.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( current_user_can( 'manage_options' ) && ( ( defined( 'LOCALHOST' ) && true != LOCALHOST ) || ! defined( 'LOCALHOST' ) ) ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_debug_info"><?php esc_html_e( 'Activate debug info page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_debug_info" name="b3_debug_info" value="1" <?php if ( $debug_info ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to \'show\' the debug page.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_filter_validation"><?php esc_html_e( 'Activate filter validation', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_filter_validation" name="b3_activate_filter_validation" value="1" <?php if ( $activate_filter_validation ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate filter validation.', 'b3-onboarding' ); ?>
                        <?php $hide_validation_note = ( 1 == get_option( 'b3_activate_filter_validation', false ) ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--validation<?php echo $hide_validation_note; ?>">
                            <?php esc_html_e( 'Don\'t forget to turn it of later on, the validation is a bit cpu intensive.', 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <hr />

                <h2>
                    <?php esc_html_e( 'Logo', 'b3-onboarding' ); ?>
                </h2>

                <?php b3_get_settings_field_open(); ?>
                    <div id="b3-main-logo-settings">
                        <p>
                            <?php esc_html_e( "This is the logo used in emails and on WordPress' default forms.", 'b3-onboarding' ); ?>
                        </p>
                        <p>
                            <label>
                                <input type="text" name="b3_main_logo" id="b3_main_logo" value="<?php echo $main_logo; ?>" />
                            </label>
                            <a href="#" id="main-logo" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?>
                            </a>
                        </p>
                    </div>
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php b3_get_submit_button(); ?>
        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
