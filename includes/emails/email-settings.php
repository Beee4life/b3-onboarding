<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <th>
            <label for="b3_notification_sender_name" class="b3__label"><?php esc_html_e( 'Notification sender name', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="b3_input b3_input--text" id="b3_notification_sender_name" name="" placeholder="<?php echo get_bloginfo( 'admin_email' ); ?>" size="50" type="text" value="" />
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3_notification_sender_email" class="b3__label"><?php esc_html_e( 'Notification sender email', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="b3_input b3_input--text" id="b3_notification_sender_email" name="" placeholder="<?php echo get_bloginfo( 'name' ); ?>" size="60" type="email" value="" />
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
        <th>
            <label for="b3_notification_email_settings" class="b3__label"><?php esc_html_e( 'Mail sending method', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <select id="b3_notification_email_settings" name="b3_notification_email_settings">
                <option value="b3_smtp"><?php esc_html_e( 'SMTP', 'b3-onboarding' ); ?>
                <option value="b3_phpmail"><?php esc_html_e( 'PHP Mail', 'b3-onboarding' ); ?>
                <option value="b3_wpmail"><?php esc_html_e( 'WP Mail', 'b3-onboarding' ); ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3_notification_email_html" class="b3__label"><?php esc_html_e( 'Send HTML emails', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <label for="b3_notification_email_html_yes" class="screen-reader-text"><?php esc_html_e( 'Yes', 'b3-onboarding' ); ?></label>
            <input class="b3_input b3_input--radio" id="b3_notification_email_html_yes" name="" type="radio" value="1" /> YES
            &nbsp;&nbsp;&nbsp;
            <label for="b3_notification_email_html_no" class="screen-reader-text"><?php esc_html_e( 'No', 'b3-onboarding' ); ?></label>
            <input class="b3_input b3_input--radio" id="b3_notification_email_html_no" name="" type="radio" value="1" /> NO
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3_notification_email_br" class="b3__label"><?php esc_html_e( 'Add br tags to HTML emails ?', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <label for="b3_notification_email_br_yes" class="screen-reader-text"><?php esc_html_e( 'Yes', 'b3-onboarding' ); ?></label>
            <input class="b3_input b3_input--radio" id="b3_notification_email_br_yes" name="" type="radio" value="1" /> YES
            &nbsp;&nbsp;&nbsp;
            <label for="b3_notification_email_br_no" class="screen-reader-text"><?php esc_html_e( 'No', 'b3-onboarding' ); ?></label>
            <input class="b3_input b3_input--radio" id="b3_notification_email_br_no" name="" type="radio" value="1" /> NO
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
