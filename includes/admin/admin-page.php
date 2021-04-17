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
                    <?php
                        if ( 'styling' == $_GET[ 'preview' ] ) {
                            esc_html_e( 'Styling preview', 'b3-onboarding' );
                        } elseif ( 'template' == $_GET[ 'preview' ] ) {
                            esc_html_e( 'Template preview', 'b3-onboarding' );
                        } else {
                            esc_html_e( 'Email preview', 'b3-onboarding' );
                        }
                    ?>
                </h1>

                <?php include 'preview.php'; ?>

            <?php } else { ?>

                <h1 id="b3__admin-title">
                    <?php _e( 'B3 OnBoarding settings', 'b3-onboarding' ); ?>
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
                        );
                        if ( is_multisite() && is_main_site() || ! is_multisite() ) {
                            $tabs[] = array(
                                'id'      => 'registration',
                                'title'   => esc_html__( 'Registration', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'registration' ),
                                'icon'    => 'shield',
                            );
                            $tabs[] = array(
                                'id'      => 'pages',
                                'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'pages' ),
                                'icon'    => 'admin-page',
                            );
                        }

                        if ( 1 == get_site_option( 'b3_style_wordpress_forms' ) ) {
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

                        if ( is_multisite() && is_main_site() || ! is_multisite() ) {
                            $tabs[] = array(
                                'id'      => 'users',
                                'title'   => esc_html__( 'Users', 'b3-onboarding' ),
                                'content' => b3_render_tab_content( 'users' ),
                                'icon'    => 'admin-users',
                            );

                            if ( true == get_site_option( 'b3_activate_recaptcha' ) ) {
                                $tabs[] = array(
                                    'id'      => 'recaptcha',
                                    'title'   => esc_html__( 'reCaptcha', 'b3-onboarding' ),
                                    'content' => b3_render_tab_content( 'recaptcha' ),
                                    'icon'    => 'plus-alt',
                                );
                            }
                        }
                    ?>
                    <div class="b3_tab-header">
                        <?php foreach ( $tabs as $tab ) { ?>
                            <?php
                                $hide_wordpress = false;
                                if ( 'wordpress' == $tab[ 'id' ] ) {
                                    if ( 1 != get_site_option( 'b3_style_wordpress_forms' ) ) {
                                        $hide_wordpress = ' hidden';
                                    }
                                }
                            ?>
                            <button class="b3_tab-button b3_tab-button--<?php echo $tab[ 'id' ]; ?><?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?><?php echo $hide_wordpress; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
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
