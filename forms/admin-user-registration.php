<?php

/**
 * Redefine new user notification function
 *
 * Emails new users their login info
 *
 * @param   integer $user_id user id
 * @param   string $plaintext_pass optional password
 */
if ( !function_exists( 'wp_new_user_notification' ) ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
 
        // set content type to html
        //add_filter( 'wp_mail_content_type', 'wpmail_content_type' );
 
        // user
        $user = new WP_User( $user_id );
        $email = stripslashes( $user->user_email );
        $username = $email;
        $nom = get_user_meta( $user_id, 'last_name', true ); 
        $prenom = get_user_meta( $user_id, 'first_name', true ); 
        $refer_url = home_url();
        $validation_token = bin2hex(random_bytes(45));
        $password = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&$:;,.*-_!?()"@#'), 0, 12);
        $password_md5 = wp_hash_password($password);        

        wp_set_password( $password, $user_id );

		update_user_meta( $user_id, 'token_activation', $validation_token );
		update_user_meta( $user_id, 'account_status', 'Non confirmé' );
		       
		// Send validation mail
		$mail_vars_registration = array($username, $email, $nom, $prenom, $refer_url, $validation_token, $password, get_footer_mail());

		notify_by_mail (array($email), 'CLER - Réseau pour la transition énergétique <' . CONTACT_GENERAL . '>', 'Un dernier clic et votre compte est activé !', true, FU_PLUGIN_DIR . '/mails/user-registration.php', $mail_vars_registration );
 
        /*ob_start();
        include plugin_dir_path( __FILE__ ).'/email_welcome.php';
        $message = ob_get_contents();
        ob_end_clean();*/
 
    }
}

/**
 * Disable password change notification function
 */
add_filter('send_password_change_email', '__return_false');