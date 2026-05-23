<?php
    if ( ! defined( 'ABSPATH' ) ) exit;

    /**
     * Function to save a file
     *
     * @url: https://stackoverflow.com/questions/11664402/save-file-onclick
     */
    if ( isset( $_GET[ 'sentby' ] ) && isset( $_GET[ 'file' ] ) ) {
        $allowed_files = [
            'default-email-styling.css',
            'default-email-template.html',
        ];

        if ( in_array( sanitize_file_name( wp_unslash( $_GET[ 'file' ] ) ), $allowed_files ) ) {
            $file_name = sanitize_file_name( wp_unslash( $_GET[ 'file' ] ) );

            if ( file_exists( $file_name ) && is_readable( $file_name ) ) {
                header( "Content-Type: application/octet-stream" );
                header( "Content-Disposition: attachment; filename={$file_name}" );
                readfile( $file_name );
            }
        }
    }
