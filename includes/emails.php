<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Render email settings field with fold out
     *
     * @since 1.0.6
     *
     * @param bool $box
     *
     * @return false|mixed|string
     */
    function b3_render_email_settings_field( $box = false ) {
        if ( false != $box ) {
            $basic_output = b3_basic_email_settings_field( $box );
            $basic_output = str_replace( '##FOLDOUTCONTENT##', b3_foldout_content( $box ), $basic_output );

        } else {
            $basic_output = sprintf( '<h4>%s...</h4>', esc_html__( 'Oops, no content yet', 'b3-onboarding' ) );
        }

        return $basic_output;
    }


    /**
     * Content for an email settings field
     *
     * @since 1.0.6
     *
     * @param bool $box
     *
     * @return false|string
     */
    function b3_basic_email_settings_field( $box = [] ) {
        $add_id_fields = [
            'email_styling',
            'email_template',
        ];

        ob_start();
        if ( ( ! empty( $box[ 'id' ] ) ) && ( ! empty( $box[ 'title' ] ) ) ) {
            $hide_field = 'logo' == $box[ 'id' ] && ! get_option( 'b3_logo_in_email' ) ? ' hidden' : '';
            $id_field   = in_array( $box[ 'id' ], $add_id_fields ) ? ' id="' . $box[ 'id' ] . '"' : false;
        ?>
        <div class="metabox-handler metabox-handler--<?php echo $box['id']; ?><?php echo $hide_field; ?>">
            <div class="b3__postbox">
                <div class="b3_foldout--header foldout__toggle"<?php echo $id_field; ?>>
                    <?php echo ( isset( $box[ 'title' ] ) ) ? $box[ 'title' ] : 'Settings'; ?>
                    <i class="dashicons dashicons-plus"></i>
                </div>

                <div class="b3__inside foldout__content">
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
     * @since 1.0.6
     *
     * @param bool $box
     *
     * @return bool|false|string
     */
    function b3_foldout_content( $box = [] ) {

        if ( ! empty( $box ) ) {

            ob_start();
            switch( $box[ 'id' ] ) {
                case 'email_settings':
                    include 'emails/email-settings.php';
                    break;
                case 'welcome_email_user':
                    include 'emails/welcome-email-user.php';
                    break;
                case 'new_user_admin':
                    include 'emails/new-user-admin.php';
                    break;
                case 'request_access_user':
                    include 'emails/request-access-user.php';
                    break;
                case 'request_access_admin':
                    include 'emails/request-access-admin.php';
                    break;
                case 'email_activation':
                    include 'emails/email-activation.php';
                    break;
                case 'account_activated':
                    include 'emails/account-activated.php';
                    break;
                case 'account_approved':
                    include 'emails/account-approved.php';
                    break;
                case 'account_rejected':
                    include 'emails/account-rejected.php';
                    break;
                case 'logo':
                    include 'emails/logo.php';
                    break;
                case 'lost_password':
                    include 'emails/lost-password.php';
                    break;
                case 'welcome_user':
                    include 'emails/welcome-user.php';
                    break;
                case 'welcome_user_manual':
                    include 'emails/welcome-user-manual.php';
                    break;
                // Multisite specific
                case 'confirm_user_email':
                    include 'emails/ms-confirm-user-email.php';
                    break;
                case 'activated_user_email':
                    include 'emails/ms-activated-user-email.php';
                    break;
                case 'confirm_user_site_email':
                    include 'emails/ms-confirm-user-site-email.php';
                    break;
                case 'activated_user_site_email':
                    include 'emails/ms-activated-user-site-email.php';
                    break;
                case 'new_wpmu_user_admin':
                    include 'emails/ms-new-user-admin.php';
                    break;
                case 'visitor_register':
                    include 'emails/ms-visitor-register.php';
                    break;
                case 'visitor_register_site':
                    include 'emails/ms-visitor-register-site.php';
                    break;
                case 'user_registered_site':
                    include 'emails/ms-user-new-site.php';
                    break;
                case 'user_deleted_site':
                    include 'emails/ms-user-delete-site.php';
                    break;
                // Email styling
                case 'email_styling':
                    include 'emails/email-styling.php';
                    break;
                case 'email_template':
                    include 'emails/email-template.php';
                    break;
                default:
            }
            $output = ob_get_clean();

            return $output;
        }

        return false;
    }
