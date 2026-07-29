<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $button_modifier = isset( $attributes[ 'button_modifier' ] ) ? $attributes[ 'button_modifier' ] : false;
    $button_value1   = isset( $attributes[ 'button_value' ] ) ? $attributes[ 'button_value' ] : esc_attr__( 'Save', 'b3-onboarding' );
?>
<div class="b3_form-element b3_form-element--button">
    <?php b3_get_submit_button( $button_value1, $button_modifier, $attributes ); ?>
    <div class="button button-primary button--submit">Use password</div>
</div>
