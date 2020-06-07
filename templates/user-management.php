<?php
    $show_first_last_name = get_option( 'b3_activate_first_last', false );
    $user_args            = array( 'role' => 'b3_approval' );
    $users                = get_users( $user_args );
    if ( current_user_can( 'promote_users' ) ) {
        $user_approved = esc_html__( 'User is successfully approved.', 'b3-onboarding' );
        $user_rejected = esc_html__( 'User is successfully rejected and the account is deleted.', 'b3-onboarding' );
        ?>

        <?php if ( ! empty( $_GET[ 'user' ] ) ) { ?>
            <?php if ( is_admin() ) { ?>
                <?php if ( 'approved' == $_GET[ 'user' ] ) { ?>
                    <?php B3Onboarding::b3_errors()->add( 'success_user_approved', $user_approved ); ?>
                <?php } elseif ( 'rejected' == $_GET[ 'user' ] ) { ?>
                    <?php B3Onboarding::b3_errors()->add( 'error_no_nonce_match', $user_rejected ); ?>
                <?php } ?>
                <?php B3Onboarding::b3_show_admin_notices(); ?>
            <?php } else { ?>
                <p class="b3_message">
                    <?php if ( 'approved' == $_GET[ 'user' ] ) { ?>
                        <?php echo $user_approved ?>
                    <?php } elseif ( 'rejected' == $_GET[ 'user' ] ) { ?>
                        <?php echo $user_rejected; ?>
                    <?php } ?>
                </p>
            <?php } ?>
        <?php } ?>

        <?php if ( $users ) { ?>
            <table class="b3_table b3_table--user">
                <thead>
                <tr>
                    <th>
                        <?php esc_html_e( 'User ID', 'b3-onboarding' ); ?>
                    </th>
                    <?php if ( false != $show_first_last_name ) { ?>
                        <th>
                            <?php esc_html_e( 'First name', 'b3-onboarding' ); ?>
                        </th>
                        <th>
                            <?php esc_html_e( 'Last name', 'b3-onboarding' ); ?>
                        </th>
                    <?php } ?>
                    <th>
                        <?php esc_html_e( 'Email', 'b3-onboarding' ); ?>
                    </th>
                    <th>
                        <?php esc_html_e( 'Actions', 'b3-onboarding' ); ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach( $users as $user ) { ?>
                    <tr>
                        <td><?php echo $user->ID; ?></td>
                        <?php if ( false != $show_first_last_name ) { ?>
                            <td><?php echo $user->first_name; ?></td>
                            <td><?php echo $user->last_name; ?></td>
                        <?php } ?>
                        <td><?php echo $user->user_email; ?></td>
                        <td>
                            <form name="b3_user_management" action="" method="post">
                                <input name="b3_manage_users_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-manage-users-nonce' ); ?>" />
                                <input name="b3_user_id" type="hidden" value="<?php echo $user->ID; ?>" />
                                <input name="b3_approve_user" class="button" type="submit" value="<?php esc_html_e( 'Approve', 'b3-onboarding' ); ?>" />
                                <input name="b3_reject_user" class="button" type="submit" value="<?php esc_html_e( 'Reject', 'b3-onboarding' ); ?>" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>
                <?php esc_html_e( 'No (more) users to approve.', 'b3-onboarding' ); ?>
            </p>
        <?php }
    }
