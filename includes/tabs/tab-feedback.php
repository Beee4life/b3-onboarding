<?php
    /**
     * Render feedback tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_feedback_tab() {

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Feedback', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can send us some feedback. All HTML will be stripped.', 'b3-onboarding' ); ?>
        </p>

        <?php
        include( B3_PLUGIN_PATH . '/includes/emails/email-feedback.php' );

        $result = ob_get_clean();

        return $result;
    }
