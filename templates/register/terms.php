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

    $input = '<input name="b3_terms_accept" type="checkbox" id="b3_terms_accept" value="1"/>';
    $label = htmlspecialchars_decode( apply_filters( 'b3_terms_text', b3_get_terms_text() ) );
    ?>
    <div class="b3_form-element b3_form-element--checkbox b3_form-element--terms">
        <label for="b3_terms_accept" class="b3_form-label"><?php echo $label; ?></label>
        <?php echo wp_kses( $input, $allowed_tags ); ?>
    </div>

