<div class="b3_form-element b3_form-element--email">
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
