<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render users tab
    function b3_render_users_tab() {
        $activate_domain_restrictions = get_option( 'b3_activate_domain_restriction' );
        $activate_front_end_approval  = get_option( 'b3_activate_front_end_approval' );
        $activate_welcome_page        = get_option( 'b3_activate_welcome_page' );
        $disallowed_domains_filter    = apply_filters( 'b3_disallowed_domains', [] );
        $disallowed_domains_string    = b3_get_disallowed_domain_names( 'string' );
        $disallowed_usernames_filter  = apply_filters( 'b3_disallowed_usernames', [] );
        $disallowed_usernames_string  = ! empty( $disallowed_usernames_filter ) ? implode( ',', $disallowed_usernames_filter ) : '';
        $front_end_approval_page_id   = get_option( 'b3_approval_page_id' ); // @TODO: get from function
        $hide_admin_bar               = get_option( 'b3_hide_admin_bar' );
        $roles                        = get_editable_roles();
        $user_may_delete              = get_option( 'b3_user_may_delete' );
        $restrict_admin               = get_option( 'b3_restrict_admin' );
        $registration_type            = get_option( 'b3_registration_type' );
        asort( $roles );

        ob_start();
        echo sprintf( '<h2>%s</h2>', esc_html__( 'Users', 'b3-onboarding' ) );
        ?>

        <form action="admin.php?page=b3-onboarding&tab=users" method="post">
            <input name="b3_users_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-users-nonce' ) ); ?>">

            <?php $hide_front_end_approval = get_option( 'b3_needs_admin_approval' ) ? false : 'hidden'; ?>
            <?php b3_get_settings_field_open( $hide_front_end_approval ); ?>
                <?php b3_get_label_field_open(); ?>
                    <label for="b3_activate_frontend_approval"><?php esc_html_e( 'Front-end user approval', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_frontend_approval" name="b3_activate_frontend_approval" value="1" <?php checked($activate_front_end_approval); ?>/>
                    <?php esc_html_e( 'Activate front-end user approval.', 'b3-onboarding' ); ?>
                    <?php if ( false == $front_end_approval_page_id ) { ?>
                        <?php $hide_user_approval_note = $activate_front_end_approval ? false : ' hidden'; ?>
                        <?php echo sprintf( '<div class="b3_settings-input-description b3_settings-input-description--approval%s">%s</div>', esc_attr( $hide_user_approval_note ), esc_html__( 'You still need to set an approval page (after you save the settings).', 'b3-onboarding' ) ); ?>
                    <?php } ?>
                </div>
            <?php b3_get_close(); ?>

            <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                    <label><?php esc_html_e( 'Restrict admin access', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox b3_settings-input--restrict-users">
                    <?php $hidden_roles = [ 'b3_approval', 'b3_activation' ]; ?>
                    <?php foreach( $hidden_roles as $role ) { ?>
                        <input type="hidden" id="b3_restrict_<?php echo esc_attr( $role ); ?>" name="b3_restrict_admin[]" value="<?php echo esc_attr( $role ); ?>" />
                    <?php } ?>
                    <?php echo sprintf( '<p>%s</p>', wp_kses_post( 'Which user roles do <b>not</b> have access to the WordPress admin ?', 'b3-onboarding' ) ); ?>

                    <?php
                        if ( is_array( $roles ) && ! empty( $roles ) ) {
                            $dont_show_roles = [ 'administrator', 'b3_approval', 'b3_activation' ];
                            $stored_roles    = ( is_array( $restrict_admin ) ) ? $restrict_admin : [
                                'b3_activation',
                                'b3_approval',
                            ];
                            echo '<div class="b3_restrict-roles">';
                            foreach( $roles as $name => $values ) {
                                if ( ! in_array( $name, $dont_show_roles ) ) {
                                    ?>
                                    <div>
                                        <input type="checkbox" id="b3_restrict_<?php echo esc_attr( $name ); ?>" name="b3_restrict_admin[]" value="<?php echo esc_attr( $name ); ?>" <?php checked(in_array( $name, $stored_roles )); ?> />
                                        <label for="b3_restrict_<?php echo esc_attr( $name ); ?>"><?php echo esc_attr( $values[ 'name' ] ); ?></label>
                                    </div>
                                    <?php
                                }
                            }
                            echo '</div>';
                        }
                    ?>
                </div>
            <?php b3_get_close(); ?>

            <?php if ( ! is_multisite() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                <?php b3_get_label_field_open(); ?>
                <label for="b3_activate_welcome_page"><?php esc_html_e( 'Welcome page', 'b3-onboarding' ); ?></label>
                <?php b3_get_close(); ?>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_activate_welcome_page" name="b3_activate_welcome_page" value="1" <?php checked($activate_welcome_page); ?>/>
                    <?php esc_html_e( "Redirect the user to a 'welcome' page after his first login.", 'b3-onboarding' ); ?>
                    <?php $hide_welcome_page_note = 1 == $activate_welcome_page ? false : ' hidden'; ?>
                    <div class="b3_settings-input-description b3_settings-input-description--welcome<?php echo esc_attr( $hide_welcome_page_note ); ?>">
                        <?php // translators: link to filter for 'welcome page' ?>
                        <?php echo sprintf( esc_html__( 'This page can only be set with a filter (for now). See %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url('https://b3onboarding.berryplasman.com/filter/b3_welcome_page/'), esc_html__( 'here', 'b3-onboarding' ) ) ); ?>
                    </div>
                </div>
                <?php b3_get_close(); ?>

                <?php if ( $activate_welcome_page ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                    <label for="b3_remove_user_meta_seen"><?php esc_html_e( "Remove 'welcome_page_seen' meta", 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_remove_user_meta_seen" name="b3_remove_user_meta_seen" value="1"/>
                        <?php esc_html_e( "Clear the 'welcome_page_seen' meta for all users, so you can show a new page.", 'b3-onboarding' ); ?>
                    </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_hide_admin_bar"><?php esc_html_e( 'Hide admin bar', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_hide_admin_bar" name="b3_hide_admin_bar" value="1" <?php checked($hide_admin_bar); ?>/>
                        <?php esc_html_e( "Hide the admin bar for user roles which don't have admin access.", 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php if ( ! is_multisite() && 'none' != $registration_type ) { ?>
                <?php
                    $email_only                 = get_option( 'b3_register_email_only' );
                    $username_restrictions      = get_option( 'b3_activate_username_restriction' );
                    $hide_username_restrictions = $username_restrictions ? false : true;
                ?>
                <?php if ( ! $email_only ) { ?>
                    <?php b3_get_settings_field_open(); ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_activate_username_restriction"><?php esc_html_e( 'Disallow usernames', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <input type="checkbox" id="b3_activate_username_restriction" name="b3_activate_username_restriction" value="1" <?php checked($username_restrictions); ?>/>
                            <?php esc_html_e( 'Block certain usernames from registering.', 'b3-onboarding' ); ?>
                        </div>
                    <?php b3_get_close(); ?>

                    <?php b3_get_settings_field_open( $hide_username_restrictions, 'username-restrictions' ); ?>
                    <?php if ( $disallowed_usernames_filter ) { $placeholder = sprintf( '%s (%s)', $disallowed_usernames_string, esc_html__( 'Set by filter', 'b3-onboarding' ) ); } else { $placeholder = esc_html__( 'Separate multiple usernames with a comma', 'b3-onboarding' ); } ?>
                    <?php if ( $disallowed_usernames_filter ) { $value = ''; } else { $value = $disallowed_usernames_string; } ?>
                        <?php b3_get_label_field_open(); ?>
                            <label for="b3_disallowed_usernames"><?php esc_html_e( 'User names', 'b3-onboarding' ); ?></label>
                        <?php b3_get_close(); ?>
                        <div class="b3_settings-input b3_settings-input--text">
                            <?php // translators: link to function for 'default reserved usernames' ?>
                            <div class="b3_above_input"><?php echo sprintf( esc_html__( 'Some usernames are excluded already by default, see them %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', sprintf( '%s/function/b3_get_default_reserved_user_names/', esc_url( B3OB_PLUGIN_SITE ) ), esc_html__( 'here', 'b3-onboarding' ) ) ); ?></div>
                            <input type="text" id="b3_disallowed_usernames" name="b3_disallowed_usernames" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php if ( ! empty( $disallowed_usernames_filter ) && is_array( $disallowed_usernames_filter ) ) { ?> disabled<?php }?> />
                        </div>
                    <?php b3_get_close(); ?>
                <?php } ?>

                <?php $hide_domain_settings = ( true == $activate_domain_restrictions ) ? false : true; ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_activate_domain_restriction"><?php esc_html_e( 'Disallow domains', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_activate_domain_restriction" name="b3_activate_domain_restriction" value="1" <?php checked($activate_domain_restrictions); ?>/>
                        <?php esc_html_e( 'Block certain domains from registering.', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_settings_field_open( $hide_domain_settings, 'domain-restrictions' ); ?>
                    <?php if ( $disallowed_domains_filter ) { $placeholder = sprintf( '%s (%s)', $disallowed_domains_string, esc_html__( 'Set by filter', 'b3-onboarding' ) ); } else { $placeholder = esc_html__( 'Separate multiple domain names with a comma', 'b3-onboarding' ); } ?>
                    <?php if ( $disallowed_domains_filter ) { $value = ''; } else { $value = $disallowed_domains_string; } ?>

                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_disallowed_domains"><?php esc_html_e( 'Domain names', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>

                    <div class="b3_settings-input b3_settings-input--text">
                        <?php echo sprintf( '<div>%s</div>', esc_html__( 'Email addresses from these domains are not allowed to register.', 'b3-onboarding' )); ?>
                        <input type="text" id="b3_disallowed_domains" name="b3_disallowed_domains" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php if ( ! empty( $disallowed_domains_filter ) && is_array( $disallowed_domains_filter ) ) { ?> disabled<?php } ?> />
                    </div>
                <?php b3_get_close(); ?>
            <?php } ?>

            <?php if ( ! is_multisite() ) { ?>
                <?php b3_get_settings_field_open(); ?>
                    <?php b3_get_label_field_open(); ?>
                        <label for="b3_user_may_delete"><?php esc_html_e( 'User may delete account', 'b3-onboarding' ); ?></label>
                    <?php b3_get_close(); ?>
                    <div class="b3_settings-input b3_settings-input--checkbox">
                        <input type="checkbox" id="b3_user_may_delete" name="b3_user_may_delete" value="1" <?php checked($user_may_delete); ?>/>
                        <?php esc_html_e( 'Allow the user to delete his/her account (through custom profile page).', 'b3-onboarding' ); ?>
                    </div>
                <?php b3_get_close(); ?>

                <?php b3_get_submit_button(); ?>
            <?php } ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
