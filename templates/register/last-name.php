<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $first_last_required = get_option( 'b3_first_last_required' );
    $last_name           = ( isset( $_POST[ 'last_name' ] ) ) ? $_POST[ 'last_name' ] : false;
    $required            = ( true == $first_last_required ) ? ' required="required"' : false;
?>
<div class="b3_form-element b3_form-element--last-name">
    <label class="b3_form-label" for="b3_last_name"><?php esc_html_e( 'Last name', 'b3-onboarding' ); ?><?php if ( $required ) { ?> <strong>*</strong><?php } ?></label>
    <input type="text" name="last_name" id="b3_last_name" class="b3_form--input" value="<?php echo $last_name; ?>"<?php echo $required; ?>>
</div>
