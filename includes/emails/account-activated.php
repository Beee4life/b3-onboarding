<?php
    $account_activated_email_subject = get_option( 'b3_account_activated_subject', false );
    $account_activated_email_message = get_option( 'b3_account_activated_message', false );
    $blog_name                      = get_bloginfo( 'name' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--account-activated__subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--account-activated__subject" name="b3_account_activated_subject" placeholder="<?php echo b3_get_account_activated_subject(); ?>" type="text" value="<?php echo $account_activated_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--account-activated__message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br /><br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=account-activated' ) ); ?>
        </th>
        <td>
            <textarea id="b3__input--account-activated__message" name="b3_account_activated_message" placeholder="<?php echo esc_textarea( b3_get_account_activated_message() ); ?>" rows="4"><?php echo $account_activated_email_message; ?></textarea>
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
