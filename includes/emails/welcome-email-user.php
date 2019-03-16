<?php
    $welcome_user_email_subject = get_option( 'b3_welcome_user_subject', false );
    $welcome_user_email_message = get_option( 'b3_welcome_user_message', false );
?>
<table class="b3_table b3_table--emails" border="0" cellpadding="0" cellspacing="0">
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
            <input class="" id="b3__input--welcome-user" name="b3_welcome_user_subject" placeholder="Welcome to <?php echo get_bloginfo( 'name' ); ?>" type="text" value="<?php echo $welcome_user_email_subject; ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--new-user" class=""><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="" name="b3_welcome_user_message" placeholder="<?php echo sprintf( esc_html__( 'Welcome [username], your registration to %s was successful. You can set your password here: %s.', 'b3-onboarding' ), get_bloginfo( 'name' ), get_permalink( b3_get_forgotpass_id() ) ); ?>" rows="4"><?php echo $welcome_user_email_message; ?></textarea>
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
