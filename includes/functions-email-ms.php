<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Get email subject for activate wpmu user (user only, no site)
     *
     * @since 3.0
     *
     * @return string|void
     */
    function b3_get_wpmu_activate_user_subject() {
        $subject = b3_default_wpmu_activate_user_subject();

        return $subject;
    }


    /**
     * Get email message for activate wpmu user (user only, no site)
     *
     * @since 3.0
     *
     * @return string|void
     */
    function b3_get_wpmu_activate_user_message() {
        $message = b3_default_wpmu_activate_user_message();

        return $message;
    }


    /**
     * Get email subject for activated wpmu user (user only, no site)
     *
     * @since 3.0
     *
     * @return string|void
     */
    function b3_get_wpmu_user_activated_subject() {
        $subject = b3_default_wpmu_user_activated_subject();

        return $subject;
    }


    /**
     * Get email message for activated wpmu user (user only, no site)
     *
     * @since 3.0
     *
     * @return string|void
     */
    function b3_get_wpmu_user_activated_message() {
        $message = b3_default_wpmu_user_activated_message();

        return $message;
    }


    /**
     * Get activate email subject for user + site
     *
     * @since 3.0
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_wpmu_activate_user_blog_subject( $user = false ) {
        $subject = get_option( 'b3_activate_wpmu_user_site_subject' );
        if ( ! $subject ) {
            $subject = b3_default_subject_new_wpmu_user_blog( $user );
        }

        return $subject;
    }


    /**
     * Get activate email message for user + site
     *
     * @since 3.0
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_wpmu_activate_user_blog_message( $user = false ) {
        $message = get_option( 'b3_activate_wpmu_user_site_message' );
        if ( ! $message ) {
            $message = b3_default_message_new_wpmu_user_blog( $user );
        }


        return $message;
    }


    /**
     * Subject for WPMU user activated
     *
     * @return false|mixed|string
     */
    function b3_get_wpmu_activated_user_blog_subject() {
        $subject = get_option( 'b3_activated_wpmu_user_site_subject' );
        if ( ! $subject ) {
            $subject = b3_default_subject_welcome_wpmu_user_blog();
        }

        return $subject;
    }


    /**
     * Message for WPMU user activated
     *
     * @param $user_login
     *
     * @return false|mixed|string
     */
    function b3_get_wpmu_activated_user_blog_message( $user_login ) {
        $message = get_option( 'b3_activated_wpmu_user_site_message' );
        if ( ! $message ) {
            $message = b3_default_message_welcome_wpmu_user_blog( $user_login );
        }

        return $message;
    }


    /**
     * Get admin email subject for new user
     *
     * @since 3.0
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_new_wpmu_user_subject_admin() {
        $message = b3_default_subject_new_wpmu_user_admin();

        return $message;
    }


    /**
     * Get admin message for new user
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_new_wpmu_user_message_admin() {
        $message = b3_default_message_new_wpmu_user_admin();

        return $message;
    }
