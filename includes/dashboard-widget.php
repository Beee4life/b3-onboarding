<?php
    /**
     * The function which outputs the dashboard widget
     *
     * @since 1.0.0
     */
    function b3_dashboard_widget_function() {
        // Widget contents
        $approval_args = array(
            'role' => 'b3_approval'
        );
        $approval_users = get_users( $approval_args );

        $activation_args = array(
            'role' => 'b3_activation'
        );
        $activation_users = get_users( $activation_args );

        $all_args = array(
            'exclude' => [ '1' ],
            'number'  => '5',
            'orderby' => 'registered',
            'order'   => 'DESC',
        );
        $all_users = get_users( $all_args );

        ?>
        <div class="b3_widget--dashboard">
            <?php if ( defined( 'WP_TESTING' ) && 1 == WP_TESTING ) { ?>
                <ul>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=template">Template</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=account-approved">Account approved</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=account-activated">Account activated</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=account-rejected">Account rejected</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=email-activation">Email activation</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=forgotpass">Forgot pass</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=new-user-admin">New user (admin)</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=request-access-admin">Request access (admin)</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=request-access-user">Request access (user)</a></li>
                    <li><a href="http://sandbox.beee/wp/wp-admin/admin.php?page=b3-onboarding&preview=welcome-user">Welcome user</a></li>
                </ul>
            <?php } ?>
            <p>
                <?php
                    if ( count( $approval_users ) > 0 ) {

                        if ( 'request_access' == get_option( 'b3_registration_type', false ) ) {
                            echo sprintf( __( 'There %s %d %s awaiting approval. <a href="%s">Click here</a> to manage %s.', 'b3-onboarding' ), _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ), count( $approval_users ), _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ), admin_url( 'admin.php?page=b3-user-approval' ), _n( 'this user', 'these users', count( $approval_users ), 'b3-onboarding' ) );
                        } else {
                            echo sprintf( __( 'There %s %d %s awaiting approval but you changed the registration type and thus also the user approval page.', 'b3-onboarding' ), _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ), count( $approval_users ), _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ) );
                        }

                    } elseif ( count( $activation_users ) > 0 ) {
                        echo sprintf( esc_html__( 'There %s %d %s awaiting activation.', 'b3-onboarding' ), _n( 'is', 'are', count( $activation_users ), 'b3-onboarding' ), count( $activation_users ), _n( 'user', 'users', count( $activation_users ), 'b3-onboarding' ) );
                    }
                ?>
            </p>

            <?php if ( ! empty( $all_users ) ) { ?>
                <?php $date_time_format = get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ); ?>
                <h3>
                    <?php esc_html_e( 'Last registered users', 'b3-onboarding' ); ?>
                </h3>
                <ul>
                    <?php foreach( $all_users as $user ) { ?>
                        <li>
                            <?php if ( current_user_can( 'edit_users' ) ) { ?>
                                <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $user->ID ); ?>">
                            <?php } ?>
                            <?php echo $user->user_login; ?>
                            <?php if ( current_user_can( 'edit_users' ) ) { ?>
                                </a>
                            <?php } ?>

                            (<?php echo date( $date_time_format, strtotime( $user->user_registered ) ); ?>)
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <?php
    }
    wp_add_dashboard_widget( 'b3-dashboard', 'B3 Onboarding', 'b3_dashboard_widget_function' );
