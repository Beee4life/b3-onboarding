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
        if ( false != $site_id && is_multisite() ) {
            switch_to_blog( $site_id );
        }
        
        $page_definitions = [
            _x( 'account', 'slug', 'b3-onboarding' )        => [
                'title'   => esc_html__( 'Account', 'b3-onboarding' ),
                'content' => '[account-page]',
                'meta'    => 'b3_account_page_id',
            ],
            _x( 'lostpassword', 'slug', 'b3-onboarding' )   => [
                'title'   => esc_html__( 'Lost password', 'b3-onboarding' ),
                'content' => '[lostpass-form]',
                'meta'    => 'b3_lost_password_page_id',
            ],
            _x( 'login', 'slug', 'b3-onboarding' )          => [
                'title'   => esc_html__( 'Login', 'b3-onboarding' ),
                'content' => '[login-form]',
                'meta'    => 'b3_login_page_id',
            ],
            _x( 'logout', 'slug', 'b3-onboarding' )         => [
                'title'   => esc_html__( 'Log Out', 'b3-onboarding' ),
                'content' => '',
                'meta'    => 'b3_logout_page_id',
            ],
            _x( 'register', 'slug', 'b3-onboarding' )       => [
                'title'   => esc_html__( 'Register', 'b3-onboarding' ),
                'content' => '[register-form]',
                'meta'    => 'b3_register_page_id',
            ],
            _x( 'reset-password', 'slug', 'b3-onboarding' ) => [
                'title'   => esc_html__( 'Reset Password', 'b3-onboarding' ),
                'content' => '[resetpass-form]',
                'meta'    => 'b3_reset_password_page_id',
            ],
        ];
        b3_create_pages( $page_definitions );

        if ( false != $site_id && is_multisite() ) {
            restore_current_blog();
        }
    }

    /**
     * Create pages
     *
     * @since 1.0.6
     *
     * @param array $page_definitions
     */
    function b3_create_pages( $page_definitions = [] ) {
        foreach( $page_definitions as $slug => $page ) {
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
            
            $existing_page_args = [
                'post_type'      => 'page',
                'posts_per_page' => 1,
                'pagename'       => $slug,
            ];
            $existing_page      = get_posts( $existing_page_args );

            if ( ! empty( $existing_page ) ) {
                $add_shortcode = false;
                $page_id       = $existing_page[ 0 ]->ID;
                $page_object   = get_post( $page_id );

                if ( false != get_post_meta( $page_id, '_b3_page', true ) ) {
                    // page has _b3_page meta
                    update_option( $page[ 'meta' ], $page_id, false );
                    if ( ! empty( $page_object->post_content ) ) {
                        if ( strpos( $page_object->post_content, $page[ 'content' ] ) === false ) {
                            $add_shortcode = true;
                        }
                    } else {
                        $add_shortcode = true;
                    }
                }
                if ( true === $add_shortcode ) {
                    $new_args = [
                        'ID'           => $page_id,
                        'post_content' => $page[ 'content' ],
                    ];
                    wp_update_post( $new_args );
                }
            } else {
                $new_post_args = [
                    'post_title'     => $page[ 'title' ],
                    'post_name'      => $slug,
                    'post_content'   => $page[ 'content' ],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                ];
                $result        = wp_insert_post( $new_post_args, true );
                if ( ! is_wp_error( $result ) ) {
                    update_option( $page[ 'meta' ], $result, false );
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
        $input_required    = ( isset( $extra_field[ 'required' ] ) && false != $extra_field[ 'required' ] ) ? ' <span class="b3__required"><strong>*</strong></span>' : false;
        $input_type        = ( isset( $extra_field[ 'type' ] ) && ! empty( $extra_field[ 'type' ] ) ) ? $extra_field[ 'type' ] : false;
        $input_options     = ( isset( $extra_field[ 'options' ] ) && ! empty( $extra_field[ 'options' ] ) ) ? $extra_field[ 'options' ] : [];
        $field_value       = ( isset( $_POST[ $input_id ] ) ) ? $_POST[ $input_id ] : '';

        if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) ) {
            ob_start();
            ?>
            <div class="b3_form-element b3_form-element--<?php echo esc_attr( $input_type ); ?><?php if ( $container_class ) { ?> b3_form-element--<?php echo esc_attr( $container_class ); ?> <?php echo esc_attr( $container_class ); } ?>">
                <?php if ( $input_label && $input_id ) { ?>
                <label class="b3_form-label" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $input_label; ?><?php echo $input_required; ?></label>
                <?php } ?>
                <?php if ( in_array( $input_type, [ 'text', 'number', 'url' ] ) ) { ?>
                    <?php $field_value =  ( false != $value && is_string( $value ) ) ? $value : false; ?>
                    <?php if ( in_array( $input_type, [ 'number' ] ) ) { ?>
                        <?php $negatives_allowed = ( isset( $extra_field[ 'negatives' ] ) && true == $extra_field[ 'negatives' ] ) ? true : false; ?>
                        <?php $validation = true; ?>
                        <?php if ( false === $negatives_allowed ) { ?>
                            <?php $validation = " min=0 oninput=\"validity.valid||(value='');\""; ?>
                        <?php } ?>
                        <input type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $input_id ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="b3_form-input b3_form-input--<?php echo esc_attr( $input_type ); ?> b3_form-input--<?php echo esc_attr( $input_class ); ?> <?php echo esc_attr( $input_class ); ?>"<?php if ( $input_placeholder ) { echo ' placeholder="' . esc_attr( $extra_field[ 'placeholder' ] ) . '"'; } ?><?php echo $validation; ?> value="<?php echo esc_attr( $field_value ); ?>"<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } else { ?>
                        <input type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $input_id ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="b3_form-input b3_form-input--<?php echo esc_attr( $input_type ); ?> b3_form-input--<?php echo esc_attr( $input_class ); ?> <?php echo esc_attr( $input_class ); ?>"<?php if ( $input_placeholder ) { echo ' placeholder="' . esc_attr( $extra_field[ 'placeholder' ] ) . '"'; } ?>value="<?php echo esc_attr( $field_value ); ?>"<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php }  ?>

                <?php } elseif ( 'textarea' === $input_type ) { ?>
                    <textarea name="<?php echo esc_attr( $input_id ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="b3_form-input b3_form-input--textarea b3_form-input--<?php echo esc_attr( $input_class ); ?> <?php echo esc_attr( $input_class ); ?>" <?php if ( $input_placeholder ) { echo ' placeholder="' . esc_attr( $extra_field[ 'placeholder' ] ) . '"'; } ?><?php if ( $input_required ) { echo ' required'; }; ?>><?php echo esc_textarea( $field_value ); ?></textarea>

                <?php } elseif ( in_array( $input_type, [ 'true_false' ] ) ) { ?>
                    <label for="<?php echo esc_attr( $input_id ); ?>" class="screen-reader-text"><?php echo esc_attr( $input_label ); ?></label>
                    <input type="checkbox" id="<?php echo esc_attr( $input_id ); ?>" name="<?php echo esc_attr( $input_id ); ?>" class="b3_form-input b3_form-input--<?php echo esc_attr( $input_type ); ?> b3_form-input--<?php echo esc_attr( $input_class ); ?> <?php echo $input_class; ?>" /> <?php echo $input_description; ?>

                <?php } elseif ( in_array( $input_type, [ 'radio', 'checkbox' ] ) ) { ?>
                    <?php if ( $input_options ) { ?>
                        <?php $counter = 1; ?>
                        <div class="b3_input-options">
                            <?php foreach( $input_options as $option ) { ?>
                                <div class="b3_input-option b3_input-option--<?php echo esc_attr( $input_type ); ?>">
                                    <?php $option_class = ( isset( $option[ 'input_class' ] ) ) ? $option[ 'input_class' ]: false; ?>
                                    <?php if ( in_array( $input_type, [ 'radio' ] ) ) { ?>
                                        <?php $checked = ( isset( $value ) && $option[ 'value' ] == $value || isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) ? ' checked="checked"' : false; ?>
                                    <?php } elseif ( in_array( $input_type, [ 'checkbox' ] ) ) { ?>
                                        <?php
                                            $checked = false;
                                            if ( isset( $value ) && is_array( $value ) && in_array( $option[ 'value' ], $value ) ) {
                                                $checked = ' checked="checked"';
                                            } elseif ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) {
                                                $checked = ' checked="checked"';
                                            }
                                        ?>
                                    <?php } ?>

                                    <?php if ( isset( $option[ 'name' ] ) && ! empty( $option[ 'name' ] && isset( $option[ 'label' ] ) && ! empty( $option[ 'label' ] ) ) ) { ?>
                                        <label for="<?php echo esc_attr( $option[ 'name' ] . '_' . $counter ); ?>" class="screen-reader-text"><?php echo esc_html( $option[ 'label' ] ); ?></label>
                                    <?php } ?>
                                    <?php if ( isset( $option[ 'name' ] ) && isset( $option[ 'label' ] ) ) { ?>
                                        <input class="b3_form-input b3_form-input--<?php echo esc_attr( $input_type ); ?><?php if ( $option_class ) { ?> b3_form-input--<?php echo esc_attr( $option_class ); ?><?php } ?>"<?php if ( isset( $option[ 'name' ] ) ) { ?> id="<?php echo esc_attr( $option[ 'name' ] . '_' . $counter ); } ?>" name="<?php echo esc_attr( $option[ 'name' ] ); if ( 'checkbox' === $input_type ) { echo '[]'; } ?>" type="<?php echo esc_attr( $input_type ); ?>" value="<?php echo ( isset( $option[ 'value' ] ) ? esc_attr($option[ 'value' ]) : '' ); ?>"<?php echo $checked; ?>> <?php echo $option[ 'label' ]; ?>
                                    <?php } ?>
                                </div>
                                <?php $counter++; ?>
                            <?php } ?>
                        </div>
                    <?php } ?>

                <?php } elseif ( 'select' === $input_type ) { ?>
                    <select name="<?php echo esc_attr( $input_id ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="<?php echo esc_attr( $input_class ); ?>">
                        <?php if ( $input_options ) { ?>
                            <?php $input_placeholder_select = ( $input_placeholder ) ? $input_placeholder : esc_attr__( 'Select an option', 'b3-onboarding' ); ?>
                            <option value=""><?php echo $input_placeholder_select; ?></option>
                            <?php foreach( $input_options as $option ) { ?>
                                <?php $selected = ( isset( $value ) && $option[ 'value' ] == $value ) ? ' selected="selected"' : false; ?>
                                <option value="<?php echo esc_attr( $option[ 'value' ] ); ?>"<?php echo $selected; ?>><?php echo $option[ 'label' ]; ?></option>
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
            $email_footer = b3_get_email_footer();
            $hide_logo    = ( 1 == get_option( 'b3_logo_in_email' ) ) ? false : true;
            $link_color   = b3_get_link_color();
            $styling      = b3_get_email_styling( $link_color );
            $template     = b3_get_email_template( $hide_logo );
            
            if ( false != $styling && false != $template ) {
                $replace_vars = [
                    '%email_footer%'  => $email_footer,
                    '%email_message%' => $message,
                    '%email_styling%' => $styling,
                ];
                $message      = strtr( $template, $replace_vars );
            }
        }

        return $message;
    }


    /**
     * Verify if privacy checkbox is clicked (when activated)
     *
     * @since 2.0.0
     *
     * @return bool
     */
    function b3_verify_privacy() {
        if ( '1' === get_option( 'b3_privacy' ) && ! isset( $_POST[ 'b3_privacy_accept' ] ) ) {
            return false;
        }

        return true;
    }


    /**
     * Check if a remote file exists
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
        if ( 200 == wp_remote_retrieve_response_code( wp_remote_get( $url ) ) ) {
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


    /**
     * Verify domain in email (single site)
     *
     * @param $email
     *
     * @return bool
     */
    function b3_verify_email_domain( $email ) {
        $disallowed_domains = b3_get_disallowed_domain_names();
        
        if ( get_option( 'b3_set_domain_restrictions' ) && is_array( $disallowed_domains ) && ! empty( $disallowed_domains ) ) {
            $domain_name = substr( strrchr( $email, '@' ), 1 );
            
            if ( $domain_name && in_array( $domain_name, $disallowed_domains ) ) {
                return false;
            }
        }

        return true;
    }
    
    
    /**
     * Verify OTP link
     *
     * @param $code
     *
     * @return false|WP_User
     */
    function b3_verify_otp( $code ) {
        if ( $code ) {
            if ( 8 == strlen( $code ) ) {
                // raw code
                $user_input = $code;
                if ( isset( $_POST[ 'email' ] ) ) {
                    $user_email = $_POST[ 'email' ];
                } else {
                    // maybe get user by code ?
                }
                
            } else {
                // hashed code
                $decoded_code = base64_decode( $code );
                $args         = explode( ':', $decoded_code );
                
                if ( isset( $args[ 0 ] ) && isset( $args[ 1 ] ) ) {
                    $user_email = $args[ 0 ];
                    $user_input = $args[ 1 ];
                }
            }
            
            if ( isset( $user_email ) ) {
                $user = get_user_by( 'email', $user_email );
                
                if ( $user instanceof WP_User ) {
                    $transient       = get_transient( sprintf( 'otp_%s', $user_email ) );
                    $hashed_password = password_hash( $transient, PASSWORD_BCRYPT );
                    
                    if ( hash_equals( $hashed_password, crypt( $user_input, $hashed_password ) ) ) {
                        return $user;
                    }
                }
            }
        }
        
        return false;
    }
