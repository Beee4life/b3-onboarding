<?php
    /*
     * Input fields for email settings
     *
     * @since 1.0.0
     */
    $notification_sender_email = get_site_option( 'b3_notification_sender_email' );
    $notification_sender_name  = get_site_option( 'b3_notification_sender_name' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <th>
            <label for="b3_notification_sender_name" class="b3__label"><?php esc_html_e( 'Notification sender name', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="b3_input b3_input--text" id="b3_notification_sender_name" name="b3_notification_sender_name" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" size="50" type="text" value="<?php echo esc_attr( $notification_sender_name ); ?>" />
        </td>
    </tr>
    <tr class="b3__description-row">
        <th>&nbsp;</th>
        <td>
            <div class="b3__input--description">
                <?php esc_html_e( 'This is the name which is used to send emails from.', 'b3-onboarding' ); ?>
            </div>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3_notification_sender_email" class="b3__label"><?php esc_html_e( 'Notification sender email', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="b3_input b3_input--text" id="b3_notification_sender_email" name="b3_notification_sender_email" placeholder="<?php echo esc_attr( get_bloginfo( 'admin_email' ) ); ?>" size="60" type="email" value="<?php echo esc_attr( $notification_sender_email ); ?>" />
        </td>
    </tr>
    <tr class="b3__description-row">
        <th>&nbsp;</th>
        <td>
            <div class="b3__input--description">
                <?php esc_html_e( 'This is the email address from which all emails will be sent.', 'b3-onboarding' ); ?>
            </div>
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
