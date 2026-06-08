<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    // These fields should be left untouched, they're needed.
?>
<input type="hidden" name="b3_form" value="lostpassword" />
<input type="hidden" name="b3_site_id" value="<?php echo esc_attr( get_current_blog_id() ); ?>" />
