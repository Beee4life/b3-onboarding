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

    <form id="accountform" action="<?php echo get_the_permalink( get_the_ID() ); ?>" method="post">
        <?php do_action( 'b3_before_account', $current_user_object, $attributes ); ?>
        <?php do_action( 'b3_account_element', 'account/hidden-fields', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/user-id', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/email', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/first-last', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/password', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/user-delete', $current_user_object ); ?>
        <?php do_action( 'b3_account_element', 'account/save', $current_user_object ); ?>
        <?php do_action( 'b3_after_account', $current_user_object, $attributes ); ?>
    </form>
</div>
