<?php
    /**
     * Ouptuts fields for register form
     *
     * @since 1.0.0
     */
?>
<div id="b3-register" class="b3_page b3_page--register">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>


    <form id="b3-register-form" class="b3_form b3_form--register" action="<?php echo wp_registration_url(); ?>" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />
        <input name="b3_form" value="custom" type="hidden" />

        <?php do_action( 'b3_add_form_messages', $attributes ); ?>

        <?php do_action( 'b3_add_username_email_fields' ); ?>

        <?php do_action( 'register_form' ); ?>

        <?php do_action( 'b3_do_before_submit_registration_form' ); ?>
        <div class="b3_form-element b3_form-element--submit">
            <?php if ( 'request_access' == get_option( 'b3_registration_type', false ) ) { ?>
                <?php $submit_label = esc_attr( 'Request access', 'b3-onboarding' ); ?>
            <?php } else { ?>
                <?php $submit_label = esc_attr( 'Register', 'b3-onboarding' ); ?>
            <?php } ?>
            <input type="submit" class="button" value="<?php echo $submit_label; ?>" />
        </div>
        <?php do_action( 'b3_do_after_submit_registration_form' ); ?>

        <?php do_action( 'b3_add_action_links', $attributes[ 'template' ] ); ?>

    </form>

</div>
