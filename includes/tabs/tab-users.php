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

    function b3_render_users_tab() {

        $disallowed_domains           = false;
        $disallowed_domains_array     = get_option( 'b3_disallowed_domains' );
        if ( is_array( $disallowed_domains_array ) && ! empty( $disallowed_domains_array ) ) {
            $disallowed_domains = implode( ' ', $disallowed_domains_array );
        }
        $disallowed_usernames         = false;
        $disallowed_usernames_array   = get_option( 'b3_disallowed_usernames' );
        if ( is_array( $disallowed_usernames_array ) && ! empty( $disallowed_usernames_array ) ) {
            $disallowed_usernames = implode( ' ', $disallowed_usernames_array );
        }
        $username_restrictions   = get_option( 'b3_restrict_usernames' );
        $domain_restrictions     = get_option( 'b3_domain_restrictions' );
        $front_end_approval      = get_option( 'b3_front_end_approval' );
        $front_end_approval_page = get_option( 'b3_approval_page_id' );
        $hide_admin_bar          = get_option( 'b3_hide_admin_bar' );
        $roles                   = get_editable_roles();
        $user_may_delete         = get_option( 'b3_user_may_delete' );
        $restrict_admin          = get_option( 'b3_restrict_admin' );
        $registration_type       = get_option( 'b3_registration_type' );
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

            <?php $hide_front_end_approval = ( 'request_access' == $registration_type ) ? false : 'hidden'; ?>
            <?php b3_get_settings_field_open( false, $hide_front_end_approval ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_frontend_approval"><?php esc_html_e( 'Front-end user approval', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_frontend_approval" name="b3_activate_frontend_approval" value="1" <?php checked($front_end_approval); ?>/>
                    <?php
                        if ( 1 == $front_end_approval ) {
                            esc_html_e( 'Uncheck this box to deactivate front-end user approval.', 'b3-onboarding' );
                        } else {
                            esc_html_e( 'Check this box to activate front-end user approval.', 'b3-onboarding' );
                        }
                    ?>
                    <?php if ( false == $front_end_approval_page ) { ?>
                        <?php $hide_user_approval_note = ( 1 == $front_end_approval ) ? false : ' hidden'; ?>
                        <?php echo sprintf( '<div class="b3_settings-input-description b3_settings-input-description--approval%s">%s</div>', $hide_user_approval_note, esc_html__( "You still need to set an approval page (after you save the settings).", 'b3-onboarding' ) ); ?>
                    <?php } ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label><?php esc_html_e( 'Restrict admin access', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <?php $hidden_roles = array( 'b3_approval', 'b3_activation' ); ?>
                    <?php foreach( $hidden_roles as $role ) { ?>
                        <input type="hidden" id="b3_restrict_<?php echo esc_attr( $role ); ?>" name="b3_restrict_admin[]" value="<?php echo esc_attr( $role ); ?>" />
                    <?php } ?>
                    <?php echo sprintf( '<p>%s</p>', __( 'Which user roles do <b>not</b> have access to the WordPress admin ?', 'b3-onboarding' ) ); ?>

                    <?php
                        if ( is_array( $roles ) && ! empty( $roles ) ) {
                            $dont_show_roles = array( 'administrator', 'b3_approval', 'b3_activation' );
                            $stored_roles    = ( is_array( $restrict_admin ) ) ? $restrict_admin : array( 'b3_activation', 'b3_approval' );
                            echo '<div class="b3_restrict-roles">';
                            foreach( $roles as $name => $values ) {
                                if ( ! in_array( $name, $dont_show_roles ) ) {
                                    ?>
                                    <div>
                                        <label for="b3_restrict_<?php echo esc_attr( $name ); ?>" class="screen-reader-text"><?php echo esc_attr( $name ); ?></label>
                                        <input type="checkbox" id="b3_restrict_<?php echo esc_attr( $name ); ?>" name="b3_restrict_admin[]" value="<?php echo esc_attr( $name ); ?>" <?php checked(in_array( $name, $stored_roles )); ?> /><?php echo $values[ 'name' ]; ?>
                                    </div>
                                    <?php
                                }
                            }
                            echo '</div>';
                        }
                    ?>
                </div>
            <?php b3_get_close(); ?>

            <?php if ( ! is_multisite() && 'none' != $registration_type ) { ?>
                <?php $hide_username_settings = ( 1 == $username_restrictions ) ? false : true; ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_restrict_usernames"><?php esc_html_e( 'Disallow user names', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--text">
                        <input type="checkbox" id="b3_restrict_usernames" name="b3_restrict_usernames" value="1" <?php checked($username_restrictions); ?>/>
                        <?php
                            if ( 1 == $username_restrictions ) {
                                esc_html_e( 'Uncheck this box to disable user name blocking.', 'b3-onboarding' );
                            } else {
                                esc_html_e( 'Check this box to block certain user names from registering.', 'b3-onboarding' );
                            }
                        ?>
                    </div>
                <?php b3_get_close(); ?>
    
                <?php b3_get_settings_field_open( false, $hide_username_settings, 'username-restrictions' ); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disallowed_usernames"><?php esc_html_e( 'User names', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--text">
                        <input type="text" id="b3_disallowed_usernames" name="b3_disallowed_usernames" placeholder="<?php esc_attr_e( 'Separate user names with a space', 'b3-onboarding' ); ?>" value="<?php if ( $disallowed_usernames ) { echo stripslashes( $disallowed_usernames ); } ?>"/>
                        <?php if ( $username_restrictions ) { ?>
                            <?php echo sprintf( '<div><small>(%s)</small></div>', esc_html__( 'separate multiple user names with a space', 'b3-onboarding' ) ); ?>
                        <?php } ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php $hide_domain_settings = ( 1 == $domain_restrictions ) ? false : true; ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_domain_restrictions"><?php esc_html_e( 'Disallow domains', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_domain_restrictions" name="b3_domain_restrictions" value="1" <?php checked($domain_restrictions); ?>/>
                        <?php
                            if ( 1 == $domain_restrictions ) {
                                esc_html_e( 'Uncheck this box to disable domain name blocking.', 'b3-onboarding' );
                            } else {
                                esc_html_e( 'Check this box to block certain domains from registering.', 'b3-onboarding' );
                            }
                        ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open( false, $hide_domain_settings, 'domain-restrictions' ); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disallowed_domains"><?php esc_html_e( 'Domain names', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--text">
                        <?php echo sprintf( '<div>%s</div>', esc_html__( 'Email addresses from these domains are not allowed to register.', 'b3-onboarding' )); ?>
                        <input type="text" id="b3_disallowed_domains" name="b3_disallowed_domains" placeholder="<?php esc_attr_e( 'Separate domain names with a space', 'b3-onboarding' ); ?>" value="<?php if ( $disallowed_domains ) { echo stripslashes( $disallowed_domains ); } ?>"/>
                        <?php if ( $disallowed_domains ) { ?>
                            <?php echo sprintf( '<div><small>(%s)</small></div>', esc_html__( 'separate multiple domain names with a space', 'b3-onboarding' ) ); ?>
                        <?php } ?>
                    </div>
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php if ( ! is_multisite() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_hide_admin_bar"><?php esc_html_e( 'Hide admin bar', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_hide_admin_bar" name="b3_hide_admin_bar" value="1" <?php checked($hide_admin_bar); ?>/>
                        <?php
                            if ( 1 == $hide_admin_bar ) {
                                esc_html_e( "Uncheck this box to show the admin bar for user roles which don't have admin access.", 'b3-onboarding' );
                            } else {
                                esc_html_e( "Check this box to hide the admin bar for user roles which don't have admin access.", 'b3-onboarding' );
                            }
                        ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_user_may_delete"><?php esc_html_e( 'User may delete account', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_user_may_delete" name="b3_user_may_delete" value="1" <?php checked($user_may_delete); ?>/>
                        <?php
                            if ( 1 == $user_may_delete ) {
                                esc_html_e( 'Uncheck this box to not allow the user to delete his/her account (through custom profile page).', 'b3-onboarding' );
                            } else {
                                esc_html_e( 'Check this box to allow the user to delete his/her account (through custom profile page).', 'b3-onboarding' );
                            }
                        ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_submit_button(); ?>
            <?php } ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
