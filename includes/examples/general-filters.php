<?php

    #####################
    ## General filters ##
    #####################

    /**
     * Filter main logo
     *
     * Must be full URL.
     *
     * @since 2.0.0
     *
     * @param $logo
     *
     * @return false|string
     */
    function b3_main_logo_example( $logo ) {
        return 'http://your-url.com/assets/images/logo-salesforce.png';
    }
    // add_filter( 'b3_main_logo', 'b3_main_logo_example' );


    /**
     * Override main link color - WITH HASHTAG
     *
     * @since 2.0.0
     *
     * @param $link_color
     *
     * @return string
     */
    function b3_link_color_example( $link_color ) {
        return '#6d32a8'; // purple
    }
    // add_filter( 'b3_link_color', 'b3_link_color_example' );


    /**
     * Add links to sidebar widget
     *
     * @param $links
     *
     * @return array|string[][]
     */
    function b3_widget_links_example( $links ) {

        $new_links = [
            [
                'link' => 'https://your-link.com',
                'label' => 'Your Label',
            ],
        ];

        if ( is_array( $links ) && ! empty( $links ) ) {
            $links = array_merge( $links, $new_links );
        } else {
            $links = $new_links;
        }

        return $links;

    }
    // add_filter( 'b3_widget_links', 'b3_widget_links_example' );
