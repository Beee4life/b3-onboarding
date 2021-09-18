<?php
    /**
     * Render registration tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_registration_tab() {

        $custom_passwords             = get_option( 'b3_activate_custom_passwords' );
        $first_last                   = get_option( 'b3_activate_first_last' );
        $first_last_required          = get_option( 'b3_first_last_required' );
        $privacy                      = get_option( 'b3_privacy' );
        $privacy_page                 = get_option( 'b3_privacy_page' );
        $privacy_page_placeholder     = __( '<a href="">Click here</a> for more info.', 'b3-onboarding' );
        $privacy_text                 = get_option( 'b3_privacy_text' );
        $redirect_set_password        = get_option( 'b3_redirect_set_password' );
        $registration_type            = get_option( 'b3_registration_type' );
        $registration_with_email_only = get_option( 'b3_register_email_only' );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Registration', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php if ( is_main_site() ) { ?>
                <?php esc_html_e( 'Here you can set registration settings.', 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php esc_html_e( 'These settings are set in the main site.', 'b3-onboarding' ); ?>
            <?php } ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=registration" method="post">
            <input name="b3_registration_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-registration-nonce' ); ?>" />
            <?php if ( is_main_site() ) { ?>

                <?php if ( is_multisite() ) { ?>
                    <?php $options = b3_get_registration_types(); ?>
                    <?php if ( ! empty( $options ) ) { ?>
                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_registration_types"><?php esc_html_e( 'Registration type', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <p>
                                <?php echo sprintf( __( 'This setting \'controls\' the Registration type on the <a href="%s">Settings page</a>.', 'b3-onboarding' ), network_admin_url( 'settings.php' ) ); ?>
                            </p>
                            <?php foreach( $options as $option ) { ?>
                                <div class="b3_settings-input b3_settings-input--radio">
                                    <div>
                                        <label for="b3_registration_type_<?php echo $option[ 'value' ]; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                        <input type="radio" id="b3_registration_type_<?php echo $option[ 'value' ]; ?>" name="b3_registration_type" value="<?php echo $option[ 'value' ]; ?>" <?php if ( $option[ 'value' ] == $registration_type ) { ?>checked="checked"<?php } ?>/> <?php echo $option[ 'label' ]; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php b3_get_close(); ?>

                    <?php } else { ?>
                        <div class="b3_settings-input b3_settings-input--radio">
                            <?php esc_html_e( 'Registrations are disabled.','b3-onboarding' ); ?>
                        </div>
                    <?php } ?>

                <?php } else { ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_types"><?php esc_html_e( 'Registration type', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <p>
                            <?php echo sprintf( __( 'These settings are now the global settings and \'control\' the values on the <a href="%s">Settings page</a>.', 'b3-onboarding' ), admin_url( 'options-general.php' ) ); ?>
                        </p>

                        <?php $options = b3_get_registration_types(); ?>
                        <?php if ( ! empty( $options ) ) { ?>
                            <?php foreach( $options as $option ) { ?>
                                <div class="b3_settings-input b3_settings-input--radio">
                                    <div>
                                        <label for="b3_registration_type_<?php echo $option[ 'value' ]; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                        <input type="radio" id="b3_registration_type_<?php echo $option[ 'value' ]; ?>" name="b3_registration_type" value="<?php echo $option[ 'value' ]; ?>" <?php if ( $option[ 'value' ] == $registration_type ) { ?>checked="checked"<?php } ?>/> <?php echo $option[ 'label' ]; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="b3_settings-input b3_settings-input--radio">
                                <?php esc_html_e( 'Registrations are disabled.','b3-onboarding' ); ?>
                            </div>
                        <?php } ?>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php if ( 'none' == $registration_type ) { ?>

                    <?php $closed_message = apply_filters( 'b3_registration_closed_message', get_option( 'b3_registration_closed_message' ) ); ?>
                    <?php $default_closed_message = apply_filters( 'b3_registration_closed_message', b3_get_registration_closed_message() ); ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_closed_message"><?php esc_html_e( 'Registration closed message', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_registration_closed_message" name="b3_registration_closed_message" placeholder="<?php echo esc_attr( $default_closed_message ); ?>" value="<?php if ( $closed_message ) { echo stripslashes( $closed_message ); } ?>"/>
                            <div class="b3_settings-input-description">
                                <?php esc_html_e( 'Links are allowed.', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>

                <?php } else { ?>

                    <?php if ( ! is_multisite() ) { ?>
                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_register_email_only"><?php esc_html_e( 'Register with email address only', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_register_email_only" name="b3_register_email_only" value="1" <?php if ( $registration_with_email_only ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to register with only an email address.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_custom_passwords = ( in_array( $registration_type, [ 'request_access', 'none' ] ) ) ? true : false; ?>
                        <?php b3_get_settings_field_open( $hide_custom_passwords, 'custom-passwords' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_custom_passwords"><?php esc_html_e( 'Custom passwords', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_custom_passwords" name="b3_activate_custom_passwords" value="1" <?php if ( $custom_passwords ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate custom passwords.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php $hide_extended_fields = ( 1 == $registration_with_email_only ) ? ' hidden' : false; ?>
                    <div class="b3-name-fields<?php echo $hide_extended_fields; ?>">

                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_first_last"><?php esc_html_e( 'Activate first and last name', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_first_last" name="b3_activate_first_last" value="1" <?php if ( $first_last ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate first and last name during registration.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_first_last_required = ( 1 == $first_last ) ? false : true; ?>
                        <?php b3_get_settings_field_open( $hide_first_last_required, 'first-last-required' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_first_last_required"><?php esc_html_e( 'Make first and last name required', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_first_last_required" name="b3_first_last_required" value="1" <?php if ( $first_last_required ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to make first and last name required.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    </div>

                    <?php if ( 'open' == $registration_type ) { ?>
                        <?php $hide_redirect_field = ( 1 == $custom_passwords ) ? true : false; ?>
                        <?php b3_get_settings_field_open( $hide_redirect_field, 'redirect' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_redirect_set_password"><?php esc_html_e( 'Redirect after register', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_redirect_set_password" name="b3_redirect_set_password" value="1" <?php if ( $redirect_set_password ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to redirect to the (re)set password page, immediately after registration.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy"><?php esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_privacy" name="b3_privacy" value="1" <?php if ( $privacy ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a privacy checkbox.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php $hide_privacy_settings = ( 1 == $privacy ) ? false : true; ?>
                    <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_text"><?php esc_html_e( 'Privacy text', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_privacy_text" name="b3_privacy_text" placeholder="<?php echo esc_attr( $privacy_page_placeholder ); ?>" value="<?php if ( $privacy_text ) { echo stripslashes( $privacy_text ); } ?>"/>
                            <div class="b3_settings-input-description">
                                <?php esc_html_e( 'Links are allowed.', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_page"><?php esc_html_e( 'Privacy page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <?php $page_args = array( 'post_type' => 'page', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'suppress_filters' => false ); ?>
                            <?php $all_pages = get_posts( $page_args ); ?>
                            <select name="b3_privacy_page" id="b3_privacy_page">
                                <option value=""><?php esc_attr_e( 'Select a page', 'b3-onboarding' ); ?></option>
                                <?php foreach( $all_pages as $page ) { ?>
                                    <?php $selected = ( $privacy_page == $page->ID ) ? ' selected="selected"' : false; ?>
                                    <option value="<?php echo $page->ID; ?>"<?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php b3_get_close(); ?>

                <?php } ?>
            <?php } ?>

            <?php b3_get_submit_button(); ?>

        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
