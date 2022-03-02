<?php
    /*
     * Input fields for 'Welcome user' email
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $welcome_user_email_subject = get_option( 'b3_welcome_user_subject' );
    $welcome_user_email_message = get_option( 'b3_welcome_user_message' );
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
            <label for="b3__input--welcome-user" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--welcome-user" name="b3_welcome_user_subject" placeholder="<?php echo esc_attr( b3_default_welcome_user_subject() ); ?>" type="text" value="<?php echo esc_attr( $welcome_user_email_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br />
            <?php echo b3_get_preview_link( 'welcome-user' ); ?>
        </th>
        <td>
            <textarea id="b3__input--new-user" name="b3_welcome_user_message" placeholder="<?php echo esc_attr( b3_default_welcome_user_message() ); ?>" rows="6"><?php echo stripslashes( $welcome_user_email_message ); ?></textarea>
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
