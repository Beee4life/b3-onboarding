<?php
    // the function which outputs the dashboard widget
    function b3_dashboard_widget_function() {
        // Widget contents
        ?>
        <div class="b3__widget--dashboard">
            @TODO
            <ul>
                <li>Show pending registrations</li>
            </ul>
        
        </div>
        <?php
    }
    
    // the function which adds the widget
    function b3_add_dashboard_widget() {
        if ( true == get_option( 'b3_dashboard_widget' ) ) {
            wp_add_dashboard_widget( 'b3-dashboard', 'Onboarding Info', 'b3_dashboard_widget_function' );
        }
    }
    add_action( 'wp_dashboard_setup', 'b3_add_dashboard_widget' );
