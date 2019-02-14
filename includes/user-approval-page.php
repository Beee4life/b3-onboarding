<?php
    
    /**
     * Content for the 'settings page'
     */
    function b3_user_approval() {
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-login' ) );
        }
        ?>

        <div class="wrap b3 b3__admin">

            <h1 id="b3__admin-title">
                <?php esc_html_e( 'User approval', 'b3-onboarding' ); ?>
            </h1>
            
            <?php echo do_shortcode( '[user-management]' ); ?>

        </div>
    <?php }
