<div class="b3_preview">
    <?php
        $content = false;
        $subject = false;
        if ( isset( $_GET[ 'preview' ] ) ) {
            $blog_name = get_option( 'blogname' );
            $content   = b3_default_email_content();
            $preview   = $_GET[ 'preview' ];
            $user      = get_userdata( get_current_user_id() );
            
            switch( $preview ) {
                case 'template':
                    break;
                case 'request-access-admin':
                    $content = str_replace( '%email_message%', b3_request_access_message_admin(), $content );
                    $subject = b3_request_access_subject_admin();
                    break;
                case 'request-access-user':
                    $content = str_replace( '%email_message%', b3_request_access_message_user(), $content );
                    $subject = b3_request_access_subject_user();
                    break;
                case 'account-approved':
                    $content = str_replace( '%email_message%', b3_request_access_message_user(), $content );
                    $subject = b3_request_access_subject_user();
                    break;
                case 'email-activation':
                    $content = str_replace( '%email_message%', b3_get_email_activation_message( $blog_name, false ), $content );
                    $subject = b3_get_email_activation_subject( $blog_name );
                    break;
                case 'account-activated':
                    $content = str_replace( '%email_message%', b3_get_account_activated_message(), $content );
                    $subject = b3_get_account_activated_subject();
                    break;
                case 'new-user-admin':
                    $content = str_replace( '%email_message%', b3_get_new_user_message( $blog_name, $user ), $content );
                    $subject = b3_get_new_user_subject( $blog_name );
                    break;
                case 'welcome-user':
                    $content = str_replace( '%email_message%', b3_get_new_user_message( $blog_name, $user ), $content );
                    $subject = b3_get_new_user_subject( $blog_name );
                    break;
                case 'forgotpass':
                    $content = str_replace( '%email_message%', b3_default_forgot_password_message( 'key', '%user_login%' ), $content );
                    $subject = b3_default_forgot_password_subject();
                    break;
                default:
                    $content = '';
                
            }
            $content = strtr( $content, b3_replace_email_vars( [] ) );
            
        }
    ?>

    <p>
        This is what the email will look like (approximately).
    </p>

    <?php if ( false != $subject ) { ?>
        <p>
            <b><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?>:</b> "<?php echo $subject; ?>"
        </p>
    <?php } ?>

    <style type="text/css">
        <?php echo include( 'default-email-styling.css' ); // @TODO: use custom css if entered ?>
    </style>
    
    <?php echo $content; ?>
    
</div>
