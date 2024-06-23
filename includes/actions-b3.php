<?php
    // This file contains functions hooked to the plugin's own hooks

    /**
     * Approve a user
     *
     * @since 1.0.0
     *
     * @param $user_id
     * @return void
     */
    function b3_do_stuff_after_new_user_approved_by_admin( $user_id ) {

        if ( is_multisite() ) {
            // get activation key
            error_log('@TODO: b3_do_stuff_after_new_user_approved_by_admin');
        } else {
            $custom_passwords  = get_option( 'b3_activate_custom_passwords' );
            $user_object       = get_userdata( $user_id );
            $user_login        = $user_object->user_login;
            $user_object->set_role( get_option( 'default_role' ) );
            
            if ( false == $custom_passwords ) {
                // user needs a password
                $key                 = get_password_reset_key( $user_object );
                $reset_pass_url      = b3_get_reset_password_url();
                $vars[ 'reset_url' ] = $reset_pass_url . '?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login );
            } else {
                // user has set a custom password or requests access
                $vars = [];
            }

            $to      = $user_object->user_email;
            $subject = b3_get_account_approved_subject();
            $subject = strtr( $subject, b3_get_replacement_vars( 'subject' ) );
            $message = b3_get_account_approved_message();
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_get_replacement_vars( 'message', $vars ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, [] );
        }
    }
    add_action( 'b3_approve_user', 'b3_do_stuff_after_new_user_approved_by_admin' );


    /**
     * Approve new WPMU signup
     *
     * @param array $signup_info
     */
    function b3_approve_new_wpmu_signup( $signup_info = [] ) {
        // update row
        global $wpdb;
        $meta_data              = unserialize( $signup_info->meta );
        $meta_data[ 'public' ]  = 1;
        $meta_data[ 'deleted' ] = 0;
        $signup_info->meta      = serialize( $meta_data );

        // set site to public and remove deleted status
        $table = $wpdb->prefix . 'signups';
        $data  = [ 'meta' => $signup_info->meta ];
        $where = [ 'signup_id' => $signup_info->signup_id ];
        $wpdb->update( $table, $data, $where, [ '%s' ] );
        
        wpmu_activate_signup( $signup_info->activation_key );
    }
    add_action( 'b3_approve_wpmu_signup', 'b3_approve_new_wpmu_signup' );


    /**
     * Reject a user (by admin)
     *
     * @since 2.5.0
     *
     * @param $user_info
     * @return void
     */
    function b3_do_stuff_before_reject_user_by_admin( $user_info ) {
        if ( ! get_option( 'b3_disable_delete_user_email' ) ) {
            $multisite = false;
            $message   = b3_get_account_rejected_message();
            $subject   = b3_get_account_rejected_subject();

            if ( isset( $user_info[ 'user_id' ] ) ) {
                $user_object = get_userdata( $user_info[ 'user_id' ] );
                $to          = $user_object->user_email;
            } elseif ( isset( $user_info[ 'user_email' ] ) ) {
                $multisite = true;
                $to        = $user_info[ 'user_email' ];
            }

            if ( $multisite || in_array( 'b3_approval', $user_object->roles ) || in_array( 'b3_activation', $user_object->roles ) ) {
                $message = b3_replace_template_styling( $message );
                $message = strtr( $message, b3_get_replacement_vars() );
                $message = htmlspecialchars_decode( stripslashes( $message ) );
                wp_mail( $to, $subject, $message, [] );
            }
        }
    }
    add_action( 'b3_before_reject_user', 'b3_do_stuff_before_reject_user_by_admin' );


    /**
     * Do stuff after user clicked activate link
     *
     * @since 1.0.0
     *
     * @TODO: check for a WordPress hook to hook to
     *
     * @param $user_id
     * @return void
     */
    function b3_do_stuff_after_user_activated( $user_id ) {
        if ( 1 != get_option( 'b3_disable_admin_notification_new_user' ) ) {
            // send 'new user' email to admin
            $user          = get_userdata( $user_id );
            $admin_to      = b3_get_notification_addresses( 'email_activation' );
            $admin_subject = b3_get_new_user_subject();
            $admin_email   = b3_get_new_user_message();
            $admin_email   = b3_replace_template_styling( $admin_email );
            $admin_email   = strtr( $admin_email, b3_get_replacement_vars( 'message', [ 'user_data' => $user ] ) );
            $admin_email   = htmlspecialchars_decode( stripslashes( $admin_email ) );
            $admin_message = $admin_email;

            wp_mail( $admin_to, $admin_subject, $admin_message, [] );
        }

        // send 'account activated' email to user
        if ( 'email_activation' === get_option( 'b3_registration_type' ) ) {
            $user    = get_userdata( $user_id );
            $to      = $user->user_email;
            $subject = b3_get_account_activated_subject_user();
            $message = b3_get_account_activated_message_user();
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_get_replacement_vars( 'message', [ 'user_data' => $user ] ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, [] );
        }
    }
    add_action( 'b3_after_user_activated', 'b3_do_stuff_after_user_activated' );


    /**
     * Ouptuts default login/email field
     *
     * @since 1.0.0
     *
     * @param $registration_type
     * @return void
     */
    function b3_add_username_email_fields( $registration_type ) {
        if ( 'blog' != $registration_type ) {
            ob_start();
            if ( is_multisite() ) {
                do_action( 'b3_render_form_element', 'register/user-login' );
                do_action( 'b3_render_form_element', 'register/user-email' );

                if ( 'all' === $registration_type ) {
                    do_action( 'b3_render_form_element', 'register/register-for' );
                } elseif ( in_array( $registration_type, [ 'site' ] ) ) { ?>
                    <input type="hidden" name="signup_for" value="blog" />
                <?php } elseif ( 'user' === $registration_type ) { ?>
                    <input type="hidden" name="signup_for" value="user" />
                <?php
                }
            } else {
                if ( get_option( 'b3_register_email_only' ) || get_option( 'b3_use_magic_link' ) ) { ?>
                    <input type="hidden" name="user_login" value="<?php echo b3_generate_user_login(); ?>">
                <?php } else {
                    do_action( 'b3_render_form_element', 'register/user-login' );
                }
                do_action( 'b3_render_form_element', 'register/user-email' );
            }
            $output = ob_get_clean();
            echo $output;
        }
    }
    add_action( 'b3_add_username_email_fields', 'b3_add_username_email_fields' );


    /**
     * Output for first/last name fields
     *
     * @since 0.8-beta
     */
    function b3_first_last_name_fields( $registration_type ) {
        if ( get_option( 'b3_activate_first_last' ) && ! get_option( 'b3_register_email_only' ) && 'blog' != $registration_type ) {
            do_action( 'b3_do_before_first_last_name' );
            ob_start();
            do_action( 'b3_render_form_element', 'register/first-name' );
            do_action( 'b3_render_form_element', 'register/last-name' );
            $output = ob_get_clean();
            echo $output;
            do_action( 'b3_do_after_first_last_name' );
        }
    }
    add_action( 'b3_add_first_last_name_fields', 'b3_first_last_name_fields' );


    /**
     * Output the password fields
     *
     * @since 0.8-beta
     */
    function b3_add_password_fields() {
        if ( ! is_multisite() && get_option( 'b3_activate_custom_passwords' ) && in_array( get_option( 'b3_registration_type' ), [ 'email_activation', 'open' ] ) ) {
            do_action( 'b3_do_before_passwords' );
            ob_start();
            ?>
            <div class="b3_form-element b3_form-element--password">
                <label class="b3_form-label" for="pass1"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
                <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3_form--input"/>
            </div>

            <div class="b3_form-element b3_form-element--password">
                <label class="b3_form-label" for="pass2"><?php esc_html_e( 'Confirm Password', 'b3-onboarding' ); ?></label>
                <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3_form--input"/>
            </div>
            <?php
            $results = ob_get_clean();
            echo $results;
            do_action( 'b3_do_after_passwords' );
        }
    }
    add_action( 'b3_add_password_fields', 'b3_add_password_fields' );


    /**
     * Add field for subdomain when WPMU is active
     *
     * @since 1.0.0
     *
     * @param $registration_type
     * @return void
     */
    function b3_add_site_fields( $registration_type ) {
        if ( is_multisite() && is_main_site() ) {
            if ( in_array( $registration_type, [
                'request_access_subdomain',
                'blog',
                'all',
                'site',
            ] ) ) {
                $register_for = apply_filters( 'b3_register_for', false );
                ob_start();
                if ( false === $register_for || 'blog' === $register_for ) {
            ?>
                <div class="b3_site-fields">
                    <?php do_action( 'b3_render_form_element', 'register/site-fields-header' ); ?>
                    <?php do_action( 'b3_render_form_element', 'register/subdomain' ); ?>
                    <?php do_action( 'b3_render_form_element', 'register/site-title' ); ?>
                    <?php do_action( 'b3_render_form_element', 'register/visibility' ); ?>
                </div>
            <?php
                }
                $output = ob_get_clean();
                echo $output;
            }
        }
    }
    add_action( 'b3_add_site_fields', 'b3_add_site_fields' );


    /**
     * Function to output any custom fields
     *
     * @since 2.0.0
     */
    function b3_add_extra_fields_registration() {
        $extra_field_values = apply_filters( 'b3_extra_fields', [] );
        if ( ! empty( $extra_field_values ) ) {
            foreach( $extra_field_values as $extra_field ) {
                echo b3_render_extra_field( $extra_field );
            }
        }
    }
    add_action( 'b3_add_extra_fields_registration', 'b3_add_extra_fields_registration' );


    /**
     * Output any hidden fields
     *
     * @since 2.0.0
     */
    function b3_add_hidden_fields_registration( $attributes ) {
        do_action( 'b3_render_form_element', 'register/hidden-fields', $attributes );
    }
    add_action( 'b3_add_hidden_fields_registration', 'b3_add_hidden_fields_registration' );


    /**
     * Add reCAPTCHA check
     *
     * @since 2.0.0
     */
    function b3_add_recaptcha_fields() {
        if ( false != get_option( 'b3_activate_recaptcha' ) ) {
            $recaptcha_public = apply_filters( 'b3_recaptcha_public', get_option( 'b3_recaptcha_public' ) );
            if ( false != $recaptcha_public ) {
                if ( '2' == get_option( 'b3_recaptcha_version', '2' ) ) {
                    do_action( 'b3_do_before_recaptcha' );
                    ?>
                    <div class="b3_form-element b3_form-element--recaptcha">
                        <div class="recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                        </div>
                    </div>
                    <?php
                        do_action( 'b3_do_after_recaptcha' );
                }
            } else {
                if ( current_user_can( 'manage_options') ) {
                    $message = sprintf( esc_html__( "You didn't set a reCaptcha key yet. You can set it %s.", 'b3-onboarding' ),
                        sprintf( '<a href="%s">%s</a>',
                            esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=recaptcha' ) ),
                                    esc_html__( 'here', 'b3-onboarding' ) . '</a>' ) );
                    echo sprintf( '<div class="b3_form-element b3_form-element--recaptcha">%s</div>', $message );
                }
            }
        }
    }
    add_action( 'b3_add_recaptcha_fields', 'b3_add_recaptcha_fields' );


    /**
     * Function to output a privacy checkbox
     */
    function b3_add_privacy_checkbox() {
        if ( true == get_option( 'b3_privacy' ) ) {
            do_action( 'b3_render_form_element', 'register/privacy' );
        }
    }
    add_action( 'b3_add_privacy_checkbox', 'b3_add_privacy_checkbox' );


    /**
     * Echo error/info message above a (custom) form
     *
     * @param $attributes
     *
     * @return void
     */
    function b3_render_form_messages( $attributes = [] ) {
        if ( ! empty( $attributes ) ) {
            $messages          = [];
            $registration_type = get_option( 'b3_registration_type' );

            if ( isset( $attributes[ 'errors' ] ) && 0 < count( $attributes[ 'errors' ] ) ) {
                foreach( $attributes[ 'errors' ] as $error ) {
                    $messages[] = $error;
                }
            } elseif ( isset( $attributes[ 'messages' ] ) && 0 < count( $attributes[ 'messages' ] ) ) {
                foreach( $attributes[ 'messages' ] as $message ) {
                    $messages[] = $message;
                }
            } else {
                if ( isset( $attributes[ 'template' ] ) ) {
                    if ( 'login' === $attributes[ 'template' ] ) {
                        $login_form_message = apply_filters( 'b3_message_above_login', false );
                        if ( false != $login_form_message ) {
                            $messages[] = $login_form_message;
                        }
                    } elseif ( 'register' === $attributes[ 'template' ] ) {
                        if ( strpos( $registration_type, 'request_access' ) !== false ) {
                            $request_access_message = b3_get_message_above_request_access();
                            if ( false != $request_access_message ) {
                                $messages[] = $request_access_message;
                            }
                        } elseif ( 'email_activation' === $registration_type ) {
                            $registration_message = apply_filters( 'b3_message_above_registration', false );
                            if ( false != $registration_message ) {
                                $messages[] = $registration_message;
                            }
                        } else {
                            if ( ! is_admin() && ! current_user_can( 'manage_network' ) ) {
                                $message              = ( 'closed' === $registration_type ) ? b3_get_registration_closed_message() : false;
                                $registration_message = apply_filters( 'b3_message_above_registration', $message );
                                if ( false != $registration_message ) {
                                    $messages[] = $registration_message;
                                }
                            }
                        }
                    } elseif ( 'lostpassword' === $attributes[ 'template' ] ) {
                        $messages[] = esc_html__( b3_get_message_above_lost_password() );

                    } elseif ( 'resetpass' === $attributes[ 'template' ] ) {
                        $messages[] = esc_html__( 'Enter your new password.', 'b3-onboarding' );

                    } elseif ( 'magiclink' === $attributes[ 'template' ] ) {
                        $messages[] = esc_html__( b3_get_message_above_magiclink_form() );
                    }
                }
            }

            if ( ! empty( $messages ) ) {
                if ( isset( $attributes[ 'errors' ] ) && ! empty( $attributes[ 'errors' ] ) ) {
                    echo '<div class="b3_message b3_message--error">';
                } else {
                    echo '<div class="b3_message">';
                }
                foreach( $messages as $message ) {
                    echo sprintf( '<p>%s</p>', $message );
                }
                echo '</div>';
            }
        }
    }
    add_action( 'b3_add_form_messages', 'b3_render_form_messages' );


    /**
     * Action links on custom forms
     *
     * @param string $form_type
     */
    function b3_add_action_links( $form_type = 'login' ) {
        if ( true != apply_filters( 'b3_disable_action_links', get_option( 'b3_disable_action_links' ) ) ) {
            $links = [];
            
            $values = [
                'login'        => [
                    'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                    'link'  => b3_get_login_url(),
                ],
                'lostpassword' => [
                    'title' => esc_html__( 'Lost password', 'b3-onboarding' ),
                    'link'  => b3_get_lostpassword_url(),
                ],
                'register'     => [
                    'title' => esc_html__( 'Register', 'b3-onboarding' ),
                    'link'  => b3_get_register_url(),
                ],
            ];

            switch( $form_type ) {
                case 'login':
                    $links[] = $values[ 'lostpassword' ];
                    if ( 'none' != get_option( 'b3_registration_type' ) ) {
                        $links[] = $values[ 'register' ];
                    }
                    break;

                case 'register':
                    $links[] = $values[ 'login' ];
                    $links[] = $values[ 'lostpassword' ];
                    break;

                case 'lostpassword':
                    $links[] = $values[ 'login' ];
                    if ( 'none' != get_option( 'b3_registration_type' ) ) {
                        $links[] = $values[ 'register' ];
                    }
                    break;

                default:
                    $links = [];
            }

            if ( count( $links ) > 0 ) {
                ob_start();
                foreach( $links as $values ) {
                    echo sprintf( '<li><a href="%s" rel="nofollow">%s</a></li>', $values[ 'link' ], $values[ 'title' ] );
                }
                $action_links = ob_get_clean();
                echo sprintf( '<ul class="b3_form-links">%s</ul>', $action_links );
            }
        }
    }
    add_action( 'b3_add_action_links', 'b3_add_action_links' );


    /**
     * Resend user activation mail
     *
     * @since 2.5.0
     *
     * @TODO: add if to determine if user's registration type (still) matches current, otherwise incorrect email could be sent
     *
     * @param $user_id
     */
    function b3_send_user_activation( $user_id ) {
        if ( $user_id ) {
            $user_data    = get_userdata( $user_id );
            $wp_mail_vars = apply_filters( 'wp_new_user_notification_email', [], $user_data, get_bloginfo( 'name' ) );

            wp_mail(
                $wp_mail_vars[ 'to' ],
                $wp_mail_vars[ 'subject' ],
                $wp_mail_vars[ 'message' ],
                $wp_mail_vars[ 'headers' ]
            );
        }
    }
    add_action( 'b3_resend_user_activation', 'b3_send_user_activation' );


    /**
     * Manually activate a user
     *
     * @since 2.5.0
     *
     * @param $user_id
     */
    function b3_manually_activate_user( $user_id ) {
        if ( $user_id ) {
            $user    = get_userdata( $user_id );
            $user->set_role( get_option( 'default_role' ) );
            $to      = $user->user_email;
            $subject = b3_get_account_approved_subject();
            $subject = strtr( $subject, b3_get_replacement_vars( 'subject' ) );
            $message = b3_get_account_approved_message();
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_get_replacement_vars( 'message', [ 'user_data' => $user ] ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, [] );
        }
    }
    add_action( 'b3_manual_user_activate', 'b3_manually_activate_user' );


    /**
     * Inform admin about something
     *
     * @param $type
     *
     * @return void
     */
    function b3_inform_admin( $type ) {
        if ( $type ) {
            switch( $type ) {
                case 'request_access':
                    $subject = b3_get_request_access_subject_admin();
                    $message = b3_get_request_access_message_admin();
                    break;
                default:
                    $subject = '';
                    $message = '';
            }

            if ( ! empty( $subject ) && ! empty( $message ) ) {
                $admin_to = b3_get_notification_addresses( $type );
                $message  = b3_replace_template_styling( $message );
                $message  = strtr( $message, b3_get_replacement_vars() );
                $message  = htmlspecialchars_decode( stripslashes( $message ) );

                wp_mail( $admin_to, $subject, $message, [] );
            }
        }
    }
    add_action( 'b3_inform_admin', 'b3_inform_admin' );


    /**
     * Redirect a user
     *
     * @param $redirect_type
     * @param $redirect_to
     *
     * @return void
     */
    function b3_redirect( $redirect_type, $redirect_to = null ) {
        if ( 'logged_in' === $redirect_type ) {
            $current_user = wp_get_current_user();
            $user_role    = reset( $current_user->roles );
            if ( in_array( $user_role, get_option( 'b3_restrict_admin', [] ) ) ) {
                $redirect_url = b3_get_account_url();
                if ( false == $redirect_url ) {
                    $redirect_url = home_url();
                }
            } else {
                if ( $redirect_to ) {
                    $redirect_url = $redirect_to;
                } else {
                    $redirect_url = admin_url();
                }
            }
        }

        if ( isset( $redirect_url ) ) {
            wp_safe_redirect( $redirect_url );
            exit;
        }
    }
    add_action( 'b3_redirect', 'b3_redirect', 10, 2 );


    /**
     * Reset to default option
     *
     * @since 3.2.0
     */
    function b3_reset_to_default() {
        if ( function_exists( 'b3_get_all_custom_meta_keys' ) ) {
            $meta_keys   = b3_get_all_custom_meta_keys();
            $meta_keys[] = 'widget_b3-widget';

            // Remove old settings new settings
            foreach( $meta_keys as $key ) {
                delete_site_option( $key );
                delete_option( $key );
            }
        }
        // init new settings
        $blog_id = is_multisite() ? get_current_blog_id() : false;
        b3_set_default_settings( $blog_id );
    }
    add_action( 'b3_reset_to_default', 'b3_reset_to_default' );


    /**
     * Add before account page output
     *
     * @since 3.2.0
     *
     * @param $attributes
     * @param $current_user
     *
     * @return void
     */
    function b3_do_before_account( $attributes, $current_user ) {
        if ( is_multisite() ) {
            $user_sites = get_blogs_of_user( $current_user->ID );

            if ( ! empty( $user_sites ) ) {
                ob_start();
                foreach( $user_sites as $site_id => $site_info ) {
                    $disallowed_roles = ! array_diff( $current_user->roles, get_option( 'b3_restrict_admin', [
                        'b3_activation',
                        'b3_approval'
                    ] ) );
                    $admin_url = apply_filters( 'b3_dashboard_url', get_admin_url( $site_id ) );
                    $home_url  = get_home_url( $site_id );
                    $link      = sprintf( '<a href="%s">%s</a>', esc_url( $home_url ), $site_info->blogname );
                    
                    if ( false === $disallowed_roles ) {
                        $link .= sprintf( ' | <a href="%s">%s</a>', $admin_url, 'Admin' );
                    }
                    
                    echo sprintf( '<li>%s</li>', $link );
                }
                $links = ob_get_clean();

                if ( false != $links ) {
                    $label = sprintf( '<label class="b3_form-label" for="yoursites">%s</label>', esc_html__( 'Your site(s)', 'b3-onboarding' ) );
                    $list  = sprintf( '<ul class="site-links">%s</ul>', $links );
                    $links = sprintf( '<div class="site-links">%s</div>', $list );
                    
                    echo sprintf( '<div class="b3_form-element b3_form-element-my-sites">%s%s</div>', $label, $links );
                }
            }
        }
    }
    add_action( 'b3_do_before_account', 'b3_do_before_account', 10, 2 );


    /**
     * Render a form element
     *
     * @since 3.2.0
     *
     * @param $element
     * @param array $attributes
     * @param false $current_user
     */
    function b3_render_form_element( $element, $attributes = [], $current_user = false ) {
        b3_get_template( $element, $attributes, $current_user);
    }
    add_action( 'b3_render_form_element', 'b3_render_form_element', 10, 3 );


    /**
     * Remove welcome page meta
     *
     * @since 3.4.0
     *
     * @return void
     */
    function b3_remove_welcome_page_meta() {
        $user_args = [
            'fields'     => 'ids',
            'meta_query' => [
                [
                    'key'   => 'b3_welcome_page_seen',
                    'value' => 'true',
                ],
            ],
        ];
        $users = get_users( $user_args );
        if ( ! empty( $users ) ) {
            foreach( $users as $user_id ) {
                delete_user_meta( $user_id, 'b3_welcome_page_seen' );
            }
        }
    }
    add_action( 'b3_remove_welcome_page_meta', 'b3_remove_welcome_page_meta', 10, 3 );


    /**
     * Add custom fields to register form hook
     *
     * @param $attributes
     *
     * @return void
     *
     * @since 1.0.0
     */
    function b3_add_registration_fields( $attributes ) {
        do_action( 'b3_add_hidden_fields_registration', $attributes );
        do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
        do_action( 'b3_add_first_last_name_fields', $attributes[ 'registration_type' ] );
        do_action( 'b3_add_password_fields' );
        do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] ); // MS
        do_action( 'b3_add_extra_fields_registration' );
        do_action( 'b3_add_privacy_checkbox' );
        do_action( 'b3_add_recaptcha_fields' );
        do_action( 'b3_render_form_element', 'general/button', $attributes );
        do_action( 'b3_add_action_links', $attributes[ 'template' ] );
    }
    add_action( 'b3_register_form', 'b3_add_registration_fields' );
    
    
    /**
     * Log a user in after OTP verification
     *
     * @param $user
     * @param $redirect
     *
     * @return void
     *
     * @since 3.11.0
     */
    function b3_log_user_in( $user, $redirect = '' ) {
        $account_url = b3_get_account_url();
        $account_url = add_query_arg( 'message', 'logged_in', $account_url );
        $redirect    = ! empty( $redirect ) ? $redirect : $account_url;
        
        if ( $user instanceof WP_User ) {
            wp_set_current_user( $user->ID, $user->user_login );
            wp_set_auth_cookie( $user->ID );
            delete_transient( sprintf( 'otp_', $user->user_email ) );
            do_action( 'wp_login', $user->user_login, $user );
            
            wp_redirect( $redirect );
            exit;
        }
    }
    add_action( 'b3_log_user_in', 'b3_log_user_in' );
