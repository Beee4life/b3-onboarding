<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="rp_login" type="hidden" value="<?php echo esc_attr( $attributes[ 'login' ] ); ?>" autocomplete="off"/>
<input name="rp_key" type="hidden" value="<?php echo esc_attr( $attributes[ 'key' ] ); ?>"/>
