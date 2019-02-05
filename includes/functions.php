<?php
    
    /**
     * Output the password fields
     */
    function b3_show_password_fields() {
        
        ob_start();
        ?>
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass1"><?php _e( 'Password', 'b3-user-register' ); ?></label>
            <input autocomplete="off" name="pass1" id="pass1" size="20" value="" type="password" class="b3__form--input" />
        </div>
        
        <div class="b3__form-element b3__form-element--register">
            <label class="b3__form-label" for="pass2"><?php _e( 'Confirm Password', 'b3-user-register' ); ?></label>
            <input autocomplete="off" name="pass2" id="pass2" size="20" value="" type="password" class="b3__form--input" />
        </div>
        <?php
        $results = ob_get_clean();
        echo $results;
    }

    function dummy_content() {
        
        ob_start();
        ?>
        <p>Well, the way they make shows is, they make one show. That show's called a pilot. Then they show that show to the people who make shows, and on the strength of that one show they decide if they're going to make more shows. Some pilots get picked and become television programs. Some don't, become nothing. She starred in one of the ones that became nothing.</p>

        <p>Normally, both your asses would be dead as fucking fried chicken, but you happen to pull this shit while I'm in a transitional period so I don't wanna kill you, I wanna help you. But I can't give you this case, it don't belong to me. Besides, I've already been through too much shit this morning over this case to hand it over to your dumb ass.</p>
        <?php
        $result = ob_get_clean();
        
        return $result;
    }
