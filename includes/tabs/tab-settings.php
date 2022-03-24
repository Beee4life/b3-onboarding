<?php
    /**
     * Render settings tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_settings_tab() {
        $activate_welcome_page      = get_option( 'b3_activate_welcome_page' );
        $activate_filter_validation = get_option( 'b3_activate_filter_validation' );
        $disable_action_links       = get_option( 'b3_disable_action_links' );
        $debug_info                 = get_option( 'b3_debug_info' );
        $main_logo                  = get_option( 'b3_main_logo' );
        $use_popup                  = get_option( 'b3_use_popup', false );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Settings', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php if ( is_main_site() ) { ?>
                <?php esc_html_e( "Here you can set some settings for the plugin (which didn't fit on other tabs).", 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php esc_html_e( 'Most settings are set in the main site.', 'b3-onboarding' ); ?>
            <?php } ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=settings" method="post">
            <input name="b3ob_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3ob-settings-nonce' ); ?>" />

            <?php if ( is_main_site() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php checked($disable_action_links); ?>/> <?php esc_html_e( 'Check this box to hide the action links on custom forms.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_welcome_page"><?php esc_html_e( 'Welcome page', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_welcome_page" name="b3_activate_welcome_page" value="1" <?php checked($activate_welcome_page); ?>/> <?php esc_html_e( "Check this box to redirect the user to a 'welcome' page after his first login.", 'b3-onboarding' ); ?>
                        <?php $hide_welcome_page_note = ( 1 == $activate_welcome_page ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--welcome<?php echo $hide_welcome_page_note; ?>">
                            <?php echo sprintf( esc_html__( 'This page can only be set with a filter (for now). See %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', '#', esc_html__( 'here', 'b3-onboarding' ) ) ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_use_popup"><?php esc_html_e( 'Use popup', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_use_popup" name="b3_use_popup" value="1" <?php checked($use_popup); ?>/> <?php esc_html_e( 'Check this box to show the login form in a popup (right now only available for the login link in the B3 widget).', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_filter_validation"><?php esc_html_e( 'Activate filter validation', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_filter_validation" name="b3_activate_filter_validation" value="1" <?php checked($activate_filter_validation); ?>/> <?php esc_html_e( 'Check this box to activate filter validation.', 'b3-onboarding' ); ?> <?php esc_html_e( "Don't leave it on longer than needed, because it's fairly cpu intensive.", 'b3-onboarding' ); ?>
                        <?php $hide_validation_note = ( 1 == $activate_filter_validation ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--validation<?php echo $hide_validation_note; ?>">
                            <?php esc_html_e( 'Don\'t forget to turn it of later on, the validation is a bit cpu intensive.', 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( current_user_can( 'manage_options' ) && ! is_localhost() ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_debug_info"><?php esc_html_e( 'Activate debug info page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_debug_info" name="b3_debug_info" value="1" <?php checked($debug_info); ?>/> <?php esc_html_e( "Check this box to 'show' the debug page.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_preserve_settings"><?php esc_html_e( 'Preserve settings', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_preserve_settings" name="b3_preserve_settings" value="1" <?php checked($debug_info); ?>/> <?php esc_html_e( 'When removing the plugin, all data is removed. To prevent this from happening, check this box.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_reset_default"><?php esc_html_e( 'Reset to default', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_reset_default" name="b3_reset_default" value="1" />
                        <?php esc_html_e( "This option resets everything back to 'factory settings'.", 'b3-onboarding' ); ?>
                        <?php echo sprintf( '%s to see what it does exactly.', sprintf( '<a href="%s">%s</a>', esc_url( B3_PLUGIN_SITE . '/faq/how-do-i-reset-everything-back-to-its-default-settings/' ), esc_html__( 'Click here', 'b3-onboarding' ) ) ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <hr />
            <?php } ?>

            <?php if ( false == apply_filters( 'b3_main_logo', false ) ) { ?>
                <h2>
                    <?php esc_html_e( 'Logo', 'b3-onboarding' ); ?>
                </h2>

                <?php b3_get_settings_field_open(); ?>
                    <div id="b3-main-logo-settings">
                        <p>
                            <?php esc_html_e( "This is the logo used in email headers.", 'b3-onboarding' ); ?>
                        </p>
                        <p>
                            <label>
                                <input type="url" name="b3_main_logo" id="b3_main_logo" value="<?php echo esc_url( $main_logo ); ?>" />
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
