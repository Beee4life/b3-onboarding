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
            <?php if ( count( $approval_users ) > 0  || count( $activation_users ) > 0 ) { ?>
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
            <?php } ?>

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
            <?php } else { ?>
                <h3>
                    <?php esc_html_e( 'Last registered users', 'b3-onboarding' ); ?>
                </h3>
                <?php if ( 'closed' == get_option( 'b3_registration_type', false ) || false == get_option( 'users_can_register', false ) ) { ?>
                    <p>
                        <?php printf( __( "You're the only user right now, but that can be because user registration is not allowed. Change it <a href=\"%s\">here</a>.", 'b3-onboarding' ), B3_PLUGIN_SETTINGS . '&tab=registration' ); ?>
                    </p>
                <?php } else { ?>
                    <p>
                        <?php esc_html_e( "You're the only user right now.", 'b3-onboarding' ); ?>
                    </p>
                <?php } ?>
            <?php } ?>
        </div>
        <?php
    }
    wp_add_dashboard_widget( 'b3-dashboard', 'B3 Onboarding', 'b3_dashboard_widget_function' );
