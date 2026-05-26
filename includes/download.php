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
                global $wp_filesystem;
                if ( empty( $wp_filesystem ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    WP_Filesystem();
                }

                header( "Content-Type: application/octet-stream" );
                header( "Content-Disposition: attachment; filename={$file_name}" );

                echo wp_kses_post( $wp_filesystem->get_contents( $file_name ) );
                exit; // It is good practice to exit after a file download
            }
        }
    }
