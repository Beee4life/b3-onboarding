<?php
    /**
     *
     */
    function b3_get_settings_field_open( $hide = false ) {
        $hide_class = ( $hide != false ) ? ' hidden' : false;
        echo '<div class="b3_settings-field' . $hide_class . '">';
    }

    /**
     *
     */
    function b3_get_label_field_open() {
        echo '<div class="b3_settings-label">';
    }

    /**
     * This function is not really needed, but it prevents PhpStorm from throwing a ton of errors
     */
    function b3_get_close() {
        echo '</div>';
    }
