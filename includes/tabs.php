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
            case 'recaptcha':
                $content = b3_render_recaptcha_tab();
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
        <h2>Main</h2>
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
                'label' => __( 'Register', 'b3-user-register' ),
                'page_id' => get_option( 'b3_register_id' ),
            ),
            array(
                'id' => 'login',
                'label' => __( 'Login', 'b3-user-register' ),
                'page_id' => get_option( 'b3_login_id' ),
            ),
            array(
                'id' => 'forgotpass',
                'label' => __( 'Forgot password', 'b3-user-register' ),
                'page_id' => get_option( 'b3_forgotpass_id' ),
            ),
            array(
                'id' => 'resetpass',
                'label' => __( 'Reset password', 'b3-user-register' ),
                'page_id' => get_option( 'b3_resetpass_id' ),
            ),
            // array(
            //     'id' => 'account',
            //     'label' => __( 'Account', 'b3-user-register' ),
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
        <h2>Pages</h2>
        <p>XXX</p>
        <form name="" class="" method="post" action="">
            <input type="hidden" name="b3_pages_nonce" value="<?php echo wp_create_nonce( 'b3-pages-nonce' ); ?>">
            <?php foreach( $b3_pages as $page ) { ?>
                <div class="b3__select-page">
                    <div class="b3__select-page__label">
                        <label for="b3_<?php echo $page[ 'id' ]; ?>"><?php echo $page[ 'label' ]; ?></label>
                    </div>
    
                    <div class="b3__select-page__selector">
                        <select name="b3_<?php echo $page[ 'id' ]; ?>_id" id="b3_<?php echo $page[ 'id' ]; ?>">
                            <option value=""> <?php _e( "Select a page", "b3-user-regiser" ); ?></option>
                            <?php foreach( $all_pages as $active_page ) { ?>
                                <option value="<?php echo $active_page->ID; ?>"<?php echo ( $active_page->ID == $page[ 'page_id' ] ) ? ' selected' : false; ?>> <?php echo $active_page->post_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <input type="submit" class="button button-primary" name="" value="<?php _e( 'Submit', 'b3-user-register' ); ?>">
        </form>
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_settings_tab() {
        
        ob_start();
        ?>
        <h2>Settings</h2>
        <form name="" class="" action="" method="post">
            <p>
                Bla Bla Bla
            </p>

            <input name="b3_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-settings-nonce' ); ?>" />

            <h3>Custom passwords</h3>
            <div>
                <label for="b3-login-custom-passwords" class="screen-reader-text">Custom passwords</label>
                <input type="checkbox" id="b3-login-custom-passwords" name="b3-login-custom-passwords" value="<?php _e( '1', 'b3-login' ); ?>" /> Activate custom passwords
            </div>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
        </form>
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_recaptcha_tab() {
        
        ob_start();
        $site_key   = get_option( 'b3-login-recaptcha-site-key' );
        $secret_key = get_option( 'b3-login-recaptcha-site-key' );
        ?>
        <h2>Recaptcha</h2>

        <form name="" class="" action="" method="post">
            <p>
                Set the reCaptcha keys here.
            </p>

            <input name="b3_recaptcha_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-recaptcha-nonce' ); ?>" />

            <h3>Public key</h3>
            <div>
                <label for="b3-login-recaptcha-site-key" class="screen-reader-text">Site key</label>
                <input type="text" id="b3-login-recaptcha-site-key" name="b3-login-recaptcha-site-key" value="<?php echo $site_key; ?>" />
            </div>

            <h3>Private key</h3>
            <div>
                <label for="b3-login-recaptcha-secret-key" class="screen-reader-text">Private key</label>
                <input type="text" id="b3-login-recaptcha-secret-key" name="b3-login-recaptcha-secret-key" value="<?php echo $secret_key; ?>" />
            </div>

            <br />
            <input type="submit" class="button button-primary" value="<?php esc_html_e( 'Save options', 'adf-core' ); ?>" />
        </form>
    
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
    
    function b3_render_debug_tab() {
    
        ob_start();
        
        // get OS
        // get ip
        // get wp version
        // get theme
        // get active plugins
        ?>
        <h2>Debug info</h2>
        <p>Operating system: <?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></p>
        <p>PHP : <?php echo phpversion(); ?></p>
        <p>Server IP: <?php echo $_SERVER[ 'SERVER_ADDR' ]; ?></p>
        <p>Scheme: <?php echo $_SERVER[ 'REQUEST_SCHEME' ]; ?></p>

        <p>Home path: <?php echo get_home_path(); ?></p>
        <p>Home url: <?php echo get_home_url(); ?></p>
        
        <p>Admin email: <?php echo get_bloginfo( 'admin_email' ); ?></p>
        <p>Blog public: <?php echo get_option( 'blog_public' ); ?></p>
        <p>Users can register: <?php echo get_option( 'users_can_register' ); ?></p>
        <p>Page on front: <?php echo get_option( 'page_on_front' ); ?></p>
        <p>Charset: <?php echo get_bloginfo( 'charset' ); ?></p>
        <p>Text direction: <?php echo get_bloginfo( 'text_direction' ); ?></p>
        <p>Language: <?php echo get_bloginfo( 'language' ); ?></p>
        
        <p>Current theme: <?php echo get_option( 'current_theme' ); ?></p>
        <p>Stylesheet: <?php echo get_option( 'stylesheet' ); ?></p>
        <p>Template: <?php echo get_option( 'template' ); ?></p>

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
