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
        
        $lost_password_page_id = home_url( 'lostpassword' );
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
    // add_filter( 'lostpassword_url', 'b3_lost_password_page_url', 10, 2 );
    
    
    /**
     * Returns the message body for the password reset mail.
     * Called through the retrieve_password_message filter.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    function b3_replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        $key = get_password_reset_key( $user_data );
    
        // Create new message
        $msg = __( 'Hello!', 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'b3-onboarding' ), $user_login ) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password to something you\'d like, visit the following address:', 'b3-onboarding' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=" . $key . "&login=" . rawurlencode( $user_data->user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'b3-onboarding' ) . "\r\n";
        
        return $msg;
    }
    add_filter( 'retrieve_password_message', 'b3_replace_retrieve_password_message', 10, 4 );

