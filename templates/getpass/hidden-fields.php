<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="getpass" type="hidden" />
<input name="get_pass" type="hidden" value="<?php esc_attr_e( $attributes[ 'email' ] ); ?>" autocomplete="off"/>
