<?php $current_user = get_userdata( get_current_user_id() ); ?>
<div class="tml tml-profile" id="sexdates1">
    <form id="your-profile" name="profileform" action="" method="post">
        <?php wp_nonce_field( 'update-user_' . $current_user->ID ); ?>
        <input type="hidden" name="from" value="profile" />
        <input type="hidden" name="checkuser_id" value="<?php echo $current_user->ID; ?>" />
        <input type="hidden" name="nickname" id="nickname" value="<?php echo esc_attr( $current_user->nickname ); ?>" class="regular-text" />
        <?php if ( current_user_can( 'manage_options' ) ) { ?>
            <input type="hidden" name="admin_bar_front" id="admin_bar_front" value="1" />
        <?php } ?>

        <h2>Email</h2>
        <table class="tml-form-table">
            <tr class="tml-user-email-wrap">
                <th><label for="email"><?php esc_html__( 'E-mail', 'b3-onboarding' ); ?> <span class="description"><?php esc_html__( '(required)', 'b3-onboarding' ); ?></span></label></th>
                <td>
                    <input type="text" name="email" id="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="input regular-text" />
                    <?php
                        $new_email = get_option( $current_user->ID . '_new_email' );
                        if ( $new_email && $new_email['newemail'] != $current_user->user_email ) : ?>
                            <div class="updated inline">
                                <p>
                                    <?php
                                        printf(
                                            esc_html__( 'There is a pending change of your e-mail to %1$s. <a href="%2$s">Cancel</a>', 'b3-onboarding' ),
                                            '<code>' . $new_email['newemail'] . '</code>',
                                            esc_url( self_admin_url( 'profile.php?dismiss=' . $current_user->ID . '_new_email' ) )
                                        );
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php
            $show_password_fields = apply_filters( 'show_password_fields', true, $current_user );
            if ( $show_password_fields ) :
        ?>
        <h2>Password</h2>
        <table class="tml-form-table">
            <tr id="password" class="user-pass1-wrap">
                <th><label for="pass1"><?php esc_html__( 'New password', 'b3-onboarding' ); ?></label></th>
                <td>
                    <!-- #24364 workaround -->
