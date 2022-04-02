<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $value = ( is_user_logged_in() ) ? esc_attr__( $current_user_object->user_email ) : false;
?>
<div class="b3_form-element b3_form-element--email">
    <?php echo sprintf( '<label class="b3_form-label" for="email">%s</label>', esc_attr__( 'Email address', 'b3-onboarding' ) ); ?>
    <input type="text" name="email" id="email" value="<?php echo $value; ?>" class="input regular-text" />

    <?php
        if ( isset( $attributes[ 'template' ] ) && 'account' == $attributes[ 'template' ] ) {
            $new_email = get_option( $current_user_object->ID . '_new_email' );
            if ( $new_email && $new_email[ 'newemail' ] != $current_user_object->user_email ) { ?>
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
            <?php }
        }
    ?>
</div>
