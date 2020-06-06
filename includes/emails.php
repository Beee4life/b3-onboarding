<?php

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

            return $basic_output;
        }

        return '<h4>Oops, no content yet...</h4>';
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
    function b3_basic_email_settings_field( $box = false ) {

        ob_start();
        if ( ( ! empty( $box[ 'id' ] ) ) && ( ! empty( $box[ 'title' ] ) ) ) {
        ?>
        <div class="metabox-handler">
            <div class="b3__postbox">
                <div class="b3_foldout--header foldout__toggle">
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
    function b3_foldout_content( $box = false ) {

        if ( false != $box ) {

            ob_start();
            switch( $box[ 'id' ] ) {
                case 'email_settings':
                    include( 'emails/email-settings.php' );
                    break;
                case 'welcome_email_user':
                    include( 'emails/welcome-email-user.php' );
                    break;
                case 'new_user_admin':
                    include( 'emails/new-user-admin.php' );
                    break;
                case 'request_access_user':
                    include( 'emails/request-access-user.php' );
                    break;
                case 'request_access_admin':
                    include( 'emails/request-access-admin.php' );
                    break;
                case 'email_activation':
                    include( 'emails/email-activation.php' );
                    break;
                case 'account_activated':
                    include( 'emails/account-activated.php' );
                    break;
                case 'account_approved':
                    include( 'emails/account-approved.php' );
                    break;
                case 'account_rejected':
                    include( 'emails/account-rejected.php' );
                    break;
                case 'forgot_password':
                    include( 'emails/forgot-password.php' );
                    break;
                case 'password_changed':
                    // @TODO: check this
                    // include( 'emails/password-changed.php' );
                    break;
                case 'visitor_register':
                    include( 'emails/ms-visitor-register.php' );
                    break;
                // Multisite specific
                case 'visitor_register_site':
                    include( 'emails/ms-visitor-register-site.php' );
                    break;
                case 'user_registered_site':
                    include( 'emails/ms-user-new-site.php' );
                    break;
                case 'user_deleted_site':
                    include( 'emails/ms-user-delete-site.php' );
                    break;
                // Email styling
                case 'email_styling':
                    include( 'emails/email-styling.php' );
                    break;
                case 'email_template':
                    include( 'emails/email-template.php' );
                    break;
                default:
            }
            $output = ob_get_clean();

            return $output;

        }

        return false;
    }
