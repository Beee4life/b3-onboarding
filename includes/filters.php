<?php
    
    /**
     * Filter lost password URL
     *
     * @param $lostpassword_url
     * @param $redirect
     *
     * @return false|mixed|string
     */
    function b3_lost_password_page_url( $lostpassword_url, $redirect ) {
        
        $lost_password_page_id = get_option( 'b3_forgotpass_page_id' );
        if ( false != $lost_password_page_id ) {
            $lost_pass_url = get_permalink( $lost_password_page_id );
            if ( class_exists( 'SitePress' ) ) {
                $lost_pass_url = apply_filters( 'wpml_object_id', $lost_password_page_id, 'page', true );
            }
            if ( false != $redirect ) {
                return $lost_pass_url . '?redirect_to=' . $redirect;
            }
            
            return $lost_pass_url;
            
        }
        
        return $lostpassword_url;
    }
    add_filter( 'lostpassword_url', 'b3_lost_password_page_url', 10, 2 );
