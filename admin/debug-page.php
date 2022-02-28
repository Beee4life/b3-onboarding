<?php
    /**
     * Content for the 'debug page'
     *
     * @since 2.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_debug_page() {

        if ( ! current_user_can( apply_filters( 'b3_user_cap', 'manage_options' ) ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-onboarding' ) );
        }
        ?>

        <div class="wrap b3 b3_admin b3_admin--debug">
            <h1 id="b3__admin-title">
                Debug info
            </h1>
            <?php
                ob_start();
                include 'debug-info.php';
                echo ob_get_clean();
            ?>
        </div>
    <?php }
