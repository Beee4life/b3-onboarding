<?php
    /**
     * Render emails tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_emails_tab() {
        $activate_custom_emails = get_option( 'b3_activate_custom_emails' );
        $email_boxes            = b3_get_email_boxes();
        $link_color             = get_option( 'b3_link_color' );
        $logo_in_email          = get_option( 'b3_logo_in_email' );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php if ( is_main_site() ) { ?>
                <?php esc_html_e( 'Here you can set default email settings.', 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php esc_html_e( 'Most email settings are done in the main site.', 'b3-onboarding' ); ?>
            <?php } ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=emails" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">

            <?php if ( is_main_site() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php checked($activate_custom_emails); ?>/> <?php esc_html_e( 'Check this box to activate your own email styling and template.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_logo_in_email"><?php esc_html_e( 'Add logo in email', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_logo_in_email" name="b3_logo_in_email" value="1" <?php checked($logo_in_email); ?>/> <?php esc_html_e( 'Check this box to activate a logo in the email header (of the default template).', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_link_color"><?php esc_html_e( 'Link color', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <input name="b3_link_color" id="b3_link_color" type="color" value="<?php echo esc_attr( $link_color ); ?>">
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_email_settings_field( $box ); ?>
            <?php } ?>

            <?php b3_get_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
