<?php
    $redirect = false;
    if ( $attributes[ 'redirect' ] ) {
        $redirect = $attributes[ 'redirect' ];
    }
?>
<div id="b3-login" class="b3_page b3_page--login">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h2>
            <?php _e( 'Log In', 'b3-onboarding' ); ?>
        </h2>
    <?php } ?>

    <?php wp_login_form() ; ?>
</div>
