<?php
    // This file contains functions hooked to the plugin's own hooks

    /**
     * Approve a user
     *
     * @since 1.0.0
     *
     * @param $arguments
     * @return void
     */
    function b3_do_stuff_after_new_user_approved_by_admin( $arguments ) {

        if ( is_multisite() ) {
            // get activation key
            error_log('@TODO: b3_do_stuff_after_new_user_approved_by_admin');
        } else {
            $custom_passwords  = get_option( 'b3_activate_custom_passwords' );
            $user_object       = get_userdata( $arguments[ 'user_id' ] );
            $user_login        = $user_object->user_login;
            $user_object->set_role( get_option( 'default_role' ) );

            if ( false == $custom_passwords ) {
                // user needs a password
                $key                 = get_password_reset_key( $user_object );
                $reset_pass_url      = b3_get_reset_password_url();
                $vars[ 'reset_url' ] = $reset_pass_url . '?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login );
            } else {
                // user has set a custom password or requests access
                $vars = array();
            }

            $to      = $user_object->user_email;
            $subject = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
            $subject = strtr( $subject, b3_replace_subject_vars() );
            $message = apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() );
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_replace_email_vars( $vars ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, array() );
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
        $wpdb->update(
            $wpdb->prefix . 'signups', array( 'meta' => $signup_info->meta ), array( 'signup_id' => $signup_info->signup_id ), array( '%s' )
        );

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
            $message   = apply_filters( 'b3_account_rejected_message', b3_get_account_rejected_message() );
            $subject   = apply_filters( 'b3_account_rejected_subject', b3_get_account_rejected_subject() );

            if ( isset( $user_info[ 'user_id' ] ) ) {
                $user_object = get_userdata( $user_info[ 'user_id' ] );
                $to          = $user_object->user_email;
            } elseif ( isset( $user_info[ 'user_email' ] ) ) {
                $multisite = true;
                $to        = $user_info[ 'user_email' ];
            }

            if ( $multisite || in_array( 'b3_approval', $user_object->roles ) || in_array( 'b3_activation', $user_object->roles ) ) {
                $message = b3_replace_template_styling( $message );
                $message = strtr( $message, b3_replace_email_vars() );
                $message = htmlspecialchars_decode( stripslashes( $message ) );
                wp_mail( $to, $subject, $message, array() );
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
            $admin_to      = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( 'email_activation' ) );
            $admin_subject = apply_filters( 'b3_new_user_subject', b3_get_new_user_subject() );
            $admin_email   = apply_filters( 'b3_new_user_message', b3_get_new_user_message() );
            $admin_email   = b3_replace_template_styling( $admin_email );
            $admin_email   = strtr( $admin_email, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $admin_email   = htmlspecialchars_decode( stripslashes( $admin_email ) );
            $admin_message = $admin_email;

            wp_mail( $admin_to, $admin_subject, $admin_message, array() );
        }

        // send 'account activated' email to user
        if ( 'email_activation' == get_option( 'b3_registration_type' ) ) {
            $user    = get_userdata( $user_id );
            $to      = $user->user_email;
            $subject = apply_filters( 'b3_account_activated_subject_user', b3_get_account_activated_subject_user() );
            $message = apply_filters( 'b3_account_activated_message_user', b3_get_account_activated_message_user() );
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, array() );
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
        ob_start();
        if ( is_multisite() ) {
            ?>
            <div class="b3_form-element b3_form-element--login">
                <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="text" name="user_name" id="b3_user_login" class="b3_form--input" autocapitalize="none" autocomplete="off" spellcheck="false" maxlength="60" value="<?php echo apply_filters( 'b3_localhost_username', false ); ?>" required>
            </div>
            <div class="b3_form-element b3_form-element--email">
                <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo apply_filters( 'b3_localhost_email', false ); ?>" required>
            </div>

            <?php if ( 'all' == $registration_type ) { ?>
                <div class="b3_form-element b3_form-element--signup-for">
                    <label class="b3_form-label" for=""><?php esc_html_e( 'Register for', 'b3-onboarding' ); ?></label>
                    <input id="signupblog" type="radio" name="signup_for" value="blog" checked="checked">
                    <label class="checkbox" for="signupblog"><?php echo apply_filters( 'b3_signup_for_site', esc_attr__( 'A site' ) ); ?></label>
                    <input id="signupuser" type="radio" name="signup_for" value="user">
                    <label class="checkbox" for="signupuser"><?php echo apply_filters( 'b3_signup_for_user', esc_attr__( 'Just a user' ) ); ?></label>
                </div>
            <?php } elseif ( in_array( $registration_type, [ 'blog', 'site' ] ) ) { ?>
                <input type="hidden" name="signup_for" value="blog" />
            <?php } elseif ( 'user' == $registration_type ) { ?>
                <input type="hidden" name="signup_for" value="user" />
            <?php } ?>
        <?php } else {
            if ( false == get_option( 'b3_register_email_only' ) ) {
                ?>
                <div class="b3_form-element b3_form-element--login">
                    <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                    <input type="text" name="user_login" id="b3_user_login" class="b3_form--input" value="<?php echo apply_filters( 'b3_localhost_username', false ); ?>" required>
                </div>
            <?php } else { ?>
                <input type="hidden" name="user_login" value="<?php echo b3_generate_user_login(); ?>">
            <?php } ?>
            <div class="b3_form-element b3_form-element--email">
                <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo apply_filters( 'b3_localhost_email', false ); ?>" required>
            </div>
            <?php
        }

        $output = ob_get_clean();
        echo $output;
    }
    add_action( 'b3_add_username_email_fields', 'b3_add_username_email_fields' );


    /**
     * Output for first/last name fields
     *
     * @since 0.8-beta
     */
    function b3_first_last_name_fields() {
        if ( get_option( 'b3_activate_first_last' ) && 1 != get_option( 'b3_register_email_only' ) ) {
            $first_last_required = get_option( 'b3_first_last_required' );
            $first_name          = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : false;
            $last_name           = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : false;
            $required            = ( true == $first_last_required ) ? ' required="required"' : false;

            do_action( 'b3_do_before_first_last_name' );
            ob_start();
        ?>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?><?php if ( $required ) { ?> <strong>*</strong><?php } ?></label>
                <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo $first_name; ?>"<?php echo $required; ?>>
            </div>
            <div class="b3_form-element b3_form-element--register">
                <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?><?php if ( $required ) { ?> <strong>*</strong><?php } ?></label>
                <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo $last_name; ?>"<?php echo $required; ?>>
            </div>
        <?php
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
        if ( get_option( 'b3_activate_custom_passwords' ) && in_array( get_option( 'b3_registration_type' ), [ 'email_activation', 'open' ] ) ) {
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
        if ( is_multisite() ) {
            if ( in_array( $registration_type, array(
                    'request_access_subdomain',
                    'blog',
                    'all',
                    'site',
                ) ) ) {
                $register_for = apply_filters( 'b3_register_for', false );
                ob_start();
                if ( false === $register_for || false != $register_for && 'blog' == $register_for ) {
            ?>
                <div class="b3_site-fields">
                    <?php
                        if ( ( false === $register_for || 'blog' == $register_for ) ) {
                            $b3_message_above_new_blog = esc_html__( 'Here you can register your new site.', 'b3-onboarding' );
                            $notice                    = apply_filters( 'b3_message_above_new_blog', $b3_message_above_new_blog );
                            if ( false !== $notice ) {
                                echo '<div class="b3_site-fields-header">' . $notice . '</div>';
                            }
                        }
                    ?>
                    <div class="b3_form-element b3_form-element--subdomain">
                        <?php $current_network = get_network(); ?>
                        <?php if ( is_subdomain_install() ) { ?>
                            <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site (sub) domain', 'b3-onboarding' ); ?></label>
                            <input name="blogname" id="blogname" value="<?php echo apply_filters( 'b3_localhost_blogname', false ); ?>" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-onboarding' ); ?>" />
                            .<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>
                        <?php } else { ?>
                            <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site address', 'b3-onboarding' ); ?></label>
                            <?php echo $current_network->domain . $current_network->path; ?><input name="blogname" id="blogname" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'address', 'b3-onboarding' ); ?>" />
                        <?php } ?>
                    </div>

                    <div class="b3_form-element b3_form-element--site-title">
                        <label class="b3_form-label" for="blog_title"><?php esc_html_e( 'Site title', 'b3-onboarding' ); ?></label>
                        <input name="blog_title" id="blog_title" value="<?php echo apply_filters( 'b3_localhost_blogtitle', false ); ?>" type="text" class="b3_form--input" />
                    </div>

                    <?php // @TODO: add languages option ?>
                    <div class="b3_form-element b3_form-element--visbility">
                        <div class="privacy-intro">
                            <?php _e( 'Allow search engines to index this site.', 'b3-onboarding' ); ?>
                            <label class="checkbox" for="blog_public_on">
                                <input type="radio" id="blog_public_on" name="blog_public" value="1" checked />
                                <?php _e( 'Yes' ); ?>
                            </label>
                            <label class="checkbox" for="blog_public_off">
                                <input type="radio" id="blog_public_off" name="blog_public" value="0" />
                                <?php _e( 'No' ); ?>
                            </label>
                        </div>
                    </div>
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
        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
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
                    do_action( 'b3_do_before_recaptcha_register' );
                    ?>
                    <div class="b3_form-element b3_form-element--recaptcha">
                        <div class="recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                        </div>
                    </div>
                    <?php
                        do_action( 'b3_do_after_recaptcha_register' );
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
            do_action( 'b3_do_before_privacy_checkbox' );
            $input = '<input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/>';
            $label = htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) );
            echo sprintf( '<div class="b3_form-element b3_form-element--privacy"><label class="b3_form-label">%s</label> %s</div>', $label, $input );
            do_action( 'b3_do_after_privacy_checkbox' );
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
            $messages          = array();
            $registration_type = get_option( 'b3_registration_type' );
            $show_messages     = false;

            if ( isset( $attributes[ 'errors' ] ) && 0 < count( $attributes[ 'errors' ] ) ) {
                $show_messages = true;
                foreach( $attributes[ 'errors' ] as $error ) {
                    $messages[] = $error;
                }
            } elseif ( isset( $attributes[ 'messages' ] ) && 0 < count( $attributes[ 'messages' ] ) ) {
                $show_messages = true;
                foreach( $attributes[ 'messages' ] as $message ) {
                    $messages[] = $message;
                }
            } else {
                if ( isset( $attributes[ 'template' ] ) ) {
                    if ( 'login' == $attributes[ 'template' ] ) {
                        $login_form_message = apply_filters( 'b3_message_above_login', false );
                        if ( false != $login_form_message ) {
                            $messages[]    = $login_form_message;
                            $show_messages = true;
                        }
                    } elseif ( 'register' == $attributes[ 'template' ] ) {
                        if ( strpos( $registration_type, 'request_access' ) !== false ) {
                            $request_access_message = apply_filters( 'b3_message_above_request_access', b3_get_message_above_request_access() );
                            if ( false != $request_access_message ) {
                                $messages[]    = $request_access_message;
                                $show_messages = true;
                            }
                        } elseif ( 'email_activation' == $registration_type ) {
                            $registration_message = apply_filters( 'b3_message_above_registration', false );
                            if ( false != $registration_message ) {
                                $messages[]    = $registration_message;
                                $show_messages = true;
                            }
                        } else {
                            if ( ! is_admin() && ! current_user_can( 'manage_network' ) ) {
                                $message              = ( 'closed' == $registration_type ) ? b3_get_registration_closed_message() : false;
                                $registration_message = apply_filters( 'b3_message_above_registration', $message );
                                if ( false != $registration_message ) {
                                    $messages[]    = $registration_message;
                                    $show_messages = true;
                                }
                            }
                        }
                    } elseif ( 'lostpassword' == $attributes[ 'template' ] ) {
                        $messages[]    = esc_html__( apply_filters( 'b3_message_above_lost_password', b3_get_message_above_lost_password() ) );
                        $show_messages = true;
                    } elseif ( 'resetpass' == $attributes[ 'template' ] ) {
                        $messages[]    = esc_html__( 'Enter your new password.', 'b3-onboarding' );
                        $show_messages = true;
                    }
                }
            }

            if ( true == $show_messages && ! empty( $messages ) ) {
                if ( isset( $attributes[ 'errors' ] ) ) {
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
            $links = array();

            $values = [
                'login' => [
                    'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                    'link'  => b3_get_login_url(),
                ],
                'lostpassword' => [
                    'title' => esc_html__( 'Lost password', 'b3-onboarding' ),
                    'link'  => b3_get_lostpassword_url(),
                ],
                'register' => [
                    'title' => esc_html__( 'Register', 'b3-onboarding' ),
                    'link'  => b3_get_register_url(),
                ],
            ];

            switch( $form_type ) {
                case 'login':
                    $links[] = $values['lostpassword'];
                    if ( 'none' != get_option( 'b3_registration_type' ) ) {
                        $links[] = $values['register'];
                    }
                    break;

                case 'register':
                    $links[] = $values[ 'login' ];
                    $links[] = $values[ 'lostpassword' ];
                    break;

                case 'lostpassword':
                    $links[] = $values[ 'lostpassword' ];
                    if ( 'none' != get_option( 'b3_registration_type' ) ) {
                        $links[] = $values[ 'register' ];
                    }
                    break;

                default:
                    $links = [];
            }

            if ( count( $links ) > 0 ) {
                echo '<ul class="b3_form-links">';
                foreach( $links as $key => $values ) {
                    echo sprintf( '<li><a href="%s" rel="nofollow">%s</a></li>', $values[ 'link' ], $values[ 'title' ] );
                }
                echo '</ul>';
            }
        }
    }
    add_action( 'b3_add_action_links', 'b3_add_action_links' );


    /**
     * Resend user activation mail
     *
     * @since 2.5.0
     *
     * @TODO: add if to determine if user's registration type (still) matches current, otherwise incorrect email will be sent
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
            $subject = apply_filters( 'b3_account_approved_subject', b3_get_account_approved_subject() );
            $subject = strtr( $subject, b3_replace_subject_vars() );
            $message = apply_filters( 'b3_account_approved_message', b3_get_account_approved_message() );
            $message = b3_replace_template_styling( $message );
            $message = strtr( $message, b3_replace_email_vars( [ 'user_data' => $user ] ) );
            $message = htmlspecialchars_decode( stripslashes( $message ) );

            wp_mail( $to, $subject, $message, array() );
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
                    $subject = apply_filters( 'b3_request_access_subject_admin', b3_get_request_access_subject_admin() );
                    $message = apply_filters( 'b3_request_access_message_admin', b3_get_request_access_message_admin() );
                    break;
                default:
                    $subject = '';
                    $message = '';
            }

            if ( ! empty( $subject ) && ! empty( $message ) ) {
                $admin_to = apply_filters( 'b3_new_user_notification_addresses', b3_get_notification_addresses( get_option( 'b3_registration-type' ) ) );
                $message  = b3_replace_template_styling( $message );
                $message  = strtr( $message, b3_replace_email_vars() );
                $message  = htmlspecialchars_decode( stripslashes( $message ) );
                
                wp_mail( $admin_to, $subject, $message, array() );
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
        if ( 'logged_in' == $redirect_type ) {
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
    function b3_before_account( $attributes, $current_user ) {
        if ( is_multisite() ) {
            $user_sites = get_blogs_of_user( $current_user->ID );

            if ( ! empty( $user_sites ) ) {
                ob_start();
                foreach( $user_sites as $site_id => $site_info ) {
                    $admin_url        = apply_filters( 'b3_dashboard_url', get_admin_url( $site_id ) );
                    $disallowed_roles = ! array_diff( $current_user->roles, get_option( 'b3_restrict_admin', [
                        'b3_activation',
                        'b3_approval'
                    ] ) );
                    $home_url         = get_home_url( $site_id );
                    echo '<li>';
                    $link             = sprintf( '<a href="%s">%s</a>', esc_url( $home_url ), $site_info->blogname );

                    if ( false == $disallowed_roles ) {
                        $link .= sprintf( ' | <a href="%s">%s</a>', $admin_url, 'Admin' );
                    }
                    echo $link;
                    echo '</li>';
                }
                $links = ob_get_clean();
                if ( false != $links ) {
                    $list  = sprintf( '<ul>%s</ul>', $links );
                    $label = sprintf( '<label class="b3_form-label" for="yoursites">%s</label>', esc_html__( 'Your site(s)', 'b3-onboarding' ) );
                    echo sprintf( '<div class="b3_form-element b3_form-element-my-sites">%s<div class="site-links">%s</div></div>', $label, $list );
                }
            }
        }
    }
    add_action( 'b3_before_account', 'b3_before_account', 10, 2 );


    /**
     * Render a form element
     *
     * @since 3.2.0
     *
     * @param $element
     * @param array $attributes
     * @param false $current_user_object
     */
    function b3_render_form_element( $element, $attributes = [], $current_user_object = false ) {
        b3_get_template( $element, $attributes, $current_user_object);
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
            'fields' => 'ids',
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
    
    
    /*
     * This file contains functions hooked to the WordPress' hooks
     */
    
    /**
     * Add custom fields to WordPress' default register form hook
     *
     * @since 1.0.0
     */
    function b3_add_registration_fields( $attributes ) {
        do_action( 'b3_add_hidden_fields_registration', $attributes );
        
        if ( 'blog' != $attributes[ 'registration_type' ] ) {
            do_action( 'b3_add_username_email_fields', $attributes[ 'registration_type' ] );
        }
        do_action( 'b3_add_first_last_name_fields' );
        
        if ( ! is_multisite() ) {
            do_action( 'b3_add_password_fields' );
        } elseif ( is_main_site() ) {
            do_action( 'b3_add_site_fields', $attributes[ 'registration_type' ] );
        }
        
        do_action( 'b3_add_extra_fields_registration' );
        do_action( 'b3_add_privacy_checkbox' );
        do_action( 'b3_add_recaptcha_fields' );
        do_action( 'b3_do_before_submit_registration_form' );
    
        if ( ! is_multisite() && 'request_access' == $attributes[ 'registration_type' ] ) {
            $submit_label = esc_attr__( 'Request access', 'b3-onboarding' );
        } else {
            $submit_label = esc_attr__( 'Register', 'b3-onboarding' );
        }
        ?>
        
        <div class="b3_form-element b3_form-element--submit">
            <?php b3_get_submit_button( $submit_label, 'register', $attributes ); ?>
        </div>
        
        <?php
            do_action( 'b3_do_after_submit_registration_form' );
            do_action( 'b3_add_action_links', $attributes[ 'template' ] );
    }
    add_action( 'b3_register_form', 'b3_add_registration_fields' );
