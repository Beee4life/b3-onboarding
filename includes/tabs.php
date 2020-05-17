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
            case 'loginpage':
                $content = b3_render_loginpage_tab();
                break;
            case 'users':
                $content = b3_render_users_tab();
                break;
            case 'support':
                $content = b3_render_support_tab();
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

        $action_links        = get_option( 'b3_disable_action_links' );
        $custom_login_page   = get_option( 'b3_custom_login_page' );
        $custom_emails       = get_option( 'b3_custom_emails' );
        $dashboard_widget    = get_option( 'b3_dashboard_widget' );
        $first_last          = get_option( 'b3_activate_first_last' );
        $first_last_required = get_option( 'b3_first_last_required' );
        $front_end_approval  = get_option( 'b3_front_end_approval' );
        $privacy             = get_option( 'b3_privacy' );
        $recaptcha           = get_option( 'b3_recaptcha' );
        $registration_type   = get_option( 'b3_registration_type' );
        $sidebar_widget      = get_option( 'b3_sidebar_widget' );

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
                                    <input type="radio" id="b3_registration_types" name="b3_registration_type" value="<?php echo $option[ 'value' ]; ?>" <?php if ( $option[ 'value' ] == $registration_type ) { ?>checked="checked"<?php } ?>/> <?php echo $option[ 'label' ]; ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="b3_settings-input b3_settings-input--radio">
                            <?php esc_html_e( 'Registrations are disabled.','b3-onboarding' ); ?>
                        </div>
                    <?php } ?>
                <?php b3_get_close(); ?>

                <?php if ( ! is_multisite() ) { ?>
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
                            <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php if ( $custom_emails ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate your own email styling', 'b3-onboarding' ); ?>
                        </div>
                        <?php if ( $custom_emails ) { ?>
                            <div class="b3_below_input"><?php esc_html_e( 'Styling can be set on the emails tab.', 'b3-onboarding' ); ?></div>
                        <?php } ?>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_custom_login_page"><?php esc_html_e( 'Custom login page', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_custom_login_page" name="b3_custom_login_page" value="1" <?php if ( $custom_login_page ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate custom settings for the login page.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_frontend_approval"><?php esc_html_e( 'Front-end user approval', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_frontend_approval" name="b3_activate_frontend_approval" value="1" <?php if ( $front_end_approval ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate front-end user approval', 'b3-onboarding' ); ?>
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

            <?php b3_get_settings_field_open( 1 ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php if ( $recaptcha ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate reCAPTCHA', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open( 1 ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_privacy"><?php esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_privacy" name="b3_activate_privacy" value="1" <?php if ( $privacy ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a privacy checkbox', 'b3-onboarding' ); ?>
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

        $email_boxes = b3_get_email_boxes();
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
     * Render login page design tab
     *
     * @return false|string
     */
    function b3_render_loginpage_tab() {

        ob_start();
        $background_color    = get_option( 'b3_loginpage_bg_color' );
        $font_family         = get_option( 'b3_loginpage_font_family' );
        $font_size           = get_option( 'b3_loginpage_font_size' );
        $logo                = get_option( 'b3_loginpage_logo' );
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

                <?php
                    echo '<div id="tgm-new-media-settings">';
                    echo '<p><a href="#" class="b3-open-media button button-primary" title="' . esc_attr__( 'Choose logo', 'b3-onboarding' ) . '">' . __( 'Choose logo', 'b3-onboarding' ) . '</a></p>';
                    echo '<p><label for="tgm-new-media-image">' . __( 'Logo url', 'b3-onboarding' ) . '</label> <input type="text" name="b3_loginpage_logo" id="b3_loginpage_logo" value="' . $logo . '" /></p>';
                    echo '</div>';
                ?>

            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_bg_color">Background color</label>
                <?php b3_get_close(); ?>
                <?php // @TODO: n2h colorpicker ?>
                # <input name="b3_loginpage_bg_color" id="b3_loginpage_bg_color" type="text" value="<?php echo $background_color; ?>" placeholder="Example FF0000"> <?php esc_html_e( 'Must be a hex value of 3 or 6 characters', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open( 1 ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_family">Font family</label>
                <?php b3_get_close(); ?>
                <select name="b3_loginpage_font_family" id="b3_loginpage_font_family">
                    <option value=""><?php esc_html_e( 'Select a font', 'b3-onboarding' ); ?></option>
                    <option value="">Arial</option>
                    <option value="">Tahoma</option>
                    <option value="">Verdana</option>
                </select>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open( 1 ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_size">Font size</label>
                <?php b3_get_close(); ?>
                <select name="b3_loginpage_font_size" id="b3_loginpage_font_size">
                    <option value=""><?php esc_html_e( 'Select a font size', 'b3-onboarding' ); ?></option>
                    <option value="10">10</option>
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

        $roles = get_editable_roles();
        asort( $roles );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Users', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'settings_saved' == $_GET[ 'success' ] ) { ?>
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
                    <label><?php esc_html_e( 'Restrict admin access', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <p>
                        <?php _e( 'Which users do <b>not</b> have access to the Wordpress admin ?', 'b3-onboarding' ); ?>
                    </p>
                    <?php
                        $disallowed_roles = [ 'administrator', 'b3_approval', 'b3_activation' ];
                        $stored_roles     = ( is_array( get_option( 'b3_restrict_admin' ) ) ) ? get_option( 'b3_restrict_admin' ) : [ 'subscriber' ];
                        // echo '<pre>'; var_dump($stored_roles); echo '</pre>'; exit;
                        foreach( $roles as $name => $capabilities ) {
                            if ( ! in_array( $name, $disallowed_roles ) ) {
                            ?>
                                <div>
                                    <label for="b3_restrict_<?php echo $name; ?>" class="screen-reader-text"><?php echo $name; ?></label>
                                    <input type="checkbox" id="b3_restrict_<?php echo $name; ?>" name="b3_restrict_admin[]" value="<?php echo $name; ?>" <?php if ( in_array( $name, $stored_roles ) ) { ?>checked="checked"<?php } ?> /> <?php echo $name; ?>
                                </div>
                                <!--<br />-->
                            <?php
                            }
                        }
                    ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label><?php esc_html_e( 'Themed profile', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <p>
                        <?php esc_html_e( 'Which users see a front-end account page ?', 'b3-onboarding' ); ?>
                    </p>
                    <?php
                        $disallowed_roles = [ 'administrator', 'b3_approval', 'b3_activation' ];
                        $stored_roles     = ( is_array( get_option( 'b3_themed_profile' ) ) ) ? get_option( 'b3_themed_profile' ) : [ 'subscriber' ];
                        foreach( $roles as $name => $capabilities ) {
                            if ( ! in_array( $name, $disallowed_roles ) ) {
                            ?>
                                <div>
                                    <label for="b3_themed_profile_<?php echo $name; ?>" class="screen-reader-text"><?php echo $name; ?></label>
                                    <input type="checkbox" id="b3_themed_profile_<?php echo $name; ?>" name="b3_themed_profile[]" value="<?php echo $name; ?>" <?php if ( in_array( $name, $stored_roles ) ) { ?>checked="checked"<?php } ?>/> <?php echo $name; ?>
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
     * Render recaptcha tab
     *
     * @return false|string
     */
    function b3_render_recaptcha_tab() {

        ob_start();
        $public_key = get_option( 'b3_recaptcha_public' );
        $secret_key = get_option( 'b3_recaptcha_secret' );
        ?>
        <h2>
            <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
        </h2>

        <?php if ( isset( $_GET[ 'success' ] ) && 'recaptcha_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Recaptcha saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set the reCaptcha settings.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_public"><?php esc_html_e( 'Public key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_public" name="b3_recaptcha_public" value="<?php if ( $public_key ) { echo $public_key; } ?>" />
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_secret"><?php esc_html_e( 'Secret key', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <input type="text" id="b3_recaptcha_secret" name="b3_recaptcha_secret" value="<?php if ( $secret_key ) { echo $secret_key; } ?>" />
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
     * Render support tab
     *
     * @return false|string
     */
    function b3_render_support_tab() {
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Support', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php echo sprintf( __( 'Please read the <a href="%s">README</a> on Github first and the <a href="%s">Wiki</a>. Those explain a lot already.', 'b3-onboarding' ), esc_url( 'https://github.com/Beee4life/b3-onboarding' ), esc_url( 'https://github.com/Beee4life/b3-onboarding/wiki' ) ); ?>
        </p>
        <p>
            <?php echo sprintf( __( 'If you need support, plese turn to the <a href="%s">issues section</a>.', 'b3-onboarding' ), esc_url( 'https://github.com/Beee4life/b3-onboarding/issues' ) ); ?>
        </p>
        <?php
        $result = ob_get_clean();

        return $result;
    }


    /**
     * Render add-ons tab
     *
     * @return false|string
     */
    function b3_render_integrations_tab() {

        ob_start();
        $public_key = get_option( 'b3_recaptcha_public' );
        $secret_key = get_option( 'b3_recaptcha_secret' );
        $version    = get_option( 'b3_recaptcha_version' );
        $show_recaptcha = false;

        if ( get_option( 'b3_recaptcha' ) ) {
            $show_recaptcha = true;
        }
        ?>
        <h2>
            <?php esc_html_e( 'Integrations', 'b3-onboarding' ); ?>
        </h2>
        <p>
            <?php esc_html_e( 'On this page you can add 3rd party integrations.', 'b3-onboarding' ); ?>
        </p>

        <?php if ( $show_recaptcha ) { ?>
            <h3>
                <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
            </h3>
        <?php } ?>


        <?php if ( isset( $_GET[ 'success' ] ) && 'recaptcha_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3_message">
                <?php esc_html_e( 'Recaptcha settings saved', 'b3-onboarding' ); ?> <span class="b3_message-close"><?php esc_html_e( 'Close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set the reCaptcha settings.', 'b3-onboarding' ); ?>
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

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_recaptcha_version"><?php esc_html_e( 'reCaptcha version', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--text">
                    <select name="b3_recaptcha_version" id="b3_recaptcha_version">
                        <option value="">Choose</option>
                        <option value="2"<?php echo ( 2 == $version ) ? ' selected="selected"' : false; ?>>v2</option>
                        <option value="3"<?php echo ( 3 == $version ) ? ' selected="selected"' : false; ?>>v3</option>
                    </select>
                </div>
            <?php b3_get_close(); ?>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'b3-onboarding' ); ?>" />
        </form>

        <?php $current_user = wp_get_current_user(); ?>
        <?php if ( defined( 'WP_TESTING' ) && 1 == WP_TESTING && $current_user->user_login == 'Beee' ) { ?>

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
                    More integrations
                </h3>
                <p>
                    We understand there might be a need for more integrations.
                    <br />
                    If we'll add more, the ones below are the first ones wer're gonna explore.
                </p>

                <ul class="b3_integrations--list"><!--
                    <?php foreach( $modules as $module ) { ?>
                    --><li class="b3_integrations--list-item b3_integrations--list-item--<?php echo $module['id']; ?>">
                        <div class="b3_integration__container">
                            <div class="b3_integration__image">
                                <img src="<?php echo plugins_url( 'assets/images/', dirname( __FILE__ ) ); ?><?php echo $module['logo']; ?>" alt="<?php echo $module['name']; ?>" />
                            </div>
                            <div class="b3_integration__name">
                                <?php echo $module['name']; ?>
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
