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
        $fields           = [];
        $stored_email_styling  = get_option( 'b3_email_styling' );
        $stored_email_template = get_option( 'b3_email_template' );

        if ( get_option( 'b3_activate_custom_emails' ) ) {
			$fields[] = [
				'id'          => esc_attr( 'email_template' ),
				'title'       => esc_html__( 'Email template', 'b3-onboarding' ),
				'placeholder' => esc_attr( b3_default_email_template() ),
				'preview'     => esc_attr( 'template' ),
				'value'       => $stored_email_template,
				'file_name'   => 'default-email-template.html',
			];
			$fields[] = [
				'id'          => esc_attr( 'email_styling' ),
				'title'       => esc_html__( 'Email styling', 'b3-onboarding' ),
				'placeholder' => b3_default_email_styling( apply_filters( 'b3_link_color', b3_get_link_color() ) ),
				'preview'     => esc_attr( 'styling' ),
				'value'       => $stored_email_styling,
				'file_name'   => 'default-email-styling.css',
			];
		}

        ob_start();
        ?>
        <form action="admin.php?page=b3-onboarding&tab=template" method="post">
            <input name="b3_template_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-template-nonce' ); ?>">

            <?php foreach( $fields as $field ) { ?>
                <div class="template_box">
                    <label for="b3__input--<?php echo $field['id']; ?>"><?php echo $field['title']; ?></label>
                    <textarea id="b3__input--<?php echo $field['id']; ?>" name="b3_<?php echo $field['id']; ?>" rows="6"><?php echo $field[ 'value' ]; ?></textarea>
                    <p>
						<?php echo b3_get_preview_link( $field['preview'] ); ?> <small>(<?php esc_html_e( 'opens in new window', 'b3-onboarding' ); ?>)</small>
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
