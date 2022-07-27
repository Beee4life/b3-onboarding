<?php
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

		// @TODO: sanitize file name
		$file_name = $_GET[ 'file' ];

		if ( file_exists( $file_name ) && is_readable( $file_name ) && in_array( $file_name, $allowed_files ) ) {
			header( "Content-Type: application/octet-stream" );
			header( "Content-Disposition: attachment; filename={$file_name}" );
			readfile( $file_name );
		}
    }
