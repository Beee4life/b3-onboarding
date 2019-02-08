<?php
    
    /**
     * Render a settings field
     *
     * @param bool $box
     *
     * @return false|mixed|string
     */
    function b3_render_email_field( $box = false ) {
        
        if ( false != $box ) {
            
            $basic_output = b3_basic_settings_email_field( $box );
            $basic_output = str_replace( '##SETTINGSFIELD##', b3_email_field_content( $box ), $basic_output );
            return $basic_output;
        }
        
        return '<h4>Oops, no content yet...</h4>';
    }

    function b3_basic_settings_email_field( $box = false ) {
    
        ob_start();
        if ( ( ! empty( $box[ 'id' ] ) ) && ( ! empty( $box[ 'title' ] )) ) {
            ?>
            <div class="metabox-handler">
                <div class="postbox" id="">
                    <div class="handlediv" title="">
                        <i class="dashicons dashicons-plus"></i>
                    </div>
                    <div class="b3__foldout--header foldout__toggle<?php echo ( isset( $box[ 'id' ] ) && 'email_settings' == $box[ 'id' ] ) ? ' open' : false; ?>">
                        <?php echo ( isset( $box[ 'title' ] ) ) ? $box[ 'title' ] : 'Settings'; ?>
                    </div>
                    
                    <div class="b3__inside <?php echo ( isset( $box[ 'id' ] ) && 'email_settings' == $box[ 'id' ] ) ? 'x' : false; ?>foldout__content <?php echo ( isset( $box[ 'id' ] ) && 'email_settings' == $box[ 'id' ] ) ? 'x' : false; ?>hidden">
                        ##SETTINGSFIELD##
                    </div>
                </div>
            </div>
            <?php
        }
        $output = ob_get_clean();
        
        return $output;
    }
    
    
    /**
     * Get the content for an email field
     *
     * @param bool $box
     *
     * @return bool|false|string
     */
    function b3_email_field_content( $box = false ) {
        
        if ( false != $box ) {
    
            ob_start();
            $output = '';
            switch ( $box[ 'id' ] ) {
                case 'email_settings':
                    include( 'emails/email-settings.php' );
                    $output = ob_get_clean();
                    break;
                default:
                    $output = ob_get_clean();
            }
            
            return $output;
    
        }
        
        return false;
    }
