<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render styling tab
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
        <form action="admin.php?page=b3-onboarding&tab=styling" method="post">
            <input name="b3_styling_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-styling-nonce' ) ); ?>">

            <?php foreach( $fields as $field ) { ?>
                <div class="template_box">
                    <label for="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>"><?php echo esc_html( $field[ 'title' ] ); ?></label>
                    <?php if ( isset( $field[ 'filter' ] ) && false != $field[ 'filter' ] ) { ?>
                        <p>
                            <?php if ( 'email_styling' == $field[ 'id' ] ) { ?>
                                <?php esc_html_e( "You have set the filter 'b3_email_styling' to change the css, so you can't override that here again with this setting because the filter takes presedence.", 'b3-onboarding' ); ?>
                            <?php } else { ?>
                                <?php esc_html_e( "You have set the filter 'b3_email_template' to change the html, so you can't override that here again with this setting because the filter takes presedence.", 'b3-onboarding' ); ?>
                            <?php } ?>
                        </p>
                    <?php } else { ?>
                        <textarea id="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>" name="b3_<?php echo esc_attr( $field[ 'id' ] ); ?>" rows="6"><?php echo esc_textarea( $field[ 'value' ] ); ?></textarea>

                        <?php if ( 'email_styling' == $field[ 'id' ] ) { ?>
                            <p>
                                <?php // translators: filter name, line number, link to filter info ?>
                                <?php echo sprintf( esc_html__( "We don't recommend overriding the css here, but rather add css at the end of the file, after line %1\$d by using the filter %2\$s.", 'b3-onboarding' ), 106, sprintf( '<a href="%s" target="_blank">b3_email_styling</a>', esc_url( 'https://b3onboarding.berryplasman.com/filter/b3_email_styling/' )) ); ?>
                            </p>
                        <?php } ?>
                        <?php if ( 'email_template' == $field[ 'id' ] ) { ?>
                            <p>
                                <?php esc_html_e( "We don't recommend overriding the template, unless you know what you're doing.", 'b3-onboarding' ); ?>
                                <?php esc_html_e( "The default template is sufficient in most cases.", 'b3-onboarding' ); ?>
                                <br>
                                <?php // translators: filter name + link to filter info ?>
                                <?php echo sprintf( esc_html__( "It's easer to override the styling to achieve your wishes, but if you really want to change the template we recommend using the filter %s.", 'b3-onboarding' ), sprintf( '<a href="%s" target="_blank">b3_email_template</a>', esc_url( 'https://b3onboarding.berryplasman.com/filter/b3_email_template/' ) ) ); ?>
                            </p>
                       <?php } ?>
                    <?php } ?>
                    <p>
                        <?php echo wp_kses_post( b3_get_preview_link( $field[ 'preview' ] ) ); ?>
                        <small>(<?php esc_html_e( 'opens in new window', 'b3-onboarding' ); ?>)</small>
                        |
                        <?php
                            $download_url = add_query_arg(
                                [
                                    'action' => 'b3_download',
                                    'file'   => $field[ 'file_name' ],
                                ],
                                admin_url( 'admin-post.php' )
                            );
                            echo sprintf(
                                '<a href="%s">%s</a> %s',
                                esc_url( $download_url ),
                                esc_html__( 'Click here', 'b3-onboarding' ),
                                esc_html__( 'to download the default.', 'b3-onboarding' )
                            );
                        ?>
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
