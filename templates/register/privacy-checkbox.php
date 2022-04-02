<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    do_action( 'b3_do_before_privacy_checkbox' );
    $input = '<input name="b3_privacy_accept" type="checkbox" id="b3_privacy_accept" value="1"/>';
    $label = htmlspecialchars_decode( apply_filters( 'b3_privacy_text', b3_get_privacy_text() ) );
    echo sprintf( '<div class="b3_form-element b3_form-element--privacy"><label class="b3_form-label">%s</label> %s</div>', $label, $input );
    do_action( 'b3_do_after_privacy_checkbox' );
