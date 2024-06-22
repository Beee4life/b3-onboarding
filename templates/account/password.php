<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $show_password_fields = apply_filters( 'show_password_fields', true, $current_user );
    if ( $show_password_fields ) {
        ?>
        <div class="b3_form-element b3_form-element--password">
            <div class="password-input user-pass1-wrap">
                <!-- Workaround : https://core.trac.wordpress.org/ticket/24364 -->
                <input class="hidden" value=" " />
                <div class="change-password-button">
                    <button type="button" class="button button-secondary button--password wp-generate-pw hide-if-no-js"><?php esc_attr_e( 'Change password', 'b3-onboarding' ); ?></button>
                </div>
                <div class="wp-pwd hide-if-js">
                    <div class="password-input">
                        <label class="b3_form-label" for="pass1">
                            <?php esc_attr_e( 'New password', 'b3-onboarding' ); ?>
                        </label>
                        <div class="input">
                            <span class="password-input-wrapper">
                                <input type="password" name="pass1" id="pass1" class="regular-text" value="" autocomplete="off" data-pw="<?php esc_attr_e( wp_generate_password( 12 ) ); ?>" aria-describedby="pass-strength-result" />
                                <br/>
                                <span class="password-input-description">
                                    <small><?php esc_html_e( 'You can also enter your own password', 'sexdates' ); ?></small>
                                </span>
                            </span>
                        </div>
                    </div>
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