<!--                    <input class="hidden" value=" " />-->
                    <button type="button" class="button button--small wp-generate-pw hide-if-no-js"><?php esc_html__( 'Generate password', 'b3-onboarding' ); ?></button>
                    <div class="wp-pwd hide-if-js">
                        <span class="password-input-wrapper">
                            <input type="password" name="pass1" id="pass1" class="input regular-text" value="" autocomplete="off" data-pw="<?php esc_attr__( wp_generate_password( 24 ) ); ?>" aria-describedby="pass-strength-result" />
                        </span>
                        <div style="display:none" id="pass-strength-result" aria-live="polite"></div>
                        <button type="button" class="button button--small wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr__( 'Hide password', 'b3-onboarding' ); ?>">
                            <span class="dashicons dashicons-hidden"></span>
                            <span class="text"><?php esc_html__( 'Hide', 'b3-onboarding' ); ?></span>
                        </button>
                        <button type="button" class="button button--small wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr__( 'Cancel password change', 'b3-onboarding' ); ?>">
                            <span class="text"><?php esc_html__( 'Cancel', 'b3-onboarding' ); ?></span>
                        </button>
                    </div>
                </td>
            </tr>
            <tr class="user-pass2-wrap hide-if-js">
                <th scope="row"><label for="pass2"><?php esc_html__( 'Repeat new password', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input name="pass2" type="password" id="pass2" class="regular-text" value="" autocomplete="off" />
                    <p class="description"><?php esc_html__( 'Type your new password again.', 'b3-onboarding' ); ?></p>
                </td>
            </tr>
            <tr class="pw-weak">
                <th><?php esc_html__( 'Confirm password', 'b3-onboarding' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="pw_weak" class="pw-checkbox" />
                        <?php esc_html__( 'Confirm use of weak password', 'b3-onboarding' ); ?>
                    </label>
                </td>
            </tr>
            <?php endif; ?>
        </table>

        <?php if ( defined( 'ENV' ) && ( ENV == 'development' ) ) { ?>
            <h2><?php esc_html__( 'Settings', 'b3-onboarding' ); ?></h2>
            <table class="tml-form-table-settings">
                <tr class="">
                    <th>
                        <label for="preferred_language"><?php esc_html__( 'Preferred language', 'b3-onboarding' ); ?></label>
                    </th>
                    <td>
                        <?php $preferred_language = get_user_meta( $current_user->ID, 'preferred_language', true ); ?>
                        <select name="preferred_language" id="preferred_language">
                            <?php $langs = apply_filters( 'wpml_active_languages', null, 'skip_missing=0&order=asc' ); ?>
                            <?php foreach( $langs as $language ) { ?>
                            <?php $selected = false; ?>
                            <?php if ( $preferred_language == $language['code'] ) { ?>
                                <?php $selected = ' selected="selected"'; ?>
                            <?php } ?>
                            <option value="<?php echo $language['code']; ?>"<?php echo $selected; ?>> <?php echo $language['translated_name']; ?>
                                <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
        <?php } ?>

        <h2><?php esc_html__( 'Invoice info', 'b3-onboarding' ); ?></h2>
        <p><?php esc_html__( 'If you want to receive an invoice with your order, this is the information we will use for the address.', 'b3-onboarding' ); ?></p>
        <?php
            $user_id         = $current_user->ID;
            $user_first      = ( false != get_user_meta( $user_id, 'first_name', true ) ) ? get_user_meta( $user_id, 'first_name', true ) : '';
            $user_last       = ( false != get_user_meta( $user_id, 'last_name', true ) ) ? get_user_meta( $user_id, 'last_name', true ) : '';
            $company_name    = ( false != get_user_meta( $user_id, 'company_name', true ) ) ? get_user_meta( $user_id, 'company_name', true ) : '';
            $invoice_address = ( false != get_user_meta( $user_id, 'invoice_address', true ) ) ? get_user_meta( $user_id, 'invoice_address', true ) : '';
            $invoice_zipcode = ( false != get_user_meta( $user_id, 'invoice_zipcode', true ) ) ? get_user_meta( $user_id, 'invoice_zipcode', true ) : '';
            $invoice_city    = ( false != get_user_meta( $user_id, 'invoice_city', true ) ) ? get_user_meta( $user_id, 'invoice_city', true ) : '';
            $invoice_country = ( false != get_user_meta( $user_id, 'invoice_country', true ) ) ? get_user_meta( $user_id, 'invoice_country', true ) : '';
            $invoice_vat     = ( false != get_user_meta( $user_id, 'invoice_vat', true ) ) ? get_user_meta( $user_id, 'invoice_vat', true ) : '';
        ?>
        <table class="tml-form-table-company">
            <tr class="tml-user-invoice-name">
                <th><label for="invoice_name"><?php esc_html__( 'Company name', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="company_name" id="invoice_name" value="<?php echo $company_name; ?>" class="input regular-text" placeholder="Required for invoice" />
                </td>
            </tr>
            <tr class="tml-user-first-name">
                <th><label for="invoice_first_name"><?php esc_html__( 'First name', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_first_name" id="invoice_first_name" value="<?php echo $user_first; ?>" class="input regular-text" placeholder="Required if no company is entered" />
                </td>
            </tr>
            <tr class="tml-user-last-name">
                <th><label for="invoice_last_name"><?php esc_html__( 'Last name', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_last_name" id="invoice_last_name" value="<?php echo $user_last; ?>" class="input regular-text" placeholder="Required if no company is entered" />
                </td>
            </tr>
            <tr class="tml-user-invoice-address">
                <th><label for="invoice_address"><?php esc_html__( 'Address', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_address" id="invoice_address" value="<?php echo $invoice_address; ?>" class="input regular-text" placeholder="Required for invoice" />
                </td>
            </tr>
            <tr class="tml-user-invoice-zipcode">
                <th><label for="invoice_zipcode"><?php esc_html__( 'Zipcode', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_zipcode" id="invoice_zipcode" value="<?php echo $invoice_zipcode; ?>" class="input regular-text" placeholder="Required for invoice" />
                </td>
            </tr>
            <tr class="tml-user-invoice-city">
                <th><label for="invoice_city"><?php esc_html__( 'City', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_city" id="invoice_city" value="<?php echo $invoice_city; ?>" class="input regular-text" placeholder="Required for invoice" />
                </td>
            </tr>
            <tr class="tml-user-invoice-country">
                <th><label for="invoice_country"><?php esc_html__( 'Country', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_country" id="invoice_country" value="<?php echo $invoice_country; ?>" class="input regular-text" placeholder="" />
                </td>
            </tr>
            <tr class="tml-user-invoice-vat">
                <th><label for="invoice_vat"><?php esc_html__( 'VAT', 'b3-onboarding' ); ?></label></th>
                <td>
                    <input type="text" name="invoice_vat" id="invoice_vat" value="<?php echo $invoice_vat; ?>" class="input regular-text" />
                </td>
            </tr>
        </table>

        <table class="tml-form-table">
            <tr class="">
                <th>&nbsp;</th>
                <td>
                    <input type="hidden" name="action" value="profile" />
                    <input type="hidden" name="instance" value="1" />
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>" />
                    <input type="submit" class="button button--small" value="<?php esc_attr__( 'Update profile', 'b3-onboarding' ); ?>" name="submit" id="submit" />
                </td>
            </tr>
        </table>

        <?php do_action( 'show_user_profile', $current_user ); ?>

        <!--        <h2>--><?php //esc_html__( 'Delete account', 'b3-onboarding' ); ?><!--</h2>-->
        <!--        <p>-->
        <!--            --><?php //$permalink = get_permalink( apply_filters( 'wpml_object_id', 220, 'page', true ) ); ?>
        <!--            --><?php //echo sprintf( esc_html__( 'If you want to remove your account, <a href="%s">click here</a>.', 'b3-onboarding' ), $permalink ); ?>
        <!--        </p>-->

    </form>
</div>
