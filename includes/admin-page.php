<?php

    /**
     * Content for the 'settings page'
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

                        if ( 1 == get_option( 'b3_style_default_pages', false ) ) {
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

                        if ( true == get_option( 'b3_recaptcha', false ) && defined( 'LOCALHOST' ) && 1 == LOCALHOST ) {
                            $tabs[] = array(
                                'id'      => 'integrations',
                                'title'   => esc_html__( 'Integrations', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'integrations' ),
                                'icon'    => 'plus-alt',
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
