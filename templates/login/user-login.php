<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    $label = esc_attr__( 'Username or Email address', 'b3-onboarding' );
    if ( 1 == get_option( 'b3_register_email_only' ) ) {
        $label = esc_attr__( 'Email address', 'b3-onboarding' );
    }

?>
<div class="b3_form-element">
    <label class="b3_form-label b3_form-label--userlogin" for="user_login"><?php echo $label; ?></label>
    <input type="text" name="log" id="user_login" class="input" value="" size="20" autocomplete="username">
</div>
