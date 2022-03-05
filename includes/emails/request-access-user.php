<?php
    /*
     * Input fields for 'Request access' mail (user)
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $request_access_email_subject_user   = get_option( 'b3_request_access_subject_user' );;
    $request_access_email_message_user   = get_option( 'b3_request_access_message_user' );;
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--request-access-subject-user"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--request-access-subject-user" name="b3_request_access_subject_user" placeholder="<?php echo esc_attr( b3_default_request_access_subject_user( ) ); ?>" type="text" value="<?php echo esc_attr( $request_access_email_subject_user ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--request-access-message-user"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'request-access-user' ); ?>
        </th>
        <td>
            <textarea id="b3__input--request-access-message-user" name="b3_request_access_message_user" placeholder="<?php echo esc_attr( b3_default_request_access_message_user() ); ?>" rows="6"><?php echo stripslashes( $request_access_email_message_user ); ?></textarea>
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
