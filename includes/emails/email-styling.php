<?php
    /*
     * Input fields for email styling
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $stored_email_styling = get_option( 'b3_email_styling' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If left empty the default styling will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-styling" class=""><?php esc_html_e( 'Email styling', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo sprintf( '<a href="%s">%s</a>', esc_url( B3_PLUGIN_SETTINGS . '&preview=styling' ), esc_html__( 'Preview', 'b3-onboarding' ) ); ?>
            <br>
            <?php echo sprintf( '<a href="%s">%s</a>', esc_url( B3_PLUGIN_URL . 'includes/download.php?file=default-email-styling.css&sentby=b3' ), esc_html__( 'Download styling', 'b3-onboarding' ) ); ?>
        </th>
        <td>
            <textarea id="b3__input--email-styling" name="b3_email_styling" placeholder="<?php echo b3_default_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ); ?>" rows="6"><?php if ( $stored_email_styling ) { echo $stored_email_styling; } ?></textarea>
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
