<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    /*
     * Input fields for 'Magic login link' email
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $magic_link_subject = get_option( 'b3_magic_link_subject' );
    $magic_link_message = get_option( 'b3_magic_link_message' );
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
            <label for="b3__input--magic-link__subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--magic-link__subject" name="b3_magic_link_subject" placeholder="<?php echo esc_attr( b3_default_magic_link_subject() ); ?>" type="text" value="<?php echo esc_attr( $magic_link_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--magic-link__message"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo wp_kses_post( b3_get_preview_link( 'magiclink' ) ); ?>
        </th>
        <td>
            <textarea id="b3__input--magic-link__message" name="b3_magic_link_message" placeholder="<?php echo esc_attr( b3_default_magic_link_message( '123', '%s' ) ); ?>" rows="6"><?php echo esc_textarea( $magic_link_message ); ?></textarea>
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
