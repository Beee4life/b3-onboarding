<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Render template tab
    function b3_render_template_tab() {
        $fields                  = [];
        $filtered_email_styling  = apply_filters( 'b3_email_styling', '' );
        $filtered_email_styling  = ! empty( $filtered_email_styling ) ? apply_filters( 'b3_email_styling', b3_get_email_styling( b3_get_link_color() ) ) : 'Write your own CSS here.';
        $filtered_email_template = apply_filters( 'b3_email_template', '' );
        $stored_email_styling    = get_option( 'b3_email_styling' );
        $stored_email_template   = get_option( 'b3_email_template' );
        $styling_placeholder     = ! empty( $filtered_email_styling ) ? $filtered_email_styling : 'Write your own CSS here.';
        $template_placeholder    = ! empty( $filtered_email_template ) ? $filtered_email_template : 'Create your own HTML template here.';

        if ( get_option( 'b3_activate_custom_emails' ) ) {
            $fields[] = [
                'id'            => 'email_template',
                'title'         => __( 'Email template', 'b3-onboarding' ),
                'placeholder'   => $template_placeholder,
                'preview'       => 'template',
                'value'         => $stored_email_template,
                'file_name'     => 'default-email-template.html',
                'set_by_filter' => ! empty( $filtered_email_template ) ? true : false,
            ];
            $fields[] = [
                'id'            => 'email_styling',
                'title'         => __( 'Email styling', 'b3-onboarding' ),
                'placeholder'   => $styling_placeholder,
                'preview'       => 'styling',
                'value'         => $stored_email_styling,
                'file_name'     => 'default-email-styling.css',
                'set_by_filter' => ! empty( $filtered_email_styling ) ? true : false,
            ];
        }

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=template" method="post">
            <input name="b3_template_nonce" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'b3-template-nonce' ) ); ?>">

            <?php foreach( $fields as $field ) { ?>
                <div class="template_box">
                    <label for="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>"><?php echo esc_html( $field[ 'title' ] ); ?></label>
                    <?php if ( $field['set_by_filter'] ) { echo '<br>('; esc_html_e( 'Set by filter', 'b3-onboarding' ); echo ')'; } ?>
                    <textarea id="b3__input--<?php echo esc_attr( $field[ 'id' ] ); ?>" name="b3_<?php echo esc_attr( $field[ 'id' ] ); ?>" placeholder="<?php echo esc_attr( $field[ 'placeholder' ] ); ?>" rows="6"><?php echo esc_textarea( $field[ 'value' ] ); ?></textarea>
                    <p>
                        <?php echo wp_kses_post( b3_get_preview_link( $field[ 'preview' ] ) ); ?>
                        <small>(<?php esc_html_e( 'opens in new window', 'b3-onboarding' ); ?>)</small>
                        |
                        <?php echo sprintf( '<a href="%s">%s</a> %s', esc_url( B3OB_PLUGIN_URL . 'includes/download.php?file=' . $field[ 'file_name' ] . '&sentby=b3' ), esc_html__( 'Click here', 'b3-onboarding' ), esc_html__( 'to download the default.', 'b3-onboarding' ) ); ?>
                    </p>
                </div>
            <?php } ?>

            <?php b3_get_submit_button(); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
