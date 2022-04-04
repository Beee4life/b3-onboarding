<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    $field_name = is_multisite() ? 'log' : 'user_login';

    $label = esc_html__( 'Username or Email address', 'b3-onboarding' );
    if ( 1 == get_option( 'b3_register_email_only' ) ) {
        $label = esc_html__( 'Email address', 'b3-onboarding' );
    }
?>
<div class="b3_form-element">
    <label class="b3_form-label b3_form-label--userlogin" for="user_login"><?php echo $label; ?></label>
    <input type="text" name="<?php echo $field_name; ?>" id="user_login" class="input" value="" size="20" autocomplete="username">
</div>
