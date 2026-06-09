<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $input = '<input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/>';
    $label = htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) );
    ?>
    <div class="b3_form-element b3_form-element--privacy">
        <label for="b3_privacy_accept" class="b3_form-label"><?php echo $label; ?></label>
        <?php
            $allowed = [
                'input' => [
                    'name'  => [],
                    'type'  => [],
                    'id'    => [],
                    'value' => [],
                ],
            ];
            echo wp_kses( $input, $allowed );
        ?>
    </div>

