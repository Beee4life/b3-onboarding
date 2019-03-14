<?php
    $new_user_email_addresses = get_option( 'b3_new_user_notification_addresses', false );
    $new_user_email_subject = get_option( 'b3_new_user_subject', false );
    $new_user_email_message = get_option( 'b3_new_user_message', false );
?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( "Enter the email addresses (searated by comma) which should receive the notification email. If no email is entered, it will be sent to the administrator's email address.", "b3-onboarding" ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-user-notification-addresses" class=""><?php esc_html_e( 'Email addresses', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--new-user-notification-addresses" name="b3_new_user_notification_addresses" placeholder="<?php echo get_option( 'admin_email' ); ?>" type="text" value="<?php echo $new_user_email_addresses; ?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-user-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--new-user-subject" name="b3_new_user_subject" placeholder="<?php echo sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), get_option( 'blogname' ) ); ?>" type="text" value="<?php echo $new_user_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <?php esc_html_e( "Available variables are:", "b3-onboarding" ); ?> %blog_name%, %home_url%, %site_url%, %user_ip%, %user_login%
            <br /><br />
            <textarea id="b3__input--new-user-message" name="b3_new_user_message" placeholder="<?php echo b3_default_new_user_admin_message(); ?>" rows="4"><?php echo $new_user_email_message; ?></textarea>
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
