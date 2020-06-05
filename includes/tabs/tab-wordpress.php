<?php
    /**
     * Render login page design tab
     *
     * @return false|string
     */
    function b3_render_wordpress_tab() {

        $fonts = [
            'Arial',
            'Arial Narrow',
            'Calibri',
            'Cambria',
            'Candara',
            'Courier',
            'Courier New',
            'Garamond',
            'Geneva',
            'Helvetica',
            'Optima',
            'Perpetua',
            'Tahoma',
            'Times',
            'Times New Roman',
            'Verdana',
        ];

        ob_start();
        $background_color = get_option( 'b3_loginpage_bg_color', false );
        $font_family      = get_option( 'b3_loginpage_font_family', false );
        $font_size        = get_option( 'b3_loginpage_font_size', false );
        $logo_height      = get_option( 'b3_loginpage_logo_height', false );
        $logo_width       = get_option( 'b3_loginpage_logo_width', false );

        ?>
        <h2>
            WordPress <?php esc_html_e( 'forms', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php // @TODO: set if for if custom login page is set ?>
            <?php esc_html_e( 'Here you can style the (default) WordPress pages.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=wordpress" method="post">
            <input name="b3_loginpage_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-loginpage-nonce' ); ?>">

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_logo_width"><?php esc_html_e( 'Logo width', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_logo_width" id="b3_loginpage_logo_width" type="number" value="<?php echo $logo_width; ?>" placeholder=""> <?php esc_html_e( 'Default = 84 px. Max 320 px.', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_logo_height"><?php esc_html_e( 'Logo height', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_logo_height" id="b3_loginpage_logo_height" type="number" value="<?php echo $logo_height; ?>" placeholder=""> <?php esc_html_e( 'Max 150 px.', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_bg_color"><?php esc_html_e( 'Background color', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <?php // @TODO: n2h colorpicker ?>
                <input name="b3_loginpage_bg_color" id="b3_loginpage_bg_color" type="text" value="<?php echo $background_color; ?>" placeholder="FF0000"> <?php esc_html_e( 'Must be a hex value of 3 or 6 characters (without hashtag)', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_size"><?php esc_html_e( 'Font size (in px)', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <input name="b3_loginpage_font_size" id="b3_loginpage_font_size" type="number" value="<?php echo $font_size; ?>" placeholder=""> <?php esc_html_e( 'Default = 14px', 'b3-onboarding' ); ?>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_loginpage_font_family"><?php esc_html_e( 'Font family', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <select name="b3_loginpage_font_family" id="b3_loginpage_font_family">
                    <option value=""><?php esc_html_e( 'Select a font', 'b3-onboarding' ); ?></option>
                    <?php
                        foreach( $fonts as $font ) {
                            $selected = ( $font == $font_family ) ? ' selected="selected"' : false;
                            echo '<option value="' . $font . '"' . $selected . '>' . $font . '</option>';
                        }
                    ?>
                </select>
            <?php b3_get_close(); ?>

            <?php b3_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
