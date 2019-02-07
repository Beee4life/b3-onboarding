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
        switch ( $tab ) {
            case 'main':
                $content = b3_render_main_tab();
                break;
            case 'settings':
                $content = b3_render_settings_tab();
                break;
            case 'pages':
                $content = b3_render_pages_tab();
                break;
            case 'emails':
                $content = b3_render_emails_tab();
                break;
            case 'recaptcha':
                $content = b3_render_recaptcha_tab();
                break;
            case 'addons':
                $content = b3_render_addons_tab();
                break;
            case 'debug':
                $content = b3_render_debug_tab();
                break;
        }
    
        return $content;
    }
    
    function b3_render_main_tab() {
        
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Main', 'b3-onboarding' ); ?>
        </h2>
        <p>
            <?php esc_html_e( 'Bla bla bla', 'b3-onboarding' ); ?>
        </p>
        <?php
        echo dummy_content();
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_pages_tab() {
        
        // get stored pages
        $b3_pages = array(
            array(
                'id' => 'register',
                'label' => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_id' ),
            ),
            array(
                'id' => 'login',
                'label' => esc_html__( 'Login', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_id' ),
            ),
            array(
                'id' => 'forgotpass',
                'label' => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_forgotpass_id' ),
            ),
            array(
                'id' => 'resetpass',
                'label' => esc_html__( 'Reset password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_resetpass_id' ),
            ),
            // array(
            //     'id' => 'account',
            //     'label' => esc_html__( 'Account', 'b3-onboarding' ),
            //     'page_id' => get_option( 'b3_account' ),
            // ),
        );
        
        // get all pages
        $all_pages = get_posts( array(
            'post_type'      => 'page',
            'post_status'    => [ 'publish', 'pending', 'draft' ],
            'posts_per_page' => -1,
        ) );
        
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Pages', 'b3-onboarding' ); ?>
        </h2>
    
        <?php if ( isset( $_GET[ 'success' ] ) && 'pages_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3__message">
                <?php esc_html_e( 'Pages saved', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( "Here you can set which pages are assigned for the various 'actions'.", "b3-onboarding" ); ?>
        </p>
        
        <?php foreach( $b3_pages as $page ) { ?>
            <div class="b3__select-page">
                <div class="b3__select-page__label">
                    <label for="b3_<?php echo $page[ 'id' ]; ?>"><?php echo $page[ 'label' ]; ?></label>
                </div>

                <div class="b3__select-page__selector">
                    <select name="b3_<?php echo $page[ 'id' ]; ?>_id" id="b3_<?php echo $page[ 'id' ]; ?>">
                        <option value=""> <?php esc_html_e( "Select a page", "b3-user-regiser" ); ?></option>
                        <?php foreach( $all_pages as $active_page ) { ?>
                            <?php $selected = ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>
                            <option value="<?php echo $active_page->ID; ?>"<?php echo $selected; ?>> <?php echo $active_page->post_title; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php } ?>
        <input type="submit" class="button button-primary" name="" value="<?php esc_html_e( 'Submit', 'b3-onboarding' ); ?>">
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_settings_tab() {
        
        $custom_emails    = get_option( 'b3_custom_emails' );
        $custom_passwords = get_option( 'b3_custom_passwords' );
        $dashboard_widget = get_option( 'b3_dashboard_widget' );
        $privacy          = get_option( 'b3_privacy' );
        $recaptcha        = get_option( 'b3_recaptcha' );
        $sidebar_widget   = get_option( 'b3_sidebar_widget' );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Settings', 'b3-onboarding' ); ?>
        </h2>
    
        <?php if ( isset( $_GET[ 'success' ] ) && 'settings_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3__message">
                <?php esc_html_e( 'Settings saved', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set some global settings for the plugin.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <input name="b3_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-settings-nonce' ); ?>" />

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom emails', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php if ( $custom_emails ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate custom emails', 'b3-onboarding' ); ?>
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_custom_passwords"><?php esc_html_e( 'Custom passwords', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_custom_passwords" name="b3_activate_custom_passwords" value="1" <?php if ( $custom_passwords ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate custom passwords', 'b3-onboarding' ); ?>
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_dashboard_widget"><?php esc_html_e( 'Dashboard widget', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_dashboard_widget" name="b3_activate_dashboard_widget" value="1" <?php if ( $dashboard_widget ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate the dashboard widget', 'b3-onboarding' ); ?>
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_sidebar_widget"><?php esc_html_e( 'Sidebar widget', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_sidebar_widget" name="b3_activate_sidebar_widget" value="1" <?php if ( $sidebar_widget ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate the sidebar widget', 'b3-onboarding' ); ?>
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php if ( $recaptcha ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate reCAPTCHA', 'b3-onboarding' ); ?>
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_privacy"><?php esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_privacy" name="b3_activate_privacy" value="1" <?php if ( $privacy ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a privacy checkbox', 'b3-onboarding' ); ?>
                </div>
            </div>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
        </form>
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_emails_tab() {
    
        $send_password_mail = get_option( 'b3_custom_emails' );
        
        $default_boxes1 = [
            [
                'id'    => 'email_settings',
                'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
            ],
            [
                'id'    => 'welcome_email_user',
                'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
            ],
            [
                'id'    => 'new_user_admin',
                'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
            ],
        ];
        if ( true == $send_password_mail ) {
            $default_boxes1[] = [
                'id'    => 'send_password_mail',
                'title' => esc_html__( 'Send password by email', 'b3-onboarding' ),
            ];
        }
        $default_boxes2 = [
            [
                'id'    => 'forgot_password',
                'title' => esc_html__( 'Forgot password email', 'b3-onboarding' ),
            ],
            [
                'id'    => 'password_changed',
                'title' => esc_html__( 'Reset password email', 'b3-onboarding' ),
            ],
        ];
        $email_boxes = array_merge( $default_boxes1, $default_boxes2 );
        
        if ( ! is_multisite() ) {
            $multisite_boxes = [
                [
                    'id'    => 'email_settings',
                    'title' => esc_html__( 'Email Settings', 'b3-onboarding' ),
                ],
                [
                    'id'    => 'welcome_email_user',
                    'title' => esc_html__( 'Welcome email (user)', 'b3-onboarding' ),
                ],
                [
                    'id'    => 'new_user_admin',
                    'title' => esc_html__( 'New user (admin)', 'b3-onboarding' ),
                ],
            ];
            $email_boxes = array_merge( $email_boxes, $multisite_boxes );
        }
        
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can will be able to set the various emails.', 'b3-onboarding' ); ?>
        </p>
        
        <form action="" method="" name="">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">
            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_settings_field( $box ); ?>
            <?php } ?>
        </form>

        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_recaptcha_tab() {
        
        ob_start();
        $public_key = get_option( 'b3-login-recaptcha-public-key' );
        $secret_key = get_option( 'b3-login-recaptcha-private-key' );
        ?>
        <h2>
            <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
        </h2>
    
        <p>
            <?php esc_html_e( 'Here you can set the reCaptcha settings.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
            <p>
                Set the reCaptcha keys here.
            </p>

            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_recaptcha_public"><?php esc_html_e( 'Public key', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--text">
                    <input type="text" id="b3_recaptcha_public" name="b3_recaptcha_public" value="<?php if ( $public_key ) { echo $public_key; } ?>" />
                </div>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_recaptcha_secret"><?php esc_html_e( 'Secret key', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--text">
                    <input type="text" id="b3_recaptcha_secret" name="b3_recaptcha_secret" value="<?php if ( $secret_key ) { echo $secret_key; } ?>" />
                </div>
            </div>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
        </form>
    
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_addons_tab() {
    
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Add ons', 'b3-onboarding' ); ?>
        </h2>
        
        <p>
            We don't have any add-ons yet... but we do understand there might be a need for them.
            <br />
            We'll look into creating an add-on for the ones below soon.
        </p>
        
        <ul>
            <li>Salesforce</li>
            <li>Mailchimp</li>
        </ul>
        
    
        <?php
        $result = ob_get_clean();
    
        return $result;

    }
    
    
    function b3_render_debug_tab() {
    
        ob_start();
        
        // get wp version
        // get theme
        // get active plugins
        ?>
        <h2>
            <?php esc_html_e( 'Debug info', 'b3-onboarding' ); ?>
        </h2>

        <h3>Server info</h3>
        <p>Operating system: <?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></p>
        <p>PHP : <?php echo phpversion(); ?></p>
        <p>Server IP: <?php echo $_SERVER[ 'SERVER_ADDR' ]; ?></p>
        <p>Scheme: <?php echo $_SERVER[ 'REQUEST_SCHEME' ]; ?></p>
        <p>Home path: <?php echo get_home_path(); ?></p>

        <h3>WP info</h3>
        <p>WP version: <?php echo get_bloginfo( 'version' ); ?></p>
        <p>Home url: <?php echo get_home_url(); ?></p>
        <p>Admin email: <?php echo get_bloginfo( 'admin_email' ); ?></p>
        <p>Blog public: <?php echo get_option( 'blog_public' ); ?></p>
        <p>Users can register: <?php echo get_option( 'users_can_register' ); ?></p>
        <p>Page on front: <?php echo get_option( 'page_on_front' ); ?></p>
        <p>Charset: <?php echo get_bloginfo( 'charset' ); ?></p>
        <p>Text direction: <?php echo get_bloginfo( 'text_direction' ); ?></p>
        <p>Language: <?php echo get_bloginfo( 'language' ); ?></p>
        
        <h3>WP info</h3>
        <p>Current theme: <?php echo get_option( 'current_theme' ); ?></p>
        <p>Stylesheet: <?php echo get_option( 'stylesheet' ); ?></p>
        <p>Template: <?php echo get_option( 'template' ); ?></p>

        <h3>Active plugins</h3>
        <ul>
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    if ( is_plugin_active( $key ) ) {
                        echo '<li>' . $value[ 'Name' ] . '</li>';
                    }
                }
            ?>
        </ul>

        <h3>Inactive plugins</h3>
        <ul>
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    if ( ! is_plugin_active( $key ) ) {
                        echo '<li>' . $value[ 'Name' ] . '</li>';
                    }
                }
            ?>
        </ul>
        <?php if ( class_exists( 'SitePress' ) ) { ?>
            <p>WPLANG: <?php echo get_option( 'WPLANG' ); ?></p>
            <p>WPML Version: <?php echo get_option( 'WPML_Plugin_verion' ); ?></p>
        <?php } ?>

        <?php
        // @TODO: download info as json
        // echo '<pre>'; var_dump($_SERVER); echo '</pre>'; exit;
        $result = ob_get_clean();
    
        return $result;
    }
