<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Render settings tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_settings_tab() {
        $activate_filter_validation = get_option( 'b3_activate_filter_validation' );
        $debug_info                 = get_option( 'b3_debug_info' );
        $disable_action_links       = get_option( 'b3_disable_action_links' );
        $preserve_settings          = get_option( 'b3_preserve_settings' );
        $use_popup                  = get_option( 'b3_use_popup', false );

        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Settings', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=settings" method="post">
            <input name="b3ob_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3ob-settings-nonce' ); ?>" />
            <?php if ( is_main_site() ) { ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php checked($disable_action_links); ?>/>
                        <?php esc_html_e( 'Hide the action links on forms.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open( true ); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_use_popup"><?php esc_html_e( 'Use popup', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_use_popup" name="b3_use_popup" value="1" <?php checked($use_popup); ?>/>
                        <?php esc_html_e( 'Show the login form in a popup (only available for the login link in the B3 widget).', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_filter_validation"><?php esc_html_e( 'Activate filter validation', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_filter_validation" name="b3_activate_filter_validation" value="1" <?php checked($activate_filter_validation); ?>/>
                        <?php esc_html_e( 'Activate the validation of all custom filters.', 'b3-onboarding' ); ?>
                        <?php $hide_validation_note = ( 1 == $activate_filter_validation ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--validation<?php echo $hide_validation_note; ?>">
                            <?php esc_html_e( "Don't forget to turn it of later on, the validation can cause a higher load time.", 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( current_user_can( 'manage_options' ) && ! is_localhost() ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_debug_info"><?php esc_html_e( 'Activate debug info page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_debug_info" name="b3_debug_info" value="1" <?php checked($debug_info); ?>/>
                            <?php esc_html_e( "Activate the debug page.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_preserve_settings"><?php esc_html_e( 'Preserve settings', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_preserve_settings" name="b3_preserve_settings" value="1" <?php checked($preserve_settings); ?>/>
                        <?php
                            if ( 1 == $preserve_settings ) {
                                esc_html_e( 'To remove the data upon plugin removal, uncheck this box.', 'b3-onboarding' );
                            } else {
                                esc_html_e( 'When removing the plugin, all data is removed. To prevent this from happening, check this box.', 'b3-onboarding' );
                            }
                        ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_reset_default"><?php esc_html_e( 'Reset to default', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_reset_default" name="b3_reset_default" value="1" />
                        <?php esc_html_e( "This option resets everything back to 'factory settings'.", 'b3-onboarding' ); ?>
                        <?php echo sprintf( esc_html__( '%s to see what it does exactly.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/reset-default-settings/' ), esc_html__( 'Click here', 'b3-onboarding' ) ) ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <hr />
            <?php } ?>

            <?php b3_get_submit_button(); ?>
        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
