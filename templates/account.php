<?php
    /**
     * Ouptuts fields for account page
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $current_user_object = get_userdata( get_current_user_id() );
    $required            = ( true == get_option( 'b3_first_last_required' ) ) ? ' required="required"' : false;

    do_action( 'b3_add_form_messages', $attributes );
?>

<div id="b3-account" class="b3_page b3_page--account">
    <form id="accountform" action="<?php echo get_the_permalink( get_the_ID() ); ?>" method="post">
        <?php wp_nonce_field( 'update-user_' . $current_user_object->ID ); ?>
        <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user_object->ID, 'show_admin_bar_front', true ); ?>" />
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="instance" value="1" />
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user_object->ID; ?>" />
        <input type="hidden" name="action" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo $current_user_object->ID; ?>" />
        <input type="hidden" name="nickname" id="nickname" value="<?php echo ( isset( $current_user_object->nickname ) ) ? esc_attr( $current_user_object->nickname ) : esc_attr( $current_user_object->user_login ); ?>" class="regular-text" />

        <?php if ( isset( $attributes[ 'updated' ] ) ) { ?>
            <?php echo sprintf( '<p class="b3_message">%s</p>', esc_html__( 'Profile saved', 'b3-onboarding' ) ); ?>
        <?php } ?>

        <?php // @TODO: create action for this ?>
        <?php if ( is_multisite() && in_array( $attributes[ 'registration_type' ], [ 'all', 'blog', 'request_access_subdomain' ] ) ) { ?>
            <?php $user_sites = get_blogs_of_user( $current_user_object->ID ); ?>
            <?php if ( ! empty( $user_sites ) ) { ?>
                <?php $url_path  = ( count( $user_sites ) > 1 ) ? 'my-sites.php' : false; ?>
                <?php $site_info = array_shift( $user_sites ); ?>
                <?php $url = apply_filters( 'b3_dashboard_url', get_admin_url( $site_info->userblog_id, $url_path ), $site_info ); ?>
                <div class="b3_form-element">
                    <label class="b3_form-label" for="yoursites"><?php esc_attr_e( 'Your site(s)', 'b3-onboarding' ); ?></label>
                    <a href="<?php echo $url; ?>">
                        <?php echo $site_info->blogname; ?>
                    </a>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="b3_form-element">
            <?php if ( false == get_option( 'b3_register_email_only' ) ) { ?>
                <label class="b3_form-label" for="user_login"><?php esc_attr_e( 'Username', 'b3-onboarding' ); ?></label>
            <?php } else { ?>
                <label class="b3_form-label" for="b3_user_login"><?php esc_attr_e( 'User ID', 'b3-onboarding' ); ?></label>
            <?php } ?>
            <?php // @TODO: just echo it (as text), but not in a disabled input ?>
            <input type="text" name="user_login" id="user_login" value="<?php esc_attr_e( $current_user_object->user_login ); ?>" disabled="disabled" />
        </div>

        <div class="b3_form-element">
            <label class="b3_form-label" for="email">
                <?php esc_attr_e( 'Email address', 'b3-onboarding' ); ?>
            </label>

            <input type="text" name="email" id="email" value="<?php esc_attr_e( $current_user_object->user_email ); ?>" class="input regular-text" />
            <?php
                $new_email = get_option( $current_user_object->ID . '_new_email' );
                if ( $new_email && $new_email[ 'newemail' ] != $current_user_object->user_email ) : ?>
                    <div class="updated inline">
                        <p>
                            <?php
                                printf(
                                    esc_html__( 'There is a pending change of your e-mail to %s. %s', 'b3-onboarding' ),
                                    '<code>' . $new_email[ 'newemail' ] . '</code>',
                                    sprintf( '<a href="%s">%s</a>', esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user_object->ID . '_new_email' ) ), esc_html__( 'Cancel', 'b3-onboarding' ) )
                                );
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
        </div>

        <div class="b3_form-element">
            <label class="b3_form-label" for="first_name"><?php _e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_attr_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
            <input class="input regular-text" id="first_name" name="first_name" type="text" value="<?php esc_attr_e( $current_user_object->first_name ); ?>"<?php echo $required; ?> />
            <br><br>
            <label class="b3_form-label" for="last_name"><?php _e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_attr_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
            <input class="input regular-text" id="last_name" name="last_name" type="text" value="<?php esc_attr_e( $current_user_object->last_name ); ?>"<?php echo $required; ?> />
        </div>

        <?php
            $show_password_fields = apply_filters( 'show_password_fields', true, $current_user_object );
            if ( $show_password_fields ) {
        ?>
        <div class="b3_form-element b3_form-element--password">
            <div class="password-input user-pass1-wrap">
                <!-- Workaround : https://core.trac.wordpress.org/ticket/24364 -->
                <input class="hidden" value=" " />
                <button type="button" class="button button-secondary button--small wp-generate-pw hide-if-no-js"><?php esc_attr_e( 'Change password', 'b3-onboarding' ); ?></button>
                <div class="wp-pwd hide-if-js">
                    <label class="b3_form-label" for="pass1">
                        <?php esc_attr_e( 'New password', 'b3-onboarding' ); ?>
                    </label>
                    <span class="password-input-wrapper">
                        <input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="<?php esc_attr_e( wp_generate_password( 12 ) ); ?>" aria-describedby="pass-strength-result" />
                        <br/>
                        <span class="password-input-description">
                            <small><?php esc_html_e( 'You can also enter your own password', 'sexdates' ); ?></small>
                        </span>
                    </span>
                    <div style="display:none" id="pass-strength-result"></div>
                    <button type="button" class="button button-secondary button--small wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', 'b3-onboarding' ); ?>">
                        <span class="dashicons dashicons-hidden"></span>
                        <span class="text hide"><?php esc_html_e( 'Hide', 'b3-onboarding' ); ?></span>
                    </button>
                    <button type="button" class="button button-secondary button--small wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change', 'b3-onboarding' ); ?>">
                        <span class="dashicons dashicons-no-alt"></span>
                        <span class="text"><?php esc_html_e( 'Cancel', 'b3-onboarding' ); ?></span>
                    </button>
                </div>
            </div>
            <div class="user-pass2-wrap hide-if-js">
                <label for="pass2"><?php esc_attr_e( 'Repeat new password', 'b3-onboarding' ); ?></label>
                <input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
                <p class="description"><?php esc_html_e( 'Type your new password again.', 'b3-onboarding' ); ?></p>
            </div>
            <div class="pw-weak">
                <label class="b3_form-label" for="pw_weak">
                    <?php esc_html_e( 'Confirm password', 'b3-onboarding' ); ?>
                </label>
                <input type="checkbox" id="pw_weak" name="pw_weak" class="pw-checkbox" />
                <?php esc_html_e( 'Confirm use of weak password', 'b3-onboarding' ); ?>
            </div>
        </div>
        <?php } ?>

        <?php if ( get_option( 'b3_user_may_delete', false ) ) { ?>
            <?php // @TODO: create action or include ?>
            <div class="b3_form-element b3_form-element--delete">
                <strong>
                    <?php esc_html_e( 'Delete account', 'b3-onboarding' ); ?>
                </strong>
                <br>
                <label for="b3_delete_account">
                    <?php esc_attr_e( 'If you click this button, your entire user profile will be deleted.', 'b3-onboarding' ); ?>
                </label>
                <div>
                    <input type="submit" id="b3_delete_account" name="b3_delete_account" class="button button--small" value="<?php esc_attr_e( 'Delete account', 'b3-onboarding' ); ?>" onclick="return confirm( 'Are you sure you want to delete your account ?' )" />
                </div>
            </div>
        <?php } ?>

        <div class="b3_form-element">
            <input type="submit" class="button button--small button--submit" value="<?php esc_attr_e( 'Update profile', 'b3-onboarding' ); ?>" id="submit" />
        </div>

    </form>
</div>
