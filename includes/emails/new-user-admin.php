<?php
    $new_user_email_subject = get_option( 'b3_new_user_subject', false );
    $new_user_email_message = get_option( 'b3_new_user_message', false );
?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2">
            <?php esc_html_e( 'If any field is left empty the placeholder will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--new-user-subject" class=""><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input class="" id="b3__input--new-user-subject" name="b3_new_user_subject" placeholder="<?php echo sprintf( esc_html__( 'New user at %s', 'b3-onboarding' ), get_option( 'blogname' ) ); ?>" type="text" value="<?php echo $new_user_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user-message" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--new-user-message" name="b3_new_user_message" placeholder="<?php echo b3_default_new_user_admin_message(); ?>" rows="4"><?php echo $new_user_email_message; ?></textarea>
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
