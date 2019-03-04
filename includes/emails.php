<?php
    
    /**
     * Render email settings field
     *
     * @param bool $box
     *
     * @return false|mixed|string
     */
    function b3_render_email_settings_field( $box = false ) {
        
        if ( false != $box ) {
            
            $basic_output = b3_basic_email_settings_field( $box );
            $basic_output = str_replace( '##FOLDOUTCONTENT##', b3_foldout_content( $box ), $basic_output );
            return $basic_output;
        }
        
        return '<h4>Oops, no content yet...</h4>';
    }
    
    
    /**
     * Content for an email settings field
     *
     * @param bool $box
     *
     * @return false|string
     */
    function b3_basic_email_settings_field( $box = false ) {
    
        ob_start();
        if ( ( ! empty( $box[ 'id' ] ) ) && ( ! empty( $box[ 'title' ] ) ) ) {
        ?>
        <div class="metabox-handler">
            <div class="postbox" id="">
                <div class="handlediv" title="">
                    <i class="dashicons dashicons-plus"></i>
                </div>
                <div class="b3__foldout--header foldout__toggle<?php echo ( isset( $box[ 'id' ] ) && 'xrequest_access' == $box[ 'id' ] ) ? ' open' : false; ?>">
                    <?php echo ( isset( $box[ 'title' ] ) ) ? $box[ 'title' ] : 'Settings'; ?>
                </div>
                
                <div class="b3__inside <?php echo ( isset( $box[ 'id' ] ) && 'xrequest_access' == $box[ 'id' ] ) ? 'x' : false; ?>foldout__content <?php echo ( isset( $box[ 'id' ] ) && 'xrequest_access' == $box[ 'id' ] ) ? 'x' : false; ?>hidden">
                    ##FOLDOUTCONTENT##
                </div>
            </div>
        </div>
        <?php
        }
        $output = ob_get_clean();
        
        return $output;
    }
    
    
    /**
     * Load fold out content
     *
     * @param bool $box
     *
     * @return bool|false|string
     */
    function b3_foldout_content( $box = false ) {
        
        if ( false != $box ) {
    
            ob_start();
            $output = '';
            switch ( $box[ 'id' ] ) {
                case 'email_settings':
                    include( 'emails/email-settings.php' );
                    $output = ob_get_clean();
                    break;
                case 'welcome_email_user':
                    include( 'emails/welcome-email-user.php' );
                    $output = ob_get_clean();
                    break;
                case 'new_user_admin':
                    include( 'emails/new-user-admin.php' );
                    $output = ob_get_clean();
                    break;
                case 'request_access':
                    include( 'emails/request-access.php' );
                    $output = ob_get_clean();
                    break;
                case 'forgot_password':
                    include( 'emails/forgot-password.php' );
                    $output = ob_get_clean();
                    break;
                case 'password_changed':
                    include( 'emails/password-changed.php' );
                    $output = ob_get_clean();
                    break;
                case 'visitor_register':
                    include( 'emails/ms-visitor-register.php' );
                    $output = ob_get_clean();
                    break;
                // Multisite specific
                case 'visitor_register_site':
                    include( 'emails/ms-visitor-register-site.php' );
                    $output = ob_get_clean();
                    break;
                case 'user_registered_site':
                    include( 'emails/ms-user-new-site.php' );
                    $output = ob_get_clean();
                    break;
                case 'user_deleted_site':
                    include( 'emails/ms-user-delete-site.php' );
                    $output = ob_get_clean();
                    break;
                // Email styling
                case 'email_styling':
                    include( 'emails/email-styling.php' );
                    $output = ob_get_clean();
                    break;
                case 'email_template':
                    include( 'emails/email-template.php' );
                    $output = ob_get_clean();
                    break;
                default:
                    $output = ob_get_clean();
            }
            
            return $output;
    
        }
        
        return false;
    }
