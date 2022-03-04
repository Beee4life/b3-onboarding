<?php
    /*
     * Input fields for 'account approved' email
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $account_approved_email_subject = get_option( 'b3_account_approved_subject' );
    $account_approved_email_message = get_option( 'b3_account_approved_message' );;
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
            <label for="b3__input--account-approved__subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--account-approved__subject" name="b3_account_approved_subject" placeholder="<?php echo esc_attr( b3_default_account_approved_subject() ); ?>" type="text" value="<?php echo esc_attr( $account_approved_email_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--account-approved__message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'account-approved' ); ?>
        </th>
        <td>
            <textarea id="b3__input--account-approved__message" name="b3_account_approved_message" placeholder="<?php echo esc_attr( b3_default_account_approved_message() ); ?>" rows="6"><?php echo stripslashes( $account_approved_email_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
