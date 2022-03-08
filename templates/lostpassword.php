<?php
    /**
     * Ouptuts fields for lost password form
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $localhost_email = is_localhost() ? apply_filters( 'b3_localhost_email', false ) : false;

    do_action( 'b3_add_form_messages', $attributes );
?>
<div class="b3_page b3_page--lostpass">
    <?php echo ( isset( $attributes[ 'title' ] ) ) ? sprintf( '<h3>%s</h3>', $attributes[ 'title' ] ) : false; ?>

    <form name="lostpasswordform" id="lostpasswordform" class="b3_form b3_form--register" action="<?php echo b3_get_current_url(); ?>" method="post">
        <?php // Don't delete the hidden fields ?>
        <input type="hidden" name="b3_form" value="lostpass" />
        <input type="hidden" name="b3_lost_pass" value="<?php echo wp_create_nonce( 'b3-lost-pass' ); ?>" />
        <input type="hidden" name="b3_site_id" value="<?php echo get_current_blog_id(); ?>" />

        <div class="b3_form-element">
            <label class="b3_form-label b3_form-label--email" for="b3_user_email"><?php esc_attr_e( 'Email address', 'b3-onboarding' ); ?></label>
            <input type="text" name="user_login" id="b3_user_email" value="<?php echo esc_attr( $localhost_email ); ?>" required>
        </div>

        <div class="b3_form-element b3_form-element--submit">
            <?php b3_get_submit_button( esc_attr__( 'Reset Password', 'b3-onboarding' ), 'lostpass' ); ?>
        </div>

        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>
    </form>

</div>
