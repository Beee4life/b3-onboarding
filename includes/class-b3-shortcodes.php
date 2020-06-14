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

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                } elseif ( 'closed' == get_option( 'b3_registration_type', false ) ) {
                    return apply_filters( 'b3_registration_closed_message', b3_get_registration_closed_message() );
                } else {

                    $attributes[ 'errors' ]             = array();
                    $attributes[ 'recaptcha_site_key' ] = get_option( 'b3_recaptcha_public', null ); // @TODO I need this ?
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );

                        foreach ( $error_codes as $error_code ) {
                            $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                        }
                    } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                        // this is for demonstration setup
                        if ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        }
                    }

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
                if ( isset( $_REQUEST[ 'login' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'login' ] );

                    foreach ( $error_codes as $code ) {
                        $errors[] = $this->b3_get_return_message( $code );
                    }
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( is_multisite() ) {
                        $attributes[ 'messages' ][] = sprintf(
                            __( 'You have successfully registered to <strong>%s</strong>. We have emailed you an activation link.', 'b3-onboarding' ),
                            get_bloginfo( 'name' )
                        );
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
                } elseif ( isset( $_REQUEST[ 'password' ] ) && 'changed' == $_REQUEST[ 'password' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'password_updated' );
                } elseif ( isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' == $_REQUEST[ 'checkemail' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'lost_password_sent' );
                } elseif ( isset( $_REQUEST[ 'logout' ] ) && 'true' == $_REQUEST[ 'logout' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'logged_out' );
                } elseif ( isset( $_REQUEST[ 'account' ] ) && 'removed' == $_REQUEST[ 'account' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'account_remove' );
                }

                $attributes[ 'errors' ] = $errors;

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
                    $attributes[ 'errors' ] = $errors;

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                }

                return false;

            }
        }

        new B3_Shortcodes();

    endif;
