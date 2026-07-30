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

    function b3_render_tab_content( $tab ) {
        $content = '';
        switch( $tab ) {
            case 'emails':
                include 'tab-emails.php';
                $content = b3_render_emails_tab();
                break;
            case 'pages':
                include 'tab-pages.php';
                $content = b3_render_pages_tab();
                break;
            case 'recaptcha':
                include 'tab-recaptcha.php';
                $content = b3_render_recaptcha_tab();
                break;
            case 'registration':
                include 'tab-registration.php';
                $content = b3_render_registration_tab();
                break;
            case 'settings':
                include 'tab-settings.php';
                $content = b3_render_settings_tab();
                break;
            case 'styling':
                include 'tab-styling.php';
                $content = b3_render_template_tab();
                break;
            case 'users':
                include 'tab-users.php';
                $content = b3_render_users_tab();
                break;
            default:
        }

        return $content;
    }
