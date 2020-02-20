<?php

/**
 * User password reset
 * Must active admin-ajax.php in scripts.php
 */


/* 
 * Password reset process
 */

function fluxi_password_reset_user(){

	$mail_user = $_POST['email'];
	$toky_toky = $_POST['toky_toky'];
	// Global array
    $results = array();
    global $reg_errors;
	$reg_errors = new WP_Error;

	// Verify nonce
	if ( isset( $_POST['fluxi_password_reset_user_nonce_field'] ) && wp_verify_nonce( $_POST['fluxi_password_reset_user_nonce_field'], 'fluxi_password_reset_user' )) :
		// Verify email & token
		if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $mail_user) && is_numeric($toky_toky) && $toky_toky == 4279361540):
			// Clean vars			
			$email = $mail_user;		    
			$refer_url = home_url();

			// Verify			
			if ( email_exists( $email )):

				$user = get_user_by( 'email', $email );
				$account_state = get_field($user->ID, 'disable_account', true);

				if( $account_state == NULL ):

					$password_user = generate_password(12);

					wp_set_password( $password_user, $user->ID );			
						
					// Send security mail
					$mail_vars_reset_password = array($email, $user->first_name, $refer_url, $password_user, get_footer_mail());
					notify_by_mail (array($email), 'CLER - Réseau pour la transition énergétique <' . CONTACT_GENERAL . '>', 'Votre mot de passe vient d\'être réinitialisé', true, FU_PLUGIN_DIR . '/mails/user-reset-password.php', $mail_vars_reset_password);	

					// Clear auth cache		
					//wp_clear_auth_cookie();
				else:
					$reg_errors->add( 'activation', 'Votre compte utilisateur n\'est pas activé. Pour cela, cliquez sur le lien contenu dans l\'email d\'activation que vous avez reçu.'  );
				endif;
			else:
				$reg_errors->add( 'email', 'Il y a un problème avec votre adresse email.' );
						
			endif;

		else:
			// If invalid mail			
			$reg_errors->add( 'email', 'Votre adresse email "'.$email.'" semble invalide.' );

		endif;

	else :
		// If invalid nonce
		$reg_errors->add( 'nonce', 'Erreur dans l\'envoie du formulaire. Essayez de l\'envoyer à nouveau. Contacter-nous si le problème persiste.' );
	endif;

	if ( is_wp_error( $reg_errors ) && count( $reg_errors->get_error_messages() ) > 0):
 		$output_errors = '';
		foreach ( $reg_errors->get_error_messages() as $error ) {
			$output_errors .= $error . '<br>';
		}
		$data = array(
			'validation' => 'error',
			'message' => $output_errors
		);
		$results[] = $data;
	else:
		$data = array(
			'validation' => 'success',
			'redirect' => $refer_url.'/connexion/',
			'message' => 'Votre mot de passe vient d\'être réinitialisé. Vous allez recevoir un mail décrivant la procédure à suivre.'
		);
		$results[] = $data;
	endif;		

	// Output JSON
	wp_send_json($results);
}

add_action('wp_ajax_nopriv_fluxi_password_reset_user', 'fluxi_password_reset_user');
add_action('wp_ajax_fluxi_password_reset_user', 'fluxi_password_reset_user');

/**
 * Generate password
 */
function generate_password($nb_chars = 12){
    $mdp = "";
    
    $key = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+@;!$%?&";
    $key_length = strlen($key);
    
    for($i = 1; $i <= $nb_chars; $i++)
    {
        $random = mt_rand(0,($key_length-1));
        $mdp .= $key[$random];
    }

    return $mdp;   
}

 