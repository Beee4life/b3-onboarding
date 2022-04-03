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

    do_action( 'b3_add_form_messages', $attributes );
?>

<div id="b3-account" class="b3_page b3_page--account">
    <?php if ( isset( $attributes[ 'updated' ] ) ) { ?>
        <?php echo sprintf( '<p class="b3_message">%s</p>', esc_html__( 'Profile saved', 'b3-onboarding' ) ); ?>
    <?php } ?>

    <form id="accountform" action="<?php echo b3_get_account_url(); ?>" method="post">
        <?php do_action( 'b3_do_before_account', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/hidden-fields', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/user-id', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/email', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/first-last', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/password', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'account/user-delete', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes, $current_user_object ); ?>
        <?php do_action( 'b3_do_after_account', $attributes, $current_user_object ); ?>
    </form>
</div>
