<?php

    #####################
    ## General filters ##
    #####################

    /**
     * Filter main logo
     *
     * @since 2.0.0
     *
     * @param $logo
     *
     * @return false|string
     */
    function b3_main_logo( $logo ) {

        $logo = B3_PLUGIN_URL . '/assets/images/logo-salesforce.png';

        return $logo;
    }
    // add_filter( 'b3_main_logo', 'b3_main_logo' );
