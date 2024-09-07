<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<input name="b3_form" value="magiclink" type="hidden" />
<input name="<?php echo $attributes[ 'nonce_id' ]; ?>" value="<?php echo $attributes[ 'nonce' ]; ?>" type="hidden" />
