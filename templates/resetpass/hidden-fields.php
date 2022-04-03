<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="resetpass" type="hidden" />
<input name="rp_login" type="hidden" value="<?php esc_attr_e( $attributes[ 'login' ] ); ?>" autocomplete="off"/>
<input name="rp_key" type="hidden" value="<?php esc_attr_e( $attributes[ 'key' ] ); ?>"/>
