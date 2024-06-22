<?php
    /*
     * Input fields for the email logo
     *
     * @since 1.0.0
     */
    $main_logo = get_option( 'b3_main_logo' );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'This is the logo used in email headers using the default template.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3_main_logo"><?php esc_html_e( 'Logo', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <?php if ( false == apply_filters( 'b3_main_logo', false ) ) { ?>
                <div class="logo-fields">
                    <div>
                        <label>
                            <input type="url" name="b3_main_logo" id="b3_main_logo" value="<?php echo esc_url( $main_logo ); ?>" />
                        </label>
                    </div>
                    <div>
                        <a href="#" id="main-logo" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>">
                            <?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?>
                        </a>
                    </div>
                </div>
                <?php if ( $main_logo ) { ?>
                    <div>
                        <img src="<?php echo $main_logo; ?>" alt="Your logo" class="preview-logo" style="max-width: 150px;" />
                    </div>
                <?php } ?>
            <?php } else { ?>
                <?php esc_html_e( "You've set this logo with a filter.", 'b3-onboarding' ); ?>
                <br>
                <img src="<?php echo apply_filters( 'b3_main_logo', false ); ?>" alt="" style="max-width: 300px;" />
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>
