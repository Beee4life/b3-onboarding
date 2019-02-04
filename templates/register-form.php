<?php $show_custom_passwords = get_option( 'sd-custom-passwords' ); ?>
<div id="register-form" class="widecolumn">
    <?php if ( $attributes[ 'show_title' ] ) : ?>
        <h3><?php _e( 'Register', 'sd-login' ); ?></h3>
    <?php endif; ?>
    <?php //echo '<pre>'; var_dump($attributes[ 'errors' ]); echo '</pre>'; exit; ?>
    <?php if ( count( $attributes[ 'errors' ] ) > 0 ) : ?>
        <?php foreach ( $attributes[ 'errors' ] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'User name', 'sd-login' ); ?> <strong>*</strong></label>
            <input type="text" name="user_login" id="user_login">
        </p>
        <p class="form-row">
            <label for="email"><?php _e( 'Email', 'sd-login' ); ?> <strong>*</strong></label>
            <input type="text" name="email" id="email">
        </p>

        <?php if ( $show_custom_passwords == true ) : ?>
        <p class="form-row">
            <label for="pass1"><?php _e( 'Password', 'sd-login' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" class="input" size="20" value="" type="password" />
        </p>

        <p class="form-row">
            <label for="pass2"><?php _e( 'Confirm Password', 'sd-login' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" class="input" size="20" value="" type="password" />
        </p>
        <?php endif; ?>

        <p class="signup-user-type">
            <label for="user-type"><?php _e( 'Register as', 'sexdates' ); ?></label>
            <?php
                $user_type = false;
                if ( isset( $_POST[ 'user_type' ] ) ) {
                    $user_type = $_POST[ 'user_type' ];
                }
                $roles = [ 'independent', 'company' ];
            ?>
            <select name="user_type" id="user-type" class="">
                <?php
                    foreach ( $roles as $role ) {
                        $selected = false;
                        if ( $user_type == $role ) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="' . $role . '" ' . $selected . '>' . ucfirst( $role ) . '</option>';
                    }
                ?>
            </select>
        </p>

        <?php if ( $attributes[ 'recaptcha_site_key' ] ) : ?>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="<?php echo $attributes[ 'recaptcha_site_key' ]; ?>"></div>
            </div>
            <p></p>
        <?php endif; ?>

        <?php if ( $show_custom_passwords != true ) : ?>
        <p class="form-row">
            <?php _e( 'Note: Your password will be generated automatically and sent to your email address.', 'sd-login' ); ?>
        </p>
        <?php endif; ?>

        <p class="signup-submit">
            <input type="submit" name="submit" class="register-button" value="<?php _e( 'Register', 'sd-login' ); ?>"/>
        </p>
    </form>
</div>
