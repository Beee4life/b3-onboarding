<?php
    /**
     * Get email subject for activate wpmu user (only, no site)
     *
     * @since 2.6.0
     *
     * @return string|void
     */
    function b3_get_wpmu_activate_user_subject() {
        $subject = b3_default_wpmu_activate_user_subject();
        
        return $subject;
    }
    
    
    /**
     * Get email message for activate wpmu user (only, no site)
     *
     * @since 2.6.0
     *
     * @return string|void
     */
    function b3_get_wpmu_activate_user_message() {
        $message = b3_default_wpmu_activate_user_message();
        
        return $message;
    }
    
    
    /**
     * Get email subject for activated wpmu user (only, no site)
     *
     * @since 2.6.0
     *
     * @return string|void
     */
    function b3_get_wpmu_user_activated_subject() {
        $subject = b3_default_wpmu_user_activated_subject();
        
        return $subject;
    }
    
    
    /**
     * Get email message for activated wpmu user (only, no site)
     *
     * @since 2.6.0
     *
     * @return string|void
     */
    function b3_get_wpmu_user_activated_message() {
        $message = b3_default_wpmu_user_activated_message();
        
        return $message;
    }
    
    
    /**
     * Get welcome email subject for user + site
     *
     * @since 2.6.0
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_welcome_wpmu_user_blog_subject( $user = false ) {
        $subject = b3_default_subject_welcome_wpmu_user_blog( $user );
        
        return $subject;
    }
    
    
    /**
     * Get welcome email message for user + site
     *
     * @since 2.6.0
     *
     * @param false $user
     *
     * @return string
     */
    function b3_get_welcome_wpmu_user_blog_message( $user = false ) {
        $message = b3_default_message_welcome_wpmu_user_blog( $user );
        
        return $message;
    }
    
    
    /**
     * Get admin email message for new user
     *
     * @since 2.6.0
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
    function b3_get_new_wpmu_user_message_admin( $user = false ) {
        $message = b3_default_message_new_wpmu_user_admin( $user );
        
        return $message;
    }
