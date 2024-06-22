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
            <?php esc_html_e( 'This is the logo used in email headers.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3_main_logo"><?php esc_html_e( 'Logo', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <?php if ( false == apply_filters( 'b3_main_logo', false ) ) { ?>
                <label>
                    <input type="url" name="b3_main_logo" id="b3_main_logo" value="<?php echo esc_url( $main_logo ); ?>" />
                </label>
                <a href="#" id="main-logo" class="b3-open-media button button-primary" title="<?php esc_attr_e( 'Choose a logo', 'b3-onboarding' ); ?>">
                    <?php esc_html_e( 'Choose a logo', 'b3-onboarding' ); ?>
                </a>
            <?php } else { ?>
                <?php esc_html_e( "You've set this logo with a filter.", 'b3-onboarding' ); ?>
                <br>
                <a href="<?php echo apply_filters( 'b3_main_logo', false ); ?>">
                    <img src="<?php echo apply_filters( 'b3_main_logo', false ); ?>" alt="" style="max-width: 300px;" />
                </a>
            <?php } ?>
        </td>
    </tr>
    </tbody>
</table>
