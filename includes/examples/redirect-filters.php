<?php

    ######################
    ## Redirect filters ##
    ######################

    /**
     * Redirect after user register
     *
     * @since 2.0.0
     *
     * @param $url
     *
     * @return string
     */
    function b3_redirect_after_register( $url ) {

        $login_url = b3_get_login_url();
        if ( false != $login_url ) {
            $url = add_query_arg( 'registered', 'success', $login_url );
        }

        return $url;
    }
    // add_filter( 'b3_redirect_after_register', 'b3_redirect_after_register' );
