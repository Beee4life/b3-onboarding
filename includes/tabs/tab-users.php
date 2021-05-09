<?php
    /**
     * Render emails tab
     *
     * @since 1.0.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) exit;

    function b3_render_users_tab() {

        $front_end_approval      = get_site_option( 'b3_front_end_approval' );
        $front_end_approval_page = get_site_option( 'b3_approval_page_id' );
        $hide_admin_bar          = get_site_option( 'b3_hide_admin_bar' );
        $roles                   = get_editable_roles();
        $user_may_delete         = get_site_option( 'b3_user_may_delete' );
        $restrict_admin          = get_site_option( 'b3_restrict_admin' );
        $registration_type       = get_site_option( 'b3_registration_type' );
        asort( $roles );

        ob_start();
        ?>
        <h2>
            <?php esc_html_e( 'Users', 'b3-onboarding' ); ?>
        </h2>

        <p>
            <?php esc_html_e( 'This page contains settings for users.', 'b3-onboarding' ); ?>
        </p>

        <form action="admin.php?page=b3-onboarding&tab=users" method="post">
            <input name="b3_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-users-nonce' ); ?>">

            <?php $hide_for_multisite = ( is_multisite() ) ? true : false; ?>
            <?php $hide_front_end_approval = ( 'request_access' == $registration_type ) ? false : 'hidden'; ?>
            <?php b3_get_settings_field_open($hide_front_end_approval ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_frontend_approval"><?php esc_html_e( 'Front-end user approval', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_frontend_approval" name="b3_activate_frontend_approval" value="1" <?php if ( $front_end_approval ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to activate front-end user approval.', 'b3-onboarding' ); ?>
                    <?php if ( false == $front_end_approval_page ) { ?>
                        <?php $hide_user_approval_note = ( 1 == $front_end_approval ) ? false : ' hidden'; ?>
                        <div class="b3_settings-input-description b3_settings-input-description--approval<?php echo $hide_user_approval_note; ?>">
                            <?php esc_html_e( "You still need to set an approval page (after you save the settings).", 'b3-onboarding' ); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_user_may_delete"><?php esc_html_e( 'User may delete account', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_user_may_delete" name="b3_user_may_delete" value="1" <?php if ( $user_may_delete ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to allow the user to delete his/her account (through custom profile page).', 'b3-onboarding' ); ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open($hide_for_multisite); ?>
                <?php b3_get_label_field_open(); ?>
                    <label><?php esc_html_e( 'Restrict admin access', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <?php $hidden_roles = array( 'b3_approval', 'b3_activation' ); ?>
                    <?php foreach( $hidden_roles as $role ) { ?>
                        <input type="hidden" id="b3_restrict_<?php echo $role; ?>" name="b3_restrict_admin[]" value="<?php echo $role; ?>" />
                    <?php } ?>
                    <p>
                        <?php _e( 'Which user roles do <b>not</b> have access to the WordPress admin ?', 'b3-onboarding' ); ?>
                    </p>
                    <?php
                        $dont_show_roles  = array( 'administrator', 'b3_approval', 'b3_activation' );
                        $stored_roles     = ( is_array( $restrict_admin ) ) ? $restrict_admin : array( 'b3_activation', 'b3_approval' );
                        foreach( $roles as $name => $values ) {
                            if ( ! in_array( $name, $dont_show_roles ) ) {
                                ?>
                                <div>
                                    <label for="b3_restrict_<?php echo $name; ?>" class="screen-reader-text"><?php echo $name; ?></label>
                                    <input type="checkbox" id="b3_restrict_<?php echo $name; ?>" name="b3_restrict_admin[]" value="<?php echo $name; ?>" <?php if ( in_array( $name, $stored_roles ) ) { ?>checked="checked"<?php } ?> /> <?php echo $values[ 'name' ]; ?>
                                </div>
                                <?php
                            }
                        }
                    ?>
                </div>
            <?php b3_get_close(); ?>

            <?php if ( ! is_multisite() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_hide_admin_bar"><?php esc_html_e( 'Hide admin bar', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_hide_admin_bar" name="b3_hide_admin_bar" value="1" <?php if ( $hide_admin_bar ) { ?>checked="checked"<?php } ?>/> <?php esc_html_e( 'Check this box to hide the admin bar for user roles who don\'t have admin access.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_submit_button(); ?>
            <?php } ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
