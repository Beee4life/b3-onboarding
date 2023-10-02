<?php
    /**
     * Content for the 'settings page'
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_user_register_settings() {

        if ( ! current_user_can( apply_filters( 'b3_user_cap', 'manage_options' ) ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-onboarding' ) );
        }
        ?>

        <div class="wrap b3 b3__admin">
            <?php
                if ( ! empty( $_GET[ 'preview' ] ) ) {
                    $page_title = esc_html__( 'Email preview', 'b3-onboarding' );
                    if ( 'styling' === $_GET[ 'preview' ] ) {
                        $page_title = esc_html__( 'Styling preview', 'b3-onboarding' );
                    } elseif ( 'template' === $_GET[ 'preview' ] ) {
                        $page_title = esc_html__( 'Template preview', 'b3-onboarding' );
                    }

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', $page_title );
                    include 'preview.php';

                } else {
                    $default_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : 'registration';
                    $tabs        = b3_get_admin_tabs();

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', get_admin_page_title() );

                    B3Onboarding::b3_show_admin_notices();

                    if ( is_array( $tabs ) ) {
                ?>
                    <div class="b3_tabs">
                        <div class="b3_tab-header">
                            <?php foreach ( $tabs as $tab ) {
                                $tab_id   = $tab[ 'id' ];
                                $active   = ( $tab[ 'id' ] === $default_tab ) ? ' active' : false;
                                $add_icon = ( isset( $tab[ 'icon' ] ) ) ? true : false;
                                $icon     = $add_icon ? sprintf( '<i class="dashicons dashicons-%s"></i>', $tab[ 'icon' ] ) : false;
                                $title    = $tab[ 'title' ];
                                echo sprintf( '<button id="b3_tab-button--%s" class="b3_tab-button b3_tab-button--%s%s" onclick="openTab(event, \'%s\')">%s%s</button>', $tab_id, $tab_id, $active, $tab_id, $icon, $title );
                            }
                            ?>
                        </div>

                        <div class="tab-contents">
                            <?php foreach ( $tabs as $tab ) {
                                $active  = ( $tab[ 'id' ] === $default_tab ) ? ' style="display: block;"' : false;
                                $content = $tab[ 'content' ];
                                $tab_id  = $tab[ 'id' ];
                                echo sprintf( '<div id="%s" class="b3_tab-content b3_tab-content--%s"%s>%s</div>', $tab_id, $tab_id, $active, $content );
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php }
