<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $allowed_tags = [
        'input' => [
            'name'  => [],
            'type'  => [],
            'id'    => [],
            'value' => [],
        ],
    ];

    $input = '<input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/>';
    $label = htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) );
    ?>
    <div class="b3_form-element b3_form-element--checkbox b3_form-element--privacy">
        <label for="b3_privacy_accept" class="b3_form-label"><?php echo esc_html( $label ); ?></label>
        <?php echo wp_kses( $input, $allowed_tags ); ?>
    </div>

