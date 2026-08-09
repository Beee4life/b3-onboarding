<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $button_modifier = isset( $attributes[ 'button_modifier' ] ) ? $attributes[ 'button_modifier' ] : false;
    $button_value    = isset( $attributes[ 'button_value' ] ) ? $attributes[ 'button_value' ] : esc_attr__( 'Save', 'b3-onboarding' );

    if ( in_array( $attributes[ 'template' ], [ 'magic-password', 'lostpassword' ] ) ) {
        $button_modifier2 = isset( $attributes[ 'button_modifier2' ] ) ? $attributes[ 'button_modifier2' ] : false;
        $button_value2    = isset( $attributes[ 'button_value2' ] ) ? $attributes[ 'button_value2' ] : '';
    }
?>
<div class="b3_form-element b3_form-element--button">
    <?php
        b3_get_submit_button( $button_value, $button_modifier, $attributes );

        if ( isset( $button_modifier2 ) && isset( $button_value2 ) ) {
            b3_get_submit_button( $button_value2, $button_modifier2, $attributes, 'button2' );
        }
    ?>
</div>
