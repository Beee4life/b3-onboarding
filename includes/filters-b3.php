<?php
    /**
     * Filter email logo
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_logo( $logo ) {

        $logo = b3_default_email_footer();

        return $logo;
    }
    add_filter( 'b3_email_logo', 'b3_email_logo' );

    /**
     * Filter email footer text
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_footer_text( $footer_text ) {

        $footer_text = b3_default_email_footer();

        return $footer_text;
    }
    add_filter( 'b3_email_footer_text', 'b3_email_footer_text' );
