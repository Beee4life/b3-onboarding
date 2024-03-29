<?php
    /*
     * Input fields for 'New user' mail (admin)
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $disable_admin_notification = get_option( 'b3_disable_admin_notification_new_user' );
    $new_user_email_addresses   = get_option( 'b3_new_user_notification_addresses' );
    $new_user_email_subject     = get_option( 'b3_new_user_subject' );
    $new_user_email_message     = get_option( 'b3_new_user_message' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <?php if ( 'email_activation' == get_option( 'b3_registration_type' ) ) { ?>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php _e( '<b>NOTE:</b> This email is sent after the user confirms his/her email address, not on initial registration.', "b3-onboarding" ); ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'Enter the email addresses (searated by comma) which should receive the notification email.', "b3-onboarding" ); ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If a field is left empty the default value will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-user-notification-addresses"><?php esc_html_e( 'Email addresses', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--new-user-notification-addresses" name="b3_new_user_notification_addresses" placeholder="<?php echo esc_attr( get_site_option( 'admin_email' ) ); ?>" type="text" value="<?php echo esc_attr( $new_user_email_addresses ); ?>" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-user-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--new-user-subject" name="b3_new_user_subject" placeholder="<?php echo esc_attr( b3_default_new_user_admin_subject() ); ?>" type="text" value="<?php echo esc_attr( $new_user_email_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user-message"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'new-user-admin' ); ?>
        </th>
        <td>
            <textarea id="b3__input--new-user-message" name="b3_new_user_message" placeholder="<?php echo esc_attr( b3_default_new_user_admin_message() ); ?>" rows="6"><?php echo stripslashes( $new_user_email_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_admin_notification_new_user" type="checkbox" value="1" <?php checked($disable_admin_notification); ?>/>
                <?php
                    if ( 1 == $disable_admin_notification ) {
                        esc_html_e( 'Enable admin notification on new user registration', 'b3-onboarding' );
                    } else {
                        esc_html_e( 'Disable admin notification on new user registration', 'b3-onboarding' );
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
