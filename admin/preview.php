<?php
    /**
     * Preview page output
     *
     * @since 2.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_preview">
    <?php
        $message = false;
        $subject = false;

        if ( isset( $_GET[ 'preview' ] ) ) {
            $hide_logo       = ! get_option( 'b3_activate_logo_in_email' ) ? true : false;
            $preview         = sanitize_text_field( wp_unslash( $_GET[ 'preview' ] ) );
            $subject_message = b3_get_subject_message( $preview );

            if ( ! empty( $subject_message ) ) {
                $subject = $subject_message[ 'subject' ];
                $message = $subject_message[ 'message' ];
            }

            if ( 'styling' !== $preview && ! empty( $message ) && ! empty( $subject ) ) {
                $subject = strtr( $subject, b3_get_replacement_vars( 'subject' ) );
                $message = b3_replace_template_styling( $message );
                $message = strtr( $message, b3_get_replacement_vars() );
                $message = htmlspecialchars_decode( stripslashes( $message ) );
            ?>

            <p>
                <?php
                    if ( 'template' === $preview ) {
                        esc_html_e( 'This is what the default email will look like (approximately). Some elements can be overridden by the css loaded in your admin.', 'b3-onboarding' );
                    } else {
                        esc_html_e( 'This is what your email will look like (approximately). Some elements can be overridden by the css loaded in your admin.', 'b3-onboarding' );
                    }
                ?>
            </p>

            <?php
                if ( 'styling' !== $preview ) {
                    $button_label = esc_html__( 'Send test email', 'b3-onboarding' );
                    echo sprintf(
                        '<p><a href="#" id="b3-send-test-email" class="button button-primary">%s</a></p>',
                        $button_label
                    );
                }
            ?>

            <?php if ( false != $subject ) { ?>
                <p>
                    <b><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?>:</b> "<?php echo esc_html($subject); ?>"
                </p>
            <?php } ?>

            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php echo $message; ?>

        <?php } else { ?>
            <p><?php esc_html_e( "These are the email's styling definitions.", 'b3-onboarding' ); ?></p>
            <pre><?php echo esc_html( wp_strip_all_tags( $message ) ); ?></pre>
        <?php } // styling !== preview ?>
    <?php } // end $_GET preview ?>
</div>
