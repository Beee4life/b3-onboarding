<?php
    /*
     * Template for user management
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( current_user_can( 'promote_users' ) ) {
        include_once B3OB_PLUGIN_PATH . '/includes/user-management-notices.php';

        if ( ! empty( $attributes[ 'users' ] ) ) {
            ?>
            <table class="b3_table b3_table--user">
                <thead>
                <tr>
                    <?php foreach( b3_get_approvement_table_headers( $attributes ) as $header ) { ?>
                        <?php echo sprintf( '<th>%s</th>', $header ); ?>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach( $attributes[ 'users' ] as $user ) { ?>
                    <?php echo b3_render_approvement_table_row( $user, $attributes ); ?>
                <?php } ?>
                </tbody>
            </table>
    <?php
        } else {
            // empty users
            echo sprintf( '<p>%s</p>', esc_html__( 'No (more) users to approve.', 'b3-onboarding' ) );
        }
    } // end if current_user_can promote_users
