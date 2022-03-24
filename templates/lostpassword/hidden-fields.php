<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    // These fields should be left untouched, they're needed.
?>
<input type="hidden" name="b3_form" value="lostpass" />
<input type="hidden" name="b3_lost_pass" value="<?php echo wp_create_nonce( 'b3-lost-pass' ); ?>" />
<input type="hidden" name="b3_site_id" value="<?php echo get_current_blog_id(); ?>" />
