<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3_form-element b3_form-element--subdomain">
    <?php $current_network = get_network(); ?>
    <?php if ( is_subdomain_install() ) { ?>
        <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site (sub) domain', 'b3-onboarding' ); ?></label>
        <div>
            <input name="blogname" id="blogname" value="<?php echo apply_filters( 'b3_localhost_blogname', false ); ?>" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'your-blogname', 'b3-onboarding' ); ?>" />
            <div class="b3_site-domain">.<?php echo $_SERVER[ 'HTTP_HOST' ]; ?></div>
        </div>
    <?php } else { ?>
        <label class="b3_form-label" for="blogname"><?php esc_html_e( 'Site address', 'b3-onboarding' ); ?></label>
        <?php echo $current_network->domain . $current_network->path; ?><input name="blogname" id="blogname" value="" type="text" class="b3_form--input" placeholder="<?php esc_html_e( 'address', 'b3-onboarding' ); ?>" />
    <?php } ?>
</div>
