<?php
    /**
     * Redirect to custom login page after the user has been logged out.
     *
     * @since 1.0.6
     */
    function b3_redirect_after_logout() {
        $redirect_url = add_query_arg( 'logout', 'true', b3_get_login_url() );
        wp_safe_redirect( $redirect_url );
        exit;
    }
    add_action( 'wp_logout', 'b3_redirect_after_logout' );


