<?php $current_user = get_userdata( get_current_user_id() ); ?>
<div class="" id="">
    <?php do_action( 'b3_before_user_profile' ); ?>
    <form id="" name="" action="" method="post">

        <input name="b3_profile_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-profile-nonce' ); ?>" />
        <?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
        <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user->ID, 'show_admin_bar_front', true ); ?>" />

        <h2>
            <?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
        </h2>
        <table class="">
            <tr class="">
                <th>
                    <label for="email"><?php esc_html_e( 'Email address', 'b3-onboarding' ); ?>
                        <span class="description"><?php esc_html_e( '(required)', 'b3-onboarding' ); ?></span>
                    </label>
                </th>
                <td>
                    <input type="text" name="email" id="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="input regular-text" />
                    <?php
                        $new_email = get_option( $current_user->ID . '_new_email' );
                        if ( $new_email && $new_email['newemail'] != $current_user->user_email ) : ?>
                            <div class="updated inline">
                                <p>
                                    <?php
                                        printf(
                                            esc_html__( 'There is a pending change of your e-mail to %1$s. <a href="%2$s">Cancel</a>', 'b3-onboarding' ),
                                            '<code>' . $new_email['newemail'] . '</code>',
                                            esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) )
                                        );
                                    ?>
                                </p>
                            </div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php
            $show_password_fields = apply_filters( 'show_password_fields', false, $current_user );
            if ( $show_password_fields ) :
        ?>
        <h2>
            <?php esc_html_e( 'Password', 'b3-onboarding' ); ?>
        </h2>
        <table class="">
            <tr id="password" class="user-pass1-wrap">
                <th><label for="pass1"><?php esc_html_e( 'New password', 'b3-onboarding' ); ?></label></th>
                <td>
                    <!-- #24364 workaround -->
<!--                    <input class="hidden" value=" " />-->
                    <button type="button" class="button button--small wp-generate-pw hide-if-no-js"><?php esc_html_e( 'Generate password', 'b3-onboarding' ); ?></button>
                    <div class="wp-pwd hide-if-js">
                        <span class="password-input-wrapper">
                            <input type="password" name="pass1" id="pass1" class="input regular-text" value="" autocomplete="off" data-pw="<?php esc_attr__( wp_generate_password( 24 ) ); ?>" aria-describedby="pass-strength-result" />
                        </span>
                        <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                        <button type="button" class="button button--small wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr__( 'Hide password', 'b3-onboarding' ); ?>">
                            <span class="dashicons dashicons-hidden"></span>
                            <span class="text"><?php esc_html_e( 'Hide', 'b3-onboarding' ); ?></span>
                        </button>
                        <button type="button" class="button button--small wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr__( 'Cancel password change', 'b3-onboarding' ); ?>">
                            <span class="text"><?php esc_html_e( 'Cancel', 'b3-onboarding' ); ?></span>
                        </button>
                    </div>
                </td>
            </tr>
            <tr class="user-pass2-wrap hide-if-js">
                <th scope="row"><label for="pass2"><?php esc_html_e( 'Repeat new password', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
                    <p class="description"><?php esc_html_e( 'Type your new password again.', 'b3-onboarding' ); ?></p>
                </td>
            </tr>
            <tr class="pw-weak">
                <th><?php esc_html_e( 'Confirm password', 'b3-onboarding' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                        <?php esc_html_e( 'Confirm use of weak password', 'b3-onboarding' ); ?>
                    </label>
                </td>
            </tr>
        </table>
        <?php endif; ?>

        <div>
            <input type="hidden" name="action" value="profile" />
            <input type="hidden" name="instance" value="1" />
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
            <input type="submit" class="button button--small" value="<?php esc_attr_e( 'Update profile', 'b3-onboarding' ); ?>" name="submit" id="submit" />
        </div>
        
        <!--        <h2>--><?php //esc_html_e( 'Delete account', 'b3-onboarding' ); ?><!--</h2>-->
        <!--        <p>-->
        <!--            --><?php //$permalink = get_permalink( apply_filters( 'wpml_object_id', 220, 'page', true ) ); ?>
        <!--            --><?php //echo sprintf( esc_html_e( 'If you want to remove your account, <a href="%s">click here</a>.', 'b3-onboarding' ), $permalink ); ?>
        <!--        </p>-->

    </form>
    
    <?php do_action( 'b3_after_user_profile' ); ?>
</div>
