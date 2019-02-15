<?php
    $request_access_email_subject = get_option( 'b3_request_access_subject', false );
    $request_access_email_message = get_option( 'b3_request_access_message', false );
?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--request-access-subject" name="b3_request_access_subject" placeholder="" type="text" value="<?php echo $request_access_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--request-access-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--request-access-message" name="b3_request_access_message" rows="4"><?php echo $request_access_email_message; ?></textarea>
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
