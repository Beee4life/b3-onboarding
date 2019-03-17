<?php
    $email_activation_subject = get_option( 'b3_email_activation_subject', false );
    $email_activation_message = get_option( 'b3_email_activation_message', false );
    $blog_name                = get_bloginfo( 'name' );
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
            <label for="b3__input--email-activation__subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--email-activation__subject" name="b3_email_activation_subject" placeholder="<?php echo esc_html__( 'Confirm your email address', 'b3-onboarding' ); ?>" type="text" value="<?php echo $email_activation_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-activation__message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br /><br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=email-activation' ) ); ?>
        </th>
        <td>
            <?php esc_html_e( "Available variables are:", "b3-onboarding" ); ?> %activation_url%, %blog_name%, %home_url%, %site_url%, %user_ip%, %user_login%
            <br /><br />
            <textarea id="b3__input--email-activation__message" name="b3_email_activation_message" placeholder="<?php echo esc_textarea( b3_get_email_activation_message( $blog_name, false ) ); ?>" rows="4"><?php echo $email_activation_message; ?></textarea>
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
