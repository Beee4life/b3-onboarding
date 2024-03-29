<?php
    /*
     * Input fields for 'Visitor registers account' (MS)
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <th>
            <label for="b3__input--password-changed-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <?php // TODO: get default value ?>
            <input id="b3__input--password-changed-subject" name="b3_input_password_change_subject" type="text" value="" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--password-changed-content"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <?php // TODO: get default value ?>
            <textarea id="b3__input--password-changed-content" name="b3_input_password_change_content" rows="6"></textarea>
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
