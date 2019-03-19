<?php
    $forgot_password_subject = get_option( 'b3_forgot_password_subject', false );
    $forgot_password_message = get_option( 'b3_forgot_password_message', false );
?>
<table class="b3_table b3_table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--forgot-password-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--forgot-password-subject" name="b3_forgot_password_subject" type="text" placeholder="<?php echo sprintf( esc_html__( 'Password reset for %s', 'b3-onboarding' ), get_option( 'blogname' ) ); ?>" value="<?php if ( $forgot_password_subject ) { echo $forgot_password_subject; } ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--forgot-password-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br /><br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=forgotpass' ) ); ?>
        </th>
        <td>
            <?php esc_html_e( "Be sure to include %reset_url% in your email, otherwise the user can't reset his/her password.", "b3-onboarding" ); ?>
            <br /><br />
            <?php esc_html_e( "Other available variables are:", "b3-onboarding" ); ?> %salutation%, %blog_name%, %email_message%, %email_styling%, %home_url%, %logo%, %site_url%, %user_ip%
            <br /><br />
            <textarea id="b3__input--forgot-password-message" name="b3_forgot_password_message" placeholder="<?php echo esc_textarea( b3_default_forgot_password_message( 'key', '%user_login%' ) ); ?>" rows="4"><?php if ( $forgot_password_message ) { echo $forgot_password_message; } ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
