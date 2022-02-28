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

        // get all sites in network
        if ( is_multisite() ) {
            $sites = get_sites( [ 'fields' => 'ids' ] );
        } else {
            // @TODO: test this on single site
            $sites = [1];
        }

        if ( ! empty( $sites ) ) {
            // @TODO: get users not connected to a network
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
        ?>
        <div class="b3_widget--dashboard">
            <?php if ( count( $approval_users ) > 0  || count( $activation_users ) > 0 ) { ?>
                <p>
                    <?php
                        if ( count( $approval_users ) > 0 ) {
                            if ( 'request_access' == get_option( 'b3_registration_type' ) ) {
                                echo sprintf( __( 'There %s %d %s awaiting approval. %s to manage %s.', 'b3-onboarding' ),
                                    _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ),
                                    count( $approval_users ),
                                    _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ),
                                    sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-user-approval' ), esc_html__( 'Click here', 'b3-onboarding' ) ),
                                    _n( 'this user', 'these users', count( $approval_users ), 'b3-onboarding' ) );
                            } else {
                                echo sprintf( __( "There %s %d %s awaiting approval but you changed the registration type. That's why the user approval page is not showing in the admin menu and there are no notifications in the admin bar, but you can reach it %s.", 'b3-onboarding' ),
                                    _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ),
                                    count( $approval_users ),
                                    _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ),
                                    sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=b3-user-approval' ), esc_html__( 'here', 'b3-onboarding' ) ) );
                            }
                        } elseif ( count( $activation_users ) > 0 ) {
                            echo sprintf( esc_html__( 'There %s %d %s pending email activation.', 'b3-onboarding' ), _n( 'is', 'are', count( $activation_users ), 'b3-onboarding' ), count( $activation_users ), _n( 'user', 'users', count( $activation_users ), 'b3-onboarding' ) );
                        }
                    ?>
                </p>
            <?php } ?>

            <?php if ( ! empty( $all_users ) ) { ?>
                <table class="b3_table">
                    <thead>
                    <tr>
                        <?php echo sprintf( '<th>%s</th>', esc_html__( 'Login', 'b3-onboarding' ) ); ?>
                        <?php echo sprintf( '<th>%s</th>', esc_html__( 'ID', 'b3-onboarding' ) ); ?>
                        <?php echo sprintf( '<th>%s</th>', esc_html__( 'Reg. date', 'b3-onboarding' ) ); ?>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $all_users as $user ) { ?>
                            <tr>
                                <?php echo sprintf( '<td><a href="%s">%s</a></td>', admin_url( 'user-edit.php?user_id=' . $user->ID ), $user->user_login ); ?>
                                <?php echo sprintf( '<td>[%s]</td>', $user->ID ); ?>
                                <?php echo sprintf( '<td>(%s)</td>', b3_get_local_date_time( $user->user_registered ) ); ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <?php if ( 'none' == get_option( 'b3_registration_type' ) ) { ?>
                    <?php echo sprintf( '<p>%s</p>', sprintf( __( "You're the only user right now, but that can be because user registration is not allowed. Change it %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', B3_PLUGIN_SETTINGS . '&tab=registration', __( 'here', 'b3-onboarding' ) ) ) ); ?>
                <?php } else { ?>
                    <?php echo sprintf( '<p>%s</p>', esc_html__( "You're the only (activated) user right now.", 'b3-onboarding' ) ); ?>
                <?php } ?>
            <?php } ?>
        </div>
        <?php
    }
    if ( current_user_can( 'promote_users' ) ) {
        wp_add_dashboard_widget( 'b3-dashboard', 'B3 OnBoarding - Last registered users', 'b3_dashboard_widget_function' );
    }


