<?php
    // the function which outputs the dashboard widget
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
            <p>
                <?php
                    if ( count( $approval_users ) > 0 && 'request_access' == get_option( 'b3_registration_type' ) ) {
                        echo sprintf( __( 'There %s %d %s awaiting approval. <a href="%s">Click here</a> to manage %s.', 'b3-onboarding' ), _n( 'is', 'are', count( $approval_users ), 'b3-onboarding' ), count( $approval_users ), _n( 'user', 'users', count( $approval_users ), 'b3-onboarding' ), admin_url( 'admin.php?page=b3-user-approval' ), _n( 'this user', 'these users', count( $approval_users ), 'b3-onboarding' ) );
                    } elseif ( count( $activation_users ) > 0 ) {
                        echo sprintf( esc_html__( 'There %s %d %s awaiting activation.', 'b3-onboarding' ), _n( 'is', 'are', count( $activation_users ), 'b3-onboarding' ), count( $activation_users ), _n( 'user', 'users', count( $activation_users ), 'b3-onboarding' ) );
                    } else {
                        esc_html_e( 'All done. No users to approve.', 'b3-onboarding' );
                    }
                ?>
            </p>
            
            <?php if ( ! empty( $all_users ) ) { ?>
                <?php $date_time_format = get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ); ?>
                <h3>
                    <?php esc_html_e( 'LAST REGISTERED USERS', 'b3-onboarding' ); ?>
                </h3>
                <ul>
                    <?php foreach( $all_users as $user ) { ?>
                        <li>
                            <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $user->ID ); ?>">
                                <?php echo $user->user_login; ?>
                            </a>
                            (<?php echo date( $date_time_format, strtotime( $user->user_registered ) ); ?>)
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
        <?php
    }
