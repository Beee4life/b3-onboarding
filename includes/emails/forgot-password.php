<?php
    $new_user_email_subject = get_option( 'b3_new_user_subject', false );
    $new_user_email_message = get_option( 'b3_new_user_message', false );
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
            <label for="b3__input--forgot-password-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--forgot-password-subject" name="b3_forgot_password_subject" type="text" value="" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--forgot-password-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--forgot-password-message" name="b3_forgot_password_message" rows="4"></textarea>
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
