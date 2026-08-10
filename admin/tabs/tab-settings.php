<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render settings tab
    function b3_render_settings_tab() {
        $above_registration_placeholder  = b3_get_default_message_above_registration();
        $above_registration_filter       = apply_filters( 'b3_message_above_registration', false );
        $above_registration_option       = wp_unslash( get_option( 'b3_message_above_registration' ) );
        $above_registration_value        = $above_registration_filter ? $above_registration_filter : $above_registration_option;
        $above_magic_link_placeholder    = b3_get_default_message_above_magic_link();
        $above_magic_link_filter         = apply_filters( 'b3_message_above_magic_link', false );
        $above_magic_link_option         = wp_unslash( get_option( 'b3_message_above_magic_link' ) );
        $above_magic_link_value          = $above_magic_link_filter ? $above_magic_link_filter : $above_magic_link_option;
        $use_magic_link                  = get_option( 'b3_use_magic_link' );
        $above_login_placeholder         = $use_magic_link ? b3_get_default_message_above_magic_link() : '';
        $above_login_filter              = apply_filters( 'b3_message_above_login', '' );
        $above_login_option              = wp_unslash( get_option( 'b3_message_above_login' ) );
        $above_login_value               = $above_login_filter ? $above_login_filter : $above_login_option;
        $above_lost_password_placeholder = b3_get_default_message_above_lost_password();
        $above_lost_password_filter      = apply_filters( 'b3_message_above_lost_password', false );
        $above_lost_password_option      = wp_unslash( get_option( 'b3_message_above_lost_password' ) );
        $above_lost_password_value       = $above_lost_password_filter ? $above_lost_password_filter : $above_lost_password_option;
        $activate_filter_validation      = get_option( 'b3_activate_filter_validation' );
        $debug_info_option               = get_option( 'b3_activate_debug_info' );
        $debug_info_filter               = apply_filters( 'b3_activate_debug_info', false );
        $debug_info                      = $debug_info_option || $debug_info_filter ? '1' : false;
        $disable_action_links            = apply_filters( 'b3_disable_action_links', get_option( 'b3_disable_action_links', false ) );
        $preserve_settings               = get_option( 'b3_preserve_settings' );
        $registration_type               = get_option( 'b3_registration_type' );
        $use_popup                       = get_option( 'b3_activate_login_popup' );


        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Settings', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=settings" method="post">
            <input name="b3ob_settings_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3ob-settings-nonce' ) ); ?>" />
            <?php if ( is_main_site() ) { ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <?php if ( apply_filters( 'b3_disable_action_links', false ) ) { ?>
                            <?php esc_html_e( 'Setting is activated by filter.', 'b3-onboarding' ); ?>
                        <?php } else { ?>
                            <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php checked( $disable_action_links ); ?>/>
                            <?php esc_html_e( 'Hide the action links on forms.', 'b3-onboarding' ); ?>
                        <?php } ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_login_popup"><?php esc_html_e( 'Use popup', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_login_popup" name="b3_activate_login_popup" value="1" <?php checked( $use_popup ); ?>/>
                        <?php esc_html_e( 'Show the login form in a popup (only available for the login link in the B3 widget).', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_filter_validation"><?php esc_html_e( 'Activate filter validation', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_filter_validation" name="b3_activate_filter_validation" value="1" <?php checked( $activate_filter_validation ); ?>/>
                        <?php esc_html_e( 'Activate the validation of all custom filters.', 'b3-onboarding' ); ?>
                        <?php $hide_validation_note = $activate_filter_validation ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--validation<?php echo esc_attr( $hide_validation_note ); ?>">
                            <?php esc_html_e( "Don't forget to turn it of later on, the validation can cause a higher load time.", 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( current_user_can( 'manage_options' ) && ! is_localhost() ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_debug_info"><?php esc_html_e( 'Activate debug info page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <?php if ( $debug_info_filter ) { ?>
                                <?php esc_html_e( 'Setting is activated by filter.', 'b3-onboarding' ); ?>
                            <?php } else { ?>
                                <input type="checkbox" id="b3_activate_debug_info" name="b3_activate_debug_info" value="1" <?php checked($debug_info); ?>/>
                                <?php esc_html_e( 'Activate the debug page.', 'b3-onboarding' ); ?>
                            <?php } ?>

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
                            if ( $preserve_settings ) {
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
                        <?php // translators: link to faq for 'reset to default settings' ?>
                        <?php echo sprintf( esc_html__( '%s to see what it does exactly.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_SITE . '/faq/reset-default-settings/' ), esc_html__( 'Click here', 'b3-onboarding' ) ) ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <hr />

                <?php echo sprintf( '<h2>%s</h2>', esc_html__( 'Form messages', 'b3-onboarding' ) );?>

                <?php if ( 'none' === $registration_type ) { ?>
                    <?php
                        $placeholder_registration_closed = b3_get_default_registration_closed_message();
                        $filter_registration_closed      = htmlspecialchars( apply_filters( 'b3_registration_closed_message', false ) );
                        $registration_closed_message     = htmlspecialchars( wp_unslash( get_option( 'b3_registration_closed_message' ) ) );
                        $registration_closed_message     = $filter_registration_closed ? $filter_registration_closed : $registration_closed_message;
                    ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_closed_message"><?php esc_html_e( 'Registration closed message', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_registration_closed_message" name="b3_registration_closed_message" placeholder="<?php echo esc_attr( $placeholder_registration_closed ); ?>" value="<?php if ( $registration_closed_message ) { echo wp_kses_post( $registration_closed_message ); } ?>"/>
                            <?php if ( $filter_registration_closed ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                <?php } else { ?>

                    <?php b3_get_settings_field_open( false, 'message-above-registration' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_message_above_registration"><?php esc_html_e( 'Message above registration', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_message_above_registration" name="b3_message_above_registration" placeholder="<?php echo esc_attr( $above_registration_placeholder ); ?>" value="<?php echo $above_registration_value; ?>"<?php if ( apply_filters( 'b3_message_above_registration', '' ) ) { ?> disabled<?php } ?>/>
                            <?php if ( $above_registration_filter ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( false, 'message-above-login' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_message_above_login"><?php esc_html_e( 'Message above login', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_message_above_login" name="b3_message_above_login" placeholder="<?php echo esc_attr( $above_login_placeholder ) ;?>" value="<?php if ( $above_login_value ) { echo wp_kses_post( $above_login_value ); } ?>"<?php if ( apply_filters( 'b3_message_above_login', '' ) ) { ?> disabled<?php } ?>/>
                            <?php if ( $above_login_filter ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php if ( $use_magic_link ) { ?>
                        <?php b3_get_settings_field_open( false, 'message-above-magic-link' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_message_above_magic_link"><?php esc_html_e( 'Message above magic link', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--text">
                                <input type="text" id="b3_message_above_magic_link" name="b3_message_above_magic_link" placeholder="<?php echo esc_attr( $above_magic_link_placeholder ); ?>" value="<?php echo $above_magic_link_value; ?>"<?php if ( apply_filters( 'b3_message_above_magic_link', '' ) ) { ?> disabled<?php } ?>/>
                                <?php if ( $above_magic_link_filter ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php $hide_message_above_lost_password = false; ?>
                    <?php if ( get_option( 'b3_use_magic_link' ) && ! get_option( 'b3_use_magic_link_password' ) ) { ?>
                        <?php $hide_message_above_lost_password = true; ?>
                    <?php } ?>
                    <?php b3_get_settings_field_open( $hide_message_above_lost_password, 'message-above-lost-password' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_message_above_lost_password"><?php esc_html_e( 'Message above lost password', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_message_above_lost_password" name="b3_message_above_lost_password" placeholder="<?php echo esc_attr( $above_lost_password_placeholder ); ?>" value="<?php echo $above_lost_password_value; ?>"<?php if ( apply_filters( 'b3_message_above_lost_password', '' ) ) { ?> disabled<?php } ?>/>
                            <?php if ( $above_registration_filter ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                <?php } ?>


            <?php } ?>

            <?php b3_get_submit_button(); ?>
        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
