<?php
    /**
     * Create initial pages upon activation
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

        if ( is_multisite() ) {
            if ( is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
                error_log('network active');
            } else {
                error_log('not network active');
            }

            if ( is_main_site() ) {
                error_log('main');
                foreach( get_sites() as $site ) {
                    error_log('Site: ' . $site->blog_id);
                    switch_to_blog( $site->blog_id );
                    b3_create_pages( $page_definitions, $create_new_site );
                    restore_current_blog();
                }
            } else {
                error_log('not main');
            }

        } else {
            b3_create_pages( $page_definitions, $create_new_site );
        }
    }

    /**
     * Create pages
     *
     * @param array $definitions
     * @param bool  $create_new_site
     */
    function b3_create_pages( $definitions = [], $create_new_site = false ) {
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

            $existing_page = [];
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
                if ( ! is_wp_error( $result) ) {
                    update_option( $page[ 'meta' ], $result, true );
                    update_post_meta( $result, '_b3_page', true );
                }
            }
        }
    }

    /**
     * Render any extra fields
     *
     * @param bool $extra_field
     *
     * @return bool|false|string
     */
    function b3_render_extra_field( $extra_field = false ) {

        if ( false != $extra_field ) {

            $container_class   = ( isset( $extra_field[ 'container_class' ] ) && ! empty( $extra_field[ 'container_class' ] ) ) ? $extra_field[ 'container_class' ] : false;
            $input_id          = ( isset( $extra_field[ 'id' ] ) && ! empty( $extra_field[ 'id' ] ) ) ? $extra_field[ 'id' ] : false;
            $input_class       = ( isset( $extra_field[ 'input_class' ] ) && ! empty( $extra_field[ 'input_class' ] ) ) ? ' ' . $extra_field[ 'input_class' ] : false;
            $input_label       = ( isset( $extra_field[ 'label' ] ) && ! empty( $extra_field[ 'label' ] ) ) ? $extra_field[ 'label' ] : false;
            $input_placeholder = ( isset( $extra_field[ 'placeholder' ] ) && ! empty( $extra_field[ 'placeholder' ] ) ) ? ' placeholder="' . $extra_field[ 'placeholder' ] . '"' : false;
            $input_required    = ( isset( $extra_field[ 'required' ] ) && ! empty( $extra_field[ 'required' ] ) ) ? ' <span class="b3__required"><strong>*</strong></span>' : false;
            $input_type        = ( isset( $extra_field[ 'type' ] ) && ! empty( $extra_field[ 'type' ] ) ) ? $extra_field[ 'type' ] : false;
            $input_options     = ( isset( $extra_field[ 'options' ] ) && ! empty( $extra_field[ 'options' ] ) ) ? $extra_field[ 'options' ] : [];

            if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) ) {

                ob_start();
                ?>
                <div class="b3_form-element b3_form-element--<?php echo $input_type; ?><?php if ( $container_class ) { ?> b3_form-element--<?php echo $container_class; } ?>">
                    <label class="b3_form-label" for="<?php echo $input_id; ?>"><?php echo $input_label; ?> <?php echo $input_required; ?></label>
                    <?php if ( 'text' == $input_type ) { ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form--input<?php echo $input_class; ?>"<?php if ( $input_placeholder && 'text' == $extra_field[ 'type' ] ) { echo $input_placeholder; } ?>value=""<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } elseif ( 'textarea' == $input_type ) { ?>
                        <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3_form--input<?php echo $input_class; ?>" value=""<?php if ( $input_placeholder ) { echo $input_placeholder; } ?><?php if ( $input_required ) { echo ' required'; }; ?>></textarea>
                    <?php } elseif ( 'radio' == $input_type ) { ?>
                        <?php if ( $input_options ) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach( $input_options as $option ) { ?>
                                <?php $option_class = ( isset( $option[ 'input_class' ] ) ) ? ' ' . $option[ 'input_class' ]: false; ?>
                                <label for="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                <input class="b3_form--input<?php echo $option_class; ?>" id="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" name="<?php echo $option[ 'name' ]; ?>" type="<?php echo $input_type; ?>" value="<?php echo $option[ 'value' ]; ?>"<?php if ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) { echo ' checked="checked"'; } ?>> &nbsp;<?php echo $option[ 'label' ]; ?>
                                <?php $counter++; ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>
                <?php
                $output = ob_get_clean();

                return $output;
            }
        }

        return false;
    }
