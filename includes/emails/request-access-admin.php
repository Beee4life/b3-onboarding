<?php
    $request_access_email_addresses     = get_option( 'b3_request_access_notification_addresses', false );
    $request_access_email_subject_admin = get_option( 'b3_request_access_subject_admin', false );
    $request_access_email_message_admin = get_option( 'b3_request_access_message_admin', false );
?>
<table class="b3_table b3_table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( "Enter the email addresses (searated by comma) which should receive the notification email. If no email is entered, it will be sent to the administrator's email address.", "b3-onboarding" ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-notification-addresses" class=""><?php esc_html_e( 'Email addresses', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--request-access-notification-addresses" name="b3_request_access_notification_addresses" placeholder="<?php echo get_option( 'admin_email' ); ?>" type="text" value="<?php echo $request_access_email_addresses; ?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-subject-admin" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--request-access-subject-admin" name="b3_request_access_subject_admin" placeholder="<?php echo b3_request_access_subject_admin(); ?>" type="text" value="<?php echo $request_access_email_subject_admin; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--request-access-message-admin" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br /><br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=request-access-admin' ) ); ?>
        </th>
        <td>
            <textarea id="b3__input--request-access-message-admin" name="b3_request_access_message_admin" placeholder="<?php echo esc_textarea( b3_request_access_message_admin() ); ?>" rows="4"><?php echo $request_access_email_message_admin; ?></textarea>
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