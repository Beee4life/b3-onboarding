<?php
    /*
     * Input fields for 'New wpmu user' email (admin)
     *
     * @since 3.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $disable_admin_notification  = get_option( 'b3_disable_admin_notification_new_user' );
    $new_wpmu_user_subject_admin = get_option( 'b3_new_wpmu_user_admin_subject' );
    $new_wpmu_user_message_admin = get_option( 'b3_new_wpmu_user_admin_message' );
    $placeholder_subject         = esc_attr( b3_get_new_wpmu_user_subject_admin() );
    $placeholder_message         = esc_attr( b3_get_new_wpmu_user_message_admin() );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If a field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-wpmu-user-admin-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--new-wpmu-user-admin-subject" name="b3_new_wpmu_user_admin_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $new_wpmu_user_subject_admin ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-wpmu-user-admin-message"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'mu-new-user-admin' ); ?>
        </th>
        <td>
            <textarea id="b3__input--new-wpmu-user-admin-message" name="b3_new_wpmu_user_admin_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $new_wpmu_user_message_admin ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <label>
                <input name="b3_disable_admin_notification_new_user" type="checkbox" value="1" <?php checked($disable_admin_notification); ?>/>
                <?php
                    if ( 1 == $disable_admin_notification ) {
                        esc_html_e( 'Enable admin notification on new user registration', 'b3-onboarding' );
                    } else {
                        esc_html_e( 'Disable admin notification on new user registration', 'b3-onboarding' );
                    }
                ?>
            </label>
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
