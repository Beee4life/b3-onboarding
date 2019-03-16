<?php
    $account_approved_email_subject = get_option( 'b3_account_approved_subject', false );
    $account_approved_email_message = get_option( 'b3_account_approved_message', false );
    $blog_name                      = get_bloginfo( 'name' );
?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--account-approved__subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--account-approved__subject" name="b3_account_approved_subject" placeholder="<?php echo esc_html__( 'Account approved', 'b3-onboarding' ); ?>" type="text" value="<?php echo $account_approved_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--account-approved__message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--account-approved__message" name="b3_account_approved_message" placeholder="<?php echo sprintf( esc_html__( 'Welcome to %s. Your account has been approved and you can now set your password on %s.', 'b3-onboarding' ), $blog_name, esc_url( b3_get_forgotpass_url() ) ); ?>" rows="4"><?php echo $account_approved_email_message; ?></textarea>
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
