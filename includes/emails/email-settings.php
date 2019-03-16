<?php
    $add_br_html_email         = get_option( 'b3_add_br_html_email' );
    $mail_sending_method       = get_option( 'b3_mail_sending_method' );
    $html_emails               = get_option( 'b3_html_emails' );
    $notification_sender_email = get_option( 'b3_notification_sender_email' );
    $notification_sender_name  = get_option( 'b3_notification_sender_name' );
?>
<table class="b3_table b3_table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <th>
            <label for="b3_notification_sender_name" class="b3__label"><?php esc_html_e( 'Notification sender name', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="b3_input b3_input--text" id="b3_notification_sender_name" name="b3_notification_sender_name" placeholder="<?php echo get_bloginfo( 'name' ); ?>" size="50" type="text" value="<?php if ( $notification_sender_name ) { echo $notification_sender_name; } ?>" />
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
            <input class="b3_input b3_input--text" id="b3_notification_sender_email" name="b3_notification_sender_email" placeholder="<?php echo get_bloginfo( 'admin_email' ); ?>" size="60" type="email" value="<?php if ( $notification_sender_email ) { echo $notification_sender_email; } ?>" />
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
    
<!--    <tr class="">-->
<!--        <th>-->
<!--            <label for="b3_mail_sending_method" class="b3__label">--><?php //esc_html_e( 'Mail sending method', 'b3-onboarding' ); ?><!--</label>-->
<!--        </th>-->
<!--        <td>-->
<!--            <select id="b3_mail_sending_method" name="b3_mail_sending_method">-->
<!--                <option value="smtp"--><?php //if ( 'smtp' == $mail_sending_method ) { echo ' selected="selected"'; } ?><!--SMTP-->
<!--                <option value="phpmail"--><?php //if ( 'phpmail' == $mail_sending_method ) { echo ' selected="selected"'; } ?><!--PHP Mail-->
<!--                <option value="wpmail"--><?php //if ( 'wpmail' == $mail_sending_method ) { echo ' selected="selected"'; } ?><!--WP Mail-->
<!--            </select>-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th>-->
<!--            <label for="b3_notification_email_html" class="b3__label">--><?php //esc_html_e( 'Send HTML emails', 'b3-onboarding' ); ?><!--</label>-->
<!--        </th>-->
<!--        <td>-->
<!--            <label for="b3_html_emails_yes" class="screen-reader-text">--><?php //esc_html_e( 'Yes', 'b3-onboarding' ); ?><!--</label>-->
<!--            <input class="b3_input b3_input--radio" id="b3_html_emails_yes" name="b3_html_emails" type="radio" value="1"--><?php //if ( '1' == $html_emails ) { echo ' checked="checked"'; } ?><!-- /> YES-->
<!--            &nbsp;&nbsp;&nbsp;-->
<!--            <label for="b3_html_emails_no" class="screen-reader-text">--><?php //esc_html_e( 'No', 'b3-onboarding' ); ?><!--</label>-->
<!--            <input class="b3_input b3_input--radio" id="b3_html_emails_no" name="b3_html_emails" type="radio" value="0"--><?php //if ( '0' == $html_emails ) { echo ' checked="checked"'; } ?><!-- /> NO-->
<!--        </td>-->
<!--    </tr>-->
<!--    <tr>-->
<!--        <th>-->
<!--            <label for="b3_notification_email_br" class="b3__label">--><?php //esc_html_e( 'Add br tags to HTML emails ?', 'b3-onboarding' ); ?><!--</label>-->
<!--        </th>-->
<!--        <td>-->
<!--            <label for="b3_notification_email_br_yes" class="screen-reader-text">--><?php //esc_html_e( 'Yes', 'b3-onboarding' ); ?><!--</label>-->
<!--            <input class="b3_input b3_input--radio" id="b3_notification_email_br_yes" name="b3_add_br_html_email" type="radio" value="1"--><?php //if ( '1' == $add_br_html_email ) { echo ' checked="checked"'; } ?><!-- /> YES-->
<!--            &nbsp;&nbsp;&nbsp;-->
<!--            <label for="b3_notification_email_br_no" class="screen-reader-text">--><?php //esc_html_e( 'No', 'b3-onboarding' ); ?><!--</label>-->
<!--            <input class="b3_input b3_input--radio" id="b3_notification_email_br_no" name="b3_add_br_html_email" type="radio" value="0"--><?php //if ( '0' == $add_br_html_email ) { echo ' checked="checked"'; } ?><!-- /> NO-->
<!--        </td>-->
<!--    </tr>-->
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
