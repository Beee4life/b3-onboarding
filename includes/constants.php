<?php
    
    if ( ! defined( 'B3_REGISTER' ) ) {
        
        $page = get_option( 'b3_register_id' );
        if ( false != $page && get_post( $page ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                define( 'B3_REGISTER', apply_filters( 'wpml_object_id', $page, 'page', true ) );
            } else {
                define( 'B3_REGISTER', $page );
            }
    
        }
    }
    
    if ( ! defined( 'B3_LOGIN' ) ) {
        
        $page = get_option( 'b3_login_id' );
        if ( false != $page && get_post( $page ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                define( 'B3_LOGIN', apply_filters( 'wpml_object_id', $page, 'page', true ) );
            } else {
                define( 'B3_LOGIN', $page );
            }
        }
    }
    
    if ( ! defined( 'B3_FORGOTPASS' ) ) {
        
        $page = get_option( 'b3_forgotpass_id' );
        if ( false != $page && get_post( $page ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                define( 'B3_FORGOTPASS', apply_filters( 'wpml_object_id', $page, 'page', true ) );
            } else {
                define( 'B3_FORGOTPASS', $page );
            }
        }
    }
    
    if ( ! defined( 'B3_RESETPASS' ) ) {
        
        $page = get_option( 'b3_resetpass_id' );
        if ( false != $page && get_post( $page ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                define( 'B3_RESETPASS', apply_filters( 'wpml_object_id', $page, 'page', true ) );
            } else {
                define( 'B3_RESETPASS', $page );
            }
        }
    }
    
    if ( ! defined( 'B3_ACCOUNT' ) ) {
        
        $page = get_option( 'b3_account_id' );
        if ( false != $page && get_post( $page ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                define( 'B3_ACCOUNT', apply_filters( 'wpml_object_id', $page, 'page', true ) );
            } else {
                define( 'B3_ACCOUNT', $page );
            }
        }
    }
