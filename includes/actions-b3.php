<?php
    // This file contains functions hooked to the plugin's own hooks

    /**
     * Approve a user
     *
     * @param $user_id
     *
     * @since 1.0.0
     *
     */
    function b3_do_stuff_after_new_user_approved_by_admin( $user_id ) {
        $custom_passwords  = get_site_option( 'b3_activate_custom_passwords', false );
        $user_object       = get_userdata( $user_id );
        $user_login        = $user_object->user_login;
        $user_object->set_role( get_option( 'default_role' ) );

        if ( false == $custom_passwords ) {
            // user needs a password
            $key                 = get_password_reset_key( $user_object );
            $reset_pass_url      = b3_get_reset_password_url();
            $vars[ 'reset_url' ] = $reset_pass_url . "?action=rp&key=" . $key . "&login=" . rawurlencode( $user_login );
        } else {
            // user has set a custom password or requests access
            $vars = [];
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
    add_action( 'b3_approve_user', 'b3_do_stuff_after_new_user_approved_by_admin' );


    /**
     * Reject a user (by admin)
     *
     * @param $user_id
     *
     * @since 2.5.0
     */
    function b3_do_stuff_before_reject_user_by_admin( $user_id ) {
        if ( false == get_site_option( 'b3_disable_delete_user_email', false ) ) {
            $user_object = get_userdata( $user_id );
            $to          = $user_object->user_email;
            $subject     = apply_filters( 'b3_account_rejected_subject', b3_get_account_rejected_subject() );
            $message     = apply_filters( 'b3_account_rejected_message', b3_get_account_rejected_message() );

            if ( in_array( 'b3_approval', $user_object->roles ) || in_array( 'b3_activation', $user_object->roles ) ) {
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
     * @param $user_id
     *
     * @since 1.0.0
     *
     * @TODO  : check for a WordPress hook to hook to
     * @TODO  : look into filter 'registration_redirect'
     *
     */
    function b3_do_stuff_after_user_activated( $user_id ) {
        if ( 1 != get_site_option( 'b3_disable_admin_notification_new_user', false ) ) {
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
        if ( 'email_activation' == get_site_option( 'b3_registration_type', false ) ) {
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
     */
    function b3_add_username_email_fields() {
        $registration_type            = get_site_option( 'b3_registration_type', false );
        $registration_with_email_only = get_site_option( 'b3_register_email_only', false );

        ob_start();

        if ( is_multisite() ) {
            ?>
            <div class="b3_form-element b3_form-element--login">
                <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="text" name="user_name" id="b3_user_login" class="b3_form--input" autocapitalize="none" autocomplete="off" spellcheck="false" maxlength="60" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_username', 'dummy' ) : ''; ?>" required>
            </div>
            <div class="b3_form-element b3_form-element--email">
                <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_email', 'dummy@email.com' ) : ''; ?>" required>
            </div>

            <?php if ( 'ms_register_site_user' == $registration_type ) { ?>
                <div class="b3_form-element b3_form-element--signup-for">
                    <div>
                        <strong><?php esc_html_e( 'Register for', 'b3-onboarding' ); ?>:</strong>
                    </div>
                    <input id="signupblog" type="radio" name="signup_for" value="blog" checked="checked">
                    <label class="checkbox" for="signupblog"><?php echo apply_filters( 'b3_signup_for_site', __( 'A site' ) ); ?></label>
                    <input id="signupuser" type="radio" name="signup_for" value="user">
                    <label class="checkbox" for="signupuser"><?php echo apply_filters( 'b3_signup_for_user', __( 'Just a user' ) ); ?></label>
                </div>
            <?php } elseif ( 'none' != $registration_type ) { ?>
                <input type="hidden" name="signup_for" value="user" />
            <?php } ?>
            <?php
        } else {
            if ( false == $registration_with_email_only ) {
                ?>
                <div class="b3_form-element b3_form-element--login">
                    <label class="b3_form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-onboarding' ); ?> <strong>*</strong></label>
                    <input type="text" name="user_login" id="b3_user_login" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_username', 'dummy' ) : ''; ?>" required>
                </div>
            <?php } else { ?>
                <input type="hidden" name="user_login" value="<?php echo b3_generate_user_login(); ?>">
            <?php } ?>
            <div class="b3_form-element b3_form-element--email">
                <label class="b3_form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-onboarding' ); ?> <strong>*</strong></label>
                <input type="email" name="user_email" id="b3_user_email" class="b3_form--input" value="<?php echo ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? apply_filters( 'b3_localhost_email', 'dummy@email.com' ) : ''; ?>" required>
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
        $activate_first_last = get_site_option( 'b3_activate_first_last', false );
        if ( $activate_first_last ) {
            $first_last_required = get_site_option( 'b3_first_last_required', false );
            $first_name          = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? 'First' : false;
            $last_name           = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : ( defined( 'LOCALHOST' ) && true == LOCALHOST ) ? 'Last' : false;
            $required            = ( true == $first_last_required ) ? ' required="required"' : false;
            ob_start();
            do_action( 'b3_do_before_first_last_name' );
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
            do_action( 'b3_do_after_first_last_name' );
            $output = ob_get_clean();
            echo $output;
        }
    }
    add_action( 'b3_add_first_last_name_fields', 'b3_first_last_name_fields' );


    /**
     * Output the password fields
     *
     * @since 0.8-beta
     */
    function b3_add_password_fields() {
        $registration_type     = get_site_option( 'b3_registration_type', false );
        $show_custom_passwords = get_site_option( 'b3_activate_custom_passwords', false );
        if ( $show_custom_passwords && in_array( $registration_type, [ 'email_activation', 'open' ] ) ) {
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
     */
    function b3_add_site_fields() {
        if ( is_multisite() ) {
            if ( in_array( get_site_option( 'b3_registration_type' ), array(
                    'request_access_subdomain',
                    'ms_loggedin_register',
                    'ms_register_site_user',
                ) ) ) {
                ob_start();
                $register_for = apply_filters( 'b3_register_for', false );
                if ( false === $register_for || false != $register_for && 'blog' == $register_for ) {
            ?>
                <div class="b3_form-element b3_form-element--site-fields">
                    <div class="b3_form-element b3_form-element--subdomain">
                        <?php $current_network = get_network(); ?>
                        <?php if ( is_subdomain_install() ) { ?>
                            <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site (sub) domain', 'b3-onboarding' ); ?></label>
                            <input name="blogname" id="blogname" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'customdomain', 'b3-onboarding' ); ?>" />.<?php echo $_SERVER[ 'HTTP_HOST' ]; ?>
                        <?php } else { ?>
                            <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site address', 'b3-onboarding' ); ?></label>
                            <?php echo $current_network->domain . $current_network->path; ?><input name="blogname" id="blogname" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'address', 'b3-onboarding' ); ?>" />
                        <?php } ?>
                    </div>

                    <div class="b3_form-element b3_form-element--site-title">
                        <label class="b3_form-label" for="blog_title"><?php esc_html_e( 'Site title', 'b3-onboarding' ); ?></label>
                        <input name="blog_title" id="blog_title" value="" type="text" class="b3_form--input" />
                    </div>

                    <?php // @TODO: add languages option ?>
                    <div class="b3_form-element b3_form-element--visbility">
                        <p class="privacy-intro">
                            <strong><?php _e( 'Privacy', 'b3-onboarding' ); ?></strong>
                            <br style="clear:both" />
                            <label class="checkbox" for="blog_public_on">
                                <input type="checkbox" id="blog_public_on" name="dont_index" value="1" />
                                <?php _e( "Don't let search engines index this site.", 'b3-onboarding' ); ?>
                            </label>
                        </p>
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
    function b3_add_hidden_fields_registration() {
        $hidden_field_values = apply_filters( 'b3_hidden_fields', array() );
        if ( is_array( $hidden_field_values ) && ! empty( $hidden_field_values ) ) {
            $hidden_fields = '';
            foreach( $hidden_field_values as $key => $value ) {
                $hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
            }
            echo $hidden_fields;
        }
    }
    add_action( 'b3_add_hidden_fields_registration', 'b3_add_hidden_fields_registration' );


    /**
     * Add reCAPTCHA check
     *
     * @param string $form_type
     *
     * @since 2.0.0
     *
     */
    function b3_add_recaptcha_fields( $form_type = 'register' ) {
        $activate_recaptcha = get_site_option( 'b3_activate_recaptcha', false );
        $recaptcha_on       = get_site_option( 'b3_recaptcha_on', [] );
        $recaptcha_public   = get_site_option( 'b3_recaptcha_public', false );
        $recaptcha_version  = get_site_option( 'b3_recaptcha_version', '2' );

        if ( false != $activate_recaptcha ) {
            if ( false != $recaptcha_public && '3' != $recaptcha_version ) {
                if ( in_array( $form_type, $recaptcha_on ) ) {
                    do_action( 'b3_do_before_recaptcha_' . $form_type );
                    ?>
                    <div class="b3_form-element b3_form-element--recaptcha">
                        <div class="recaptcha-container">
                            <div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_public; ?>"></div>
                        </div>
                    </div>
                    <?php
                    do_action( 'b3_do_after_recaptcha_' . $form_type );
                }
            }
        }
    }
    add_action( 'b3_add_recaptcha_fields', 'b3_add_recaptcha_fields' );


    /**
     * Function to output a privacy checkbox
     */
    function b3_add_privacy_checkbox() {
        $show_privacy = get_site_option( 'b3_privacy', false );
        if ( true == $show_privacy ) {
            do_action( 'b3_do_before_privacy_checkbox' );
            ?>
            <div class="b3_form-element b3_form-element--privacy">
                <label>
                    <input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/> <?php echo htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) ); ?>
                </label>
            </div>
            <?php
            do_action( 'b3_do_after_privacy_checkbox' );
        }
    }
    add_action( 'b3_add_privacy_checkbox', 'b3_add_privacy_checkbox' );


    /**
     * Echo error/info message above a (custom) form
     *
     * @param bool $attributes
     *
     * @return bool
     * @since 2.0.0
     *
     */
    function b3_render_form_messages( $attributes = false ) {

        if ( false != $attributes ) {
            $messages          = array();
            $show_errors       = false;
            $registration_type = get_site_option( 'b3_registration_type', false );

            if ( isset( $attributes[ 'errors' ] ) && 0 < count( $attributes[ 'errors' ] ) ) {
                $show_errors = true;
                foreach( $attributes[ 'errors' ] as $error ) {
                    $messages[] = $error;
                }
            } elseif ( isset( $attributes[ 'messages' ] ) && 0 < count( $attributes[ 'messages' ] ) ) {
                $show_errors = true;
                foreach( $attributes[ 'messages' ] as $message ) {
                    $messages[] = $message;
                }
            } else {
                if ( isset( $attributes[ 'template' ] ) && 'lostpassword' == $attributes[ 'template' ] ) {
                    $show_errors = true;
                    $messages[]  = esc_html__( apply_filters( 'b3_message_above_lost_password', b3_get_message_above_lost_password() ) );
                } elseif ( isset( $attributes[ 'template' ] ) && 'register' == $attributes[ 'template' ] ) {
                    if ( 'request_access' == $registration_type ) {
                        $request_access_message = __( apply_filters( 'b3_message_above_request_access', b3_get_message_above_request_access() ) );
                        if ( false != $request_access_message ) {
                            $show_errors = true;
                            $messages[]  = $request_access_message;
                        }
                    } elseif ( 'closed' != $registration_type ) {
                        $registration_message = apply_filters( 'b3_message_above_registration', false );
                        if ( false != $registration_message ) {
                            $show_errors = true;
                            $messages[]  = $registration_message;
                        }
                    }
                } elseif ( isset( $attributes[ 'template' ] ) && 'resetpass' == $attributes[ 'template' ] ) {
                    $show_errors = true;
                    $messages[]  = esc_html__( 'Enter your new password.', 'b3-onboarding' );
                }
            }

            if ( true == $show_errors && ! empty( $messages ) ) {
                echo '<div class="b3_message">';
                foreach( $messages as $message ) {
                    echo '<p>';
                    echo $message;
                    echo '</p>';
                }
                echo '</div>';
            }
        }

        return false;
    }
    add_action( 'b3_add_form_messages', 'b3_render_form_messages' );

    function b3_add_action_links( $form_type = 'login' ) {

        if ( true != apply_filters( 'b3_disable_action_links', get_site_option( 'b3_disable_action_links', false ) ) ) {
            $page_types = array();

            switch( $form_type ) {

                case 'login':
                    $page_types[ 'lostpassword' ] = [
                        'title' => esc_html__( 'Lost password', 'b3-onboarding' ),
                        'link'  => b3_get_lostpassword_url(),
                    ];
                    if ( 'closed' != get_site_option( 'b3_registration_type', false ) ) {
                        $page_types[ 'register' ] = [
                            'title' => esc_html__( 'Register', 'b3-onboarding' ),
                            'link'  => b3_get_register_url(),
                        ];
                    }
                    break;

                case 'register':
                    $page_types[ 'login' ] = [
                        'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                        'link'  => b3_get_login_url(),
                    ];
                    $page_types[ 'fogotpassword' ] = [
                        'title' => esc_html__( 'Lost password', 'b3-onboarding' ),
                        'link'  => b3_get_lostpassword_url(),
                    ];
                    break;

                case 'lostpassword':
                    $page_types[ 'login' ] = [
                        'title' => esc_html__( 'Log In', 'b3-onboarding' ),
                        'link'  => b3_get_login_url(),
                    ];
                    if ( 'closed' != get_site_option( 'b3_registration_type', false ) ) {
                        $page_types[ 'register' ] = [
                            'title' => esc_html__( 'Register', 'b3-onboarding' ),
                            'link'  => b3_get_register_url(),
                        ];
                    }
                    break;

                default:
                    break;
            }

            if ( count( $page_types ) > 0 ) {
                echo '<ul class="b3_form-links">';
                foreach( $page_types as $key => $values ) {
                    echo '<li><a href="' . $values[ 'link' ] . '" rel="nofollow">' . $values[ 'title' ] . '</a></li>';
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
     * @TODO: add if to determine if user's registration type (still) matches current,
     * otherwise incorrect email will be sent
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
