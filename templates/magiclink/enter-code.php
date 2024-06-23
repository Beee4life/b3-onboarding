<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $label = esc_html__( 'Enter code', 'b3-onboarding' );
?>
<div class="b3_form-element">
    <label class="b3_form-label b3_form-label--entercode" for="enter_code"><?php echo $label; ?></label>
    <input type="text" name="b3_one_time_password" id="enter_code" class="input" value="" size="20" autocomplete="off">
</div>
