<?php
    /**
     * Render template tab
     *
     * @since 3.7.0
     *
     * @return false|string
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_render_template_tab() {
        $fields                = [];
        $stored_email_styling  = get_option( 'b3_email_styling' );
        $stored_email_template = get_option( 'b3_email_template' );
        $filter_styling        = apply_filters( 'b3_email_styling', false );
        $filter_template       = apply_filters( 'b3_email_template', false );

        if ( get_option( 'b3_activate_custom_emails' ) ) {
            $fields[] = [
                'id'          => 'email_template',
                'title'       => __( 'Email template', 'b3-onboarding' ),
                'placeholder' => b3_default_email_template(),
                'preview'     => 'template',
                'value'       => $stored_email_template,
                'file_name'   => 'default-email-template.html',
                'filter'      => $filter_template,
            ];
            $fields[] = [
                'id'          => 'email_styling',
                'title'       => __( 'Email styling', 'b3-onboarding' ),
                'placeholder' => b3_default_email_styling( b3_get_link_color() ),
                'preview'     => 'styling',
                'value'       => $stored_email_styling,
                'file_name'   => 'default-email-styling.css',
                'filter'      => $filter_styling,
            ];
        }

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=template" method="post">
            <input name="b3_template_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-template-nonce' ) ); ?>">

            <?php foreach( $fields as $field ) { ?>
                <div class="template_box">
                    <label for="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>"><?php echo esc_html( $field[ 'title' ] ); ?></label>
                    <?php if ( isset( $field[ 'filter' ] ) && false != $field[ 'filter' ] ) { ?>
                        <p>
                            <?php if ( 'email_styling' == $field[ 'id' ] ) { ?>
                                <?php esc_html_e( "You have set the filter 'b3_email_styling' to override the css, so you can't override that here again with this setting because the filter takes presedence.", 'b3-onboarding' ); ?>
                            <?php } else { ?>
                                <?php esc_html_e( "You have set the filter 'b3_email_template' to override the html, so you can't override that here again with this setting because the filter takes presedence.", 'b3-onboarding' ); ?>
                            <?php } ?>
                        </p>
                    <?php } else { ?>
                        <textarea id="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>" name="b3_<?php echo esc_attr( $field[ 'id' ] ); ?>" rows="6"><?php echo esc_textarea( $field[ 'value' ] ); ?></textarea>
                    <?php } ?>
                    <p>
                        <?php echo wp_kses_post( b3_get_preview_link( $field[ 'preview' ] ) ); ?>
                        <small>(<?php esc_html_e( 'opens in new window', 'b3-onboarding' ); ?>)</small>
                        |
                        <?php echo sprintf( '<a href="%s">%s</a> %s', esc_url( B3OB_PLUGIN_URL . 'includes/download.php?file=' . $field[ 'file_name' ] . '&sentby=b3' ), esc_html__( 'Click here', 'b3-onboarding' ), esc_html__( 'to download the default.', 'b3-onboarding' ) ); ?>
                    </p>
                </div>
            <?php } ?>

            <?php if ( ! $filter_styling || ! $filter_template ) { ?>
                <?php b3_get_submit_button(); ?>
            <?php } ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
