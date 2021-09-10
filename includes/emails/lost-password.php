<?php
    /*
     * Input fields for 'Lost password' email
     *
     * @since 1.0.0
     */
    $disable_admin_notification = get_option( 'b3_disable_admin_notification_password_change' );
    $disable_user_notification  = get_option( 'b3_disable_user_notification_password_change' ); // @TODO: add to docs/site
    $lost_password_subject      = get_option( 'b3_lost_password_subject' );
    $lost_password_message      = get_option( 'b3_lost_password_message' );
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
            <label for="b3__input--lost-password-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--lost-password-subject" name="b3_lost_password_subject" type="text" placeholder="<?php echo esc_attr( b3_default_lost_password_subject() ); ?>" value="<?php echo esc_attr( $lost_password_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--lost-password-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br />
            <?php echo sprintf( __( '<a href="%s" target="_blank" rel="noopener">Preview</a>', 'b3-onboarding' ), esc_url( B3_PLUGIN_SETTINGS . '&preview=lostpass' ) ); ?>
        </th>
        <td>
            <?php esc_html_e( "Be sure to include %reset_url% in your email, otherwise the user can't reset his/her password.", "b3-onboarding" ); ?>
            <br /><br />
            <?php esc_html_e( "Other available variables are:", "b3-onboarding" ); ?> %blog_name%, %email_footer%, %home_url%, %logo%, %registration_date%, %site_url%, %user_ip%, %user_login%
            <br /><br />
            <textarea id="b3__input--lost-password-message" name="b3_lost_password_message" placeholder="<?php echo esc_attr( b3_default_lost_password_message() ); ?>" rows="6"><?php echo stripslashes( $lost_password_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_user_notification_password_change" type="checkbox" value="1" <?php if ( 1 == $disable_user_notification ) { echo 'checked="checked" '; } ?>/> <?php esc_html_e( 'Disable user notification on password change', 'b3-onboarding' ); ?>
            </label>
            <br />
            <label>
                <input name="b3_disable_admin_notification_password_change" type="checkbox" value="1" <?php if ( 1 == $disable_admin_notification ) { echo 'checked="checked" '; } ?>/> <?php esc_html_e( 'Disable admin notification on password change', 'b3-onboarding' ); ?>
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
