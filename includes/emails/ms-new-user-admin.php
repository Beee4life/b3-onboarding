<?php
    /*
     * Input fields for 'New user' email (admin)
     *
     * @since 2.6.0
     */
    $new_wpmu_user_subject_admin = get_option( 'xb3_email_subject', false );
    $new_wpmu_user_message_admin = get_option( 'xb3_email_activation_message', false );
    $placeholder_subject         = sprintf( __( 'New user registration: %s' ), 'username' );
    $placeholder_message         = esc_attr( b3_get_new_wpmu_user_message_admin() );
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
            <label for="b3__input--new-user-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--new-user-subject" name="b3_input_new_wpmu_user_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $new_wpmu_user_subject_admin ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user-message" class=""><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--new-user-message" name="b3_input_new_wpmu_user_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $new_wpmu_user_message_admin ); ?></textarea>
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
