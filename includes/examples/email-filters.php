<?php

    ###################
    ## Email filters ##
    ###################

    function b3_notification_sender_email( $email ) {

        $email = 'info@xx';

        return $email;
    }
    // add_filter( 'b3_notification_sender_email', 'b3_notification_sender_email' );

    /**
     * Filter email footer text
     *
     * @since 2.0.0
     *
     * @param $footer_text
     *
     * @return false|string
     */
    function b3_email_footer_text( $footer_text ) {

        $footer_text = 'My footer text';

        return $footer_text;
    }
    add_filter( 'b3_email_footer_text', 'b3_email_footer_text' );

    /**
     * Set who to inform after (custom) user register
     *
     * @since 2.0.0
     *
     * @param $inform
     *
     * @return string
     */
    function b3_custom_register_inform( $inform ) {
        return 'none';
    }
    // add_filter( 'b3_custom_register_inform', 'b3_custom_register_inform' );

    /**
     * Overrides subject in 'Account approved' subject (user)
     *
     * @since 2.0.0
     *
     * @return string
     */
    function b3_account_approved_subject( $subject ) {
        return 'Filter subject - Account approved';
    }
    // add_filter( 'b3_account_approved_subject', 'b3_account_approved_subject' );

    function b3_account_approved_message( $message ) {
        return 'Filter message - <a href="#">Account</a> approved';
    }
    // add_filter( 'b3_account_approved_message', 'b3_account_approved_message' );

    function b3_account_activated_subject_user( $subject ) {
        return 'Filter subject - Account activated';
    }
    // add_filter( 'b3_account_activated_subject_user', 'b3_account_activated_subject_user' );

    function b3_account_activated_message_user( $message ) {
        return 'Filter message - <a href="#">Account</a> activated';
    }
    // add_filter( 'b3_account_activated_message_user', 'b3_account_activated_message_user' );

    function b3_account_rejected_subject( $subject ) {
        return 'Filter subject - Account rejected';
    }
    // add_filter( 'b3_account_rejected_subject', 'b3_account_rejected_subject' );

    function b3_account_rejected_message( $message ) {
        return 'Filter message - <a href="#">Account</a> rejected';
    }
    // add_filter( 'b3_account_rejected_message', 'b3_account_rejected_message' );

    function b3_email_activation_subject_user( $subject ) {
        return 'Filter subject - Confirm email';
    }
    // add_filter( 'b3_email_activation_subject_user', 'b3_email_activation_subject_user' );

    function b3_email_activation_message_user( $message ) {
        return 'Filter message - Activate your <a href="#">email</a>';
    }
    // add_filter( 'b3_email_activation_message_user', 'b3_email_activation_message_user' );

    function b3_password_reset_subject( $subject ) {
        return 'Filter subject - Password reset';
    }
    // add_filter( 'b3_password_reset_subject', 'b3_password_reset_subject' );

    function b3_password_reset_message( $message ) {
        return 'Filter message - Password <a href="#">reset</a>';
    }
    // add_filter( 'b3_password_reset_message', 'b3_password_reset_message' );

    function b3_new_user_subject( $subject ) {
        return 'Filter subject - New user';
    }
    // add_filter( 'b3_new_user_subject', 'b3_new_user_subject' );

    function b3_new_user_message( $message ) {
        return 'Filter message - New <a href="#">user</a>';
    }
    // add_filter( 'b3_new_user_message', 'b3_new_user_message' );

    function b3_request_access_subject_admin( $subject ) {
        return 'Filter subject - request access';
    }
    // add_filter( 'b3_request_access_subject_admin', 'b3_request_access_subject_admin' );

    function b3_request_access_message_admin( $message ) {
        return 'Filter message - request <a href="#">access</a>';
    }
    // add_filter( 'b3_request_access_message_admin', 'b3_request_access_message_admin' );

    function b3_request_access_subject_user( $subject ) {
        return 'Filter subject - request access';
    }
    // add_filter( 'b3_request_access_subject_user', 'b3_request_access_subject_user' );

    function b3_request_access_message_user( $message ) {
        return '%activation_url%';
    }
    // add_filter( 'b3_request_access_message_user', 'b3_request_access_message_user' );

    function b3_welcome_user_subject( $subject ) {
        return 'Filter subject - welcome user';
    }
    // add_filter( 'b3_welcome_user_subject', 'b3_welcome_user_subject' );

    function b3_welcome_user_message( $message ) {
        return 'Filter message - welcome user';
    }
    // add_filter( 'b3_welcome_user_message', 'b3_welcome_user_message' );
