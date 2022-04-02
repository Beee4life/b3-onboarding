<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $first_last_required = get_option( 'b3_first_last_required' );
    $first_name          = ( isset( $_POST[ 'first_name' ] ) ) ? $_POST[ 'first_name' ] : false;
    $required            = ( true == $first_last_required ) ? ' required="required"' : false;
?>
<div class="b3_form-element b3_form-element--register">
    <label class="b3_form-label" for="b3_first_name"><?php esc_html_e( 'First name', 'b3-onboarding' ); ?><?php if ( $required ) { ?> <strong>*</strong><?php } ?></label>
    <input type="text" name="first_name" id="b3_first_name" class="b3_form--input" value="<?php echo $first_name; ?>"<?php echo $required; ?>>
</div>
