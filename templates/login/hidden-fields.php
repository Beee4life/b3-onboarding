<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="login" type="hidden" />
<?php if ( isset( $attributes[ 'redirect' ] ) ) { ?>
    <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $attributes[ 'redirect' ] ); ?>">
<?php } ?>
