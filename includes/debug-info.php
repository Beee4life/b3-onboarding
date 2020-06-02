<ul class="debug-list">
    <li class="debug-list-part">
        <b>SERVER INFO</b>
        <ul>
            <li>Operating system: <?php echo $_SERVER[ 'SERVER_SOFTWARE' ]; ?></li>
            <li>PHP : <?php echo phpversion(); ?></li>
            <li>Server IP: <?php echo $_SERVER[ 'SERVER_ADDR' ]; ?></li>
            <li>Scheme: <?php echo $_SERVER[ 'REQUEST_SCHEME' ]; ?></li>
            <li>Home path: <?php echo get_home_path(); ?></li>
        </ul>
    </li>
    <li class="debug-list-part">
        <b>WP INFO</b>
        <ul>
            <li>WP version: <?php echo get_bloginfo( 'version' ); ?></li>
            <li>Home url: <?php echo get_home_url(); ?></li>
            <li>Admin email: <?php echo get_bloginfo( 'admin_email' ); ?></li>
            <li>Blog public: <?php echo get_option( 'blog_public' ); ?></li>
            <li>Users can register: <?php echo get_option( 'users_can_register' ); ?></li>
            <li>Page on front: <?php echo get_option( 'page_on_front' ); ?></li>
            <li>Charset: <?php echo get_bloginfo( 'charset' ); ?></li>
            <li>Text direction: <?php echo is_rtl() ? 'RTL' : 'LTR'; ?></li>
            <li>Language: <?php echo get_bloginfo( 'language' ); ?></li>
        </ul>
    </li>
    <?php if ( is_multisite() ) { ?>
        <li class="debug-list-part">
            <b>WPMU</b>
            <ul>
                <li>Main site: <?php echo ( is_main_site() ) ? __( 'Yes', 'b3-onboarding' ) : __( 'No', 'b3-onboarding' ); ?> </li>
                <li>Main registration: <?php echo ( get_site_option( 'registration' ) ) ? : false; ?> </li>
                <li>Subsite registration: <?php echo ( get_option( 'b3_registration_type', false ) ) ? : false; ?> </li>
            </ul>
        </li>
    <?php } ?>
    <li class="debug-list-part">
        <b>WP INFO</b>
        <ul>
            <li>Current theme: <?php echo get_option( 'current_theme' ); ?></li>
            <li>Stylesheet: <?php echo get_option( 'stylesheet' ); ?></li>
            <li>Template: <?php echo get_option( 'template' ); ?></li>
        </ul>
    </li>
    <li class="debug-list-part">
        <b>ACTIVE PLUGINS</b>
        <ul>
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    if ( is_plugin_active( $key ) ) {
                        echo '<li>' . $value[ 'Name' ] . '</li>';
                    }
                }
            ?>
        </ul>
    </li>
    <li class="debug-list-part">
        <b>INACTIVE PLUGINS</b>
        <ul>
            <?php
                $plugins = get_plugins();
                foreach( $plugins as $key => $value ) {
                    if ( ! is_plugin_active( $key ) ) {
                        echo '<li>' . $value[ 'Name' ] . '</li>';
                    }
                }
            ?>
        </ul>
    </li>
    <?php if ( class_exists( 'SitePress' ) ) { ?>
        <li class="debug-list-part">
            <b>WPML</b>
            <ul>
                <li>WPLANG: <?php echo get_option( 'WPLANG' ); ?></li>
                <li>WPML Version: <?php echo get_option( 'WPML_Plugin_verion' ); ?></li>
            </ul>
        </li>
    <?php } ?>

    <li class="debug-list-part">
        <?php $b3_values = b3_get_all_custom_meta_keys(); ?>
        <b>B3 values</b>
        <ul>
            <?php
                if ( ( $key = array_search( 'b3_email_styling', $b3_values ) ) !== false ) {
                    unset( $b3_values[ $key ] );
                }
                if ( ( $key = array_search( 'b3_email_template', $b3_values ) ) !== false ) {
                    unset( $b3_values[ $key ] );
                }
                foreach( $b3_values as $meta_key ) {
                    echo '<li>';
                    echo $meta_key . ': ';
                    $value = get_option( $meta_key );
                    if ( is_array( $value ) ) {
                        echo 'array( ' . implode( ', ', $value ) . ' )';
                    } else {
                        echo ($value) ? $value : esc_html__( 'empty', 'b3-onboarding');
                    }
                    echo '</li>';
                }
            ?>
        </ul>
    </li>
</ul>



