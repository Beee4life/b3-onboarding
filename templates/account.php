<?php
    /**
     * Ouptuts fields for account page
     *
     * @since 1.0.0
     */
    $current_user                 = get_userdata( get_current_user_id() );
    $registration_with_email_only = get_option( 'b3_register_email_only', false );
    $required                     = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false;
    $user_delete                  = get_option( 'b3_user_may_delete', false );
?>
<?php do_action( 'b3_add_form_messages', $attributes ); ?>

<div id="b3-account" class="b3_page b3_page--account">
    <form id="accountform" name="accountform" action="<?php echo get_the_permalink( get_the_ID() ); ?>" method="post">
        <?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
        <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user->ID, 'show_admin_bar_front', true ); ?>" />
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="action" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
        <input type="hidden" name="nickname" id="nickname" value="<?php echo ( isset( $current_user->nickname ) ) ? esc_attr( $current_user->nickname ) : esc_attr( $current_user->user_login ); ?>" class="regular-text" />

        <?php if ( isset( $attributes[ 'updated' ] ) ) { ?>
            <p class="b3_message">
                <?php echo esc_html__( 'Profile saved', 'b3-onboarding' ); ?>
                <span class="error__close"><?php echo esc_html__( 'close', 'b3-onboarding' ); ?></span>
            </p>
        <?php } ?>

        <?php if ( false == $registration_with_email_only ) { ?>
            <h3>
                <?php echo esc_html__( 'Username', 'b3-onboarding' ); ?>
            </h3>

            <table class="b3_table b3_table--account">
                <tr>
                    <td>
                        <label for="user_login"><?php echo esc_html__( 'Username', 'b3-onboarding' ); ?></label>
                    </td>
                    <td>
                        <input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $current_user->user_login ); ?>" disabled="disabled" />
                        <span class="description"><?php _e( "Can't be changed.", 'b3-onboarding' ); ?></span>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <h3>
                <?php echo esc_html__( 'User ID', 'b3-onboarding' ); ?>
            </h3>

            <table class="b3_table b3_table--account">
                <tr>
                    <td>
                        <label for="user_login"><?php echo esc_html__( 'User ID', 'b3-onboarding' ); ?></label>
                    </td>
                    <td>
                        <?php echo esc_attr( $current_user->user_login ); ?>
                    </td>
                </tr>
            </table>
        <?php } ?>

        <h3>
            <?php echo esc_html__( 'Email', 'b3-onboarding' ); ?>
        </h3>

        <table class="b3_table b3_table--account">
            <tr>
                <td>
                    <label for="email"><?php echo esc_html__( 'Email address', 'b3-onboarding' ); ?>
                        <span class="description"><?php echo esc_html__( '(required)', 'b3-onboarding' ); ?></span>
                    </label>
                </td>
                <td>
                    <input type="text" name="email" id="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="input regular-text" />
                    <?php
                        $new_email = get_option( $current_user->ID . '_new_email' );
                        if ( $new_email && $new_email[ 'newemail' ] != $current_user->user_email ) : ?>
                            <div class="updated inline">
                                <p>
                                    <?php
                                        printf(
                                            esc_html__( 'There is a pending change of your e-mail to %1$s. <a href="%2$s">Cancel</a>', 'b3-onboarding' ),
                                            '<code>' . $new_email[ 'newemail' ] . '</code>',
                                            esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) )
                                        );
                                    ?>
                                </p>
                            </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <h3>
            <?php echo esc_html__( 'Name', 'b3-onboarding' ); ?>
        </h3>
        <table class="b3_table b3_table--account">
            <tr>
                <td>
                    <label for="first_name"><?php _e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php echo esc_attr( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
                </td>
                <td>
                    <input class="input regular-text" id="first_name" name="first_name" type="text" value="<?php echo esc_attr( $current_user->first_name ); ?>"<?php echo $required; ?> />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="last_name"><?php _e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php echo esc_attr( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
                </td>
                <td>
                    <input class="input regular-text" id="last_name" name="last_name" type="text" value="<?php echo esc_attr( $current_user->last_name ); ?>"<?php echo $required; ?> />
                </td>
            </tr>
        </table>

        <?php
            $show_password_fields = apply_filters( 'show_password_fields', true, $current_user );
            if ( $show_password_fields ) :
        ?>
        <h3>
            <?php echo esc_html__( 'Password', 'b3-onboarding' ); ?>
        </h3>
        <table class="b3_table b3_table--account">
            <tr id="password" class="user-pass1-wrap">
                <td>
                    <label for="pass1"><?php echo esc_html__( 'New password', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <!-- Workaround : https://core.trac.wordpress.org/ticket/24364 -->
                    <input class="hidden" value=" " />
                    <button type="button" class="button button-secondary button--small wp-generate-pw hide-if-no-js"><?php _e( 'Generate Password', 'b3-onboarding' ); ?></button>
                    <div class="wp-pwd hide-if-js">
                        <span class="password-input-wrapper">
                            <input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="<?php echo esc_attr( wp_generate_password( 12 ) ); ?>" aria-describedby="pass-strength-result" />
                            <br/>
                            <small><?php _e( 'You can also enter your own password', 'sexdates' ); ?></small>
                        </span>
                        <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                        <button type="button" class="button button-secondary button--small wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', 'b3-onboarding' ); ?>">
                            <span class="dashicons dashicons-hidden"></span>
                            <span class="text"><?php _e( 'Hide', 'b3-onboarding' ); ?></span>
                        </button>
                        <button type="button" class="button button-secondary button--small wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change', 'b3-onboarding' ); ?>">
                            <span class="text"><?php _e( 'Cancel', 'b3-onboarding' ); ?></span>
                        </button>
                    </div>
                </td>
            </tr>
            <tr class="user-pass2-wrap hide-if-js">
                <td>
                    <label for="pass2"><?php echo esc_attr( 'Repeat new password', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
                    <p class="description"><?php echo esc_html__( 'Type your new password again.', 'b3-onboarding' ); ?></p>
                </td>
            </tr>
            <tr class="pw-weak">
                <td><?php echo esc_html__( 'Confirm password', 'b3-onboarding' ); ?></td>
                <td>
                    <label>
                        <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                        <?php echo esc_html__( 'Confirm use of weak password', 'b3-onboarding' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php endif; ?>

        <?php if ( $user_delete ) { ?>
            <h3>
                <?php echo esc_html__( 'Delete account', 'b3-onboarding' ); ?>
            </h3>
            <table class="b3_table b3_table--account">
                <tr>
                    <td colspan="2">
                        <label>
                            <?php echo esc_attr( 'If you click this button, your entire user profile will be deleted.', 'b3-onboarding' ); ?>
                            <input type="submit" name="b3_delete_account" class="button button--small" value="<?php echo esc_attr( 'Delete account', 'b3-onboarding' ); ?>" id="submit" onclick="return confirm( 'Are you sure you want to delete your account ?' )" />
                        </label>
                    </td>
                </tr>
            </table>
        <?php } ?>

        <div>
            <input type="hidden" name="action" value="profile" />
            <input type="hidden" name="instance" value="1" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
            <input type="submit" class="button button--small button--submit" value="<?php echo esc_attr( 'Update profile', 'b3-onboarding' ); ?>" id="submit" />
        </div>

    </form>
</div>
