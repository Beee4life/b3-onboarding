<?php
    /*
     * Input fields for 'feedback' email
     *
     * @since 2.0.0
     */
?>
<form action="admin.php?page=b3-onboarding" method="post">
    <input name="b3_feedback_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-feedback-nonce' ); ?>">
    <input name="b3_feedback_user" type="hidden" value="<?php echo get_current_user_id(); ?>">

    <table class="b3_table b3_table--feedback">
        <tbody>
        <tr>
            <th class="align-top">
                <label for="b3__input--feedback__message"><?php esc_html_e( 'Email message', 'b3-onboarding' ); ?></label>
            </th>
            <td>
                <textarea id="b3__input--feedback__message" name="b3_feedback_message" placeholder="Please let us know what you think of the plugin." rows="4"></textarea>
            </td>
        </tr>
        <tr>
            <th class="align-top">
                <label for="b3_followup" class="screen-reader-text"><?php esc_html_e( 'You may contact me for follow-up', 'b3-onboarding' ); ?></label>
            </th>
            <td>
                <div class="b3_settings-input b3_settings-input--checkbox">
                    <input type="checkbox" id="b3_followup" name="b3_followup" value="1" checked="checked" /> <?php esc_html_e( 'You may contact me for follow-up questions.', 'b3-onboarding' ); ?>
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="2">&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Send feedback', 'b3-onboarding' ); ?>" />
            </td>
        </tr>
        </tbody>
    </table>
</form>
