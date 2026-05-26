<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="magiclink" type="hidden" />
<input name="<?php echo esc_attr( $attributes[ 'nonce_id' ] ); ?>" value="<?php echo esc_attr( $attributes[ 'nonce' ] ); ?>" type="hidden" />
