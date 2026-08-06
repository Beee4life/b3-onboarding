<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render registration tab
    function b3_render_registration_tab() {
        $activate_recaptcha           = get_option( 'b3_activate_recaptcha' );
        $custom_passwords             = get_option( 'b3_activate_custom_passwords' );
        $first_last                   = get_option( 'b3_activate_first_last' );
        $first_last_required          = get_option( 'b3_first_last_required' );
        $activate_honeypot            = get_option( 'b3_activate_honeypot' );
        $activate_privacy_page        = get_option( 'b3_activate_privacy_page' );
        $activate_terms_page          = get_option( 'b3_activate_terms_page' );
        /* translators: click here link */
        $default_accept_placeholder   = sprintf( esc_attr__( '%s for more info.', 'b3-onboarding' ), sprintf( '<a href="">%s</a>', esc_attr__( 'Click here', 'b3-onboarding' ) ) );
        $needs_admin_approval         = get_option( 'b3_needs_admin_approval' );
        $privacy_page                 = get_option( 'b3_privacy_page_id' );
        $privacy_placeholder          = apply_filters( 'b3_privacy_text', $default_accept_placeholder );
        $privacy_text                 = apply_filters( 'b3_privacy_text', '' ) ? '' : get_option( 'b3_privacy_text' );
        $redirect_set_password        = get_option( 'b3_redirect_set_password' );
        $registration_type            = get_option( 'b3_registration_type' );
        $registration_with_email_only = get_option( 'b3_register_email_only' );
        $terms_page                   = get_option( 'b3_terms_page_id' );
        $terms_placeholder            = apply_filters( 'b3_terms_text', $default_accept_placeholder );
        $terms_text                   = apply_filters( 'b3_terms_text', '' ) ? '' : get_option( 'b3_terms_text' );
        $use_magic_link               = get_option( 'b3_use_magic_link' );
        $use_magic_link_password      = get_option( 'b3_use_magic_link_password' );

        ob_start();

        echo sprintf( '<h2>%s</h2>', esc_html__( 'Registration', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=registration" method="post">
            <input name="b3_registration_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-registration-nonce' ) ); ?>" />
            <?php if ( is_main_site() ) { ?>
                <?php $options = b3_get_registration_types(); ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_registration_type"><?php esc_html_e( 'Registration type', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>

                    <?php $admin_url = is_multisite() ? network_admin_url( 'settings.php' ) : admin_url( 'options-general.php' ); ?>
                    <?php // translators: link to settings page ?>
                    <?php echo sprintf( '<p>%s</p>', sprintf( esc_html__( "This setting 'controls' the Registration type on the %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( $admin_url ), esc_html__( 'Settings page', 'b3-onboarding' ) ) ) ); ?>

                    <div class="b3_settings-input b3_settings-input--select">
                        <select name="b3_registration_type" id="b3_registration_type">
                            <?php foreach( $options as $option ) { ?>
                                <option value="<?php echo esc_attr( $option[ 'value' ] ); ?>" <?php selected( $option[ 'value' ], $registration_type ); ?>>
                                    <?php echo esc_attr( $option[ 'label' ] ); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( 'none' === $registration_type ) { ?>
                    <?php $filter_message = htmlspecialchars( apply_filters( 'b3_registration_closed_message', false ) ); ?>
                    <?php $closed_message = htmlspecialchars( get_option( 'b3_registration_closed_message' ) ); ?>
                    <?php $default_closed_message = b3_default_registration_closed_message(); ?>
                    <?php $placeholder_registration_closed = $filter_message ? $filter_message : $default_closed_message; ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_closed_message"><?php esc_html_e( 'Registration closed message', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <?php if ( false != $filter_message ) { ?>
                                <?php echo sprintf( '<div class="filter-override">%s</div>', esc_html__( 'You have set a filter to override this setting', 'b3-onboarding' ) ); ?>
                            <?php } ?>
                            <input type="text" id="b3_registration_closed_message" name="b3_registration_closed_message" placeholder="<?php echo esc_attr( $placeholder_registration_closed ); ?>" value="<?php if ( $closed_message ) { echo wp_kses_post( $closed_message ); } ?>"/>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                <?php } else { ?>

                    <?php if ( in_array( $registration_type, [ 'user', 'all', 'site', 'email_activation' ] ) ) { ?>
                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_needs_admin_approval"><?php esc_html_e( 'Needs admin approval', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                                <div class="b3_settings-input b3_settings-input--checkbox">
                                    <input type="checkbox" id="b3_needs_admin_approval" name="b3_needs_admin_approval" value="1" <?php checked($needs_admin_approval); ?>/>
                                    <?php esc_html_e( 'An administrator must approve each registration.', 'b3-onboarding' ); ?>
                                </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

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

                        <?php $hide_custom_passwords = ( in_array( $registration_type, [ 'none' ] ) ) ? true : false; ?>
                        <?php b3_get_settings_field_open( $hide_custom_passwords, 'custom-passwords' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_custom_passwords"><?php esc_html_e( 'Custom passwords', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_custom_passwords" name="b3_activate_custom_passwords" value="1" <?php checked($custom_passwords); ?>/>
                                <?php esc_html_e( 'Activate custom passwords on the registration form.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    <?php } ?>

                    <?php $hide_extended_fields = $registration_with_email_only ? ' hidden' : false; ?>
                    <div class="b3-name-fields<?php echo esc_attr( $hide_extended_fields ); ?>">
                        <?php b3_get_settings_field_open(); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_activate_first_last"><?php esc_html_e( 'First and last name', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_activate_first_last" name="b3_activate_first_last" value="1" <?php checked($first_last); ?>/>
                                <?php esc_html_e( 'Activate the first and last name during registration.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>

                        <?php $hide_first_last_required = $first_last ? false : true; ?>
                        <?php b3_get_settings_field_open( $hide_first_last_required, 'first-last-required' ); ?>
                            <?php b3_get_label_field_open(); ?>
                                <label for="b3_first_last_required"><?php esc_html_e( 'Make first and last name required', 'b3-onboarding' ); ?></label>
                            <?php b3_get_close(); ?>
                            <div class="b3_settings-input b3_settings-input--checkbox">
                                <input type="checkbox" id="b3_first_last_required" name="b3_first_last_required" value="1" <?php checked($first_last_required); ?>/>
                                <?php esc_html_e( 'Make first and last name required on the registration form.', 'b3-onboarding' ); ?>
                            </div>
                        <?php b3_get_close(); ?>
                    </div>

                    <?php b3_get_settings_field_open( false, 'magic-link' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_use_magic_link"><?php esc_html_e( 'Magic link', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_use_magic_link" name="b3_use_magic_link" value="1" <?php checked($use_magic_link); ?>/>
                            <?php esc_html_e( 'Activate magic link login.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php $hide_magic_password = $use_magic_link ? false : ' hidden'; ?>
                    <?php b3_get_settings_field_open( $hide_magic_password, 'use-both' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_use_magic_link_password"><?php esc_html_e( 'Use password and magic link', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_use_magic_link_password" name="b3_use_magic_link_password" value="1" <?php checked($use_magic_link_password); ?>/>
                            <?php esc_html_e( 'Use both magic link and password login.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php if ( 'open' === $registration_type ) { ?>
                        <?php $hide_redirect_field = $custom_passwords ? true : false; ?>
                        <?php b3_get_settings_field_open( $hide_redirect_field, 'redirect' ); ?>
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
                            <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php checked($activate_recaptcha); ?>/>
                            <?php esc_html_e( 'Activate reCAPTCHA.', 'b3-onboarding' ); ?>
                            <?php $show_note = $activate_recaptcha ? false : true; ?>
                            <?php $hide_recaptcha_note = $activate_recaptcha ? false : ' hidden'; ?>
                            <?php if ( $show_note ) { ?>
                                <div class="b3_settings-input-description b3_settings-input-description--recaptcha<?php echo esc_attr( $hide_recaptcha_note ); ?>">
                                    <?php esc_html_e( 'See tab reCaptcha (after saving)', 'b3-onboarding' ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_honeypot"><?php esc_html_e( 'Honeypot', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_honeypot" name="b3_activate_honeypot" value="1" <?php checked($activate_honeypot); ?>/>
                            <?php esc_html_e( 'Activate a honeypot option.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_terms_page"><?php esc_html_e( 'General terms', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_terms_page" name="b3_activate_terms_page" value="1" <?php checked($activate_terms_page); ?>/>
                            <?php esc_html_e( "Activate an 'Accept terms' checkbox.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php $hide_terms_settings = 1 == $activate_terms_page ? false : true; ?>
                    <?php b3_get_settings_field_open( $hide_terms_settings, 'terms' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_terms_text"><?php esc_html_e( 'Terms text', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_terms_text" name="b3_terms_text" placeholder="<?php echo esc_attr( $terms_placeholder ); ?>" value="<?php if ( $terms_text ) { echo wp_kses_post( $terms_text ); } ?>"<?php if ( apply_filters( 'b3_terms_text', '' ) ) { ?> disabled<?php } ?>/>
                            <?php if ( apply_filters( 'b3_terms_text', '' ) ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( $hide_terms_settings, 'terms' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_terms_page_id"><?php esc_html_e( 'Terms page', 'b3-onboarding' ); ?></label>
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
                            <select name="b3_terms_page_id" id="b3_terms_page_id">
                                <option value=""><?php esc_attr_e( 'Select a page', 'b3-onboarding' ); ?></option>
                                <?php foreach( $all_pages as $page ) { ?>
                                    <option value="<?php echo esc_attr( $page->ID ); ?>"<?php echo selected($terms_page, $page->ID); ?>><?php echo esc_attr( $page->post_title ); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_privacy_page"><?php esc_html_e( 'Privacy', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_privacy_page" name="b3_activate_privacy_page" value="1" <?php checked($activate_privacy_page); ?>/>
                            <?php esc_html_e( "Activate an 'Accept privacy policy' checkbox.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php $hide_privacy_settings = 1 == $activate_privacy_page ? false : true; ?>
                    <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_text"><?php esc_html_e( 'Privacy text', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_privacy_text" name="b3_privacy_text" placeholder="<?php echo esc_attr( $privacy_placeholder ); ?>" value="<?php if ( $privacy_text ) { echo wp_kses_post( $privacy_text ); } ?>"<?php if ( apply_filters( 'b3_privacy_text', '' ) ) { ?> disabled<?php } ?>/>
                            <?php if ( apply_filters( 'b3_privacy_text', '' ) ) { esc_html_e( 'Set by filter', 'b3-onboarding' ); } ?>
                            <?php echo sprintf( '<div class="b3_settings-input-description">%s</div>', esc_html__( 'Links are allowed.','b3-onboarding' ) ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
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
                                    <option value="<?php echo esc_attr( $page->ID ); ?>"<?php echo selected($privacy_page, $page->ID); ?>><?php echo esc_attr( $page->post_title ); ?></option>
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
