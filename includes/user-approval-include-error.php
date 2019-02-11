<?php
    if ( current_user_can( 'manage_options' ) ) {

        // get users which are awaiting approval
        // $user_args = array(
        //     'role' => 'b3_approval'
        // );
        // $users = get_users( $user_args );
        ?>
        <?php if ( ! empty( $_GET[ 'user' ] ) ) { ?>
            <?php if ( 'approved' == $_GET[ 'user' ] ) { ?>
<!--                <p class="b3__message">--><?php //esc_html_e( 'User is successfully approved', 'b3-onboarding' ); ?><!--</p>-->
            <?php } elseif ( 'rejected' == $_GET[ 'user' ] ) { ?>
<!--                <p class="b3__message">--><?php //esc_html_e( 'User is successfully rejected and user is deleted', 'b3-onboarding' ); ?><!--</p>-->
            <?php } ?>
        <?php } ?>
        <?php if ( $users ) { ?>
<!--            <table class="b3__user-table" border="0" cellspacing="0" cellpadding="0" style="">-->
<!--                <thead>-->
<!--                <tr>-->
<!--                    <th>-->
<!--                        User ID-->
<!--                    </th>-->
<!--                    <th>-->
<!--                        Email-->
<!--                    </th>-->
<!--                    <th>-->
<!--                        Actions-->
<!--                    </th>-->
<!--                </tr>-->
<!--                </thead>-->
<!--                <tbody>-->
<!--                --><?php //foreach( $users as $user ) { ?>
<!--                    <tr>-->
<!--                        <td>--><?php //echo $user->ID; ?><!--</td>-->
<!--                        <td>--><?php //echo $user->user_email; ?><!--</td>-->
<!--                        <td>-->
<!--                            <form name="b3_user_management" action="" method="post">-->
<!--                                <input name="b3_users_nonce" type="hidden" value="--><?php //echo wp_create_nonce( 'b3-users-nonce' ); ?><!--" />-->
<!--                                <input name="b3_user_id" type="hidden" value="--><?php //echo $user->ID; ?><!--" />-->
<!--                                <input name="b3_approve_user" class="button" type="submit" value="--><?php //esc_html_e( 'Approve user', 'b3-onboarding' ); ?><!--" />-->
<!--                                <input name="b3_reject_user" class="button" type="submit" value="--><?php //esc_html_e( 'Reject user', 'b3-onboarding' ); ?><!--" />-->
<!--                            </form>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                --><?php //} ?>
<!--                </tbody>-->
<!--            </table>-->
        <?php } else { ?>
<!--            <p>--><?php //esc_html_e( 'All good, no one to approve...', 'b3-onboarding' ); ?><!--</p>-->
        <?php } ?>
<?php } ?>
