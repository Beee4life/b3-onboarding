<?php
    /**
     * The function which outputs the dashboard widget
     *
     * @since 2.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_dashboard_widget_debug_function() {
        $preview_page = admin_url( 'admin.php?page=b3-onboarding&preview=' );
        $widget_title = sprintf( '<h3>%s</h3>', esc_html__( 'Email preview links', 'b3-onboarding' ) );

        ob_start();
        if ( is_multisite() && is_main_site() ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'mu-confirm-email' ),  esc_html__( 'Confirm email (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'mu-user-activated' ),  esc_html__( 'User activated (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'mu-new-user-admin' ),  esc_html__( 'New user (admin)', 'b3-onboarding' ) );

        } elseif ( ! is_multisite() ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'account-approved' ),  esc_html__( 'Account approved (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'account-activated' ),  esc_html__( 'Account activated (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'account-rejected' ),  esc_html__( 'Account rejected (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'email-activation' ),  esc_html__( 'Email activation (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'request-access-admin' ),  esc_html__( 'Request access (admin)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'request-access-user' ),  esc_html__( 'Request access (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'new-user-admin' ),  esc_html__( 'New user (admin)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'welcome-user' ),  esc_html__( 'Welcome (user)', 'b3-onboarding' ) );
        }

        if ( is_main_site() ) {
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'lostpass' ),  esc_html__( 'Lost password (user)', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'styling' ),  esc_html__( 'Styling', 'b3-onboarding' ) );
            echo sprintf( '<li><a href="%s">%s</a></li>', esc_url( $preview_page . 'template' ),  esc_html__( 'Template', 'b3-onboarding' ) );
        }
        $links      = ob_get_clean();
        $links_list = sprintf( '<ul>%s</ul>', $links );

        $widget_content = $widget_title . $links_list;
        echo sprintf( '<div class="b3_widget--dashboard">%s</div>', $widget_content );
    }
    if ( current_user_can('manage_options' ) ) {
        wp_add_dashboard_widget( 'b3-dashboard-debug', 'B3 OnBoarding (debug)', 'b3_dashboard_widget_debug_function' );
    }
