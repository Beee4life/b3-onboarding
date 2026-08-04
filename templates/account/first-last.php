<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $required    = get_option( 'b3_first_last_required' ) ? ' required="required"' : false;
    $show_fields = apply_filters( 'b3_show_first_last_account', true );

    if ( $show_fields ) {
?>
<div class="b3_form-element b3_form-element--first-name">
    <label class="b3_form-label" for="first_name">
        <?php esc_html_e( 'First name', 'b3-onboarding' ); ?>
        <?php if ( $required ) { ?><strong>*</strong><?php } ?>
    </label>
    <input class="input regular-text" id="first_name" name="first_name" type="text" value="<?php echo esc_attr( $current_user->first_name ); ?>"<?php echo esc_attr( $required ); ?> />
</div>

<div class="b3_form-element b3_form-element--last-name">
    <label class="b3_form-label" for="last_name">
        <?php esc_html_e( 'Last name', 'b3-onboarding' ); ?>
        <?php if ( $required ) { ?><strong>*</strong><?php } ?>
    </label>
    <input class="input regular-text" id="last_name" name="last_name" type="text" value="<?php echo esc_attr( $current_user->last_name ); ?>"<?php echo esc_attr( $required ); ?> />
</div>
<?php }
