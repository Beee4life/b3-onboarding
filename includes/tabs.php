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
            case 'pages':
                $content = b3_render_pages_tab();
                break;
            case 'emails':
                $content = b3_render_emails_tab();
                break;
            case 'debug':
                $content = b3_render_debug_tab();
                break;
        }
    
        return $content;
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
                'label' => esc_html__( 'Log In', 'b3-onboarding' ),
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
    
        $send_password_mail = get_option( 'b3_custom_emails' );
        
        $email_boxes = b3_get_email_boxes( $send_password_mail );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can set some default email settings.', 'b3-onboarding' ); ?>
        </p>
        
        <form action="" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">
            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_email_field( $box ); ?>
            <?php } ?>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save all email settings', 'b3-onboarding' ); ?>" />
        </form>

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
        $result = ob_get_clean();
    
        return $result;
    }
