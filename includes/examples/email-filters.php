<?php

    ###################
    ## Email filters ##
    ###################

    /**
     * Filters the sender email address
     *
     * @since 2.0.0
     *
     * @param $email
     *
     * @return string
     */
    function b3_notification_sender_email_example( $email ) {
        $email = 'info@xx.cc';

        return $email;
    }
    // add_filter( 'b3_notification_sender_email', 'b3_notification_sender_email_example' );


    /**
     * Filters the email address which will be notified
     *
     * @since 2.0.0
     *
     * @param $recipients
     *
     * @return string
     */
    function b3_new_user_notification_addresses_example( $recipients ) {

        $email = 'info@address1.com, info@address1.com';

        return $email;
    }
    // add_filter( 'b3_new_user_notification_addresses', 'b3_new_user_notification_addresses_example' );


    /**
     * Filters the email footer text
     *
     * @since 2.0.0
     *
     * @param $footer_text
     *
     * @return string
     */
    function b3_email_footer_text_example( $footer_text ) {

        $footer_text = 'My footer text';

        return $footer_text;
    }
    // add_filter( 'b3_email_footer_text', 'b3_email_footer_text_example' );


    /**
     * Filters who to inform upon custom registration (non WP forms)
     *
     * @since 2.0.0
     *
     * @param $inform
     *
     * @return string
     */
    function b3_custom_register_inform_example( $inform ) {
        return 'none';
    }
    // add_filter( 'b3_custom_register_inform', 'b3_custom_register_inform_example' );


    /**
     * Filters account approved subject (user)
     *
     * @param $subject
     *
     * @return string
     *@since 2.0.0
     *
     */
    function b3_account_approved_subject_example( $subject ) {
        return 'Filter subject - Account approved for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_account_approved_subject', 'b3_account_approved_subject_example' );


    /**
     * Filters account approved message (user)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_account_approved_message_example( $message ) {
        return 'Filter message - <a href="#">Account</a> approved';
    }
    // add_filter( 'b3_account_approved_message', 'b3_account_approved_message_example' );


    /**
     * Filters account activated subject (user)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_account_activated_subject_user_example( $subject ) {
        return 'Filter subject - Account activated for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_account_activated_subject_user', 'b3_account_activated_subject_user_example' );


    /**
     * Filters account activated message (user)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_account_activated_message_user_example( $message ) {
        return 'Filter message - <a href="#">Account</a> activated';
    }
    // add_filter( 'b3_account_activated_message_user', 'b3_account_activated_message_user_example' );


    /**
     * Filters account rejected subject (user)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_account_rejected_subject_example( $subject ) {
        return 'Filter subject - Account rejected for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_account_rejected_subject', 'b3_account_rejected_subject_example' );


    /**
     * Filters account rejected message (user)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_account_rejected_message_example( $message ) {
        return 'Filter message - <a href="#">Account</a> rejected';
    }
    // add_filter( 'b3_account_rejected_message', 'b3_account_rejected_message_example' );


    /**
     * Filters email activation subject (user)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_email_activation_subject_user_example( $subject ) {
        return 'Filter subject - Confirm email for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_email_activation_subject_user', 'b3_email_activation_subject_user_example' );


    /**
     * Filters email activation message (user)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_email_activation_message_user_example( $message ) {
        return 'Filter message - Activate your <a href="#">email</a>';
    }
    // add_filter( 'b3_email_activation_message_user', 'b3_email_activation_message_user_example' );


    /**
     * Filters lost password subject
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_lost_password_subject_example( $subject ) {
        return 'Filter subject - Password reset for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_lost_password_subject', 'b3_lost_password_subject_example' );


    /**
     * Filters lost password message
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_password_lost_message_example( $message ) {
        return 'Filter message - Password <a href="#">reset</a>';
    }
    // add_filter( 'b3_password_lost_message', 'b3_password_lost_message_example' );


    /**
     * Filters new user subject (admin)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_new_user_subject_example( $subject ) {
        return 'Filter subject - New user for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_new_user_subject', 'b3_new_user_subject_example' );


    /**
     * Filters new user message (admin)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_new_user_message_example( $message ) {
        return 'Filter message - New <a href="#">user</a>';
    }
    // add_filter( 'b3_new_user_message', 'b3_new_user_message_example' );


    /**
     * Filters request access subject (admin)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_request_access_subject_admin_example( $subject ) {
        return 'Filter subject - request access for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_request_access_subject_admin', 'b3_request_access_subject_admin_example' );


    /**
     * Filters request access message (admin)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_request_access_message_admin_example( $message ) {
        return 'Filter message - request <a href="#">access</a>';
    }
    // add_filter( 'b3_request_access_message_admin', 'b3_request_access_message_admin_example' );

    /**
     * Filters request access subject (user)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_request_access_subject_user_example( $subject ) {
        return 'Filter subject - request access for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_request_access_subject_user', 'b3_request_access_subject_user_example' );

    /**
     * Filters request access message (admin)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_request_access_message_user_example( $message ) {
        return esc_html__( 'You have successfully requested access. Someone will check your request.', 'b3-onboarding' );
    }
    // add_filter( 'b3_request_access_message_user', 'b3_request_access_message_user_example' );


    /**
     * Filters welcome user subject (user)
     *
     * @since 2.0.0
     *
     * @param $subject
     *
     * @return string
     */
    function b3_welcome_user_subject_example( $subject ) {
        return 'Filter subject - welcome user for %blog_name% %user_login% %first_name%';
    }
    // add_filter( 'b3_welcome_user_subject', 'b3_welcome_user_subject_example' );


    /**
     * Filters welcome user message (user)
     *
     * @since 2.0.0
     *
     * @param $message
     *
     * @return string
     */
    function b3_welcome_user_message_example( $message ) {
        return 'Filter message - welcome user';
    }
    // add_filter( 'b3_welcome_user_message', 'b3_welcome_user_message_example' );
