<?php
    /**
     * Ouptuts fields for account page
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $current_user = get_userdata( get_current_user_id() );

    do_action( 'b3_add_form_messages', $attributes );
?>

<div id="b3-account" class="b3_page b3_page--account">
    <form id="accountform" action="<?php echo b3_get_account_url(); ?>" method="post">
        <?php do_action( 'b3_do_before_account', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/hidden-fields', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/user-id', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/email', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/first-last', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/password', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'general/button', $attributes, $current_user ); ?>
        <?php do_action( 'b3_render_form_element', 'account/user-delete', $attributes, $current_user ); ?>
        <?php do_action( 'b3_do_after_account', $attributes, $current_user ); ?>
    </form>
</div>
