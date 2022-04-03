<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<?php // @TODO: add languages option ?>
<div class="b3_form-element b3_form-element--visbility">
    <div class="privacy-intro">
        <?php _e( 'Allow search engines to index this site.', 'b3-onboarding' ); ?>
        <label class="checkbox" for="blog_public_on">
            <input type="radio" id="blog_public_on" name="blog_public" value="1" checked />
            <?php _e( 'Yes' ); ?>
        </label>
        <label class="checkbox" for="blog_public_off">
            <input type="radio" id="blog_public_off" name="blog_public" value="0" />
            <?php _e( 'No' ); ?>
        </label>
    </div>
</div>
