<?php
    /**
     * Render emails tab
     *
     * @return false|string
     */
    function b3_render_emails_tab() {

        $custom_emails    = get_option( 'b3_custom_emails', false );
        $email_boxes      = b3_get_email_boxes();
        $email_link_color = get_option( 'b3_email_link_color', false );
        $logo_in_email    = get_option( 'b3_logo_in_email', false );
        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Emails', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can set default email settings.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=emails" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php if ( $custom_emails ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate your own email styling and template.', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_logo_in_email"><?php esc_html_e( 'Add logo in email', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_logo_in_email" name="b3_logo_in_email" value="1" <?php if ( $logo_in_email ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a logo in the email header (of the default template).', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_email_link_color"><?php esc_html_e( 'Link color', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <?php // @TODO: n2h colorpicker ?>
                <input name="b3_email_link_color" id="b3_email_link_color" type="text" value="<?php echo $email_link_color; ?>" placeholder="FF0000"> <?php esc_html_e( 'Must be a hex value of 3 or 6 characters (without hashtag)', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_email_settings_field( $box ); ?>
            <?php } ?>

            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save all email settings', 'b3-onboarding' ); ?>" />
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
