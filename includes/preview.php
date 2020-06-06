<div class="b3_preview">
    <?php
        $content = false;
        $subject = false;
        if ( isset( $_GET[ 'preview' ] ) ) {
            $hide_logo      = ( '1' === get_option( 'b3_logo_in_email' ) ) ? false : true;
            $content        = b3_default_email_content( $hide_logo ); // @TODO: remove, if apply_filters doens't break anything
            $content        = apply_filters( 'b3_template', b3_get_email_template( $hide_logo ) );
            $email_template = get_option( 'b3_template', false );
            $link_color     = apply_filters( 'b3_email_link_color', get_option( 'b3_email_link_color', false ) );
            $preview        = $_GET[ 'preview' ];
            $user           = get_userdata( get_current_user_id() );

            $lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non purus magna. Nam quam est, rutrum non consequat sed, finibus quis mi. Vestibulum eget felis risus. Phasellus nibh ligula, tristique non lorem in, blandit <a href="">iaculis</a> enim. In eleifend fermentum scelerisque. Mauris ultrices tortor non massa lobortis, eget molestie nunc fringilla. Integer fermentum ultrices quam vel scelerisque. Nullam non augue laoreet, sagittis orci ac, eleifend massa.
            <br /><br />
            Quisque <a href="">quis nibh</a> gravida, condimentum nibh sed, facilisis ligula. Phasellus placerat, metus a ultricies vulputate, arcu massa ullamcorper enim, id iaculis nisl augue eu dolor. Aliquam vel nisi at lacus ultrices fringilla. In cursus mattis lectus, non ultricies orci vulputate nec. Fusce non vestibulum nulla. Cras libero metus, fermentum sit amet venenatis sit amet, vestibulum vitae lectus. Donec interdum volutpat blandit.
            <br /><br />
            Morbi vehicula metus vestibulum, eleifend arcu quis, rutrum massa. Sed porttitor pellentesque convallis. Suspendisse potenti. Nam dapibus vitae tortor a egestas. Ut at lobortis tortor. Sed tellus sem, pulvinar sit amet posuere non, vulputate vitae mi. Vestibulum ac massa suscipit, placerat risus ut, rutrum turpis. Integer in risus ac turpis dapibus viverra. Nulla facilisi. Nam ut cursus felis. Pellentesque <a href="">congue scelerisque</a> nisl, nec ultricies ex. Vivamus id ex ac dolor porttitor tempus. Maecenas pulvinar porta nunc, in mollis erat egestas et.';

            switch( $preview ) {
                case 'template':
                    // break;
                    $content = str_replace( '%email_message%', $lorem_ipsum, $content );
                    break;
                case 'account-approved':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() ), $content );
                    $subject = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
                    break;
                case 'account-activated':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() ), $content );
                    $subject = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
                    break;
                case 'account-rejected':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_account_rejected_message', b3_get_account_rejected_message() ), $content );
                    $subject = apply_filters( 'b3_account_rejected_subject', b3_get_account_rejected_subject() );
                    break;
                case 'email-activation':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_email_activation_message_user', b3_get_email_activation_message_user() ), $content );
                    $subject = apply_filters( 'b3_email_activation_subject_user', b3_get_email_activation_subject_user() );
                    break;
                case 'forgotpass':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_password_reset_message', b3_get_password_reset_message() ), $content );
                    $subject = apply_filters( 'b3_password_reset_subject', b3_get_password_reset_subject() );
                    break;
                case 'new-user-admin':
                    // @TODO: maybe make new one, don't use b3_get_new_user_message
                    $content = str_replace( '%email_message%', apply_filters( 'b3_new_user_message', b3_get_new_user_message() ), $content );
                    $subject = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );
                    break;
                case 'request-access-admin':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() ), $content );
                    $subject = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );
                    break;
                case 'request-access-user':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_request_access_message_user', b3_get_request_access_message_user() ), $content );
                    $subject = apply_filters( 'b3_request_access_subject_user', b3_get_request_access_subject_user() );
                    break;
                case 'welcome-user':
                    $content = str_replace( '%email_message%', apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() ), $content );
                    $subject = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                    break;
                default:
                    $content = '';

            }
            $content = htmlspecialchars_decode( $content );
            $content = strtr( $content, b3_replace_email_vars() );

        }
    ?>

    <p>
        <?php esc_html_e( 'This is what the email will look like (approximately).', 'b3-onboarding' ); ?>
    </p>

    <?php if ( 'template' == $preview && ! empty( $email_template ) ) { ?>
        <p>
            <?php esc_html_e( 'The default template is loaded, not your own (if defined). This due to double html/body tags.', 'b3-onboarding' ); ?>
        </p>
    <?php } ?>

    <?php if ( false != $subject ) { ?>
        <p>
            <b><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?>:</b> "<?php echo $subject; ?>"
        </p>
    <?php } ?>

    <style type="text/css">
        <?php echo apply_filters( 'b3_email_styling', b3_get_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ) ); ?>
    </style>

    <?php echo $content; ?>

</div>
