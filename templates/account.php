<?php
    $current_user = get_userdata( get_current_user_id() );
    $required     = ( true == get_option( 'b3_first_last_required', false ) ) ? ' required="required"' : false;
    $user_delete  = get_option( 'b3_user_may_delete', false );
?>
<div id="b3-account" class="b3_page b3_page--account">
    <?php if ( isset( $attributes[ 'updated' ] ) ) { ?>
        <p class="b3_message">
            <?php
                echo esc_html__( 'Profile saved', 'b3-onboarding' );
                echo '<span class="error__close">' . esc_html__( 'close', 'sexdates' ) . '</span>';
            ?>
        </p>
    <?php } ?>

    <form id="" name="" action="<?php echo get_permalink( get_the_ID() ); ?>" method="post">
        <?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
        <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user->ID, 'show_admin_bar_front', true ); ?>" />
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="action" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
        <input type="hidden" name="nickname" id="nickname" value="<?php echo ( isset( $current_user->nickname ) ) ? esc_attr( $current_user->nickname ) : esc_attr( $current_user->user_login ); ?>" class="regular-text" />

        <h3>
            <?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
        </h3>

        <table class="b3_table b3_table--account">
            <tr>
                <td>
                    <label for="email"><?php esc_html_e( 'Email address', 'b3-onboarding' ); ?>
                        <span class="description"><?php esc_html_e( '(required)', 'b3-onboarding' ); ?></span>
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
            <?php esc_html_e( 'Name', 'b3-onboarding' ); ?>
        </h3>
        <table class="b3_table b3_table--account">
            <tr>
                <td>
                    <label for="first_name"><?php _e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_html_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
                </td>
                <td>
                    <input class="input regular-text" id="first_name" name="first_name" type="text" value="<?php echo esc_attr( $current_user->first_name ); ?>"<?php echo $required; ?> />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="last_name"><?php _e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_html_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
                </td>
                <td>
                    <input class="input regular-text" id="last_name" name="last_name" type="text" value="<?php echo esc_attr( $current_user->last_name ); ?>"<?php echo $required; ?> />
                </td>
            </tr>
        </table>

        <?php
            // @TODO: check why this IF
            $show_password_fields = apply_filters( 'show_password_fields', true, $current_user );
            if ( $show_password_fields ) :
        ?>
        <h3>
            <?php esc_html_e( 'Password', 'b3-onboarding' ); ?>
        </h3>
        <table class="b3_table b3_table--account">
            <tr id="password" class="user-pass1-wrap">
                <td>
                    <label for="pass1"><?php esc_html_e( 'New password', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <!-- Workaround : https://core.trac.wordpress.org/ticket/24364 -->
                    <input class="hidden" value=" " />
                    <button type="button" class="button button-secondary wp-generate-pw hide-if-no-js"><?php _e( 'Generate Password', 'b3-onboarding' ); ?></button>
                    <div class="wp-pwd hide-if-js">
                        <span class="password-input-wrapper">
                            <input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="<?php echo esc_attr( wp_generate_password( 12 ) ); ?>" aria-describedby="pass-strength-result" />
                            <br/>
                            <small><?php _e( 'You can also enter your own password', 'sexdates' ); ?></small>
                        </span>
                        <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                        <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', 'b3-onboarding' ); ?>">
                            <span class="dashicons dashicons-hidden"></span>
                            <span class="text"><?php _e( 'Hide', 'b3-onboarding' ); ?></span>
                        </button>
                        <button type="button" class="button button-secondary wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change', 'b3-onboarding' ); ?>">
                            <span class="text"><?php _e( 'Cancel', 'b3-onboarding' ); ?></span>
                        </button>
                    </div>
                </td>
            </tr>
            <tr class="user-pass2-wrap hide-if-js">
                <td>
                    <label for="pass2"><?php esc_html_e( 'Repeat new password', 'b3-onboarding' ); ?></label>
                </td>
                <td>
                    <input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
                    <p class="description"><?php esc_html_e( 'Type your new password again.', 'b3-onboarding' ); ?></p>
                </td>
            </tr>
            <tr class="pw-weak">
                <td><?php esc_html_e( 'Confirm password', 'b3-onboarding' ); ?></td>
                <td>
                    <label>
                        <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                        <?php esc_html_e( 'Confirm use of weak password', 'b3-onboarding' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php endif; ?>

        <?php if ( $user_delete ) { ?>
            <h3>
                <?php esc_html_e( 'Delete account', 'b3-onboarding' ); ?>
            </h3>
            <table class="b3_table b3_table--account">
                <tr>
                    <td colspan="2">
                        <label>
                            <input type="checkbox" name="b3_delete_account" value="1" />
                            <?php esc_html_e( 'If you select this option, your entire user profile will be deleted.', 'b3-onboarding' ); ?>
                        </label>
                    </td>
                </tr>
            </table>
        <?php } ?>

        <div>
            <input type="hidden" name="action" value="profile" />
            <input type="hidden" name="instance" value="1" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
            <input type="submit" class="button button--small" value="<?php esc_attr_e( 'Update profile', 'b3-onboarding' ); ?>" name="submit" id="submit" />
        </div>

    </form>
</div>
