<?php
    /*
     * Input fields for 'Confirm email for new user (no site)' (MS)
     *
     * @since 3.0
     */
    $new_wpmu_user_subject = get_option( 'b3_confirm_wpmu_user_subject' );
    $new_wpmu_user_message = get_option( 'b3_confirm_wpmu_user_message' );
    $placeholder_subject   = sprintf( esc_attr( b3_default_wpmu_activate_user_subject() ), get_site_option( 'site_name' ) );
    $placeholder_message   = esc_attr( b3_default_wpmu_activate_user_message() );
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
            <label for="b3__input--confirm-wpmu-user-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--confirm-wpmu-user-subject" name="b3_confirm_wpmu_user_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $new_wpmu_user_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--confirm-wpmu-user-message"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'mu-confirm-user-email' ); ?>
        </th>
        <td>
            <textarea id="b3__input--confirm-wpmu-user-message" name="b3_confirm_wpmu_user_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $new_wpmu_user_message ); ?></textarea>
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
