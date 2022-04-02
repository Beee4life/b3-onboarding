<?php
    /*
     * Input fields for 'Request access' mail (admin)
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $disable_admin_notification         = get_option( 'b3_disable_admin_notification_new_user' );
    $request_access_email_addresses     = get_option( 'b3_request_access_notification_addresses' );
    $request_access_email_subject_admin = get_option( 'b3_request_access_subject_admin' );
    $request_access_email_message_admin = get_option( 'b3_request_access_message_admin' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'Enter the email addresses (searated by comma) which should receive the notification email.', "b3-onboarding" ); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If a field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-notification-addresses"><?php esc_html_e( 'Email addresses', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--request-access-notification-addresses" name="b3_request_access_notification_addresses" placeholder="<?php echo get_site_option( 'admin_email' ); ?>" type="text" value="<?php echo esc_attr( $request_access_email_addresses ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-subject-admin"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--request-access-subject-admin" name="b3_request_access_subject_admin" placeholder="<?php echo esc_attr( b3_default_request_access_subject_admin( ) ); ?>" type="text" value="<?php echo esc_attr( $request_access_email_subject_admin ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--request-access-message-admin"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'request-access-admin' ); ?>
        </th>
        <td>
            <textarea id="b3__input--request-access-message-admin" name="b3_request_access_message_admin" placeholder="<?php echo esc_attr( b3_default_request_access_message_admin() ); ?>" rows="6"><?php echo stripslashes( $request_access_email_message_admin ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_admin_notification_new_user" type="checkbox" value="1" <?php checked($disable_admin_notification); ?>/>
                <?php
                    if ( 1 == $disable_admin_notification ) {
                        esc_html_e( 'Uncheck this box to enable admin notification on new user registration', 'b3-onboarding' );
                    } else {
                        esc_html_e( 'Check this box to disable admin notification on new user registration', 'b3-onboarding' );
                    }
                ?>
            </label>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
