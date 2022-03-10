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
    <form id="accountform" action="<?php echo get_the_permalink( get_the_ID() ); ?>" method="post">
        <?php // Leave the hidden fields ! ?>
        <?php wp_nonce_field( 'update-user_' . $current_user_object->ID ); ?>
        <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="<?php echo get_user_meta( $current_user_object->ID, 'show_admin_bar_front', true ); ?>" />
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="instance" value="1" />
        <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user_object->ID; ?>" />
        <input type="hidden" name="action" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo $current_user_object->ID; ?>" />
        <input type="hidden" name="nickname" id="nickname" value="<?php echo ( isset( $current_user_object->nickname ) ) ? esc_attr( $current_user_object->nickname ) : esc_attr( $current_user_object->user_login ); ?>" class="regular-text" />

        <?php if ( isset( $attributes[ 'updated' ] ) ) { ?>
            <?php echo sprintf( '<p class="b3_message">%s</p>', esc_html__( 'Profile saved', 'b3-onboarding' ) ); ?>
        <?php } ?>

        <?php do_action( 'b3_before_account', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_user_id', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_email', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_first_last', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_password', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_user_delete', $current_user_object, $attributes ); ?>

        <?php do_action( 'b3_form_save' ); ?>

        <?php do_action( 'b3_after_account', $current_user_object, $attributes ); ?>

    </form>
</div>
