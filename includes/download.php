<?php
    /**
     * Function to save a file
     *
     * @url: https://stackoverflow.com/questions/11664402/save-file-onclick
     */
    if ( isset( $_GET[ 'file' ] ) ) {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$_GET[ 'file' ]}");
        readfile($_GET[ 'file' ]);
    }
