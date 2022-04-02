<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $input = '<input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/>';
    $label = htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) );
    echo sprintf( '<div class="b3_form-element b3_form-element--privacy"><label class="b3_form-label">%s</label> %s</div>', $label, $input );
