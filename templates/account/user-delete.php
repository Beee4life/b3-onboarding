<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<?php if ( get_option( 'b3_user_may_delete', false ) ) { ?>
    <div class="b3_form-element b3_form-element--delete">
        <strong>
            <?php esc_html_e( 'Delete account', 'b3-onboarding' ); ?>
        </strong>
        <br>
        <label for="b3_delete_account">
            <?php esc_attr_e( 'If you click this button, your entire user profile will be deleted.', 'b3-onboarding' ); ?>
        </label>
        <div>
            <input type="submit" id="b3_delete_account" name="b3_delete_account" class="button button--small" value="<?php esc_attr_e( 'Delete account', 'b3-onboarding' ); ?>" onclick="return confirm( 'Are you sure you want to delete your account ?' )" />
        </div>
    </div>
<?php } ?>
