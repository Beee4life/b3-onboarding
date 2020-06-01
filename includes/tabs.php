<?php

    /**
     * Get tab content
     *
     * @param $tab
     *
     * @return string
     */
    function b3_render_tab_content( $tab ) {

        $content = '';
        switch( $tab ) {
            case 'settings':
                $content = b3_render_settings_tab();
                break;
            case 'pages':
                $content = b3_render_pages_tab();
                break;
            case 'emails':
                $content = b3_render_emails_tab();
                break;
            case 'registration':
                $content = b3_render_registration_tab();
                break;
            case 'loginpage':
                $content = b3_render_loginpage_tab();
                break;
            case 'users':
                $content = b3_render_users_tab();
                break;
            case 'integrations':
                $content = b3_render_integrations_tab();
                break;
            case 'debug':
                $content = b3_render_debug_tab();
                break;
        }

        return $content;
    }


    /**
     * Render settings tab
     *
     * @return false|string
     */
    function b3_render_settings_tab() {

        $style_default_pages     = get_option( 'b3_style_default_pages' );
        $dashboard_widget        = get_option( 'b3_dashboard_widget' );
        $debug_info              = get_option( 'b3_debug_info' );
        $force_custom_login_page = get_option( 'b3_force_custom_login_page' );
        $sidebar_widget          = get_option( 'b3_sidebar_widget' );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Settings', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'settings_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set various global settings for the plugin.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <input name="b3_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-settings-nonce' ); ?>" />

            <?php if ( is_multisite() && is_main_site() || ! is_multisite() ) { ?>
                <?php if ( ! is_multisite() ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_style_default_pages"><?php esc_html_e( 'Style default pages', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_style_default_pages" name="b3_style_default_pages" value="1" <?php if ( $style_default_pages ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( "Check this box to activate custom settings for WordPress' default login page.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_force_custom_login_page"><?php esc_html_e( 'Force custom login page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_force_custom_login_page" name="b3_force_custom_login_page" value="1" <?php if ( $force_custom_login_page ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( "Check this box to disable WordPress' own pages and force using your custom pages.", 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php if ( current_user_can( 'manage_options' ) ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_debug_info"><?php esc_html_e( 'Activate debug info page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_debug_info" name="b3_debug_info" value="1" <?php if ( $debug_info ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to \'show\' the debug page.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

            <?php } ?>


            <hr />

            <h2>
                <?php esc_html_e( 'Widget settings', 'b3-onboarding' ); ?>
            </h2>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_dashboard_widget"><?php esc_html_e( 'Dashboard widget', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_dashboard_widget" name="b3_activate_dashboard_widget" value="1" <?php if ( $dashboard_widget ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate the dashboard widget', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_sidebar_widget"><?php esc_html_e( 'Sidebar widget', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_sidebar_widget" name="b3_activate_sidebar_widget" value="1" <?php if ( $sidebar_widget ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate the sidebar widget', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'b3-onboarding' ); ?>" />
        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render pages tab
     *
     * @return false|string
     */
    function b3_render_pages_tab() {

        // get stored pages
        $b3_pages = array(
            array(
                'id'      => 'register_page',
                'label'   => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_page_id' ),
            ),
            array(
                'id'      => 'login_page',
                'label'   => esc_html__( 'Log In', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_page_id' ),
            ),
            array(
                'id'      => 'logout_page',
                'label'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_logout_page_id' ),
            ),
            array(
                'id'      => 'forgotpass_page',
                'label'   => esc_html__( 'Forgot Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_forgotpass_page_id' ),
            ),
            array(
                'id'      => 'resetpass_page',
                'label'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_resetpass_page_id' ),
            ),
            array(
                'id'      => 'account_page',
                'label'   => esc_html__( 'Account', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_account_page_id' ),
            ),
        );

        $front_end_approval = array(
            'id'      => 'approval_page',
            'label'   => esc_html__( 'Approval page', 'b3-onboarding' ),
            'page_id' => get_option( 'b3_approval_page_id' ),
        );

        if ( true == get_option( 'b3_front_end_approval' ) ) {
            $b3_pages[] = $front_end_approval;
        }

        // get all pages
        $all_pages = get_posts( array(
            'post_type'      => 'page',
            'post_status'    => [ 'publish', 'pending', 'draft' ],
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );

        ob_start();
        ?>
        <form action="" method="post">
            <input name="b3_pages_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-pages-nonce' ); ?>" />
            <h2>
                <?php esc_html_e( 'Pages', 'b3-onboarding' ); ?>
            </h2>

            <?php if ( isset( $_GET[ 'success' ] ) && 'pages_saved' == $_GET[ 'success' ] ) { ?>
                <p class="b3_message">
                    <?php esc_html_e( 'Pages saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
                </p>
            <?php } ?>

            <p>
                <?php esc_html_e( "Here you can set which pages are assigned for the various 'actions'.", "b3-onboarding" ); ?>
            </p>

            <?php foreach( $b3_pages as $page ) { ?>
                <div class="b3_select-page">
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_<?php echo $page[ 'id' ]; ?>"><?php echo $page[ 'label' ]; ?></label>
                    <?php b3_get_close(); ?>

                    <div class="b3_select-page__selector">
                        <select name="b3_<?php echo $page[ 'id' ]; ?>_id" id="b3_<?php echo $page[ 'id' ]; ?>">
                            <option value=""> <?php esc_html_e( "Select a page", "b3-user-regiser" ); ?></option>
                            <?php foreach( $all_pages as $active_page ) { ?>
                                <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                                <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <?php if ( false != get_option( 'b3_' . $page[ 'id' ] . '_id' ) ) { ?>
                        <div class="b3_select-page__edit">
                            <a href="<?php echo get_edit_post_link( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Edit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                        <div class="b3_select-page__link">
                            <a href="<?php echo get_the_permalink( get_option( 'b3_' . $page[ 'id' ] . '_id' ) ); ?>" target="_blank" rel="noopener" title="<?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Visit', 'b3-onboarding' ); ?>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            <?php } ?>
            <p><small><?php esc_html_e( 'Links open in new window/tab.', 'b3-onboarding' ); ?></small></p>
            <input type="submit" class="button button-primary" name="" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>">
        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render emails tab
     *
     * @return false|string
     */
    function b3_render_emails_tab() {

        $custom_emails = get_option( 'b3_custom_emails' );
        $email_boxes   = b3_get_email_boxes();
        $email_format  = get_option( 'b3_email_format' );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'emails_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Email settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set some default email settings.', 'b3-onboarding' ); ?>
        </p>

        <form action="" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">

            <?php b3_get_settings_field_open(1); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_email_format"><?php esc_html_e( 'Email format', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--radio">
                    <label>
                        <input type="radio" name="b3_email_format" value="html" <?php if ( 'html' == $email_format ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'HTML', 'b3-onboarding' ); ?>
                    <label>
                    <label>
                        <input type="radio" name="b3_email_format" value="text" <?php if ( 'text' == $email_format ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Text', 'b3-onboarding' ); ?>
                    <label>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php if ( $custom_emails ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate your own email styling and template', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_email_settings_field( $box ); ?>
            <?php } ?>

            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save all email settings', 'b3-onboarding' ); ?>" />
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render registration tab
     *
     * @return false|string
     */
    function b3_render_registration_tab() {

        $action_links             = get_option( 'b3_disable_action_links' );
        $first_last               = get_option( 'b3_activate_first_last' );
        $first_last_required      = get_option( 'b3_first_last_required' );
        $privacy                  = get_option( 'b3_privacy' );
        $privacy_page             = get_option( 'b3_privacy_page' );
        $privacy_page_placeholder = __( '<a href="">Click here</a> for more info.', 'b3-onboarding' );
        $privacy_text             = get_option( 'b3_privacy_text' );
        $recaptcha                = get_option( 'b3_recaptcha' );
        $recaptcha_login          = get_option( 'b3_recaptcha_login' );
        $registration_type        = get_option( 'b3_registration_type' );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Registration', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'registration_settings_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Registration settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set the main registration settings.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <input name="b3_registration_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-registration-nonce' ); ?>" />
            <?php if ( is_multisite() && is_main_site() || ! is_multisite() ) { ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_registration_types"><?php esc_html_e( 'Registration options', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <?php if ( is_multisite() && is_main_site() ) { ?>
                        <p>
                            <?php echo sprintf( __( 'These settings are now the global settings and \'control\' the values on the <a href="%s">Network admin</a> page.', 'b3-onboarding' ), network_admin_url( 'settings.php' ) ); ?>
                        </p>
                    <?php } else if ( ! is_multisite() ) { ?>
                        <p>
                            <?php echo sprintf( __( 'These settings are now the global settings and \'control\' the values on the <a href="%s">Settings page</a>.', 'b3-onboarding' ), admin_url( 'options-general.php' ) ); ?>
                        </p>
                    <?php } ?>

                    <?php $options = b3_registration_types(); ?>
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

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_first_last"><?php esc_html_e( 'Activate first and last name', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_first_last" name="b3_activate_first_last" value="1" <?php if ( $first_last ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate first and last name during registration', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( $first_last ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_first_last_required"><?php esc_html_e( 'Make first and last name required', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_first_last_required" name="b3_first_last_required" value="1" <?php if ( $first_last_required ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to make first and last name required', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php if ( $recaptcha ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate reCAPTCHA', 'b3-onboarding' ); ?>
                        <?php if ( 1 == get_option( 'b3_recaptcha' ) ) { ?>
                            <div class="b3_settings-input--description">
                                <?php esc_html_e( 'See tab integrations', 'b3-onboarding' ); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( get_option( 'b3_recaptcha' ) ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_recaptcha_login"><?php esc_html_e( 'Add reCaptcha on login page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_recaptcha_login" name="b3_recaptcha_login" value="1" <?php if ( $recaptcha_login ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to add reCaptcha on the login form.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>


                <?php b3_get_settings_field_open(1); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_privacy"><?php esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_privacy" name="b3_activate_privacy" value="1" <?php if ( $privacy ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a privacy checkbox', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( 1 == get_option( 'b3_privacy' ) ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_text"><?php esc_html_e( 'Privacy text', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_privacy_text" name="b3_privacy_text" placeholder="<?php echo esc_html( $privacy_page_placeholder ); ?>" value="<?php if ( $privacy_text ) { echo $privacy_text; } ?>"/>
                            <div class="b3_settings-input--description">
                                <?php esc_html_e( 'Links are allowed.', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_privacy_page"><?php esc_html_e( 'Privacy page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_privacy_page" name="b3_privacy_page" value="<?php if ( $privacy_page ) { echo $privacy_page; } ?>"/>
                            <div class="b3_settings-input--description">
                                <?php esc_html_e( 'Must be a valid URL (incl. http(s)://)', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php // @TODO: check for filter for MS ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php if ( $action_links ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to hide the action links on (custom) forms', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

            <?php } ?>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render login page design tab
     *
     * @return false|string
     */
    function b3_render_loginpage_tab() {

        $fonts = [
            'Arial',
            'Tahoma',
            'Times New Roman',
            'Verdana',
        ];

        ob_start();
        $background_color = get_option( 'b3_loginpage_bg_color' );
        $font_family      = get_option( 'b3_loginpage_font_family' );
        $font_size        = get_option( 'b3_loginpage_font_size' );
        $logo             = get_option( 'b3_loginpage_logo' );
        $logo_height      = get_option( 'b3_loginpage_logo_height' );
        $logo_width       = get_option( 'b3_loginpage_logo_width' );

        ?>
        <h2>
            <?php esc_html_e( 'Login page', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'loginpage_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Login page settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } elseif ( isset( $_GET[ 'errors' ] ) ) { ?>
            <?php if ( isset( $_GET[ 'errors' ] ) ) { ?>
                <p class="b3_message">
                    <?php esc_html_e( 'Error: hex codes must be 3 or 6 chracters in length', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
                </p>
            <?php } ?>
        <?php } ?>

        <p>
            <?php // @TODO: set if for if custom login page is set ?>
            <?php esc_html_e( 'Here you can style the (default) WordPress login page.', 'b3-onboarding' ); ?>
        </p>

        <form action="" method="post">
            <input name="b3_loginpage_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-loginpage-nonce' ); ?>">

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_logo">LOGO</label>
                <?php b3_get_close(); ?>
                <div id="b3-new-media-settings">
                    <p><a href="#" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>"><?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?></a></p>
                    <p><input type="text" name="b3_loginpage_logo" id="b3_loginpage_logo" value="<?php echo $logo; ?>" /></p>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_logo_width"><?php esc_html_e( 'Logo width', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_logo_width" id="b3_loginpage_logo_width" type="number" value="<?php echo $logo_width; ?>" placeholder=""> <?php esc_html_e( 'Default = 84 px. Max 320 px.', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_logo_height"><?php esc_html_e( 'Logo height', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_logo_height" id="b3_loginpage_logo_height" type="number" value="<?php echo $logo_height; ?>" placeholder=""> <?php esc_html_e( 'Max 150 px.', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_bg_color"><?php esc_html_e( 'Background color', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <?php // @TODO: n2h colorpicker ?>
                <input name="b3_loginpage_bg_color" id="b3_loginpage_bg_color" type="text" value="<?php echo $background_color; ?>" placeholder="FF0000"> <?php esc_html_e( 'Must be a hex value of 3 or 6 characters (without hashtag)', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_size"><?php esc_html_e( 'Font size (in px)', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_font_size" id="b3_loginpage_font_size" type="number" value="<?php echo $font_size; ?>" placeholder=""> <?php esc_html_e( 'Default = 14px', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_family"><?php esc_html_e( 'Font family', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <select name="b3_loginpage_font_family" id="b3_loginpage_font_family">
                    <option value=""><?php esc_html_e( 'Select a font', 'b3-onboarding' ); ?></option>
                    <?php
                        foreach( $fonts as $font ) {
                            $selected = ( $font == $font_family ) ? ' selected="selected"' : false;
                            echo '<option value="' . $font . '"' . $selected . '>' . $font . '</option>';
                        }
                    ?>
                </select>
            <?php b3_get_close(); ?>

            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render emails tab
     *
     * @return false|string
     */
    function b3_render_users_tab() {

        $front_end_approval = get_option( 'b3_front_end_approval' );
        $roles              = get_editable_roles();
        asort( $roles );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Users', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'user_settings_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'This page contains restrictions settings for users.', 'b3-onboarding' ); ?>
        </p>

        <form action="" method="post">
            <input name="b3_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-users-nonce' ); ?>">

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_frontend_approval"><?php esc_html_e( 'Front-end user approval', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_frontend_approval" name="b3_activate_frontend_approval" value="1" <?php if ( $front_end_approval ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate front-end user approval', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>


            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label><?php esc_html_e( 'Restrict admin access', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <?php $hidden_roles = [ 'b3_approval', 'b3_activation' ]; ?>
                    <?php foreach( $hidden_roles as $role ) { ?>
                        <input type="hidden" id="b3_restrict_<?php echo $role; ?>" name="b3_restrict_admin[]" value="<?php echo $role; ?>" />
                    <?php } ?>
                    <p>
                        <?php _e( 'Which user roles do <b>not</b> have access to the Wordpress admin ?', 'b3-onboarding' ); ?>
                    </p>
                    <?php
                        $dont_show_roles = [ 'administrator', 'b3_approval', 'b3_activation' ];
                        $stored_roles     = ( is_array( get_option( 'b3_restrict_admin' ) ) ) ? get_option( 'b3_restrict_admin' ) : [ 'b3_activation', 'b3_approval' ];
                        foreach( $roles as $name => $values ) {
                            if ( ! in_array( $name, $dont_show_roles ) ) {
                            ?>
                                <div>
                                    <label for="b3_restrict_<?php echo $name; ?>" class="screen-reader-text"><?php echo $name; ?></label>
                                    <input type="checkbox" id="b3_restrict_<?php echo $name; ?>" name="b3_restrict_admin[]" value="<?php echo $name; ?>" <?php if ( in_array( $name, $stored_roles ) ) { ?>checked="checked"<?php } ?> /> <?php echo $values[ 'name' ]; ?>
                                </div>
                            <?php
                            }
                        }
                    ?>
                </div>
            <?php b3_get_close(); ?>

            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save all user settings', 'b3-onboarding' ); ?>" />
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render integrations tab
     *
     * @return false|string
     */
    function b3_render_integrations_tab() {

        ob_start();
        $public_key        = get_option( 'b3_recaptcha_public' );
        $recaptcha_version = get_option( 'b3_recaptcha_version' );
        $secret_key        = get_option( 'b3_recaptcha_secret' );
        $show_recaptcha    = ( true == get_option( 'b3_recaptcha' ) ) ? true : false;
        ?>
        <h2>
            <?php esc_html_e( 'Integrations', 'b3-onboarding' ); ?>
        </h2>
        <p>
            <?php esc_html_e( 'On this page you can add 3rd party integrations. Right now we only have a reCaptcha but more can be expected in the future.', 'b3-onboarding' ); ?>
            <br />
            <?php echo sprintf( __( 'Get your (free) reCaptcha keys <a href="%s" target="_blank" rel="noopener">here</a>.', 'b3-onboarding' ), esc_url( 'https://www.google.com/recaptcha/admin#list' ) ); ?>
        </p>

            <h3>
                <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
            </h3>

        <?php if ( isset( $_GET[ 'success' ] ) && 'recaptcha_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Recaptcha settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <?php if ( $show_recaptcha ) { ?>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set the v2 reCaptcha settings, v3 is not working (yet).', 'b3-onboarding' ); ?>
            <br />
            <?php esc_html_e( 'Both keys must be entered, for reCaptcha to work.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

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

            <?php b3_get_settings_field_open(1); ?>
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

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save reCaptcha', 'b3-onboarding' ); ?>" />
        </form>

        <?php if ( defined( 'WP_TESTING' ) && 1 == WP_TESTING ) { ?>

            <?php b3_get_settings_field_open(); ?>
            <?php
                $modules = [
                    [
                        'id'   => 'salesforce',
                        'name' => 'Salesforce',
                        'logo' => 'logo-salesforce.png',
                        'link' => '#',
                    ],
                    [
                        'id'   => 'mailchimp',
                        'name' => 'Mailchimp',
                        'logo' => 'logo-mailchimp.png',
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
                                    src="<?php echo plugins_url( 'assets/images/', dirname( __FILE__ ) ); ?><?php echo $module[ 'logo' ]; ?>"
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


    /**
     * Render debug page
     *
     * @return false|string
     */
    function b3_render_debug_tab() {

        ob_start();
        include( 'debug-info.php' );
        $result = ob_get_clean();
        // @TODO: output $result as json

        return $result;
    }
