<?php
    // @TODO: maybe merge with functions (or function into here)

    /**
     * Get register page id/link
     *
     * @since 1.0.6
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
                return get_the_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_registration_url();
        }

        return $id;

    }


    /**
     * Get login page id/link
     *
     * @since 1.0.6
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
                return get_the_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_login_url();
        }

        return $id;

    }


    /**
     * Get logout page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_logout_id( $return_link = false ) {
        $id = get_option( 'b3_logout_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_the_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_logout_url();
        }

        return $id;

    }


    /**
     * Get account page page id/link
     *
     * @since 1.0.6
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
                return get_the_permalink( $id );
            }
        }

        return $id;

    }


    /**
     * Get forgot pass page id/link
     *
     * @since 1.0.6
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
                return get_the_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            return wp_lostpassword_url();
        }

        return $id;

    }

    /**
     * Get reset pass page id/link
     *
     * @since 1.0.6
     *
     * @return bool|string
     */
    function b3_get_resetpass_id( $return_link = false ) {
        $id = get_option( 'b3_resetpass_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_the_permalink( $id );
            }
        } elseif ( true == $return_link ) {
            // @TODO: add wp page
        }

        return $id;

    }


    /**
     * Get account page id/link
     *
     * @since 1.0.6
     *
     * @param bool $return_link
     *
     * @return bool|mixed|void
     */
    function b3_get_user_approval_id( $return_link = false ) {
        $id = get_option( 'b3_approval_page_id', false );
        if ( false != $id && get_post( $id ) ) {
            if ( class_exists( 'Sitepress' ) ) {
                $id = apply_filters( 'wpml_object_id', $id, 'page', true );
            }
            if ( true == $return_link ) {
                return get_the_permalink( $id );
            }
        }

        return $id;

    }


    /**
     * Get current url
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_get_current_url() {
        // @TODO: look into these
        $url = remove_query_arg( array( 'instance', 'action', 'checkemail', 'error', 'loggedout', 'registered', 'redirect_to', 'updated', 'key', '_wpnonce', 'reauth', 'login', 'updated' ) );

        return $url;
    }


    /**
     * Get current protocol
     *
     * @since 1.0.6
     *
     * @return string
     */
    function b3_get_protocol() {
        $protocol = ( isset( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) ? 'https' : 'http';

        return $protocol;
    }

