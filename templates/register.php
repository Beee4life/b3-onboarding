<?php
    $recaptcha_public         = get_option( 'b3_recaptcha_public', false );
    $registration_type        = get_option( 'b3_registration_type', false );
    $send_password_by_mail    = get_option( 'b3_send_pass_mail', false );
    $show_custom_passwords    = false;
    $show_recaptcha           = get_option( 'b3_recaptcha', false );
?>
<div id="b3-register" class="b3_page b3_page--register">
    <?php if ( $attributes[ 'title' ] ) { ?>
        <h3>
            <?php echo $attributes[ 'title' ]; ?>
        </h3>
    <?php } ?>


    <form id="b3-register-form" class="b3_form b3_form--register" action="<?php echo wp_registration_url(); ?>" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />
        <?php do_action( 'b3_hidden_fields_registration_form' ); ?>

        <?php do_action( 'b3_show_form_messages', $attributes ); ?>

        <?php if ( 'closed' != $registration_type ) { ?>

            <?php do_action( 'b3_login_email_fields' ); ?>

            <?php // @TODO: check if this hook can be used - is WP default, normally after email field ?>
            <?php // do_action( 'register_form' ); ?>

            <?php do_action( 'b3_add_first_last_name' ); ?>

            <?php do_action( 'b3_add_password_fields' ); ?>

            <?php do_action( 'b3_add_subdomain_field' ); ?>

            <?php do_action( 'b3_add_custom_fields_registration' ); ?>

            <?php do_action( 'b3_add_recaptcha_fields' ); ?>

            <?php do_action( 'b3_add_privacy_checkbox' ); ?>

            <?php do_action( 'b3_do_before_submit_registration_form' ); ?>

            <?php // @TODO: maybe use function/action for submit button ?>
            <div class="b3_form-element b3_form-element--submit">
                <?php if ( 'request_access' == $registration_type ) { ?>
                    <?php $submit_label = esc_html__( 'Request access', 'b3-onboarding' ); ?>
                <?php } else { ?>
                    <?php $submit_label = esc_html__( 'Register', 'b3-onboarding' ); ?>
                <?php } ?>
                <input type="submit" class="button" value="<?php echo $submit_label; ?>" />
            </div>
            <?php do_action( 'b3_do_after_submit_registration_form' ); ?>

        <?php } ?>

        <?php echo b3_get_form_links( 'register' ); ?>

    </form>

</div>
