<?php
    /**
     * Get tab content
     *
     * @since 1.0.0
     *
     * @param $tab
     *
     * @return string
     */
    
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    include 'tab-emails.php';
    include 'tab-recaptcha.php';
    include 'tab-pages.php';
    include 'tab-registration.php';
    include 'tab-settings.php';
    include 'tab-template.php';
    include 'tab-users.php';

    function b3_render_tab_content( $tab ) {
        $content = '';
        switch( $tab ) {
            case 'emails':
                $content = b3_render_emails_tab();
                break;
            case 'pages':
                $content = b3_render_pages_tab();
                break;
            case 'recaptcha':
                $content = b3_render_recaptcha_tab();
                break;
            case 'registration':
                $content = b3_render_registration_tab();
                break;
            case 'settings':
                $content = b3_render_settings_tab();
                break;
            case 'template':
                $content = b3_render_template_tab();
                break;
            case 'users':
                $content = b3_render_users_tab();
                break;
            default:
        }

        return $content;
    }
