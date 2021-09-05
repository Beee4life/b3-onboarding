<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Create initial pages upon activation
     *
     * @since 1.0.6
     *
     * @param bool $site_id
     */
    function b3_setup_initial_pages( $site_id = false ) {
        if ( false == $site_id ) {
            $site_id = get_current_blog_id();
        }

        switch_to_blog($site_id);

        $page_definitions = array(
            _x( 'account', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Account', 'b3-onboarding' ),
                'content' => '[account-page]',
                'meta'    => 'b3_account_page_id'
            ),
            _x( 'lostpassword', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Lost password', 'b3-onboarding' ),
                'content' => '[lostpass-form]',
                'meta'    => 'b3_lost_password_page_id'
            ),
            _x( 'login', 'slug', 'b3-onboarding' )           => array(
                'title'   => esc_html__( 'Login', 'b3-onboarding' ),
                'content' => '[login-form]',
                'meta'    => 'b3_login_page_id'
            ),
            _x( 'logout', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'content' => '',
                'meta'    => 'b3_logout_page_id'
            ),
            _x( 'register', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Register', 'b3-onboarding' ),
                'content' => '[register-form]',
                'meta'    => 'b3_register_page_id'
            ),
            _x( 'reset-password', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'content' => '[resetpass-form]',
                'meta'    => 'b3_reset_password_page_id'
            ),
        );
        b3_create_pages( $page_definitions );

        restore_current_blog();
    }

    /**
     * Create pages
     *
     * @since 1.0.6
     *
     * @param array $page_definitions
     */
    function b3_create_pages( $page_definitions = array() ) {
        foreach ( $page_definitions as $slug => $page ) {

            // Check if there's a page assigned already
            $stored_id = get_option( $slug );
            if ( $stored_id ) {
                $check_page = get_post( $stored_id );
                if ( ! $check_page ) {
                    delete_option( $page[ 'meta' ] );
                }
            } else {
                // no stored id, so continue
            }

            $existing_page_args = array(
                'post_type'      => 'page',
                'posts_per_page' => 1,
                'pagename'       => $slug,
            );
            $existing_page = get_posts( $existing_page_args );

            if ( ! empty( $existing_page ) ) {
                $add_shortcode = false;
                $page_id       = $existing_page[ 0 ]->ID;
                $page_object   = get_post( $page_id );

                if ( false != get_post_meta( $page_id, '_b3_page', true ) ) {
                    // page has _b3_page meta
                    update_option( $page[ 'meta' ], $page_id );
                    if ( ! empty( $page_object->post_content ) ) {
                        if ( strpos( $page_object->post_content, $page[ 'content' ] ) === false ) {
                            $add_shortcode = true;
                        }
                    } else {
                        $add_shortcode = true;
                    }
                }
                if ( true === $add_shortcode ) {
                    $new_args = array(
                        'ID'           => $page_id,
                        'post_content' => $page[ 'content' ],
                    );
                    wp_update_post( $new_args );
                }
            } else {
                $new_post_args = array(
                    'post_title'     => $page[ 'title' ],
                    'post_name'      => $slug,
                    'post_content'   => $page[ 'content' ],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                );
                $result = wp_insert_post( $new_post_args, true );
                if ( ! is_wp_error( $result ) ) {
                    update_option( $page[ 'meta' ], $result );
                    update_post_meta( $result, '_b3_page', true );
                }
            }
        }
    }


    /**
     * Render any extra fields
     * Options are: text, textarea, number, url, radio, checkbox, select
     *
     * @since 1.0.6
     *
     * @param bool $extra_field
     *
     * @return bool|false|string
     */
    function b3_render_extra_field( $extra_field = [], $value = false ) {

        $container_class   = ( isset( $extra_field[ 'container_class' ] ) && ! empty( $extra_field[ 'container_class' ] ) ) ? $extra_field[ 'container_class' ] : false;
        $input_id          = ( isset( $extra_field[ 'id' ] ) && ! empty( $extra_field[ 'id' ] ) ) ? $extra_field[ 'id' ] : false;
        $input_class       = ( isset( $extra_field[ 'input_class' ] ) && ! empty( $extra_field[ 'input_class' ] ) ) ? '' . $extra_field[ 'input_class' ] : false;
        $input_description = ( isset( $extra_field[ 'input_description' ] ) && ! empty( $extra_field[ 'input_description' ] ) ) ? '' . $extra_field[ 'input_description' ] : false;
        $input_label       = ( isset( $extra_field[ 'label' ] ) && ! empty( $extra_field[ 'label' ] ) ) ? $extra_field[ 'label' ] : false;
        $input_placeholder = ( isset( $extra_field[ 'placeholder' ] ) && ! empty( $extra_field[ 'placeholder' ] ) ) ? $extra_field[ 'placeholder' ] : false;
        $input_required    = ( isset( $extra_field[ 'required' ] ) && ! empty( $extra_field[ 'required' ] ) ) ? ' <span class="b3__required"><strong>*</strong></span>' : false;
        $input_type        = ( isset( $extra_field[ 'type' ] ) && ! empty( $extra_field[ 'type' ] ) ) ? $extra_field[ 'type' ] : false;
        $input_options     = ( isset( $extra_field[ 'options' ] ) && ! empty( $extra_field[ 'options' ] ) ) ? $extra_field[ 'options' ] : array();
        $field_value       = ( isset( $_POST[ $input_id ] ) ) ? $_POST[ $input_id ] : '';

        if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) ) {
            ob_start();
            ?>
            <div class="b3_form-element b3_form-element--<?php echo $input_type; ?><?php if ( $container_class ) { ?> b3_form-element--<?php echo $container_class; ?> <?php echo $container_class; } ?>">

                <label class="b3_form-label" for="<?php echo $input_id; ?>"><?php echo $input_label; ?><?php echo $input_required; ?></label>
                <?php if ( in_array( $input_type, array( 'text' , 'number', 'url' ) ) ) { ?>
                    <?php
                    if ( false != $value && is_string( $value ) ) {
                        $field_value = $value;
                    } else {
                        $field_value = false;
                    }
                    ?>
                    <?php if ( in_array( $input_type, array( 'number' ) ) ) { ?>
                        <?php $negatives_allowed = ( isset( $extra_field[ 'negatives' ] ) && true == $extra_field[ 'negatives' ] ) ? true : false; ?>
                        <?php $validation = true; ?>
                        <?php if ( false == $negatives_allowed ) { ?>
                            <?php $validation = " min=0 oninput=\"validity.valid||(value='');\""; ?>
                        <?php } ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--<?php echo $input_type; ?> b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>"<?php if ( $input_placeholder ) { echo ' placeholder="' . $extra_field[ 'placeholder' ] . '"'; } ?><?php echo $validation; ?> value="<?php echo $field_value; ?>"<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } else { ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--<?php echo $input_type; ?> b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>"<?php if ( $input_placeholder ) { echo ' placeholder="' . $extra_field[ 'placeholder' ] . '"'; } ?>value="<?php echo $field_value; ?>"<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php }  ?>

                <?php } elseif ( 'textarea' == $input_type ) { ?>
                    <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--textarea b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>" <?php if ( $input_placeholder ) { echo ' placeholder="' . $extra_field[ 'placeholder' ] . '"'; } ?><?php if ( $input_required ) { echo ' required'; }; ?>><?php echo $field_value; ?></textarea>

                <?php } elseif ( in_array( $input_type, array( 'true_false' ) ) ) { ?>
                    <?php $selected = false; ?>
                    <label for="<?php echo $input_id; ?>" class="screen-reader-text"><?php echo $input_label; ?></label>
                    <input type="checkbox" id="<?php echo $input_id; ?>" name="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--<?php echo $input_type; ?> b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>" /> <?php echo $input_description; ?>

                <?php } elseif ( in_array( $input_type, array( 'radio', 'checkbox' ) ) ) { ?>

                    <?php if ( $input_options ) { ?>
                        <?php $counter = 1; ?>
                        <div class="b3_input-options">
                            <?php foreach( $input_options as $option ) { ?>
                                <div class="b3_input-option b3_input-option--<?php echo $input_type; ?>">
                                    <?php $option_class = ( isset( $option[ 'input_class' ] ) ) ? $option[ 'input_class' ]: false; ?>
                                    <?php if ( in_array( $input_type, array( 'radio' ) ) ) { ?>
                                        <?php $selected = ( isset( $value ) && $option[ 'value' ] == $value || isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) ? ' checked="checked"' : false; ?>
                                    <?php } elseif ( in_array( $input_type, array( 'checkbox' ) ) ) { ?>
                                        <?php
                                            $selected = false;
                                            if ( isset( $value ) && is_array( $value ) && in_array( $option[ 'value' ], $value ) ) {
                                                $selected = ' checked="checked"';
                                            } elseif ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) {
                                                $selected = ' checked="checked"';
                                            }
                                        ?>
                                    <?php } ?>
                                    <label for="<?php echo $option[ 'name' ]; ?>_<?php echo $counter; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                    <input class="b3_form-input b3_form-input--<?php echo $input_type; ?><?php if ( $option_class ) { ?> b3_form-input--<?php echo $option_class; ?><?php } ?>"<?php if ( isset( $option[ 'name' ] ) ) { ?> id="<?php echo $option[ 'name' ]; ?>_<?php echo $counter; ?><?php } ?>" name="<?php echo $option[ 'name' ]; if ( 'checkbox' == $input_type ) { echo '[]'; } ?>" type="<?php echo $input_type; ?>" value="<?php echo $option[ 'value' ]; ?>"<?php echo $selected; ?>> <?php echo $option[ 'label' ]; ?>
                                </div>
                                <?php $counter++; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>

                <?php } elseif ( 'select' == $input_type ) { ?>
                    <select name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="<?php echo $input_class; ?>">
                        <?php if ( $input_options ) { ?>
                            <?php $input_placeholder_select = ( $input_placeholder ) ? $input_placeholder : __( 'Select an option', 'b3-onboarding' ); ?>
                            <option value=""><?php echo $input_placeholder_select; ?></option>
                            <?php foreach( $input_options as $option ) { ?>
                                <?php $selected = ( isset( $value ) && $option[ 'value' ] == $value ) ? ' selected="selected"' : false; ?>
                                <option value="<?php echo $option[ 'value' ]; ?>"<?php echo $selected; ?>><?php echo $option[ 'label' ]; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                <?php } ?>
            </div>
            <?php
            $output = ob_get_clean();

            return $output;
        }

        return false;
    }


    /**
     * Replace vars in email subject
     *
     * @since 2.0.0
     *
     * @param $vars
     *
     * @return array
     */
    function b3_replace_subject_vars( $vars = array() ) {

        $user_data  = false;
        if ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
            if ( false != $user_data ) {
                $vars[ 'user_data' ] = $user_data;
            }
        }

        $user_login = ( true != get_site_option( 'b3_register_email_only' ) && false != $user_data ) ? $user_data->user_login : false;

        $replacements = array(
            '%blog_name%'    => get_option( 'blogname' ),
            '%network_name%' => get_site_option( 'site_name' ),
            '%user_login%'   => $user_login,
            '%first_name%'   => ( false != $user_data ) ? $user_data->first_name : false,
        );

        if ( isset( $vars[ 'blog_id' ] ) ) {
            switch_to_blog( $vars[ 'blog_id' ] );
            $replacements[ '%site_name%' ] = get_option( 'blogname' );
            restore_current_blog();
        }

        return $replacements;

    }


    /**
     * Replace vars in email message
     *
     * @since 2.0.0
     *
     * @param $vars
     *
     * @return array
     */
    function b3_replace_email_vars( $vars = array(), $activation = false ) {

        $user_data = false;
        if ( isset( $vars[ 'user_data' ] ) ) {
            $user_data = $vars[ 'user_data' ];
        } elseif ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
            if ( false != $user_data ) {
                $vars[ 'user_data' ] = $user_data;
            }
        }
        $blog_id = ( isset( $vars[ 'site' ]->blog_id ) ) ? $vars[ 'site' ]->blog_id : get_current_blog_id();

        if ( isset( $vars[ 'registration_date' ] ) ) {
            $registration_date_gmt = $vars[ 'registration_date' ];
        } elseif ( isset( $vars[ 'user_data' ]->user_registered ) ) {
            $registration_date_gmt = $vars[ 'user_data' ]->user_registered;
        } else {
            $registration_date_gmt = false;
        }

        $local_registration_date = b3_get_local_date_time( $registration_date_gmt );
        $user_login              = ( false != $user_data && isset( $user_data->user_login ) ) ? $user_data->user_login : false;

        // More info: http://itman.in/en/how-to-get-client-ip-address-in-php/
        if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
            // check ip from share internet
            $user_ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
            // to check ip is pass from proxy
            $user_ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        } else {
            $user_ip = $_SERVER[ 'REMOTE_ADDR' ];
        }

        $replacements = array(
            '%account_page%'      => b3_get_account_url(),
            '%login_url%'         => b3_get_login_url(),
            '%blog_name%'         => ( is_multisite() ) ? get_blog_option( $blog_id, 'blogname' ) : get_option( 'blogname' ), // check in single site
            '%email_footer%'      => apply_filters( 'b3_email_footer_text', b3_get_email_footer() ),
            '%lostpass_url%'      => b3_get_lostpassword_url(),
            '%home_url%'          => get_home_url( $blog_id, '/' ),
            '%logo%'              => apply_filters( 'b3_main_logo', b3_get_main_logo() ),
            '%network_name%'      => get_site_option( 'site_name' ),
            '%registration_date%' => $local_registration_date,
            '%reset_url%'         => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
            '%user_ip%'           => $user_ip,
            '%user_login%'        => $user_login,
        );

        if ( is_multisite() ) {
            $options_site_url = esc_url( admin_url( 'admin.php?page=b3-onboarding&tab=emails' ) );
            $replacements[ '%settings_url%' ] = $options_site_url;

            if ( isset( $vars[ 'blog_id' ] )  ) {
                $replacements[ '%home_url%' ]  = get_home_url( $vars[ 'blog_id' ] );
            }
            if ( isset( $vars[ 'domain' ] ) && isset( $vars[ 'path' ] )  ) {
                $replacements[ '%home_url%' ] = b3_get_protocol() . '://' . $vars[ 'domain' ] . $vars[ 'path' ];
            }
            if ( isset( $vars[ 'user_password' ] ) ) {
                $replacements[ '%user_password%' ] = $vars[ 'user_password' ];
            }
            $replacements[ 'network_name' ] = get_option( 'name' );
        }

        if ( false != $activation ) {
            if ( is_multisite() ) {
                if ( isset( $vars[ 'key' ] ) ) {
                    $activate_url                       = b3_get_login_url() . "?activate=user&key={$vars[ 'key' ]}";
                    $replacements[ '%activation_url%' ] = esc_url( $activate_url );
                }
            } else {
                $replacements[ '%activation_url%' ] = b3_get_activation_url( $user_data );
            }
        }

        return $replacements;
    }


    /**
     * Replace vars in email template
     *
     * @since 2.0.0
     *
     * @param bool $message
     *
     * @return bool|string
     */
    function b3_replace_template_styling( $message = false ) {

        if ( false != $message ) {
            $email_footer = apply_filters( 'b3_email_footer_text', b3_get_email_footer() );
            $hide_logo    = ( '1' === get_site_option( 'b3_logo_in_email' ) ) ? false : true;
            $link_color   = apply_filters( 'b3_link_color', b3_get_link_color() );
            $styling      = apply_filters( 'b3_email_styling', b3_get_email_styling( $link_color ) );
            $template     = apply_filters( 'b3_email_template', b3_get_email_template( $hide_logo ) );

            if ( false != $styling && false != $template ) {
                $replace_vars = array(
                    '%email_footer%'  => $email_footer,
                    '%email_message%' => $message,
                    '%email_styling%' => $styling,
                );
                $message = strtr( $template, $replace_vars );
            }
        }

        return $message;
    }


    /**
     * Verify if privacy checkbox is clicked (when activated)
     *
     * @since 2.0.0
     *
     * @param $errors
     */
    function b3_verify_privacy() {
        $error = false;
        if ( 1 == get_option( 'b3_privacy' ) ) {
            if ( ! isset( $_POST[ 'b3_privacy_accept' ] ) ) {
                $error = true;
            }
        }

        return $error;

    }


    /**
     * Check if a remote file exists
     *
     * @TODO: check if ext-curl is installed/needed
     * @TODO: look into wp_remote_get
     *
     * @since 2.0.0
     *
     * @link: https://stackoverflow.com/a/7051633/8275339
     *
     * @param $url
     *
     * @return bool
     */
    function b3_check_remote_file( $url ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_NOBODY, 1 );
        curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $result = curl_exec( $ch );
        curl_close( $ch );
        if ( $result !== false ) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Generate user login
     *
     * @return string
     */
    function b3_generate_user_login() {
        $now        = gmdate( 'U', time() );
        $now_min_50 = $now - ( 50 * YEAR_IN_SECONDS );
        $user_login = (string) $now_min_50;

        return $user_login;

    }
