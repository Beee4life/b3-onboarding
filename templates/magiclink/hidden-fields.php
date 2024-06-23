<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="getpass" type="hidden" />
<input name="<?php echo $attributes[ 'nonce_id' ]; ?>" value="<?php echo $attributes[ 'nonce' ]; ?>" type="hidden" />

<?php if ( isset( $attributes[ 'email' ] ) ) { ?>
    <input name="email" type="hidden" value="<?php esc_attr_e( $attributes[ 'email' ] ); ?>" />
<?php } ?>
