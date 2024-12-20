<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // check if class already exists
    if ( ! class_exists( 'B3Shortcodes' ) ) {

        /**
         * Class B3Shortcodes
         *
         * @since 2.0.0
         */
        class B3Shortcodes extends B3Onboarding {
            /**
             * B3Shortcodes constructor
             */
            public function __construct() {
                parent::__construct();
                
                add_shortcode( 'account-page',      [ $this, 'b3_render_account_page' ] );
                add_shortcode( 'lostpass-form',     [ $this, 'b3_render_lost_password_form' ] );
                add_shortcode( 'login-form',        [ $this, 'b3_render_login_form' ] );
                add_shortcode( 'register-form',     [ $this, 'b3_render_register_form' ] );
                add_shortcode( 'resetpass-form',    [ $this, 'b3_render_reset_password_form' ] );
                add_shortcode( 'user-management',   [ $this, 'b3_render_user_approval_page' ] );
            }
            
            
            /**
             * Renders the register form
             *
             * @param $shortcode_args
             *
             * @return mixed|string|void
             * @since 1.0.0
             *
             */
            public function b3_render_register_form( $shortcode_args ) {
                $admin_approval    = get_option( 'b3_needs_admin_approval' );
                $registration_type = get_option( 'b3_registration_type' );
                
                if ( is_user_logged_in() && 'blog' != $registration_type ) {
                    return sprintf( '<p class="b3_message">%s</p>', esc_html__( 'You are already logged in.', 'b3-onboarding' ) );
                }

                if ( $admin_approval && 'user' == $registration_type ) {
                    $button_value = esc_attr__( 'Request user', 'b3-onboarding' );
                } elseif ( $admin_approval ) {
                    $button_value = esc_attr__( 'Request access', 'b3-onboarding' );
                } elseif ( 'request_access' == $registration_type ) {
                    $button_value = esc_attr__( 'Request access', 'b3-onboarding' );
                } else {
                    $button_value = esc_attr__( 'Register', 'b3-onboarding' );
                }

                $default_attributes = [
                    'button_modifier'   => 'register',
                    'button_value'      => $button_value,
                    'template'          => 'register',
                    'title'             => false,
                ];
                
                $attributes                        = shortcode_atts( $default_attributes, $shortcode_args );
                $attributes[ 'registration_type' ] = $registration_type;
                
                if ( $admin_approval && ! isset( $_GET[ 'registered' ] ) ) {
                    if ( 'user' === $registration_type ) {
                        $attributes[ 'messages' ][] = apply_filters( 'b3_message_above_request_site', esc_html__( 'You have to request access to register a user.', 'b3-onboarding' ) );
                    } elseif ( 'all' === $registration_type ) {
                        $attributes[ 'messages' ][] = apply_filters( 'b3_message_above_request_site', esc_html__( 'You have to request access to register a user or site.', 'b3-onboarding' ) );
                    }
                }

                if ( isset( $_REQUEST[ 'registered' ] ) && 'new_blog' === $_REQUEST[ 'registered' ] ) {
                    // @TODO: Improve/DRY this
                    if ( isset( $_GET[ 'site_id' ] ) && ! empty( $_GET[ 'site_id' ] ) ) {
                        switch_to_blog( $_GET[ 'site_id' ] );
                        $home_url  = home_url( '/' );
                        $site_info = get_site( $_GET[ 'site_id' ] );
                        $admin_url = apply_filters( 'b3_dashboard_url', admin_url( '/' ), $site_info );
                        restore_current_blog();

                        $message = '<p class="b3_message b3_message--success">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= esc_html__( 'Visit it on', 'b3-onboarding' ) . ': ';
                        $message .= sprintf( '<a href="%s">%s</a>', esc_url( $home_url ), esc_url( $home_url ) );
                        $message .= '<br>';
                        $message .= sprintf( esc_html__( 'You can manage your new site %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( $admin_url ), esc_html__( 'here', 'b3-onboarding' ) ) );
                        $message .= '</p>';

                    } else {
                        $message = '<p class="b3_message b3_message--success">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '</p>';
                    }

                    return $message;
                }

                if ( 'none' === $registration_type && ! current_user_can( 'manage_network' ) ) {
                    ob_start();
                    echo sprintf( '<p class="b3_message">%s</p>', b3_get_registration_closed_message() );
                    do_action( 'b3_add_action_links', $attributes[ 'template' ] );
                    $rego_closed = ob_get_clean();
                    return $rego_closed;

                } elseif ( 'blog' === $registration_type && ! is_user_logged_in() ) {
                    // logged in registration only
                    return sprintf( '<p class="b3_message">%s</p>', b3_get_logged_in_registration_only_message() );

                } else {
                    $attributes[ 'errors' ] = [];
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );
                        $error_count = 1;
                        foreach ( $error_codes as $error_code ) {
                            if ( 1 === count( $error_codes ) ) {
                                $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code, false );
                            } else {
                                if ( 1 < $error_count ) {
                                    // 2 errors only occurs with extra fields
                                    if ( strpos( $error_code, 'field_' ) !== false ) {
                                        $field_id           = substr( $error_code, 6 );
                                        $extra_field_values = apply_filters( 'b3_extra_fields', [] );
                                        $column             = array_column( $extra_field_values, 'id' );
                                        $key                = array_search( $field_id, $column );
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
                        if ( 'access_requested' === $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'dummy' === $_REQUEST[ 'registered' ] ) {
                            // dummy is for demonstration setup
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        }
                    }

                    if ( 1 == get_option( 'b3_activate_recaptcha' ) && 'register' === $attributes[ 'template' ] ) {
                        $recaptcha_public  = get_option( 'b3_recaptcha_public' );
                        $recaptcha_version = get_option( 'b3_recaptcha_version' );

                        $attributes[ 'recaptcha' ] = [
                            'public'  => $recaptcha_public,
                            'version' => $recaptcha_version,
                        ];
                    }

                    B3Onboarding::b3_show_admin_notices();

                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }
            }


            /**
             * A shortcode for rendering the login form.
             *
             * @since 1.0.0
             *
             * @param array  $shortcode_args Shortcode attributes.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $shortcode_args ) {
                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                }
                
                $errors             = [];
                $error_codes        = [];
                $default_attributes = [
                    'button_value' => esc_attr__( 'Log in', 'b3-onboarding' ),
                    'template'     => 'login',
                    'title'        => false,
                ];
                $attributes         = shortcode_atts( $default_attributes, $shortcode_args );
                
                // Pass the redirect parameter to the WordPress login functionality: but
                // only if a valid redirect URL has been passed as request parameter, use it.
                $attributes[ 'registration_type' ] = get_option( 'b3_registration_type' );
                $attributes[ 'redirect' ]          = false;

                if ( isset( $_REQUEST[ 'redirect_to' ] ) ) {
                    $attributes[ 'redirect' ] = wp_validate_redirect( $_REQUEST[ 'redirect_to' ], $attributes[ 'redirect' ] );
                }
                
                // @TODO: create function for this
                if ( isset( $_REQUEST[ 'login' ] ) || isset( $_REQUEST[ 'error' ] ) ) {
                    if ( isset( $_REQUEST[ 'login' ] ) ) {
                        // @TODO: look into this
                        if ( 'enter_code' === $_REQUEST[ 'login' ] ) {
                            error_log('class-b3-shortcodes.php line 193');
                            if ( isset( $_REQUEST[ 'otpcode' ] ) ) {
                                // enter code
                                error_log('class-b3-shortcodes.php line 196');
                            } else {
                                $error_codes = explode( ',', $_REQUEST[ 'login' ] );
                            }
                        } else {
                            $error_codes = explode( ',', $_REQUEST[ 'login' ] );
                        }
                    } elseif ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    }

                    foreach ( $error_codes as $code ) {
                        $errors[] = $this->b3_get_return_message( $code );
                    }
                    
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( is_multisite() ) {
                        if ( 'access_requested' === $_REQUEST[ 'registered' ] ) {
                            // access_requested
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } else {
                            $attributes[ 'messages' ][] = sprintf( esc_html__( 'You have successfully registered to %s. We have emailed you an activation link.', 'b3-onboarding' ), sprintf( '<strong>%s</strong>', get_site_option( 'site_name' ) ) );
                        }
                    } else {
                        if ( in_array( $_REQUEST[ 'registered' ], [ 'access_requested', 'confirm_email', 'dummy' ] ) ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'success' === $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success' );
                        } else {
                            error_log( 'FIX ELSE - line 116 class-b3-shortcodes.php' );
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( '' );
                        }
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' === $_REQUEST[ 'activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'mu-activate' ] ) && 'success' === $_REQUEST[ 'mu-activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'mu_activate_success' );
                } elseif ( isset( $_REQUEST[ 'password' ] ) && 'changed' === $_REQUEST[ 'password' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'password_updated' );
                } elseif ( isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' === $_REQUEST[ 'checkemail' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'lost_password_sent' );
                } elseif ( isset( $_REQUEST[ 'logout' ] ) && 'true' === $_REQUEST[ 'logout' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'logged_out' );
                } elseif ( isset( $_REQUEST[ 'account' ] ) && 'removed' === $_REQUEST[ 'account' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'account_remove' );
                }
                
                if ( 1 == get_option( 'b3_use_magic_link' ) ) {
                    $attributes[ 'button_value' ] = esc_attr__( 'Get magic link', 'b3-onboarding' );
                    $attributes[ 'form_action' ]  = add_query_arg( 'login', 'code_sent', b3_get_login_url() );
                    $attributes[ 'nonce_id' ]     = 'b3_set_otp_nonce';
                    $attributes[ 'nonce' ]        = wp_create_nonce( 'b3-set-otp-nonce' );
                    $attributes[ 'template' ]     = 'magiclink';
                }
                
                $attributes[ 'errors' ] = $errors;

                $attributes = apply_filters( 'b3_attributes', $attributes );

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the password lost form.
             *
             * @since 1.0.0
             *
             * @param array $shortcode_args Shortcode attributes.
             *
             * @return string  The shortcode output
             */
            public function b3_render_lost_password_form( $shortcode_args ) {
                $default_attributes = [
                    'button_value' => esc_attr__( 'Reset password', 'b3-onboarding' ),
                    'template'     => 'lostpassword',
                    'title'        => false,
                ];
                $attributes         = shortcode_atts( $default_attributes, $shortcode_args );

                if ( is_user_logged_in() ) {
                    return sprintf( '<p class="b3_message">%s</p>', esc_html__( 'You are already logged in.', 'b3-onboarding' ) );
                }

                $attributes[ 'errors' ] = [];
                if ( isset( $_REQUEST[ 'error' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' === $_REQUEST[ 'activate' ] ) {
                    // you can now log in... should this be here ?
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( 'success' === $_REQUEST[ 'registered' ] ) {
                        $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success_enter_password' );
                    }
                }
                
                if ( 1 == get_option( 'b3_use_magic_link' ) ) {
                    $attributes[ 'button_value' ] = esc_attr__( 'Get magic link', 'b3-onboarding' );
                    $attributes[ 'form_action' ]  = add_query_arg( 'login', 'code_sent', b3_get_login_url() );
                    $attributes[ 'nonce_id' ]     = 'b3_set_otp_nonce';
                    $attributes[ 'nonce' ]        = wp_create_nonce( 'b3-set-otp-nonce' );
                    $attributes[ 'template' ]     = 'magiclink';
                }
                
                $attributes = apply_filters( 'b3_attributes', $attributes );

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the reset password form.
             *
             * @since 1.0.0
             *
             * @param array $shortcode_args Shortcode attributes.
             *
             * @return string The shortcode output
             */
            public function b3_render_reset_password_form( $shortcode_args ) {
                $default_attributes = [
                    'button_value' => esc_attr__( 'Set password', 'b3-onboarding' ),
                    'template'     => 'resetpass',
                    'title'        => false,
                ];
                $attributes         = shortcode_atts( $default_attributes, $shortcode_args );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];
                        $errors                = [];
                        
                        if ( isset( $_REQUEST[ 'error' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_return_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;

                        $attributes = apply_filters( 'b3_attributes', $attributes );

                        return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                    } else {
                        // error message for password reset
                        $message = esc_html__( 'This is not a valid password reset link.', 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= esc_html__( 'Please click the provided link in your email.', 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= sprintf( esc_html__( "If you haven't received any email, please %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( b3_get_lostpassword_url() ), esc_html__( 'click here', 'b3-onboarding' ) ) );

                        return $message;
                    }
                }
            }
            
            
            /**
             * Render user/account page
             *
             * @since 1.0.0
             *
             * @param $shortcode_args
             *
             * @return bool|string
             */
            public function b3_render_account_page( $shortcode_args ) {
                if ( is_user_logged_in() ) {
                    wp_enqueue_script( 'user-profile' );
                    $errors             = [];
                    $default_attributes = [
                        'button_value' => esc_attr__( 'Update profile', 'b3-onboarding' ),
                        'template'     => 'account',
                        'title'        => false,
                    ];
                    $attributes         = shortcode_atts( $default_attributes, $shortcode_args );

                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    } elseif ( isset( $_REQUEST[ 'message' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'message' ] );
                    } elseif ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $error_codes = [ 'profile_saved' ];
                    }
                    if ( isset( $error_codes ) ) {
                        foreach( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ]            = $errors;
                    $attributes[ 'registration_type' ] = get_option( 'b3_registration_type' );

                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_return_message( $_REQUEST[ 'updated' ] );
                    }
                    
                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }

                return false;
            }


            /**
             * Render user management page
             *
             * @since 1.0.0
             *
             * @param $shortcode_args
             *
             * @return bool|string
             */
            public function b3_render_user_approval_page( $shortcode_args ) {
                if ( current_user_can( 'promote_users' ) ) {
                    $default_attributes = [
                        'title'    => false,
                        'template' => 'user-management',
                    ];
                    
                    $attributes           = shortcode_atts( $default_attributes, $shortcode_args );
                    $errors               = [];
                    $needs_admin_approval = get_option( 'b3_needs_admin_approval' );
                    
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ]               = $errors;
                    $attributes[ 'register_email_only' ]  = get_option( 'b3_register_email_only' );
                    $attributes[ 'registration_type' ]    = get_option( 'b3_registration_type' );
                    $attributes[ 'show_first_last_name' ] = get_option( 'b3_activate_first_last' );

                    if ( is_multisite() ) {
                        global $wpdb;
                        $query                 = "SELECT * FROM $wpdb->signups WHERE active = '0'";
                        $attributes[ 'users' ] = $wpdb->get_results( $query );
                    } else {
                        $user_args             = [ 'role' => 'b3_approval' ];
                        $attributes[ 'users' ] = get_users( $user_args );
                    }

                    B3Onboarding::b3_show_admin_notices();

                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }

                return false;
            }
        }

        new B3Shortcodes();
    }
