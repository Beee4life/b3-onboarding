<?php
    /*
     * Template for user management
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // @TODO: optimize this file

    if ( current_user_can( 'promote_users' ) ) {
        include_once B3_PLUGIN_PATH . '/includes/user-management-notices.php';

        if ( ! empty( $attributes[ 'users' ] ) ) { ?>
        <table class="b3_table b3_table--user">
            <thead>
            <tr>
                <th>
                    <?php echo ( is_multisite() ) ? esc_html__( 'Signup ID', 'b3-onboarding' ) : esc_html__( 'User ID', 'b3-onboarding' ); ?>
                </th>
                <?php if ( false == $attributes[ 'register_email_only' ] ) { ?>
                    <th><?php echo esc_html__( 'User name', 'b3-onboarding' ); ?></th>
                <?php } ?>
                <?php if ( false != $attributes[ 'show_first_last_name' ] ) { ?>
                    <th><?php echo esc_html__( 'First name', 'b3-onboarding' ); ?></th>
                    <th><?php echo esc_html__( 'Last name', 'b3-onboarding' ); ?></th>
                <?php } ?>
                <th><?php echo esc_html__( 'Email', 'b3-onboarding' ); ?></th>
                <?php if ( is_multisite() ) { ?>
                    <th><?php echo esc_html__( 'Domain', 'b3-onboarding' ); ?></th>
                    <th><?php echo esc_html__( 'Site name', 'b3-onboarding' ); ?></th>
                <?php } ?>
                <th><?php echo esc_html__( 'Actions', 'b3-onboarding' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $attributes[ 'users' ] as $user ) { ?>
                <tr>
                    <td>
                        <?php echo ( is_multisite() ) ? $user->signup_id : $user->ID; ?>
                    </td>

                    <?php if ( false == $attributes[ 'register_email_only' ] ) { ?>
                        <td><?php echo $user->user_login; ?></td>
                    <?php } ?>

                    <?php if ( false != $attributes[ 'show_first_last_name' ] ) { ?>
                        <td><?php echo $user->first_name; ?></td>
                        <td><?php echo $user->last_name; ?></td>
                    <?php } ?>

                    <td><?php echo $user->user_email; ?></td>

                    <?php if ( is_multisite() ) { ?>
                        <td><?php echo $user->domain; ?></td>
                        <td><?php echo $user->title; ?></td>
                    <?php } ?>

                    <td>
                        <form name="b3_user_management" method="post">
                            <input name="b3_manage_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>" />
                            <input name="b3_approve_user" class="button" type="submit" value="<?php echo esc_attr__( 'Approve', 'b3-onboarding' ); ?>" />
                            <input name="b3_reject_user" class="button" type="submit" value="<?php echo esc_attr__( 'Reject', 'b3-onboarding' ); ?>" />
                            <?php if ( is_multisite() ) { ?>
                                <input name="b3_signup_id" type="hidden" value="<?php echo esc_attr( $user->signup_id ); ?>" />
                            <?php } else { ?>
                                <input name="b3_user_id" type="hidden" value="<?php echo esc_attr( $user->ID ); ?>" />
                            <?php } ?>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php
        } else {
            // empty users
            echo sprintf( '<p>%s</p>', esc_html__( 'No (more) users to approve.', 'b3-onboarding' ) );
        }
    } // end if current_user_can promote_users
