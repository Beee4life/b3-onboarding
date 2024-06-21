<?php
    include 'filters-b3.php';
    include 'filters-wp.php';
    
    if ( get_option( 'b3_activate_filter_validation' ) ) {
        include 'includes/verify-filters.php';
    }
