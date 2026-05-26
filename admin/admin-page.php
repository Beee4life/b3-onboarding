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

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', esc_html( $page_title ) );
                    include 'preview.php';

                } else {
                    $default_tab = ! is_multisite() || is_main_site() ? 'registration' : 'emails';
                    $default_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : $default_tab;
                    $tabs        = b3_get_admin_tabs();
                    // echo '<pre>'; var_dump($tabs); echo '</pre>'; exit;

                    echo sprintf( '<h1 id="b3__admin-title">%s</h1>', esc_html( get_admin_page_title() ) );

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
                                echo sprintf( '<button id="b3_tab-button--%1$s" class="b3_tab-button b3_tab-button--%2$s%3$s" onclick="openTab(event, \'%4$s\')">%5$s%6$s</button>', esc_attr( $tab_id ), esc_attr( $tab_id ), esc_attr( $active ), esc_attr( $tab_id ), wp_kses_post( $icon ), esc_html( $title ) );
                            }
                            ?>
                        </div>

                        <div class="tab-contents">
                            <?php
                                foreach ( $tabs as $tab ) {
                                    $style_value = ( $tab[ 'id' ] === $default_tab ) ? 'display: block;' : '';
                                    $content     = $tab[ 'content' ];
                                    $tab_id      = $tab[ 'id' ];
                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is safe, hardcoded plugin form markup.
                                    echo sprintf( '<div id="%1$s" class="b3_tab-content b3_tab-content--%2$s" style="%3$s">%4$s</div>', esc_attr( $tab_id ), esc_attr( $tab_id ), esc_attr( $style_value ), $content );
                                }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php }
