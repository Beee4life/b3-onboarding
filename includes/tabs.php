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
            case 'support':
                $content = b3_render_support_tab();
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
    
    function b3_render_settings_tab() {
    
        $custom_emails     = get_option( 'b3_custom_emails' );
        $custom_passwords  = get_option( 'b3_custom_passwords' );
        $dashboard_widget  = get_option( 'b3_dashboard_widget' );
        $privacy           = get_option( 'b3_privacy' );
        $recaptcha         = get_option( 'b3_recaptcha' );
        $registration_type = get_option( 'b3_registration_type' );
        $sidebar_widget    = get_option( 'b3_sidebar_widget' );
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
                    <label for="b3_registration_types"><?php esc_html_e( 'Registration options', 'b3-onboarding' ); ?></label>
                </div>
                
                <?php $options = b3_registration_types(); ?>
                <?php foreach( $options as $option ) { ?>
                    <div class="b3__settings-input b3__settings-input--radio">
                        <input type="radio" id="b3_registration_types" name="b3_registration_type" value="<?php echo $option[ 'value' ]; ?>" <?php if ( $option[ 'value' ] == $registration_type ) { ?>checked="checked"<?php } ?>/> <?php echo $option[ 'label' ]; ?>
                    </div>
                <?php } ?>
            </div>

            <div class="b3__settings-field">
                <div class="b3__settings-label">
                    <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom emails', 'b3-onboarding' ); ?></label>
                </div>
                <div class="b3__settings-input b3__settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php if ( $custom_emails ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate custom emails', 'b3-onboarding' ); ?>
                </div>
            </div>

<!--            <div class="b3__settings-field">-->
<!--                <div class="b3__settings-label">-->
<!--                    <label for="b3_activate_custom_passwords">--><?php //esc_html_e( 'Custom passwords', 'b3-onboarding' ); ?><!--</label>-->
<!--                </div>-->
<!--                <div class="b3__settings-input b3__settings-input--checkbox">-->
<!--                    <input type="checkbox" id="b3_activate_custom_passwords" name="b3_activate_custom_passwords" value="1" --><?php //if ( $custom_passwords ) { ?><!--checked="checked"--><?php //} ?><!--/> --><?php //esc_html_e( 'Check this box to activate custom passwords', 'b3-onboarding' ); ?>
<!--                </div>-->
<!--            </div>-->

<!--            <div class="b3__settings-field">-->
<!--                <div class="b3__settings-label">-->
<!--                    <label for="b3_activate_dashboard_widget">--><?php //esc_html_e( 'Dashboard widget', 'b3-onboarding' ); ?><!--</label>-->
<!--                </div>-->
<!--                <div class="b3__settings-input b3__settings-input--checkbox">-->
<!--                    <input type="checkbox" id="b3_activate_dashboard_widget" name="b3_activate_dashboard_widget" value="1" --><?php //if ( $dashboard_widget ) { ?><!--checked="checked"--><?php //} ?><!--/> --><?php //esc_html_e( 'Check this box to activate the dashboard widget', 'b3-onboarding' ); ?>
<!--                </div>-->
<!--            </div>-->

<!--            <div class="b3__settings-field">-->
<!--                <div class="b3__settings-label">-->
<!--                    <label for="b3_activate_sidebar_widget">--><?php //esc_html_e( 'Sidebar widget', 'b3-onboarding' ); ?><!--</label>-->
<!--                </div>-->
<!--                <div class="b3__settings-input b3__settings-input--checkbox">-->
<!--                    <input type="checkbox" id="b3_activate_sidebar_widget" name="b3_activate_sidebar_widget" value="1" --><?php //if ( $sidebar_widget ) { ?><!--checked="checked"--><?php //} ?><!--/> --><?php //esc_html_e( 'Check this box to activate the sidebar widget', 'b3-onboarding' ); ?>
<!--                </div>-->
<!--            </div>-->

<!--            <div class="b3__settings-field">-->
<!--                <div class="b3__settings-label">-->
<!--                    <label for="b3_activate_recaptcha">--><?php //esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?><!--</label>-->
<!--                </div>-->
<!--                <div class="b3__settings-input b3__settings-input--checkbox">-->
<!--                    <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" --><?php //if ( $recaptcha ) { ?><!--checked="checked"--><?php //} ?><!--/> --><?php //esc_html_e( 'Check this box to activate reCAPTCHA', 'b3-onboarding' ); ?>
<!--                </div>-->
<!--            </div>-->

<!--            <div class="b3__settings-field">-->
<!--                <div class="b3__settings-label">-->
<!--                    <label for="b3_activate_privacy">--><?php //esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?><!--</label>-->
<!--                </div>-->
<!--                <div class="b3__settings-input b3__settings-input--checkbox">-->
<!--                    <input type="checkbox" id="b3_activate_privacy" name="b3_activate_privacy" value="1" --><?php //if ( $privacy ) { ?><!--checked="checked"--><?php //} ?><!--/> --><?php //esc_html_e( 'Check this box to activate a privacy checkbox', 'b3-onboarding' ); ?>
<!--                </div>-->
<!--            </div>-->

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'b3-onboarding' ); ?>" />
        </form>
        <?php
        $result = ob_get_clean();
    
        return $result;
    }
    
    function b3_render_pages_tab() {
        
        // get stored pages
        $b3_pages = array(
            array(
                'id' => 'register_page',
                'label' => esc_html__( 'Register', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_register_page_id' ),
            ),
            array(
                'id' => 'login_page',
                'label' => esc_html__( 'Log In', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_login_page_id' ),
            ),
            array(
                'id' => 'forgotpass_page',
                'label' => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_forgotpass_page_id' ),
            ),
            array(
                'id' => 'resetpass_page',
                'label' => esc_html__( 'Reset password', 'b3-onboarding' ),
                'page_id' => get_option( 'b3_resetpass_page_id' ),
            ),
        );
        
        // get all pages
        $all_pages = get_posts( array(
            'post_type'      => 'page',
            'post_status'    => [ 'publish', 'pending', 'draft' ],
            'posts_per_page' => -1,
        ) );
        
        ob_start();
        ?>
        <form action="" method="post">
            <input name="b3_pages_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-pages-nonce' ); ?>" />
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
            <input type="submit" class="button button-primary" name="" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>">
        </form>
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_emails_tab() {
    
        $send_password_mail = get_option( 'b3_custom_passwords' );
        
        $email_boxes = b3_get_email_boxes( $send_password_mail );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>
    
        <?php if ( isset( $_GET[ 'success' ] ) && 'emails_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3__message">
                <?php esc_html_e( 'Email settings saved', 'b3-onboarding' ); ?>
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
    
    function b3_render_recaptcha_tab() {
        
        ob_start();
        $public_key = get_option( 'b3_recaptcha_public' );
        $secret_key = get_option( 'b3_recaptcha_secret' );
        ?>
        <h2>
            <?php esc_html_e( 'Recaptcha', 'b3-onboarding' ); ?>
        </h2>
    
        <?php if ( isset( $_GET[ 'success' ] ) && 'recaptcha_saved' == $_GET[ 'success' ] ) { ?>
            <p class="b3__message">
                <?php esc_html_e( 'Recaptcha saved', 'b3-onboarding' ); ?>
            </p>
        <?php } ?>

        <p>
            <?php esc_html_e( 'Here you can set the reCaptcha settings.', 'b3-onboarding' ); ?>
        </p>

        <form name="" class="" action="" method="post">
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
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'b3-onboarding' ); ?>" />
        </form>
    
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
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
    
    function b3_render_addons_tab() {
    
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Add-ons', 'b3-onboarding' ); ?>
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
        ?>
        <h2>
            <?php esc_html_e( 'Debug info', 'b3-onboarding' ); ?>
        </h2>

        <b>Server info</b>
        <ul>
            <li>Operating system: <?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></li>
            <li>PHP : <?php echo phpversion(); ?></li>
            <li>Server IP: <?php echo $_SERVER[ 'SERVER_ADDR' ]; ?></li>
            <li>Scheme: <?php echo $_SERVER[ 'REQUEST_SCHEME' ]; ?></li>
            <li>Home path: <?php echo get_home_path(); ?></li>
        </ul>

        <b>WP info</b>
        <ul>
            <li>WP version: <?php echo get_bloginfo( 'version' ); ?></li>
            <li>Home url: <?php echo get_home_url(); ?></li>
            <li>Admin email: <?php echo get_bloginfo( 'admin_email' ); ?></li>
            <li>Blog public: <?php echo get_option( 'blog_public' ); ?></li>
            <li>Users can register: <?php echo get_option( 'users_can_register' ); ?></li>
            <li>Page on front: <?php echo get_option( 'page_on_front' ); ?></li>
            <li>Charset: <?php echo get_bloginfo( 'charset' ); ?></li>
            <li>Text direction: <?php echo get_bloginfo( 'text_direction' ); ?></li>
            <li>Language: <?php echo get_bloginfo( 'language' ); ?></li>
        </ul>
        
        <b>WP info</b>
        <ul>
            <li>Current theme: <?php echo get_option( 'current_theme' ); ?></li>
            <li>Stylesheet: <?php echo get_option( 'stylesheet' ); ?></li>
            <li>Template: <?php echo get_option( 'template' ); ?></li>
        </ul>

        <b>Active plugins</b>
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

        <b>Inactive plugins</b>
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
            <b>WPML</b>
            <ul>
                <li>WPLANG: <?php echo get_option( 'WPLANG' ); ?></li>
                <li>WPML Version: <?php echo get_option( 'WPML_Plugin_verion' ); ?></li>
            </ul>
        <?php } ?>

        <?php
        // @TODO: download info as json
        $result = ob_get_clean();
    
        return $result;
    }
