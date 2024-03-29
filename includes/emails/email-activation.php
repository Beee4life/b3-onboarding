<?php
    /*
     * Input fields for 'Email activated' email (user)
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $email_activation_subject = get_option( 'b3_email_activation_subject' );
    $email_activation_message = get_option( 'b3_email_activation_message' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If a field is left empty the default value will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--email-activation__subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--email-activation__subject" name="b3_email_activation_subject" placeholder="<?php echo esc_attr( b3_default_email_activation_subject() ); ?>" type="text" value="<?php echo esc_attr( $email_activation_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-activation__message"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'email-activation' ); ?>
        </th>
        <td>
            <textarea id="b3__input--email-activation__message" name="b3_email_activation_message" placeholder="<?php echo esc_attr( b3_default_email_activation_message() ); ?>" rows="6"><?php echo stripslashes( $email_activation_message ); ?></textarea>
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
