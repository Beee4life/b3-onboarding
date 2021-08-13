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
        $approval_args = array(
            'role' => 'b3_approval'
        );
        $approval_users = get_users( $approval_args );

        $activation_args = array(
            'role' => 'b3_activation'
        );
        $activation_users = get_users( $activation_args );

        // get all sites in network
        $all_users = [];
        $sites     = get_sites( [ 'fields' => 'ids' ] );

        if ( ! empty( $sites ) ) {
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
                            if ( 'request_access' == get_site_option( 'b3_registration_type' ) ) {
                                echo sprintf( __( 'There %s %d %s awaiting approval. <a href="%s">Click here</a> to manage %s.', 'b3-onboarding' ), _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ), count( $approval_users ), _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ), admin_url( 'admin.php?page=b3-user-approval' ), _n( 'this user', 'these users', count( $approval_users ), 'b3-onboarding' ) );
                            } else {
                                echo sprintf( __( 'There %s %d %s awaiting approval but you changed the registration type. That\'s why the user approval page is not showing in the admin menu and there are no notifications in the admin bar, but you can reach it <a href="%s">here</a>.', 'b3-onboarding' ), _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ), count( $approval_users ), _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ), admin_url( 'admin.php?page=b3-user-approval' ) );
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
                        <th>Login</th>
                        <th>ID</th>
                        <th>Reg. date</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach( $all_users as $user ) { ?>
                            <tr>
                                <td>
                                    <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $user->ID ); ?>">
                                        <?php echo $user->user_login; ?>
                                    </a>
                                </td>
                                <td>
                                    [<?php echo $user->ID; ?>]
                                </td>
                                <td>
                                    (<?php echo b3_get_local_date_time( $user->user_registered ); ?>)
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <?php if ( 'closed' == get_site_option( 'b3_registration_type' ) ) { ?>
                    <p>
                        <?php printf( __( "You're the only user right now, but that can be because user registration is not allowed. Change it <a href=\"%s\">here</a>.", 'b3-onboarding' ), B3_PLUGIN_SETTINGS . '&tab=registration' ); ?>
                    </p>
                <?php } else { ?>
                    <p>
                        <?php esc_html_e( "You're the only (activated) user right now.", 'b3-onboarding' ); ?>
                    </p>
                <?php } ?>
            <?php } ?>
        </div>
        <?php
    }
    if ( current_user_can( 'promote_users' ) ) {
        wp_add_dashboard_widget( 'b3-dashboard', 'B3 OnBoarding - Last registered users', 'b3_dashboard_widget_function' );
    }


