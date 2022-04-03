<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--site-title">
    <label class="b3_form-label" for="blog_title"><?php esc_html_e( 'Site title', 'b3-onboarding' ); ?></label>
    <input name="blog_title" id="blog_title" value="<?php echo apply_filters( 'b3_localhost_blogtitle', false ); ?>" type="text" class="b3_form--input" />
</div>
