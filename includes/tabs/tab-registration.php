<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Render registration tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_registration_tab() {
        $custom_passwords             = get_option( 'b3_activate_custom_passwords' );
        $first_last                   = get_option( 'b3_activate_first_last' );
        $first_last_required          = get_option( 'b3_first_last_required' );
        $honeypot                     = get_option( 'b3_honeypot' );
        $privacy                      = get_option( 'b3_privacy' );
        $privacy_page                 = get_option( 'b3_privacy_page_id' );
        $privacy_page_placeholder     = sprintf( esc_attr__( '%s for more info.', 'b3-onboarding' ), sprintf( '<a href="">%s</a>', esc_attr__( 'Click here', 'b3-onboarding' ) ) );
        $privacy_text                 = get_option( 'b3_privacy_text' );
        $recaptcha                    = get_option( 'b3_activate_recaptcha' );
        $redirect_set_password        = get_option( 'b3_redirect_set_password' );
        $registration_type            = get_option( 'b3_registration_type' );
        $registration_with_email_only = get_option( 'b3_register_email_only' );

        ob_start();

        echo sprintf( '<h2>%s</h2>', esc_html__( 'Registration', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=registration" method="post">
            <input name="b3_registration_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-registration-nonce' ); ?>" />
            <?php if ( is_main_site() ) { ?>
                <?php $options = b3_get_registration_types(); ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_registration_type"><?php esc_html_e( 'Registration type', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>

                    <?php $admin_url = is_multisite() ? network_admin_url( 'settings.php' ) : admin_url( 'options-general.php' ); ?>
                    <?php echo sprintf( '<p>%s</p>', sprintf( esc_html__( "This setting 'controls' the Registration type on the %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', $admin_url, esc_html__( 'Settings page', 'b3-onboarding' ) ) ) ); ?>

                    <div class="b3_settings-input b3_settings-input--select">
                        <select name="b3_registration_type" id="b3_registration_type">
                            <?php foreach( $options as $option ) { ?>
                                <option value="<?php echo esc_attr( $option[ 'value' ] ); ?>" <?php selected( $option[ 'value' ], $registration_type ); ?>> <?php echo $option[ 'label' ]; ?>
                            <?php } ?>
                        </select>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( 'none' === $registration_type ) { ?>
                    <?php $filter_message = htmlspecialchars( apply_filters( 'b3_registration_closed_message', false ) ); ?>
                    <?php $closed_message = htmlspecialchars( get_option( 'b3_registration_closed_message' ) ); ?>
                    <?php $default_closed_message = b3_get_registration_closed_message(); ?>
                    <?php $default_closed_message = ( false != $filter_message ) ? $filter_message : $default_closed_message; ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_closed_message"><?php esc_html_e( 'Registration closed message', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <?php if ( false != $filter_message ) { ?>
                                <?php echo sprintf( '<div class="filter-override">%s</div>', esc_html__( 'You have set a filter to override this setting', 'b3-onboarding' ) ); ?>
                            <?php } ?>
                            <input type="text" id="b3_registration_closed_message" name="b3_registration_closed_message" placeholder="<?php echo esc_attr( $default_closed_message ); ?>" value="<?php if ( $closed_message ) { echo htmlspecialchars_decode(stripslashes( $closed_message )); } ?>"/>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                <?php } else { ?>

                    <?php if ( ! is_multisite() ) { ?>
                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_register_email_only"><?php esc_html_e( 'Email address only', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_register_email_only" name="b3_register_email_only" value="1" <?php checked($registration_with_email_only); ?>/>
                                <?php esc_html_e( 'Register with only an email address.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_custom_passwords = ( in_array( $registration_type, [ 'request_access', 'none' ] ) ) ? true : false; ?>
                        <?php b3_get_settings_field_open( false, $hide_custom_passwords, 'custom-passwords' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_custom_passwords"><?php esc_html_e( 'Custom passwords', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_custom_passwords" name="b3_activate_custom_passwords" value="1" <?php checked($custom_passwords); ?>/>
                                <?php esc_html_e( 'Activate custom passwords on the registration form.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_one_time_password = false; ?>
                        <?php b3_get_settings_field_open( false, $hide_one_time_password, 'one-time-password' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_use_one_time_password"><?php esc_html_e( 'One-time password', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_use_one_time_password" name="b3_use_one_time_password" value="1" <?php checked($custom_passwords); ?>/>
                                <?php esc_html_e( 'Use one-time password.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php $hide_extended_fields = ( 1 == $registration_with_email_only ) ? ' hidden' : false; ?>
                    <div class="b3-name-fields<?php echo $hide_extended_fields; ?>">

                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_first_last"><?php esc_html_e( 'First and last name', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_first_last" name="b3_activate_first_last" value="1" <?php checked($first_last); ?>/>
                                <?php esc_html_e( 'Activate the first and last name during registration.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_first_last_required = ( 1 == $first_last ) ? false : true; ?>
                        <?php b3_get_settings_field_open( false, $hide_first_last_required, 'first-last-required' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_first_last_required"><?php esc_html_e( 'Make first and last name required', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_first_last_required" name="b3_first_last_required" value="1" <?php checked($first_last_required); ?>/>
                                <?php esc_html_e( 'Make first and last name required on the registration form.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    </div>

                    <?php if ( 'open' === $registration_type ) { ?>
                        <?php $hide_redirect_field = ( 1 == $custom_passwords ) ? true : false; ?>
                        <?php b3_get_settings_field_open( false, $hide_redirect_field, 'redirect' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_redirect_set_password"><?php esc_html_e( 'Redirect after register', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_redirect_set_password" name="b3_redirect_set_password" value="1" <?php checked($redirect_set_password); ?>/>
                                <?php esc_html_e( 'Redirect to the (re)set password page, immediately after registration.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php checked($recaptcha); ?>/>
                            <?php esc_html_e( 'Activate reCAPTCHA.', 'b3-onboarding' ); ?>
                            <?php $show_note = ( 1 == $recaptcha ) ? false : true; ?>
                            <?php $hide_recaptcha_note = ( 1 == $recaptcha ) ? false : ' hidden'; ?>
                            <?php if ( $show_note ) { ?>
                                <div class="b3_settings-input-description b3_settings-input-description--recaptcha<?php echo $hide_recaptcha_note; ?>">
                                    <?php esc_html_e( 'See tab reCaptcha (after saving)', 'b3-onboarding' ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_honeypot"><?php esc_html_e( 'Honeypot', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_honeypot" name="b3_honeypot" value="1" <?php checked($honeypot); ?>/>
                            <?php esc_html_e( 'Activate a honeypot option.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy"><?php esc_html_e( 'Privacy', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_privacy" name="b3_privacy" value="1" <?php checked($privacy); ?>/>
                            <?php esc_html_e( 'Activate a privacy checkbox.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php $hide_privacy_settings = ( 1 == $privacy ) ? false : true; ?>
                    <?php b3_get_settings_field_open( false, $hide_privacy_settings, 'privacy' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_text"><?php esc_html_e( 'Privacy text', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_privacy_text" name="b3_privacy_text" placeholder="<?php echo esc_attr( $privacy_page_placeholder ); ?>" value="<?php if ( $privacy_text ) { echo stripslashes( $privacy_text ); } ?>"/>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( false, $hide_privacy_settings, 'privacy' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_page_id"><?php esc_html_e( 'Privacy page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <?php
								$page_args = [
									'post_type'        => 'page',
									'posts_per_page'   => -1,
									'orderby'          => 'title',
									'order'            => 'ASC',
									'suppress_filters' => false,
								];
								$all_pages = get_posts( $page_args );
							?>
                            <select name="b3_privacy_page_id" id="b3_privacy_page_id">
                                <option value=""><?php esc_attr_e( 'Select a page', 'b3-onboarding' ); ?></option>
                                <?php foreach( $all_pages as $page ) { ?>
                                    <option value="<?php echo esc_attr( $page->ID ); ?>"<?php echo selected($privacy_page, $page->ID); ?>><?php echo $page->post_title; ?></option>
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
