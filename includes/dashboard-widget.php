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
        <div class="b3__widget--dashboard">
            <p>
                <?php
                    if ( count( $approval_users ) > 0 ) {
                        echo sprintf( __( 'There are %d users awaiting approval.', 'b3-onboarding' ), count( $approval_users ) );
                    } elseif ( count( $activation_users ) > 0 ) {
                        echo sprintf( __( 'There are %d users awaiting activation.', 'b3-onboarding' ), count( $activation_users ) );
                    } else {
                        _e( 'All done. No users to approve.', 'b3-onboarding' );
                    }
                ?>
            </p>
            
            <?php if ( ! empty( $all_users ) ) { ?>
                <ul>
                    <?php foreach( $all_users as $user ) { ?>
                        <li>
                            <?php echo $user->user_login; ?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            
        </div>
        <?php
    }
    
    // the function which adds the widget
    function b3_add_dashboard_widget() {
        if ( true == get_option( 'b3_dashboard_widget' ) ) {
            wp_add_dashboard_widget( 'b3-dashboard', 'B3 Onboarding', 'b3_dashboard_widget_function' );
        }
    }
    add_action( 'wp_dashboard_setup', 'b3_add_dashboard_widget' );
