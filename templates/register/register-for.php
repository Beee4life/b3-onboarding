<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--signup-for">
    <label class="b3_form-label" for="">
        <?php esc_html_e( 'Register for', 'b3-onboarding' ); ?>
    </label>

    <div>
        <input id="signupblog" type="radio" name="signup_for" value="blog" checked="checked">
        <label class="checkbox" for="signupblog">
            <?php echo esc_html( apply_filters( 'b3_signup_for_site', esc_html__( 'A site', 'b3-onboarding' ) ) ); ?>
        </label>
        <input id="signupuser" type="radio" name="signup_for" value="user">
        <label class="checkbox" for="signupuser">
            <?php echo esc_html( apply_filters( 'b3_signup_for_user', esc_html__( 'Just a user', 'b3-onboarding' ) ) ); ?>
        </label>
    </div>
</div>
