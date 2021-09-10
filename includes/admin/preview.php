<?php
    /**
     * Preview page output
     *
     * @since 2.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_preview">
    <?php
        $message = false;
        $subject = false;
        if ( isset( $_GET[ 'preview' ] ) ) {
            $hide_logo = ( '1' === get_option( 'b3_logo_in_email' ) ) ? false : true;
            $preview   = $_GET[ 'preview' ];
            $user      = get_userdata( get_current_user_id() );

            $lorem_ipsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut non purus magna. Nam quam est, rutrum non consequat sed, finibus quis mi. Vestibulum eget felis risus. Phasellus nibh ligula, tristique non lorem in, blandit <a href="">iaculis</a> enim. In eleifend fermentum scelerisque. Mauris ultrices tortor non massa lobortis, eget molestie nunc fringilla. Integer fermentum ultrices quam vel scelerisque. Nullam non augue laoreet, sagittis orci ac, eleifend massa.
            <br /><br />
            Quisque <a href="">quis nibh</a> gravida, condimentum nibh sed, facilisis ligula. Phasellus placerat, metus a ultricies vulputate, arcu massa ullamcorper enim, id iaculis nisl augue eu dolor. Aliquam vel nisi at lacus ultrices fringilla. In cursus mattis lectus, non ultricies orci vulputate nec. Fusce non vestibulum nulla. Cras libero metus, fermentum sit amet venenatis sit amet, vestibulum vitae lectus. Donec interdum volutpat blandit.
            <br /><br />
            Morbi vehicula metus vestibulum, eleifend arcu quis, rutrum massa. Sed porttitor pellentesque convallis. Suspendisse potenti. Nam dapibus vitae tortor a egestas. Ut at lobortis tortor. Sed tellus sem, pulvinar sit amet posuere non, vulputate vitae mi. Vestibulum ac massa suscipit, placerat risus ut, rutrum turpis. Integer in risus ac turpis dapibus viverra. Nulla facilisi. Nam ut cursus felis. Pellentesque <a href="">congue scelerisque</a> nisl, nec ultricies ex. Vivamus id ex ac dolor porttitor tempus. Maecenas pulvinar porta nunc, in mollis erat egestas et.';

            switch( $preview ) {
                case 'template':
                    $message = $lorem_ipsum;
                    break;
                case 'mu-confirm-user-email':
                    // @TODO: add filter
                    $message = b3_get_wpmu_activate_user_message();
                    $subject = b3_get_wpmu_activate_user_subject();
                    break;
                case 'mu-user-activated':
                    // @TODO: add filter
                    $message = b3_get_wpmu_user_activated_message();
                    $subject = b3_get_wpmu_user_activated_subject();
                    break;
                case 'mu-confirm-user-site-email':
                    // @TODO: add filter
                    $message = b3_get_wpmu_activate_user_blog_message();
                    $subject = b3_get_wpmu_activate_user_blog_subject();
                    break;
                case 'mu-user-site-activated':
                    // @TODO: add filter
                    $message = b3_get_wpmu_user_activated_message();
                    $subject = b3_get_wpmu_user_activated_subject();
                    break;
                case 'mu-new-user-admin':
                    // @TODO: add filter
                    $message = b3_get_new_wpmu_user_message_admin();
                    $subject = b3_get_new_wpmu_user_subject_admin();
                    break;
                case 'account-approved':
                    $message = apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() );
                    $subject = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
                    break;
                case 'account-activated':
                    $message = apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() );
                    $subject = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
                    break;
                case 'account-rejected':
                    $message = apply_filters( 'b3_account_rejected_message', b3_get_account_rejected_message() );
                    $subject = apply_filters( 'b3_account_rejected_subject', b3_get_account_rejected_subject() );
                    break;
                case 'email-activation':
                    $message = apply_filters( 'b3_email_activation_message_user', b3_get_email_activation_message_user() );
                    $subject = apply_filters( 'b3_email_activation_subject_user', b3_get_email_activation_subject_user() );
                    break;
                case 'lostpass':
                    $message = apply_filters( 'b3_lost_password_message', b3_get_lost_password_message() );
                    $subject = apply_filters( 'b3_lost_password_subject', b3_get_lost_password_subject() );
                    break;
                case 'new-user-admin':
                    // @TODO: maybe make new one, don't use b3_get_new_user_message
                    $message = apply_filters( 'b3_new_user_message', b3_get_new_user_message() );
                    $subject = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );
                    break;
                case 'request-access-admin':
                    $message = apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );
                    $subject = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );
                    break;
                case 'request-access-user':
                    $message = apply_filters( 'b3_request_access_message_user', b3_get_request_access_message_user() );
                    $subject = apply_filters( 'b3_request_access_subject_user', b3_get_request_access_subject_user() );
                    break;
                case 'welcome-user':
                    $message = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );
                    $subject = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                    break;
                case 'welcome-user-manual':
                    $message = apply_filters( 'b3_welcome_user_message', b3_get_welcome_user_message() );
                    $subject = apply_filters( 'b3_welcome_user_subject', b3_get_welcome_user_subject() );
                    break;
                case 'styling':
                    $css = apply_filters( 'b3_email_styling', b3_get_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ) );
                    break;
                default:
                    $message = 'OOPS';
            }

            if ( 'styling' !== $_GET[ 'preview' ] ) {
                $subject = strtr( $subject, b3_replace_subject_vars() );
                $message = b3_replace_template_styling( $message );
                $message = strtr( $message, b3_replace_email_vars() );
                $message = htmlspecialchars_decode( stripslashes( $message ) );
            ?>

            <p>
                <?php
                    if ( 'styling' == $_GET[ 'preview' ] ) {
                        esc_html_e( 'These are css definitions which are used.', 'b3-onboarding' );
                    } elseif ( 'template' == $_GET[ 'preview' ] ) {
                        esc_html_e( 'This is what the default email will look like (approximately). Some elements can be overridden by the css loaded in your admin.', 'b3-onboarding' );
                    } else {
                        esc_html_e( 'This is what the email will look like (approximately). Some elements can be overridden by the css loaded in your admin.', 'b3-onboarding' );
                    }
                ?>
            </p>

            <?php if ( false != $subject ) { ?>
                <p>
                    <b><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?>:</b> "<?php echo $subject; ?>"
                </p>
            <?php } ?>

            <?php echo $message; ?>
        <?php } else { ?>
            <p><?php esc_html_e( "These are the email's styling definitions.", 'b3-onboarding' ); ?></p>
            <pre><?php echo $css; ?></pre>
        <?php } ?>
    <?php } ?>
</div>
