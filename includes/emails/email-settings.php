<?php
    $notification_sender_email = get_option( 'b3_notification_sender_email', false );
    $notification_sender_name  = get_option( 'b3_notification_sender_name', false );
    $add_logo_in_email         = get_option( 'b3_logo_in_email', false );
    $email_logo                = get_option( 'b3_email_logo', false );
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
    <?php if ( true == $add_logo_in_email ) { ?>
        <tr>
            <th>
                <label for="b3_email_logo" class="b3__label"><?php esc_html_e( 'Logo in emails', 'b3-onboarding' ); ?></label>
            </th>
            <td>
                <!--<input class="b3_input b3_input--text" id="b3_email_logo" name="b3_email_logo" placeholder="--><?php //echo esc_attr( b3_default_email_logo() ); ?><!--" size="60" type="text" value="--><?php //echo esc_attr( $email_logo ); ?><!--" />-->

                <div id="b3-new-media-settings">
                    <p><a href="#" id="email-logo" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>"><?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?></a></p>
                    <p><input type="text" name="b3_email_logo" id="b3_email_logo" value="<?php echo $email_logo; ?>" /></p>
                </div>
                <?php b3_get_close(); ?>


            </td>
        </tr>
        <tr class="b3__description-row">
            <th>&nbsp;</th>
            <td>
                <div class="b3__input--description">
                    <?php esc_html_e( 'This is the image url for the logo sent in all emails.', 'b3-onboarding' ); ?>
                </div>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
