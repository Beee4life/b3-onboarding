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
        $attachment_id = 'your_attachement_id';
        if ( $attachment_id && get_post( $attachment_id ) ) {
            $image_array = wp_get_attachment_image_src( $attachment_id, 'medium' );
            if ( isset( $image_array[0] ) ) {
                $logo = $image_array[0];
            }
        }
        
        return $logo;
    }
    add_filter( 'b3_main_logo', 'b3_main_logo_example' );


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
    add_filter( 'b3_link_color', 'b3_link_color_example' );


    /**
     * Add links to sidebar widget
     *
     * @param $links
     *
     * @return array|string[][]
     */
    function b3_widget_links_example( $links ) {
        $links[] = [
            'link' => 'https://your-link.com',
            'label' => 'Your Label',
        ];

        return $links;

    }
    add_filter( 'b3_widget_links', 'b3_widget_links_example' );
    
    
    /**
     * Filter to show email widget when localhost is inactive
     *
     * @param $setting
     *
     * @return bool
     */
    function b3_show_email_widget_example( $setting ) {
        return true;
    }
    add_filter( 'b3_show_email_widget', 'b3_show_email_widget_example' );
