<?php
    /**
     * The function which outputs the dashboard widget
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_dashboard_widget_function() {
        $activation_users = get_users( [ 'role' => 'b3_activation' ] );
        $all_users        = [];
        $approval_users   = get_users( [ 'role' => 'b3_approval' ] );

        if ( is_multisite() ) {
            $sites = get_sites( [ 'fields' => 'ids' ] );
        } else {
            $sites = [1];
        }

        if ( ! empty( $sites ) ) {
            // @TODO: also get users not connected to a network
            foreach( $sites as $site_id ) {
                $all_args = array(
                    'exclude'      => array( get_current_user_id() ),
                    'blog_id'      => $site_id,
                    'number'       => '5',
                    'orderby'      => 'registered',
                    'order'        => 'DESC',
                    'role__not_in' => array( 'b3_activation', 'b3_approval' ),
                );
                $all_users = array_merge_recursive( $all_users, get_users( $all_args ) );
            }
        }

        echo '<div class="b3_widget--dashboard">';

        if ( count( $approval_users ) > 0 ) {
            if ( 'request_access' === get_option( 'b3_registration_type' ) ) {
                $notice = sprintf( esc_html__( 'There %s %d %s awaiting approval. %s to manage %s.', 'b3-onboarding' ),
                    _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ),
                    count( $approval_users ),
                    _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ),
                    sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-user-approval' ), esc_html__( 'Click here', 'b3-onboarding' ) ),
                    _n( 'this user', 'these users', count( $approval_users ), 'b3-onboarding' ) );
            } else {
                $notice = sprintf( esc_html__( "There %s %d %s awaiting approval but you changed the registration type. That's why the user approval page is not showing in the admin menu and there are no notifications in the admin bar, but you can reach it %s.", 'b3-onboarding' ),
                    _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ),
                    count( $approval_users ),
                    _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ),
                    sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-user-approval' ), esc_html__( 'here', 'b3-onboarding' ) ) );
            }
        } elseif ( count( $activation_users ) > 0 ) {
            $notice = sprintf( esc_html__( 'There %s %d %s pending email activation.', 'b3-onboarding' ), _n( 'is', 'are', count( $activation_users ), 'b3-onboarding' ), count( $activation_users ), _n( 'user', 'users', count( $activation_users ), 'b3-onboarding' ) );
        }
        if ( isset( $notice ) ) {
            echo sprintf( '<p>%s</p>', $notice );
        }
        if ( ! empty( $all_users ) ) {
            ob_start();
            echo '<thead><tr>';
            echo sprintf( '<th>%s</th>', esc_html__( 'Login', 'b3-onboarding' ) );
            echo sprintf( '<th>%s</th>', esc_html__( 'ID', 'b3-onboarding' ) );
            echo sprintf( '<th>%s</th>', esc_html__( 'Reg. date', 'b3-onboarding' ) );
            echo '</tr></thead>';
            $table_headers = ob_get_clean();

            ob_start();
            echo '<tbody>';
            foreach( $all_users as $user ) {
                echo '<tr>';
                echo sprintf( '<td><a href="%s">%s</a></td>', admin_url( 'user-edit.php?user_id=' . $user->ID ), $user->user_login );
                echo sprintf( '<td>%s</td>', $user->ID );
                echo sprintf( '<td>%s</td>', b3_get_local_date_time( $user->user_registered ) );
                echo '</tr>';
            }
            echo '</tbody>';
            $table_rows    = ob_get_clean();
            $table_content = $table_headers . $table_rows;

            echo sprintf( '<table class="b3_table">%s</table>', $table_content );
        } else {
            if ( 'none' === get_option( 'b3_registration_type' ) ) {
                echo sprintf( '<p>%s</p>', sprintf( esc_html__( "You're the only user right now, but that can be because user registration is not allowed. Change it %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', B3OB_PLUGIN_SETTINGS . '&tab=registration', esc_html__( 'here', 'b3-onboarding' ) ) ) );
            } else {
                echo sprintf( '<p>%s</p>', esc_html__( "You're the only (activated) user right now.", 'b3-onboarding' ) );
            }
        }

        echo '</div>';
    }
    if ( current_user_can( 'promote_users' ) ) {
        wp_add_dashboard_widget( 'b3-dashboard', 'B3 OnBoarding - Last registered users', 'b3_dashboard_widget_function' );
    }


