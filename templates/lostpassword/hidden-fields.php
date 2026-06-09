<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    // This should be left untouched, it's needed.
?>
<input type="hidden" name="b3_site_id" value="<?php echo esc_attr( get_current_blog_id() ); ?>" />
