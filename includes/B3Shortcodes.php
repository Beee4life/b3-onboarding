<?php
    // exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // check if class already exists
    if ( ! class_exists( 'B3Shortcodes' ) ) :

        /**
         * Class B3Shortcodes
         */
        class B3Shortcodes extends B3Onboarding {

            public function __construct() {
                parent::__construct();

                add_shortcode( 'register-form',     array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'login-form',        array( $this, 'b3_render_login_form' ) );
                add_shortcode( 'forgotpass-form',   array( $this, 'b3_render_forgot_password_form' ) );
                add_shortcode( 'resetpass-form',    array( $this, 'b3_render_reset_password_form' ) );
                add_shortcode( 'account-page',      array( $this, 'b3_render_account_page' ) );
                add_shortcode( 'user-management',   array( $this, 'b3_render_user_approval_page' ) );
            }

            public function b3_render_register_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'register',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                // Retrieve recaptcha key
                $attributes[ 'recaptcha_site_key' ] = get_option( 'b3-onboarding-recaptcha-public-key', null );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
                } elseif ( 'closed' == get_option( 'b3_registration_type', false ) ) {
                    return apply_filters( 'b3_filter_closed_message', esc_html__( 'Registering new users is currently not allowed.', 'b3-onboarding' ) );
                } else {

                    // Retrieve possible errors from request parameters
                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );

                        foreach ( $error_codes as $error_code ) {
                            $attributes[ 'errors' ][] = $this->b3_get_error_message( $error_code );
                        }
                    }

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }
            }

            /**
             * A shortcode for rendering the login form.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $user_variables, $content = null ) {

                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'login',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already signed in.', 'b3-onboarding' );
                }

                // Pass the redirect parameter to the WordPress login functionality: but
                // only if a valid redirect URL has been passed as request parameter, use it.
                $attributes[ 'redirect' ] = false;
                if ( isset( $_REQUEST[ 'redirect_to' ] ) ) {
                    error_log('$_REQUEST[ \'redirect_to\' ] is used =' . $_REQUEST[ 'redirect_to' ]);
                    $attributes[ 'redirect' ] = wp_validate_redirect( $_REQUEST[ 'redirect_to' ], $attributes[ 'redirect' ] );
                }

                // Error messages
                $errors = array();
                if ( isset( $_REQUEST[ 'login' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'login' ] );

                    foreach ( $error_codes as $code ) {
                        $errors[] = $this->b3_get_error_message( $code );
                    }
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( is_multisite() ) {
                        // @TODO
                        $attributes[ 'messages' ][] = sprintf(
                            __( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-onboarding' ),
                            get_bloginfo( 'name' )
                        );
                    } else {
                        if ( 'access_requested' == $_REQUEST[ 'registered' ] ) {
                            // access_requested
                            $attributes[ 'messages' ][] = $this->b3_get_error_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'confirm_email' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_error_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_error_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'success' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_error_message( 'registration_success' );
                        } else {
                            error_log('FIX ELSE - line 112 B3Shortcodes.php');
                            $attributes[ 'messages' ][] = $this->b3_get_error_message( '' );
                        }
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'password' ] ) && 'changed' == $_REQUEST[ 'password' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'password_updated' );
                } elseif ( isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' == $_REQUEST[ 'checkemail' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'lost_password_sent' );
                } elseif ( isset( $_REQUEST[ 'logout' ] ) && 'true' == $_REQUEST[ 'logout' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'logged_out' );
                } elseif ( isset( $_REQUEST[ 'account' ] ) && 'removed' == $_REQUEST[ 'account' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'account_remove' );
                }

                $attributes[ 'errors' ] = $errors;

                // Render the login form using an external template
                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the form used to initiate the password reset.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_forgot_password_form( $user_variables, $content = null ) {

                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'lostpassword',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already logged in.', 'b3-onboarding' );
                }

                // Retrieve possible errors from request parameters
                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'error' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_error_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    // you can now log in... should this be here ?
                    error_log( 'isset( $_REQUEST[ \'activate\' ] ) && \'success\' == $_REQUEST[ \'activate\' ] is used' );
                    $attributes[ 'messages' ][] = $this->b3_get_error_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( 'success' == $_REQUEST[ 'registered' ] ) {
                        $attributes[ 'messages' ][] = $this->b3_get_error_message( 'registration_success_enter_password' );
                    }
                }
                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the form used to reset a user's password.
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_reset_password_form( $user_variables, $content = null ) {
                // Parse shortcode attributes
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'resetpass',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return esc_html__( 'You are already logged in.', 'b3-onboarding' );
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];

                        // Error messages
                        $errors = [];
                        if ( isset( $_REQUEST[ 'error' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_error_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;

                        return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                    } else {

                        $message = esc_html__( 'This is not a valid password reset link.', 'b3-onboarding' );
                        $message .= '<br />';
                        $message .= esc_html__( 'Please click the provided link in your email.', 'b3-onboarding' );
                        $message .= '<br />';
                        $message .= sprintf( __( "If you haven't received any email, please <a href=\"%s\">click here</a>.", 'b3-onboarding' ), b3_get_forgotpass_id( true ) );

                        return $message;
                    }
                }
            }


            /**
             * Render user/account page
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_account_page( $user_variables, $content = null ) {

                if ( is_user_logged_in() ) {

                    wp_enqueue_script( 'user-profile' );

                    // Parse shortcode attributes
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'account',
                    );
                    $attributes = shortcode_atts( $default_attributes, $user_variables );

                    // error messages
                    $errors = array();
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_error_message( $code );
                        }
                    }
                    $attributes[ 'errors' ] = $errors;

                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_error_message( $_REQUEST[ 'updated' ] );
                    }

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                }

                return false;
            }


            /**
             * Render user management page
             *
             * @param $user_variables
             * @param null $content
             */
            public function b3_render_user_approval_page( $user_variables, $content = null ) {

                $show_first_last_name = get_option( 'b3_activate_first_last', false );
                $user_args            = array( 'role' => 'b3_approval' );
                $users                = get_users( $user_args );
                if ( current_user_can( 'promote_users' ) ) {
                    ?>
                    <p>
                        <?php echo __( 'On this page you can approve/deny user requests for access.', 'b3-onboaarding' ); ?>
                    </p>
                    <?php
                    if ( ! empty( $_GET[ 'user' ] ) ) {
                        if ( 'approved' == $_GET[ 'user' ] ) { ?>
                            <p class="b3_message">
                                <?php esc_html_e( 'User is successfully approved', 'b3-onboarding' ); ?>
                            </p>
                        <?php } elseif ( 'rejected' == $_GET[ 'user' ] ) { ?>
                            <p class="b3_message">
                                <?php esc_html_e( 'User is successfully rejected and user is deleted', 'b3-onboarding' ); ?>
                            </p>
                        <?php } ?>
                    <?php } ?>

                    <?php if ( $users ) { ?>

                        <table class="b3_table b3_table--user">
                            <thead>
                            <tr>
                                <th>
                                    <?php esc_html_e( 'User ID', 'b3-onboarding' ); ?>
                                </th>
                                <?php if ( false != $show_first_last_name ) { ?>
                                    <th>
                                        <?php esc_html_e( 'First name', 'b3-onboarding' ); ?>
                                    </th>
                                    <th>
                                        <?php esc_html_e( 'Last name', 'b3-onboarding' ); ?>
                                    </th>
                                <?php } ?>
                                <th>
                                    <?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
                                </th>
                                <th>
                                    <?php esc_html_e( 'Actions', 'b3-onboarding' ); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach( $users as $user ) { ?>
                                <tr>
                                    <td><?php echo $user->ID; ?></td>
                                    <?php if ( false != $show_first_last_name ) { ?>
                                        <td><?php echo $user->first_name; ?></td>
                                        <td><?php echo $user->last_name; ?></td>
                                    <?php } ?>
                                    <td><?php echo $user->user_email; ?></td>
                                    <td>
                                        <form name="b3_user_management" action="" method="post">
                                            <input name="b3_manage_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>" />
                                            <input name="b3_user_id" type="hidden" value="<?php echo $user->ID; ?>" />
                                            <input name="b3_approve_user" class="button" type="submit" value="<?php esc_html_e( 'Approve', 'b3-onboarding' ); ?>" />
                                            <input name="b3_reject_user" class="button" type="submit" value="<?php esc_html_e( 'Reject', 'b3-onboarding' ); ?>" />
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p><?php esc_html_e( 'No (more) users to approve.', 'b3-onboarding' ); ?></p>
                    <?php }
                } // endif user can promote_users
            }

        }

        new B3Shortcodes();

    endif;
