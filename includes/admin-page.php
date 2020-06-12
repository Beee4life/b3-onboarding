<?php

    /**
     * Content for the 'settings page'
     *
     * @since 1.0.0
     */
    function b3_user_register_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-onboarding' ) );
        }
        ?>

        <div class="wrap b3 b3__admin">

            <?php if ( ! empty( $_GET[ 'preview' ] ) ) { ?>
                <h1 id="b3__admin-title">
                    <?php _e( 'Email preview', 'b3-onboarding' ); ?>
                </h1>

                <?php include( 'preview.php' ); ?>

            <?php } else { ?>

                <h1 id="b3__admin-title">
                    <?php _e( 'Onboarding settings', 'b3-onboarding' ); ?>
                </h1>

                <?php B3Onboarding::b3_show_admin_notices(); ?>

                <div class="b3_tabs">
                    <?php
                        if ( isset( $_GET[ 'tab' ] ) ) {
                            $default_tab = $_GET[ 'tab' ];
                        } else {
                            $default_tab = 'settings';
                        }

                        $tabs = array(
                            array(
                                'id'      => 'settings',
                                'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'settings' ),
                                'icon'    => 'admin-generic',
                            ),
                            array(
                                'id'      => 'pages',
                                'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'pages' ),
                                'icon'    => 'admin-page',
                            ),
                        );

                        $tabs[] = array(
                            'id'      => 'registration',
                            'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'registration' ),
                            'icon'    => 'shield',
                        );

                        if ( 1 == get_option( 'b3_style_wordpress_forms', false ) ) {
                            $tabs[] = array(
                                'id'      => 'wordpress',
                                'title'   => 'WordPress',
                                'content' => b3_render_tab_content( 'wordpress' ),
                                'icon'    => 'art',
                            );
                        }

                        $tabs[] = array(
                            'id'      => 'emails',
                            'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'emails' ),
                            'icon'    => 'email',
                        );

                        $tabs[] = array(
                            'id'      => 'users',
                            'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'users' ),
                            'icon'    => 'admin-users',
                        );

                        if ( true == get_option( 'b3_recaptcha', false ) ) {
                            $tabs[] = array(
                                'id'      => 'recaptcha',
                                'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'recaptcha' ),
                                'icon'    => 'plus-alt',
                            );
                        }

                        $feedback_counter = get_option( 'b3_feedback_sent', 0 );
                        if ( 0 == $feedback_counter ) {
                            $show_feedback_tab = true;
                        } elseif ( defined( 'LOCALHOST' ) && 1 == LOCALHOST ) {
                            if ( 2 > $feedback_counter ) {
                                $show_feedback_tab = true;
                            }
                        }
                        if ( isset( $show_feedback_tab ) && true == $show_feedback_tab ) {
                            $tabs[] = array(
                                'id'      => 'feedback',
                                'title'   => esc_html__( 'Feedback', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'feedback' ),
                                'icon'    => 'megaphone',
                            );
                        }
                    ?>
                    <div class="b3_tab-header">
                        <?php foreach ( $tabs as $tab ) { ?>
                            <button class="b3_tab-button<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
                                <?php if ( isset( $tab[ 'icon' ] ) ) { ?>
                                    <i class="dashicons dashicons-<?php echo $tab[ 'icon' ]; ?>"></i>
                                <?php } ?>
                                <?php echo $tab[ 'title' ]; ?>
                            </button>
                        <?php } ?>
                    </div>

                    <div class="tab-contents">
                        <?php foreach ( $tabs as $tab ) { ?>
                            <div id="<?php echo $tab[ 'id' ]; ?>" class="b3_tab-content b3_tab-content--<?php echo $tab[ 'id' ]; ?>"<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' style="display: block;"' : false; ?>>
                                <?php if ( $tab[ 'content' ] ) { ?>
                                    <?php echo $tab[ 'content' ]; ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php }
