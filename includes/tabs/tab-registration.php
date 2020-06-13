<?php
    /**
     * Render registration tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */
    function b3_render_registration_tab() {

        $action_links             = get_option( 'b3_disable_action_links', false );
        $first_last               = get_option( 'b3_activate_first_last', false );
        $first_last_required      = get_option( 'b3_first_last_required', false );
        $privacy                  = get_option( 'b3_privacy', false );
        $privacy_page             = get_option( 'b3_privacy_page', false );
        $privacy_page_placeholder = __( '<a href="">Click here</a> for more info.', 'b3-onboarding' );
        $privacy_text             = get_option( 'b3_privacy_text', false );
        $recaptcha                = get_option( 'b3_recaptcha', false );
        $recaptcha_login          = get_option( 'b3_recaptcha_login', false );
        $registration_type        = get_option( 'b3_registration_type', false );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Registration', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'Here you can set the main registration settings.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=registration" method="post">
            <input name="b3_registration_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-registration-nonce' ); ?>" />
            <?php if ( is_multisite() && is_main_site() || ! is_multisite() ) { ?>

                <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_registration_types"><?php esc_html_e( 'Registration options', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <?php if ( is_multisite() && is_main_site() ) { ?>
                    <p>
                        <?php echo sprintf( __( 'These settings are now the global settings and \'control\' the values on the <a href="%s">Network admin</a> page.', 'b3-onboarding' ), network_admin_url( 'settings.php' ) ); ?>
                    </p>
                <?php } else if ( ! is_multisite() ) { ?>
                    <p>
                        <?php echo sprintf( __( 'These settings are now the global settings and \'control\' the values on the <a href="%s">Settings page</a>.', 'b3-onboarding' ), admin_url( 'options-general.php' ) ); ?>
                    </p>
                <?php } ?>

                <?php $options = b3_get_registration_types(); ?>
                <?php if ( ! empty( $options ) ) { ?>
                    <?php foreach( $options as $option ) { ?>
                        <div class="b3_settings-input b3_settings-input--radio">
                            <div>
                                <label for="b3_registration_type_<?php echo $option[ 'value' ]; ?>" class="screen-reader-text"><?php echo $option[ 'label' ]; ?></label>
                                <input type="radio" id="b3_registration_type_<?php echo $option[ 'value' ]; ?>" name="b3_registration_type" value="<?php echo $option[ 'value' ]; ?>" <?php if ( $option[ 'value' ] == $registration_type ) { ?>checked="checked"<?php } ?>/> <?php echo $option[ 'label' ]; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="b3_settings-input b3_settings-input--radio">
                        <?php esc_html_e( 'Registrations are disabled.','b3-onboarding' ); ?>
                    </div>
                <?php } ?>
                <?php b3_get_close(); ?>

                <?php if ( 'closed' == get_option( 'b3_registration_type', false ) ) { ?>
                    <?php $closed_message = get_option( 'b3_registration_closed_message', false ); ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_registration_closed_message"><?php esc_html_e( 'Registration closed message', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="text" id="b3_registration_closed_message" name="b3_registration_closed_message" placeholder="<?php echo esc_attr( $closed_message ); ?>" value="<?php if ( $closed_message ) { echo stripslashes( $closed_message ); } ?>"/>
                            <div class="b3_settings-input-description">
                                <?php esc_html_e( 'Links are allowed.', 'b3-onboarding' ); ?>
                            </div>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_first_last"><?php esc_html_e( 'Activate first and last name', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_first_last" name="b3_activate_first_last" value="1" <?php if ( $first_last ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate first and last name during registration.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php if ( $first_last ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_first_last_required"><?php esc_html_e( 'Make first and last name required', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--checkbox">
                            <input type="checkbox" id="b3_first_last_required" name="b3_first_last_required" value="1" <?php if ( $first_last_required ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to make first and last name required.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_recaptcha"><?php esc_html_e( 'reCAPTCHA', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_recaptcha" name="b3_activate_recaptcha" value="1" <?php if ( $recaptcha ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate reCAPTCHA.', 'b3-onboarding' ); ?>
                        <?php $hide_recaptcha_note = ( 1 == get_option( 'b3_recaptcha', false ) ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--recaptcha<?php echo $hide_recaptcha_note; ?>">
                            <?php esc_html_e( 'See tab reCaptcha (after saving)', 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_privacy"><?php esc_html_e( 'Privacy checkbox', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_privacy" name="b3_privacy" value="1" <?php if ( $privacy ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate a privacy checkbox.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php $hide_privacy_settings = ( 1 == get_option( 'b3_privacy', false ) ) ? false : true; ?>
                <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_privacy_text"><?php esc_html_e( 'Privacy text', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--text">
                        <input type="text" id="b3_privacy_text" name="b3_privacy_text" placeholder="<?php echo esc_attr( $privacy_page_placeholder ); ?>" value="<?php if ( $privacy_text ) { echo stripslashes( $privacy_text ); } ?>"/>
                        <div class="b3_settings-input-description">
                            <?php esc_html_e( 'Links are allowed.', 'b3-onboarding' ); ?>
                        </div>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open( $hide_privacy_settings, 'privacy' ); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_privacy_page"><?php esc_html_e( 'Privacy page', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--text">
                        <?php $page_args = array( 'post_type' => 'page', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ); ?>
                        <?php $all_pages = get_posts( $page_args ); ?>
                        <select name="b3_privacy_page" id="b3_privacy_page">
                            <option value=""><?php esc_attr_e( 'Select a page', 'b3-onboarding' ); ?></option>
                            <?php foreach( $all_pages as $page ) { ?>
                                <?php $selected = ( $privacy_page == $page->ID ) ? ' selected="selected"' : false; ?>
                                <option value="<?php echo $page->ID; ?>"<?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disable_action_links"><?php esc_html_e( 'Disable action links', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_disable_action_links" name="b3_disable_action_links" value="1" <?php if ( $action_links ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to hide the action links on custom forms.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php b3_get_submit_button(); ?>

        </form>
        <?php
        $result = ob_get_clean();

        return $result;
    }
