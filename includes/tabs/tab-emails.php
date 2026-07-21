<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render emails tab
    function b3_render_emails_tab() {
        $activate_custom_emails = get_option( 'b3_activate_custom_emails' );
        $activate_logo_in_email = get_option( 'b3_activate_logo_in_email' );
        $email_boxes            = b3_get_email_boxes();
        $filter_link_color      = apply_filters( 'b3_link_color', false );
        $filter_styling         = apply_filters( 'b3_email_styling', false );
        $filter_template        = apply_filters( 'b3_email_template', false );
        $link_color             = b3_get_link_color();
        $main_logo              = get_option( 'b3_main_logo' );

        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Emails', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=emails" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-emails-nonce' ) ); ?>">

            <?php if ( is_main_site() ) { ?>
                <?php // @TODO: add to all sites ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_link_color"><?php esc_html_e( 'Link color', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <input name="b3_link_color" id="b3_link_color" type="color" value="<?php echo esc_attr( $link_color ); ?>">
                    <?php if ( $filter_link_color ) { ?>
                        <?php esc_html_e( 'Set by filter', 'b3-onboarding' ); ?>
                    <?php } ?>
                <?php b3_get_close(); ?>

                <?php // @TODO: add to all sites ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <?php if ( $filter_styling && $filter_template ) { ?>
                        <?php esc_html_e( 'You have set both template and styling by filter.', 'b3-onboarding' ); ?>
                    <?php } else { ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php checked($activate_custom_emails); ?>/>
                            <?php esc_html_e( 'Activate your own email styling and template.', 'b3-onboarding' ); ?>
                        </div>
                    <?php } ?>
                <?php b3_get_close(); ?>

                <?php if ( ! $activate_custom_emails ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_logo_in_email"><?php esc_html_e( 'Add logo in email', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_activate_logo_in_email" name="b3_activate_logo_in_email" value="1" <?php checked($activate_logo_in_email); ?>/>
                            <?php esc_html_e( 'Activate a logo in the email header (of the default template).', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>
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
