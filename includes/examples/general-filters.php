<?php

    #####################
    ## General filters ##
    #####################

    /**
     * Filter main logo
     *
     * Must be full URL.
     *
     * @since 2.0.0
     *
     * @param $logo
     *
     * @return false|string
     */
    function b3_main_logo( $logo ) {
        return 'http://your-url.com/assets/images/logo-salesforce.png';
    }
    // add_filter( 'b3_main_logo', 'b3_main_logo' );


    /**
     * Override main link color - WITH HASHTAG
     *
     * @since 2.0.0
     *
     * @param $link_color
     *
     * @return string
     */
    function b3_link_color( $link_color ) {
        return '#6d32a8'; // purple
    }
    // add_filter( 'b3_link_color', 'b3_link_color' );
