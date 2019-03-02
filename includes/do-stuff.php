<?php
    /**
     * Create initial pages upon activation
     */
    function b3_create_initial_pages() {
        
        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'account' => array(
                'title'   => esc_html__( 'Account', 'b3-onboarding' ),
                'content' => '[account-page]',
                'meta'    => 'b3_account_page_id'
            ),
            'lostpassword' => array(
                'title'   => esc_html__( 'Forgot password', 'b3-onboarding' ),
                'content' => '[forgotpass-form]',
                'meta'    => 'b3_forgotpass_page_id'
            ),
            'login'           => array(
                'title'   => esc_html__( 'Login', 'b3-onboarding' ),
                'content' => '[login-form]',
                'meta'    => 'b3_login_page_id'
            ),
            'logout'           => array(
                'title'   => esc_html__( 'Log out', 'b3-onboarding' ),
                'content' => '',
                'meta'    => 'b3_logout_page_id'
            ),
            'register'        => array(
                'title'   => esc_html__( 'Register', 'b3-onboarding' ),
                'content' => '[register-form]',
                'meta'    => 'b3_register_page_id'
            ),
            'reset-password'  => array(
                'title'   => esc_html__( 'Reset password', 'b3-onboarding' ),
                'content' => '[resetpass-form]',
                'meta'    => 'b3_resetpass_page_id'
            ),
        );
        
        foreach ( $page_definitions as $slug => $page ) {

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
    
            $post_id = false;
            $query   = new WP_Query( 'pagename=' . $slug );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) : $query->the_post();
                    $post_id = get_the_ID();
                    break;
                endwhile;
            }
            
            if ( ! $query->have_posts() ) {
    
                // Add the page using the data from the array above
                $errors = new WP_Error();
                $result = wp_insert_post( array(
                        'post_title'     => $page[ 'title' ],
                        'post_name'      => $slug,
                        'post_content'   => $page[ 'content' ],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    ),
                    false
                );
                // if page doesn't return an error (thus successful)
                if ( ! is_wp_error( $result) ) {
                    update_option( $page[ 'meta' ], $result, true );
                    update_post_meta( $result, '_b3_page', true );
                } else {
                    // if page does return an error (thus a page exists with $slug)
                    $alternative_result = wp_insert_post( array(
                            'post_title'     => $page[ 'title' ],
                            'post_name'      => $slug . '-2',
                            'post_content'   => $page[ 'content' ],
                            'post_status'    => 'publish',
                            'post_type'      => 'page',
                            'ping_status'    => 'closed',
                            'comment_status' => 'closed',
                        ),
                        false
                    );
                    if ( ! is_wp_error( $alternative_result) ) {
                        update_option( $page[ 'meta' ], $alternative_result, true );
                        update_post_meta( $alternative_result, '_b3_page', true );
                    }
                }

            } else {
                // page exists
                update_option( $page[ 'meta' ], $post_id, true );
                update_post_meta( $post_id, '_b3_page', true );
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
            
            $input_id          = ( ! empty( $extra_field[ 'id' ] ) ) ? $extra_field[ 'id' ] : false;
            $input_class       = ( ! empty( $extra_field[ 'class' ] ) ) ? ' ' . $extra_field[ 'class' ] : false;
            $input_label       = ( ! empty( $extra_field[ 'label' ] ) ) ? $extra_field[ 'label' ] : false;
            $input_placeholder = ( ! empty( $extra_field[ 'placeholder' ] ) ) ? ' placeholder="' . $extra_field[ 'placeholder' ] . '"' : false;
            $input_required    = ( ! empty( $extra_field[ 'required' ] ) ) ? ' <span class="b3__required"><strong>*</strong></span>' : false;
            $input_type        = ( ! empty( $extra_field[ 'type' ] ) ) ? $extra_field[ 'type' ] : false;
            $input_options     = ( ! empty( $extra_field[ 'options' ] ) ) ? $extra_field[ 'options' ] : [];
            
            if ( isset( $extra_field[ 'id' ] ) && isset( $extra_field[ 'label' ] ) && isset( $extra_field[ 'type' ] ) && isset( $extra_field[ 'class' ] ) ) {
                
                ob_start();
                ?>
                <div class="b3__form-element b3__form-element--<?php echo $input_type; ?>">
                    <label class="b3__form-label" for="<?php echo $input_id; ?>"><?php echo $input_label; ?> <?php echo $input_required; ?></label>
                    <?php if ( 'text' == $input_type ) { ?>
                        <input type="<?php echo $input_type; ?>" name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>"<?php if ( $input_placeholder && 'text' == $extra_field[ 'type' ] ) { echo $input_placeholder; } ?>value=""<?php if ( $input_required ) { echo ' required'; }; ?>>
                    <?php } elseif ( 'textarea' == $input_type ) { ?>
                        <textarea name="<?php echo $input_id; ?>" id="<?php echo $input_id; ?>" class="b3__form--input<?php echo $input_class; ?>" value=""<?php if ( $input_placeholder ) { echo $input_placeholder; } ?><?php if ( $input_required ) { echo ' required'; }; ?>></textarea>
                    <?php } elseif ( 'radio' == $input_type ) { ?>
                        <?php if ( $input_options ) { ?>
                            <?php $counter = 1; ?>
                            <?php foreach( $input_options as $option ) { ?>
                                <label for="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                <input class="b3__form--input<?php echo $input_class; ?>" id="<?php echo $option[ 'value' ]; ?>_<?php echo $counter; ?>" name="<?php echo $option[ 'name' ]; ?>" type="<?php echo $input_type; ?>" value="<?php echo $option[ 'value' ]; ?>"<?php if ( isset( $option[ 'checked' ] ) && true == $option[ 'checked' ] ) { echo ' checked="checked"'; } ?>> &nbsp;<?php echo $option[ 'label' ]; ?>
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
