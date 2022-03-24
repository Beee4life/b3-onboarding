<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<?php $required = ( true == get_option( 'b3_first_last_required' ) ) ? ' required="required"' : false; ?>
<div class="b3_form-element b3_form-element--first-name">
    <label class="b3_form-label" for="first_name"><?php _e( 'First name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_attr_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
    <input class="input regular-text" id="first_name" name="first_name" type="text" value="<?php esc_attr_e( $current_user_object->first_name ); ?>"<?php echo $required; ?> />
</div>

<div class="b3_form-element b3_form-element--last-name">
    <label class="b3_form-label" for="last_name"><?php _e( 'Last name', 'b3-onboarding' ); ?> <?php if ( $required ) { ?><span class="description"><?php esc_attr_e( '(required)', 'b3-onboarding' ); ?></span><?php } ?></label>
    <input class="input regular-text" id="last_name" name="last_name" type="text" value="<?php esc_attr_e( $current_user_object->last_name ); ?>"<?php echo $required; ?> />
</div>
