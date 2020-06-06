<?php
    /**
     * Create initial pages upon activation
     *
     * @since 1.0.6
     *
     * @param bool $create_new_site
     */
    function b3_setup_initial_pages( $create_new_site = false ) {

        // Information needed for creating the plugin's pages
        $page_definitions = array(
            _x( 'account', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Account', 'b3-onboarding' ),
                'content' => '[account-page]',
                'meta'    => 'b3_account_page_id'
            ),
            _x( 'forgotpassword', 'slug', 'b3-onboarding' ) => array(
                'title'   => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'content' => '[forgotpass-form]',
                'meta'    => 'b3_forgotpass_page_id'
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
                'meta'    => 'b3_resetpass_page_id'
            ),
        );

        b3_create_pages( $page_definitions, $create_new_site );
    }

    /**
     * Create pages
     *
     * @since 1.0.6
     *
     * @param array $definitions
     * @param bool  $create_new_site
     */
    function b3_create_pages( $definitions = array(), $create_new_site = false ) {
        foreach ( $definitions as $slug => $page ) {

            // Check if there's a page assigned already
            $stored_id = get_option( $slug, false );
            if ( $stored_id ) {
                $check_page = get_post( $stored_id );
                if ( ! $check_page ) {
                    delete_option( $slug );
                }
            } else {
                // no stored id, so continue
            }

            $existing_page = array();
            if ( false == $create_new_site ) {
                $existing_page_args = [
                    'post_type'      => 'page',
                    'posts_per_page' => 1,
                    'pagename'       => $slug,
                ];
                $existing_page = get_posts( $existing_page_args );
            }
            if ( ! empty( $existing_page ) ) {
                // check for meta
                $post_id = $existing_page[ 0 ]->ID;
                // if meta exists
                $meta = get_post_meta( $post_id, '_b3_page', true );
                if ( false == $meta ) {
                    // @TODO: pass to add new
                }
            }
            if ( empty( $existing_page ) ) {
                // Add the page using the data from the array above
                $result = wp_insert_post( array(
                    'post_title'     => $page[ 'title' ],
                    'post_name'      => $slug,
                    'post_content'   => $page[ 'content' ],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'ping_status'    => 'closed',
                    'comment_status' => 'closed',
                ),
                    true
                );
                // if page doesn't return an error (thus successful)
                if ( ! is_wp_error( $result ) ) {
                    update_option( $page[ 'meta' ], $result, true );
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
    function b3_render_extra_field( $extra_field = false ) {

        if ( false != $extra_field ) {

            $container_class   = ( isset( $extra_field[ 'container_class' ] ) && ! empty( $extra_field[ 'container_class' ] ) ) ? $extra_field[ 'container_class' ] : false;
            $input_id          = ( isset( $extra_field[ 'id' ] ) && ! empty( $extra_field[ 'id' ] ) ) ? $extra_field[ 'id' ] : false;
            $input_class       = ( isset( $extra_field[ 'input_class' ] ) && ! empty( $extra_field[ 'input_class' ] ) ) ? '' . $extra_field[ 'input_class' ] : false;
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
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--<?php echo $input_type; ?> b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>"<?php if ( $input_placeholder ) { echo ' placeholder="' . $extra_field[ 'placeholder' ] . '"'; } ?>value="<?php echo $field_value; ?>"<?php if ( $input_required ) { echo ' required'; }; ?>>

                    <?php } elseif ( 'textarea' == $input_type ) { ?>
                        <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form-input b3_form-input--textarea b3_form-input--<?php echo $input_class; ?> <?php echo $input_class; ?>" <?php if ( $input_placeholder ) { echo ' placeholder="' . $extra_field[ 'placeholder' ] . '"'; } ?><?php if ( $input_required ) { echo ' required'; }; ?>><?php echo $field_value; ?></textarea>

                    <?php } elseif ( in_array( $input_type, array( 'radio', 'checkbox' ) ) ) { ?>

                        <?php if ( $input_options ) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach( $input_options as $option ) { ?>
                                <div class="b3_input-option">
                                    <?php $option_class = ( isset( $option[ 'input_class' ] ) ) ? ' ' . $option[ 'input_class' ]: false; ?>
                                    <label for="<?php echo $option[ 'name' ]; ?>_<?php echo $counter; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                    <input class="b3_form-input b3_form-input--<?php echo $input_type; ?> b3_form-input--<?php echo $option_class; ?> <?php echo $option_class; ?>" id="<?php echo $option[ 'name' ]; ?>_<?php echo $counter; ?>" name="<?php echo $option[ 'name' ]; if ( 'checkbox' == $input_type ) { echo '[]'; } ?>" type="<?php echo $input_type; ?>" value="<?php echo $option[ 'value' ]; ?>"<?php if ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) { echo ' checked="checked"'; } ?>> <?php echo $option[ 'label' ]; ?>
                                </div>
                                <?php $counter++; ?>
                            <?php } ?>
                        <?php } ?>

                    <?php } elseif ( 'select' == $input_type ) { ?>
                        <select name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="<?php echo $input_class; ?>">
                            <?php if ( $input_options ) { ?>
                                <?php $input_placeholder_select = ( $input_placeholder ) ? $input_placeholder : __( 'Select an option', 'b3-onboarding' ); ?>
                                <option value=""><?php echo $input_placeholder_select; ?></option>
                                <?php foreach( $input_options as $option ) { ?>
                                    <option value="<?php echo $option[ 'value' ]; ?>"><?php echo $option[ 'label' ]; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
                <?php
                $output = ob_get_clean();

                return $output;
            }
        }

        return false;
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
        if ( is_user_logged_in() ) {
            $user_data = get_userdata( get_current_user_id() );
            if ( false != $user_data ) {
                $vars[ 'user_data' ] = $user_data;
            }
        } elseif ( isset( $vars[ 'user_data' ] ) ) {
            $user_data = $vars[ 'user_data' ];
        }

        // @TODO: make function from this
        $date_format           = get_option( 'date_format' );
        $gmt_offset            = get_option( 'gmt_offset' );
        $time_format           = get_option( 'time_format' );
        $timezone              = get_option( 'timezone_string' );
        $registration_date     = gmdate( $date_format . ' ' . $time_format, time() ); // fallback, returns now in UTC
        $registration_date_gmt = ( isset( $vars[ 'registration_date' ] ) ) ? $vars[ 'registration_date' ] : ( isset( $vars[ 'user_data' ]->user_registered ) ) ? $vars[ 'user_data' ]->user_registered : false;
        if ( false != $registration_date_gmt ) {
            if ( ! empty( $timezone ) ) {
                $new_date = new DateTime( $registration_date_gmt, new DateTimeZone( 'UTC' ) );
                $new_date->setTimeZone( new DateTimeZone( $timezone ) );
                $registration_date = $new_date->format( $date_format . ' @ ' . $time_format );
            } elseif ( ! empty( $gmt_offset ) ) {
                $registration_date_gmt_ts = strtotime( $registration_date_gmt );
                $registration_date_ts     = $registration_date_gmt_ts + ( $gmt_offset * HOUR_IN_SECONDS );
                $registration_date        = gmdate( $date_format . ' @ ' . $time_format, $registration_date_ts );
            }
        }

        $replacements = array(
            '%blog_name%'         => get_option( 'blogname' ),
            '%email_footer%'      => apply_filters( 'b3_email_footer_text', b3_get_email_footer() ),
            '%forgotpass_url%'    => b3_get_forgotpass_url(),
            '%home_url%'          => get_home_url(),
            '%logo%'              => apply_filters( 'b3_main_logo', b3_get_main_logo() ),
            '%registration_date%' => $registration_date,
            '%reset_url%'         => ( isset( $vars[ 'reset_url' ] ) ) ? $vars[ 'reset_url' ] : false,
            '%user_ip%'           => $_SERVER[ 'REMOTE_ADDR' ] ? : ( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ? : $_SERVER[ 'HTTP_CLIENT_IP' ] ),
            '%user_login%'        => ( false != $user_data ) ? $user_data->user_login : false,
        );
        // Replace %blog_name% again if used in the footer
        if ( strpos( $replacements[ '%email_footer%' ], '%' ) !== false ) {
            $replacements[ '%email_footer%' ] = str_replace( '%blog_name%', get_option( 'blogname' ), $replacements[ '%email_footer%' ] );
        }
        if ( false != $activation ) {
            $replacements[ '%activation_url%' ] = b3_get_activation_url( $user_data );
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
            $hide_logo    = ( '1' === get_option( 'b3_logo_in_email' ) ) ? false : true;
            $link_color   = apply_filters( 'b3_link_color', b3_get_link_color() );
            $styling      = apply_filters( 'b3_email_styling', b3_get_email_styling( $link_color ) );
            $template     = apply_filters( 'b3_email_template', b3_get_email_template( $hide_logo ) );

            if ( false != $styling && false != $template ) {
                $replace_vars = [
                    '%email_footer%'  => $email_footer,
                    '%email_message%' => $message,
                    '%email_styling%' => $styling,
                ];
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
        if ( 1 == get_option( 'b3_privacy', false ) ) {
            if ( ! isset( $_POST[ 'b3_privacy_accept' ] ) ) {
                $error = true;
            }
        }

        return $error;

    }
