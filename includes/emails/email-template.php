<?php
    /*
     * Input fields for email template
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $stored_email_template = get_option( 'b3_email_template' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2">
            <?php echo __( 'This is the default email template.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-template" class=""><?php esc_html_e( 'Email template', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'template' ); ?>
            <br>
            <?php echo sprintf(  '<a href="%s">%s</a>', esc_url( B3_PLUGIN_URL . 'includes/download.php?file=default-email-template.html&sentby=b3' ), esc_html__( 'Download template', 'b3-onboarding' ) ); ?>
        </th>
        <td>
            <textarea id="b3__input--email-template" name="b3_email_template" placeholder="<?php echo esc_attr( b3_default_email_template() ); ?>" rows="6"><?php if ( $stored_email_template ) { echo $stored_email_template; } ?></textarea>
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
