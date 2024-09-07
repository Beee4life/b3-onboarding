<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    if ( ! get_option( 'b3_use_magic_link' ) ) {
?>
<div class="b3_form-element">
    <label class="b3_form-label" for="user_pass"><?php esc_html_e( 'Password', 'b3-onboarding' ); ?></label>
    <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" autocomplete="current-password">
</div>
<?php } ?>
