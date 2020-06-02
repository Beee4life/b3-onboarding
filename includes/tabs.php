<?php

    /**
     * Get tab content
     *
     * @param $tab
     *
     * @return string
     */
    include( 'tabs/tab-emails.php' );
    include( 'tabs/tab-integrations.php' );
    include( 'tabs/tab-loginpage.php' );
    include( 'tabs/tab-pages.php' );
    include( 'tabs/tab-registration.php' );
    include( 'tabs/tab-settings.php' );
    include( 'tabs/tab-users.php' );

    function b3_render_tab_content( $tab ) {
        $content = '';
        switch( $tab ) {
            case 'settings':
                $content = b3_render_settings_tab();
                break;
            case 'pages':
                $content = b3_render_pages_tab();
                break;
            case 'emails':
                $content = b3_render_emails_tab();
                break;
            case 'registration':
                $content = b3_render_registration_tab();
                break;
            case 'loginpage':
                $content = b3_render_loginpage_tab();
                break;
            case 'users':
                $content = b3_render_users_tab();
                break;
            case 'integrations':
                $content = b3_render_integrations_tab();
                break;
            case 'debug':
                $content = b3_render_debug_tab();
                break;
        }

        return $content;
    }


    /**
     * Render debug page
     *
     * @return false|string
     */
    function b3_render_debug_tab() {

        ob_start();
        include( 'debug-info.php' );
        $result = ob_get_clean();
        // @TODO: output $result as json

        return $result;
    }
