<?php
    /*
     * Input fields for 'account rejected' email
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $disable_admin_notification = get_option( 'b3_disable_delete_user_email' );
    $reject_user_email_subject  = get_option( 'b3_account_rejected_subject' );
    $reject_user_email_message  = get_option( 'b3_account_rejected_message' );
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
            <label for="b3__input--account-rejected__subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--account-rejected__subject" name="b3_account_rejected_subject" placeholder="<?php echo esc_attr( b3_default_account_rejected_subject() ); ?>" type="text" value="<?php echo esc_attr( $reject_user_email_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--account-rejected__message"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'account-rejected' ); ?>
        </th>
        <td>
            <textarea id="b3__input--account-rejected__message" name="b3_account_rejected_message" placeholder="<?php echo esc_attr( b3_default_account_rejected_message() ); ?>" rows="6"><?php echo stripslashes( $reject_user_email_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_delete_user_email" type="checkbox" value="1" <?php if ( 1 == $disable_admin_notification ) { echo 'checked="checked" '; } ?>/> <?php esc_html_e( "Don't send email to user when deleting or rejecting", 'b3-onboarding' ); ?>
            </label>
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
