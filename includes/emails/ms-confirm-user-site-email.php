<?php
    /*
     * Input fields for 'Confirm email for new user + site' (MS)
     *
     * @since 3.0
     */
    $new_wpmu_user_subject = get_option( 'b3_activate_wpmu_user_site_subject' );
    $new_wpmu_user_message = get_option( 'b3_activate_wpmu_user_site_message' );
    $placeholder_subject   = b3_default_subject_new_wpmu_user_blog();
    $placeholder_subject   = strtr( $placeholder_subject, b3_replace_subject_vars() );
    $placeholder_message   = esc_attr( b3_default_message_new_wpmu_user_blog() );
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
            <label for="b3__input--confirm-wpmu-user-site-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--confirm-wpmu-user-site-subject" name="b3_activate_wpmu_user_site_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $new_wpmu_user_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--confirm-wpmu-user-site-message"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'mu-confirm-user-email' ); ?>
        </th>
        <td>
            <textarea id="b3__input--confirm-wpmu-user-site-message" name="b3_activate_wpmu_user_site_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $new_wpmu_user_message ); ?></textarea>
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
