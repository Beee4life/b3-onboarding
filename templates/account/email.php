<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $value = ( is_user_logged_in() ) ? esc_attr__( $current_user->user_email ) : false;
?>
<div class="b3_form-element b3_form-element--email">
    <label class="b3_form-label" for="email">
        <?php esc_attr_e( 'Email address', 'b3-onboarding' ); ?>
    </label>
    <input type="text" name="email" id="email" value="<?php echo $value; ?>" class="input regular-text" />

    <?php
        if ( isset( $attributes[ 'template' ] ) && 'account' == $attributes[ 'template' ] ) {
			$new_email = get_user_meta( $current_user->ID, 'x_new_email', true );
            if ( $new_email && $new_email[ 'newemail' ] != $current_user->user_email ) { ?>
                <div class="updated inline">
                    <p>
                        <?php
							printf(
							/* translators: %s: New email. */
								esc_html__( 'There is a pending change of your email, which is sent to %s.', 'b3-onboarding' ),
								'<code>' . esc_html( $new_email[ 'newemail' ] ) . '</code>'
							);
                            // @TODO: maybe change to front-end url ?
							printf(
								' <a href="%1$s">%2$s</a>',
								esc_url( wp_nonce_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ), 'dismiss-' . $current_user->ID . '_new_email' ) ),
								__( 'Cancel' )
							);
                        ?>
                    </p>
                </div>
            <?php }
        }
    ?>
</div>
