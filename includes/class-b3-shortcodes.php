<?php
    // exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // check if class already exists
    if ( ! class_exists( 'B3Shortcodes' ) ) :

        /**
         * Class B3Shortcodes
         *
         * @since 2.0.0
         */
        class B3_Shortcodes extends B3Onboarding {

            public function __construct() {
                parent::__construct();

                add_shortcode( 'account-page',      array( $this, 'b3_render_account_page' ) );
                add_shortcode( 'lostpass-form',     array( $this, 'b3_render_lost_password_form' ) );
                add_shortcode( 'login-form',        array( $this, 'b3_render_login_form' ) );
                add_shortcode( 'register-form',     array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'resetpass-form',    array( $this, 'b3_render_reset_password_form' ) );
                add_shortcode( 'user-management',   array( $this, 'b3_render_user_approval_page' ) );
            }

            /**
             * Renders the register form
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return mixed|string|void
             */
            public function b3_render_register_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'register',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_multisite() ) {
                    $registration_type = get_site_option( 'b3_registration_type' );
                } else {
                    $registration_type = get_option( 'b3_registration_type' );
                }
                $attributes[ 'registration_type' ] = $registration_type;;

                if ( is_user_logged_in() ) {
                    if ( 'all' == $registration_type ) {
                        // register site only, if registration type == user, he should be blocked already
                    } elseif ( ! in_array( $registration_type, [ 'blog' ] ) ) {
                        return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                    }
                }

                if ( in_array( $registration_type, [ 'closed', 'none' ] ) ) {
                    return '<p class="b3_message">' . apply_filters( 'b3_registration_closed_message', b3_get_registration_closed_message() ) . '</p>';
                } elseif ( in_array( $registration_type, [ 'blog' ] ) && ! is_user_logged_in() ) {
                    return '<p class="b3_message">' . apply_filters( 'b3_logged_in_registration_only_message', b3_get_logged_in_registration_only_message() ) . '</p>';
                } elseif ( isset( $_REQUEST[ 'registered' ] ) && 'new_blog' == $_REQUEST[ 'registered' ] ) {
                    if ( isset( $_GET[ 'site_id' ] ) && ! empty( $_GET[ 'site_id' ] ) ) {
                        switch_to_blog( $_GET['site_id'] );
                        $home_url  = home_url( '/' );
                        $admin_url = admin_url( '/' );
                        restore_current_blog();
                        $message = '<p class="b3_message">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '<br />';
                        $message .= esc_html__( 'Visit it on:', 'b3-onboarding' ) . ' ';
                        $message .= '<a href="' . esc_url( $home_url ) . '">' . esc_url( $home_url ) . '</a>';
                        $message .= '<br />';
                        $message .= sprintf( __( 'You can manage your new site <a href="%s">here</a>.', 'b3-onboarding' ), esc_url( $admin_url ) );
                        $message .= '</p>';

                        return $message;
                    } else {
                        // fallback
                        $message = '<p class="b3_message">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '</p>';

                        return $message;
                    }
                } else {

                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );
                        $error_count = 1;
                        foreach ( $error_codes as $error_code ) {
                            if ( 1 == count( $error_codes ) ) {
                                $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code, false );
                            } else {
                                if ( 1 < $error_count ) {
                                    // 2 errors only occurs with extra fields
                                    if ( strpos( $error_code, 'field_' ) !== false ) {
                                        $field_id                 = substr( $error_code, 6 );
                                        $extra_field_values       = apply_filters( 'b3_extra_fields', array() );
                                        $column                   = array_column( $extra_field_values, 'id' );
                                        $key                      = array_search( $field_id, $column );
                                        if ( isset( $extra_field_values[ $key ][ 'label' ] ) ) {
                                            $sprintf_variable         = $extra_field_values[ $key ][ 'label' ];
                                            $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_codes[ 0 ], $sprintf_variable );
                                        }
                                    } else {
                                        $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                                    }
                                }
                            }
                            $error_count++;
                        }
                    } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                        // dummy is for demonstration setup
                        if ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'access_requested' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        }
                    }

                    B3Onboarding::b3_show_admin_notices();

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }
            }


            /**
             * A shortcode for rendering the login form.
             *
             * @since 1.0.0
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $user_variables, $content = null ) {

                $default_attributes = array(
                    'title'    => false,
                    'template' => 'login',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                }

                // Pass the redirect parameter to the WordPress login functionality: but
                // only if a valid redirect URL has been passed as request parameter, use it.
                $attributes[ 'redirect' ] = false;
                if ( isset( $_REQUEST[ 'redirect_to' ] ) ) {
                    $attributes[ 'redirect' ] = wp_validate_redirect( $_REQUEST[ 'redirect_to' ], $attributes[ 'redirect' ] );
                }

                $errors = array();
                if ( isset( $_REQUEST[ 'login' ] ) || isset( $_REQUEST[ 'error' ] ) ) {
                    if ( isset( $_REQUEST[ 'login' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'login' ] );
                    } elseif ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    }

                    foreach ( $error_codes as $code ) {
                        $errors[] = $this->b3_get_return_message( $code );
                    }

                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( is_multisite() ) {
                        $attributes[ 'messages' ][] = sprintf(
                            __( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-onboarding' ),
                            get_site_option( 'site_name' ) );
                    } else {
                        if ( 'access_requested' == $_REQUEST[ 'registered' ] ) {
                            // access_requested
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'confirm_email' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'success' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success' );
                        } else {
                            error_log('FIX ELSE - line 116 class-b3-shortcodes.php');
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( '' );
                        }
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'mu-activate' ] ) && 'success' == $_REQUEST[ 'mu-activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'mu_activate_success' );
                } elseif ( isset( $_REQUEST[ 'password' ] ) && 'changed' == $_REQUEST[ 'password' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'password_updated' );
                } elseif ( isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' == $_REQUEST[ 'checkemail' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'lost_password_sent' );
                } elseif ( isset( $_REQUEST[ 'logout' ] ) && 'true' == $_REQUEST[ 'logout' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'logged_out' );
                } elseif ( isset( $_REQUEST[ 'account' ] ) && 'removed' == $_REQUEST[ 'account' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'account_remove' );
                }

                $attributes[ 'errors' ]            = $errors;
                $attributes[ 'registration_type' ] = get_site_option( 'b3_registration_type' );;

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

            }


            /**
             * A shortcode for rendering the password lost form.
             *
             * @since 1.0.0
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_lost_password_form( $user_variables, $content = null ) {

                $default_attributes = array(
                    'title'    => false,
                    'template' => 'lostpassword',
                );
                $attributes = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                }

                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'error' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    // you can now log in... should this be here ?
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( 'success' == $_REQUEST[ 'registered' ] ) {
                        $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success_enter_password' );
                    }
                }

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

            }


            /**
             * A shortcode for rendering the reset password form.
             *
             * @since 1.0.0
             *
             * @param  array $attributes Shortcode attributes.
             * @param  string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_reset_password_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'resetpass',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];

                        $errors = array();
                        if ( isset( $_REQUEST[ 'error' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_return_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;

                        return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                    } else {

                        // error message for password reset
                        $message = esc_html__( 'This is not a valid password reset link.', 'b3-onboarding' );
                        $message .= '<br />';
                        $message .= esc_html__( 'Please click the provided link in your email.', 'b3-onboarding' );
                        $message .= '<br />';
                        $message .= sprintf( __( "If you haven't received any email, please <a href=\"%s\">click here</a>.", 'b3-onboarding' ), b3_get_lostpassword_url() );

                        return $message;

                    }
                }
            }


            /**
             * Render user/account page
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_account_page( $user_variables, $content = null ) {

                if ( is_user_logged_in() ) {
                    wp_enqueue_script( 'user-profile' );
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'account',
                    );
                    $attributes = shortcode_atts( $default_attributes, $user_variables );

                    $errors = array();
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ] = $errors;

                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_return_message( $_REQUEST[ 'updated' ] );
                    }

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                }

                return false;
            }


            /**
             * Render user management page
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_user_approval_page( $user_variables, $content = null ) {
                if ( current_user_can( 'promote_users' ) ) {
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'user-management',
                    );
                    $attributes = shortcode_atts( $default_attributes, $user_variables );

                    $errors = array();
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );

                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ]               = $errors;
                    $attributes[ 'register_email_only' ]  = get_site_option( 'b3_register_email_only' );
                    $attributes[ 'registration_type' ]    = get_site_option( 'b3_registration_type' );;
                    $attributes[ 'show_first_last_name' ] = get_site_option( 'b3_activate_first_last' );

                    B3Onboarding::b3_show_admin_notices();

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                }

                return false;

            }
        }

        new B3_Shortcodes();

    endif;

