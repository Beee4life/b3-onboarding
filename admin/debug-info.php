<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="b3-debug">
    <div class="b3-debug-left">
        <div class="b3-debug-header">SERVER INFO</div>
        <table class="b3_table b3_table--debug">
            <tr>
                <td>Operating system</td>
                <td><?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></td>
            </tr>
            <tr>
                <td>PHP</td>
                <td><?php echo phpversion(); ?></td>
            </tr>
            <tr>
                <td>IP</td>
                <td><?php echo $_SERVER[ 'SERVER_ADDR' ]; ?></td>
            </tr>
            <tr>
                <td>Scheme</td>
                <td><?php echo $_SERVER[ 'REQUEST_SCHEME' ]; ?></td>
            </tr>
            <tr>
                <td>Home path</td>
                <td><?php echo get_home_path(); ?></td>
            </tr>
        </table>

        <div class="b3-debug-header">WP INFO</div>
        <table class="b3_table b3_table--debug">
            <tr>
                <td>WP version</td>
                <td><?php echo get_bloginfo( 'version' ); ?></td>
            </tr>
            <tr>
                <td>Home url</td>
                <td><?php echo get_home_url(); ?></td>
            </tr>
            <tr>
                <td>Blog public</td>
                <td><?php echo get_option( 'blog_public' ); ?></td>
            </tr>
            <tr>
                <td>Users can register</td>
                <td><?php echo get_option( 'users_can_register' ); ?></td>
            </tr>
            <tr>
                <td>Page on front</td>
                <td><?php echo get_option( 'page_on_front' ); ?></td>
            </tr>
            <tr>
                <td>Charset</td>
                <td><?php echo get_bloginfo( 'charset' ); ?></td>
            </tr>
            <tr>
                <td>Text direction</td>
                <td><?php echo is_rtl() ? 'RTL' : 'LTR'; ?></td>
            </tr>
            <tr>
                <td>Language</td>
                <td><?php echo get_bloginfo( 'language' ); ?></td>
            </tr>
        </table>

        <?php if ( is_multisite() ) { ?>
            <div class="b3-debug-header">WPMU</div>
            <table class="b3_table b3_table--debug">
                <tr>
                    <td>Main site</td>
                    <td><?php echo ( is_main_site() ) ? __( 'Yes', 'b3-onboarding' ) : __( 'No', 'b3-onboarding' ); ?> </td>
                </tr>
                <tr>
                    <td>Main registration</td>
                    <td><?php echo ( get_site_option( 'registration' ) ) ? : false; ?> </td>
                </tr>
            </table>
        <?php } ?>

        <div class="b3-debug-header">THEME INFO</div>
        <table class="b3_table b3_table--debug">
            <tr>
                <td>Current theme</td>
                <td>Current theme: <?php echo get_option( 'current_theme' ); ?></td>
            </tr>
            <tr>
                <td>Stylesheet</td>
                <td>Stylesheet: <?php echo get_option( 'stylesheet' ); ?></td>
            </tr>
            <tr>
                <td>Template</td>
                <td>Template: <?php echo get_option( 'template' ); ?></td>
            </tr>
        </table>

        <div class="b3-debug-header">ACTIVE PLUGINS</div>
        <table class="b3_table b3_table--debug">
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    // echo '<pre>'; var_dump($value); echo '</pre>'; exit;
                    if ( is_plugin_active( $key ) ) {
                        echo '<tr>';
                        echo sprintf( '<td>%s</td>', $value[ 'Name' ] );
                        echo sprintf( '<td>%s</td>', $value[ 'Version' ] );
                        echo '</tr>';
                    }
                }
            ?>
        </table>

        <div class="b3-debug-header">INACTIVE PLUGINS</div>
        <table class="b3_table b3_table--debug">
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    if ( ! is_plugin_active( $key ) ) {
                        echo '<tr>';
                        echo sprintf( '<td>%s</td>', $value[ 'Name' ] );
                        echo sprintf( '<td>%s</td>', $value[ 'Version' ] );
                        echo '</tr>';
                    }
                }
            ?>
        </table>
    </div>

    <div class="b3-debug-right">
        <div class="b3-debug-header">B3 META VALUES</div>
        <table class="b3_table b3_table--debug b3_table--debug-meta">
            <?php
                $b3_values = b3_get_all_custom_meta_keys();
                foreach( $b3_values as $meta_key ) {
                    $meta_value = '';
                    $value      = get_option( $meta_key );

                    if ( is_array( $value ) ) {
                        $meta_value = 'array( ' . implode( ', ', $value ) . ' )';
                    } else {
                        if ( 'b3_email_template' == $meta_key ) {
                            $value = 'Set';
                        }
                        $meta_value = ($value) ? $value : esc_html__( 'not set', 'b3-onboarding');
                    }

                    echo '<tr>';
                    echo sprintf( '<td>%s</td>', $meta_key );
                    echo sprintf( '<td>%s</td>', $meta_value );
                    echo '</tr>';
                }
            ?>
        </table>
    </div>
</div>
