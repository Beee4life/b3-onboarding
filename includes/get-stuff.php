<?php
    /**
     * Return register ID page (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_register_id( $return_link = false ) {
        $id = get_option( 'b3_register_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_registration_url();
        }
        
        return $id;
        
    }
    
    
    /**
     * Return login page id
     *
     * @return bool|string
     */
    function b3_get_login_id( $return_link = false ) {
        $id = get_option( 'b3_login_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_login_url();
        }
        
        return $id;
        
    }
    
    
    /**
     * Get page id for account page
     *
     * @return mixed
     */
    function b3_get_account_id( $return_link = false ) {
        $id = get_option( 'b3_account_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_permalink( $id );
            }
        }
        
        return $id;
        
    }
    
    
    /**
     * Return forgot pass page id (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_forgotpass_id( $return_link = false ) {
        $id = get_option( 'b3_forgotpass_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_lostpassword_url();
        }
    
        return $id;
        
    }
    
    /**
     * Return reset pass page id (for current language if WPML is active)
     *
     * @return bool|string
     */
    function b3_get_resetpass_id( $return_link = false ) {
        $id = get_option( 'b3_resetpass_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    function b3_get_user_approval_id() {
        $id = get_option( 'b3_approval_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
        }
        
        return $id;
        
    }
    
    
    /**
     * Returns current url
     *
     * @return string
     */
    function b3_get_current_url() {
        // @TODO: look into these
        $url = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce', 'reauth', 'login', 'updated' ) );
        
        return $url;
    }
    
    
    /**
     * Returns used protocol
     *
     * @return string
     */
    function b3_get_protocol() {
        $protocol = ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) ? 'https' : 'http';
        
        return $protocol;
    }
    
