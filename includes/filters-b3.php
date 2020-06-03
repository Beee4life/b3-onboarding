<?php
    /**
     * Filter email logo
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_logo( $logo ) {

        $logo = B3_PLUGIN_URL . '/assets/images/logo-salesforce.png';

        return $logo;
    }
    // add_filter( 'b3_email_logo', 'b3_email_logo' );

    /**
     * Filter email footer text
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_footer_text( $footer_text ) {

        $footer_text = 'Some test text with a <a href="https://nu.nl">LINK</a>.';

        return $footer_text;
    }
    // add_filter( 'b3_email_footer_text', 'b3_email_footer_text' );

    /**
     * Override link color in email
     *
     * @param $link_color
     *
     * @return string
     */
    function b3_email_link_color( $link_color ) {

        $link_color = '6d32a8'; // purple

        return $link_color;
    }
    // add_filter( 'b3_email_link_color', 'b3_email_link_color' );

    /**
     * Add hidden fields to form
     *
     * @param $fields
     *
     * @return array
     */
    function b3_filter_hidden_fields_values( $fields ) {
        $fields[ 'field_id' ] = 'field_value';

        return $fields;
    }
    // add_filter( 'b3_filter_hidden_fields_values', 'b3_filter_hidden_fields_values' );
