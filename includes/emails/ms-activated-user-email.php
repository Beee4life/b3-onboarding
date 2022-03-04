<?php
    /*
     * Input fields for 'Confirm email for new user (no site)' (MS)
     *
     * @since 3.0
     */
    $activated_wpmu_user_subject = get_option( 'b3_activated_wpmu_user_subject' );
    $activated_wpmu_user_message = get_option( 'b3_activated_wpmu_user_message' );
    $placeholder_subject         = sprintf( esc_attr( b3_default_wpmu_user_activated_subject() ), get_site_option( 'site_name' ) );
    $placeholder_message         = esc_attr( b3_default_wpmu_user_activated_message() );
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
            <label for="b3__input--activated-wpmu-user-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--activated-wpmu-user-subject" name="b3_activated_wpmu_user_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $activated_wpmu_user_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--activated-wpmu-user-message" class=""><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'mu-user-activated' ); ?>
        </th>
        <td>
            <textarea id="b3__input--activated-wpmu-user-message" name="b3_activated_wpmu_user_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $activated_wpmu_user_message ); ?></textarea>
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
