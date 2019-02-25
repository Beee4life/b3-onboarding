<?php $email_template = get_option( 'b3_email_template', false ); ?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-template" class=""><?php esc_html_e( 'Email template', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--email-template" name="b3_email_template" rows="4"><?php if ( $email_template ) { echo $email_template; } ?></textarea>
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
