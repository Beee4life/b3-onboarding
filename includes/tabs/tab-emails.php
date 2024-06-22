<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Render emails tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_emails_tab() {
        $activate_custom_emails = get_option( 'b3_activate_custom_emails' );
        $email_boxes            = b3_get_email_boxes();
        $link_color             = b3_get_link_color();
        $main_logo              = get_option( 'b3_main_logo' );
        $filter_link_color      = apply_filters( 'b3_link_color', false );
        $logo_in_email          = get_option( 'b3_logo_in_email' );
        $hide_logo_field        = $logo_in_email ? false : ' hidden';
        $hide_logo_notice       = $logo_in_email ? false : ' hidden';

        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Emails', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=emails" method="post">
            <input name="b3_emails_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-emails-nonce' ); ?>">

            <?php if ( is_main_site() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_link_color"><?php esc_html_e( 'Link color', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <input name="b3_link_color" id="b3_link_color" type="color" value="<?php echo esc_attr( $link_color ); ?>">
                    <?php if ( $filter_link_color ) { ?>
                        <?php esc_html_e( "The 'b3_link_color' filter is active which sets the link color.", 'b3-onboarding' ); ?>
                    <?php } ?>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_custom_emails"><?php esc_html_e( 'Custom email styling/template', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_custom_emails" name="b3_activate_custom_emails" value="1" <?php checked($activate_custom_emails); ?>/>
                        <?php esc_html_e( 'Activate your own email styling and template.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( ! $activate_custom_emails ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_logo_in_email"><?php esc_html_e( 'Add logo in email', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_logo_in_email" name="b3_logo_in_email" value="1" <?php checked($logo_in_email); ?>/>
                            <?php esc_html_e( 'Activate a logo in the email header (of the default template).', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>
            <?php } ?>

            <?php foreach( $email_boxes as $box ) { ?>
                <?php echo b3_render_email_settings_field( $box ); ?>
            <?php } ?>
            
            <?php b3_get_settings_field_open( $hide_logo_field, 'logo' ); ?>
                <?php echo sprintf( '<h2>%s</h2>', esc_html__( 'Logo', 'b3-onboarding' ) ); ?>
                <div id="b3-main-logo-settings">
                    <?php echo sprintf( '<p>%s</p>', esc_html__( "This is the logo used in email headers.", 'b3-onboarding' ) ); ?>
                    <p>
                        <?php if ( false == apply_filters( 'b3_main_logo', false ) ) { ?>
                            <label>
                                <input type="url" name="b3_main_logo" id="b3_main_logo" value="<?php echo esc_url( $main_logo ); ?>" />
                            </label>
                            <a href="#" id="main-logo" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>">
                                <?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?>
                            </a>
                        <?php } else { ?>
                            <?php esc_html_e( "You've set this logo with a filter.", 'b3-onboarding' ); ?>
                            <br>
                            <a href="<?php echo apply_filters( 'b3_main_logo', false ); ?>">
                                <img src="<?php echo apply_filters( 'b3_main_logo', false ); ?>" alt="" style="max-width: 300px;" />
                            </a>
                        <?php } ?>
                    </p>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
