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
        $email_boxes = [];
        $stored_email_template = get_option( 'b3_email_template' );
        $stored_email_styling = get_option( 'b3_email_styling' );
    
        if ( get_option( 'b3_activate_custom_emails' ) ) {
            $email_boxes[] = array(
                'id'          => esc_attr( 'email_styling' ),
                'title'       => esc_html__( 'Email styling', 'b3-onboarding' ),
                'placeholder' => b3_default_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ),
                'preview'     => esc_attr( 'styling' ),
                'value'       => $stored_email_styling,
            );
            $email_boxes[] = array(
                'id'          => esc_attr( 'email_template' ),
                'title'       => esc_html__( 'Email template', 'b3-onboarding' ),
                'placeholder' => esc_attr( b3_default_email_template() ),
                'preview'     => esc_attr( 'template' ),
                'value'       => $stored_email_template,
            );
        }

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=template" method="post">
            <input name="b3_template_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-template-nonce' ); ?>">

            <?php foreach( $email_boxes as $box ) { ?>
                <div class="template_box">
                    <label for="b3__input--<?php echo $box['id']; ?>"><?php echo $box['title']; ?></label>
                    <textarea id="b3__input--<?php echo $box['id']; ?>" name="b3_<?php echo $box['id']; ?>" placeholder="<?php echo esc_attr( b3_default_email_template() ); ?>" rows="6"><?php echo $box[ 'value' ]; ?></textarea>
                    <p>
                        <?php echo b3_get_preview_link( $box['preview'] ); ?> <small>(<?php esc_html_e( 'opens in new window', 'b3-onboarding' ); ?></small>
                        |
                        <?php echo sprintf(  '<a href="%s">%s</a>', esc_url( B3OB_PLUGIN_URL . 'includes/download.php?file=default-email-template.html&sentby=b3' ), esc_html__( 'Download template', 'b3-onboarding' ) ); ?>
                    </p>
                </div>
            <?php } ?>
        
            <?php b3_get_submit_button( esc_attr__( 'Save templates', 'b3-onboarding' ) ); ?>
        </form>

        <?php
        $result = ob_get_clean();

        return $result;
    }
