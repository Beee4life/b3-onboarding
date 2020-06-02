<div class="b3_preview">
    <?php
        $content = false;
        $subject = false;
        if ( isset( $_GET[ 'preview' ] ) ) {
            $blog_name = get_option( 'blogname' );
            $content   = b3_default_email_content();
            $preview   = $_GET[ 'preview' ];
            $user      = get_userdata( get_current_user_id() );

            $lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non purus magna. Nam quam est, rutrum non consequat sed, finibus quis mi. Vestibulum eget felis risus. Phasellus nibh ligula, tristique non lorem in, blandit iaculis enim. In eleifend fermentum scelerisque. Mauris ultrices tortor non massa lobortis, eget molestie nunc fringilla. Integer fermentum ultrices quam vel scelerisque. Nullam non augue laoreet, sagittis orci ac, eleifend massa.
            <br /><br />
            Quisque quis nibh gravida, condimentum nibh sed, facilisis ligula. Phasellus placerat, metus a ultricies vulputate, arcu massa ullamcorper enim, id iaculis nisl augue eu dolor. Aliquam vel nisi at lacus ultrices fringilla. In cursus mattis lectus, non ultricies orci vulputate nec. Fusce non vestibulum nulla. Cras libero metus, fermentum sit amet venenatis sit amet, vestibulum vitae lectus. Donec interdum volutpat blandit.
            <br /><br />
            Morbi vehicula metus vestibulum, eleifend arcu quis, rutrum massa. Sed porttitor pellentesque convallis. Suspendisse potenti. Nam dapibus vitae tortor a egestas. Ut at lobortis tortor. Sed tellus sem, pulvinar sit amet posuere non, vulputate vitae mi. Vestibulum ac massa suscipit, placerat risus ut, rutrum turpis. Integer in risus ac turpis dapibus viverra. Nulla facilisi. Nam ut cursus felis. Pellentesque congue scelerisque nisl, nec ultricies ex. Vivamus id ex ac dolor porttitor tempus. Maecenas pulvinar porta nunc, in mollis erat egestas et.';

            switch( $preview ) {
                case 'template':
                    // break;
                    $content = str_replace( '%email_message%', $lorem_ipsum, $content );
                    break;
                case 'account-approved':
                    $content = str_replace( '%email_message%', b3_get_account_approved_message(), $content );
                    $subject = b3_get_account_approved_subject();
                    break;
                case 'account-activated':
                    $content = str_replace( '%email_message%', b3_get_account_activated_message_user(), $content );
                    $subject = b3_get_account_activated_subject_user();
                    break;
                case 'account-rejected':
                    $content = str_replace( '%email_message%', b3_get_account_rejected_message(), $content );
                    $subject = b3_get_account_rejected_subject();
                    break;
                case 'email-activation':
                    $content = str_replace( '%email_message%', b3_get_email_activation_message_user(), $content );
                    $subject = b3_get_email_activation_subject_user();
                    break;
                case 'forgotpass':
                    $content = str_replace( '%email_message%', b3_get_password_reset_message(), $content );
                    $subject = b3_get_password_reset_subject();
                    break;
                case 'new-user-admin':
                    // @TODO: maybe make new one, don't use b3_get_new_user_message
                    $content = str_replace( '%email_message%', b3_get_new_user_message(), $content );
                    $subject = b3_get_new_user_subject();
                    break;
                case 'request-access-admin':
                    $content = str_replace( '%email_message%', b3_request_access_message_admin(), $content );
                    $subject = b3_request_access_subject_admin();
                    break;
                case 'request-access-user':
                    $content = str_replace( '%email_message%', b3_request_access_message_user(), $content );
                    $subject = b3_request_access_subject_user();
                    break;
                case 'welcome-user':
                    $content = str_replace( '%email_message%', b3_get_welcome_user_message(), $content );
                    $subject = b3_get_welcome_user_subject();
                    break;
                default:
                    $content = '';

            }
            // echo '<pre>'; var_dump($content); echo '</pre>'; exit;
            $content = htmlspecialchars_decode( $content );
            $content = strtr( $content, b3_replace_email_vars( [] ) );
            // echo '<pre>'; var_dump($content); echo '</pre>'; exit;

        }
    ?>

    <p>
        <?php esc_html_e( 'This is what the email will look like (approximately).', 'b3-onboarding' ); ?>
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
