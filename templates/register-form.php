<div id="b3-register" class="b3">
    <?php if ( $attributes[ 'show_title' ] ) { ?>
        <h3><?php esc_html_e( 'Register', 'b3-user-register' ); ?></h3>
    <?php } ?>

    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) { ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) { ?>
            <p class="b3__message">
                <?php echo $error; ?>
            </p>
        <?php } ?>
    <?php } ?>
    
    <form id="b3-register-form" class="b3__form b3__form--register" action="" method="post">
        <input name="b3_register_user" value="<?php echo wp_create_nonce( 'b3-register-user' ); ?>" type="hidden" />

        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="b3_user_login"><?php esc_html_e( 'User name', 'b3-user-register' ); ?> <strong>*</strong></label>
            <input type="text" name="b3_user_login" id="b3_user_login" class="b3__form--input" value="" required>
        </div>

        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="b3_user_email"><?php esc_html_e( 'Email', 'b3-user-register' ); ?> <strong>*</strong></label>
            <input type="text" name="b3_user_email" id="b3_user_email" class="b3__form--input" value="" required>
        </div>

        <div class="b3__form-element b3__form-element--register">
            <?php esc_html_e( 'Note: You can set your own password after registering.', 'b3-user-register' ); ?>
        </div>

        <div class="b3__form-element b3__form-element--submit">
            <input type="submit" name="submit" class="button" value="<?php esc_html_e( 'Register', 'b3-user-register' ); ?>"/>
        </div>
    
    </form>

</div>
