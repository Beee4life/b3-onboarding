<?php
    
    /**
     * Add help tabs
     *
     * @param $old_help  string
     * @param $screen_id int
     * @param $screen    object
     */
    function b3_help_tabs( $old_help, $screen_id, $screen ) {
        
        // echo '<pre>'; var_dump($screen_id); echo '</pre>'; exit;
        
        $screen_array = array(
            'toplevel_page_b3-onboarding',
        );
        if ( ! in_array( $screen_id, $screen_array ) ) {
            return false;
        }
        
        if ( 'toplevel_page_b3-onboarding' == $screen_id ) {
            $screen->add_help_tab( array(
                'id'      => 'b3-email-vars',
                'title'   => esc_html__( 'Email variables', 'b3-onboarding' ),
                'content' =>
                    '<h3>Available email variables</h3>
					<p>' . esc_html__( 'These are the available variables in emails.', 'b3-onboarding' ) . '</p>
					<ul>
					<li>%blog_name% = ' . get_option( 'blogname' ) . '</li>
					<li>%email_styling%</li>
					<li>%home_url% = ' . get_home_url() . '</li>
					<li>%registration_date% (only available in admin notification)</li>
					<li>%reset_url% (only available in reset password email)</li>
					<li>%user_ip% (only available in admin notification)</li>
					<li>%user_login%</li>
					</ul>
					'
            ) );
        }
        
        get_current_screen()->set_help_sidebar(
            '<p><strong>' . esc_html__( 'Author', 'b3-onboarding' ) . '</strong></p>
			<p><a href="http://www.berryplasman.com?utm_source=' . $_SERVER[ 'SERVER_NAME' ] . '&utm_medium=onboarding_admin&utm_campaign=free_promo">berryplasman.com</a></p>'
        );
        
        return $old_help;
    }
    add_filter( 'contextual_help', 'b3_help_tabs', 5, 3 );
