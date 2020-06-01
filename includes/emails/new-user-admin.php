<?php
    $disable_admin_notification = get_option( 'b3_disable_admin_notification_new_user', false );
    $new_user_email_addresses   = get_option( 'b3_new_user_notification_addresses', false );
    $new_user_email_subject     = b3_get_new_user_subject();
    $new_user_email_message     = b3_get_new_user_message();
?>
<table class="b3_table b3_table--emails">
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
            <input class="" id="b3__input--new-user-notification-addresses" name="b3_new_user_notification_addresses" placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" type="text" value="<?php echo esc_attr( $new_user_email_addresses ); ?>" />
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
            <input class="" id="b3__input--new-user-subject" name="b3_new_user_subject" placeholder="<?php echo esc_attr( b3_default_new_user_admin_subject() ); ?>" type="text" value="<?php echo esc_attr( $new_user_email_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br /><br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=new-user-admin' ) ); ?>
        </th>
        <td>
            <?php esc_html_e( "Available variables are:", "b3-onboarding" ); ?> %blog_name%, %home_url%, %site_url%, %user_ip%, %user_login%
            <br /><br />
            <textarea id="b3__input--new-user-message" name="b3_new_user_message" placeholder="<?php echo esc_attr( b3_default_new_user_admin_message() ); ?>" rows="6"><?php echo stripslashes( $new_user_email_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_admin_notification_new_user" type="checkbox" value="1" <?php if ( 1 == $disable_admin_notification ) { echo 'checked="checked" '; } ?>/> <?php esc_html_e( 'Disable admin notification on new user', 'b3-onboarding' ); ?>
            </label>
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
