<?php
    /*
     * Input fields for manually welcome new user email, when registration is closed
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // @TODO: check if user is manually added through site
    $new_user_subject    = get_option( 'b3_welcome_user_subject' );
    $new_user_message    = get_option( 'b3_welcome_user_message_manual' );
    $placeholder_subject = esc_attr( b3_default_welcome_user_subject() );

    if ( 'none' == get_option( 'b3_registration_type' ) ) {
        $placeholder_message = esc_attr( b3_default_manual_welcome_user_message() );
    } else {
        $placeholder_message = esc_attr( b3_default_manual_welcome_user_message() );
    }
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
            <label for="b3__input--welcome-user-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--welcome-user-subject" name="b3_welcome_user_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $new_user_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--welcome-user-message-manual"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'welcome-user-manual' ); ?>
        </th>
        <td>
            <textarea id="b3__input--welcome-user-message-manual" name="b3_welcome_user_message_manual" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $new_user_message ); ?></textarea>
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
