<?php

/**
 * User update profil infos
 * Must active admin-ajax.php in scripts.php
 */

/*
 * Disable notification email
 */
add_filter( 'send_email_change_email', '__return_false' );

/* 
 * Update profil infos
 */

function fluxi_update_user(){

	$mail_user = $_POST['email'];
	$toky_toky = $_POST['toky_toky'];
	// Global array
    $results = array();
    global $reg_errors;
	$reg_errors = new WP_Error;

	$current_user = wp_get_current_user();

	// Verify nonce
	if ( isset( $_POST['fluxi_update_user_nonce_field'] ) && wp_verify_nonce( $_POST['fluxi_update_user_nonce_field'], 'fluxi_update_user' )) :
		// Verify email & token
		if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $mail_user) && is_numeric($toky_toky) && $toky_toky == 3476954852):
			// Clean vars
			$nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
			$prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
			$email = $mail_user;
			$refer_url = home_url();

			// Verify			
			if ( $current_user->user_email != $email && email_exists( $email )):
				
			    $reg_errors->add( 'email', 'Cette adresse email est déjà utilisée. Vous ne pouvez créer deux comptes avec la même adresse email.' );			

			else:

		        wp_update_user( array( 
		       		'ID' => $current_user->ID,
		       		'last_name' => $nom,
		       		'first_name' => $prenom,
		       		'user_email' => $email
		        ) );

				if ( $current_user->user_email != $email ):		
					
					// Send security mail
					$mail_vars_update_email = array($email, $prenom, $refer_url);
					notify_by_mail (array($email), 'Le CLER <' . CONTACT_GENERAL . '>', 'Votre adresse email vient d\'être modifiée', true, FU_PLUGIN_DIR . '/mails/user-update-email.php', $mail_vars_update_email);	

					// Clear auth cache		
				    wp_clear_auth_cookie();
				endif;		
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
			'redirect' => home_url().'/mon-profil/',
			'message' => 'Votre profil est à jour.'
		);
		$results[] = $data;
	endif;		

	// Output JSON
	wp_send_json($results);
}

add_action('wp_ajax_nopriv_fluxi_update_user', 'fluxi_update_user');
add_action('wp_ajax_fluxi_update_user', 'fluxi_update_user');



/* 
 * Update user password
 */
function fluxi_password_user(){

	$password_user = $_POST['password'];
	$toky_toky = $_POST['toky_toky'];
	// Global array
    $results = array();
    global $reg_errors;
	$reg_errors = new WP_Error;

	$current_user = wp_get_current_user();
	$refer_url = home_url();

	// Verify nonce
	if ( isset( $_POST['fluxi_password_user_nonce_field'] ) && wp_verify_nonce( $_POST['fluxi_password_user_nonce_field'], 'fluxi_password_user' )) :
		// Verify token
		if (is_numeric($toky_toky) && $toky_toky == 685349752):
			
			wp_set_password( $password_user, $current_user->ID );
			
			// Send security mail
			$mail_vars_update_password = array($current_user->user_email, $current_user->user_firstname, $refer_url);
			notify_by_mail (array($current_user->user_email), 'CLER - Réseau pour la transition énergétique <info@cler.org>', 'Votre password vient d\'être modifié', true, FU_PLUGIN_DIR . '/mails/user-update-password.php', $mail_vars_update_password);

			// Clear auth cache		
			wp_clear_auth_cookie();	

		else:
			// If toky error
			$reg_errors->add( 'toky', 'Erreur dans l\'envoie du formulaire. Essayez de l\'envoyer à nouveau. Contacter-nous si le problème persiste.' );			

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
			'redirect' => $refer_url.'/mon-profil/',
			'message' => 'Votre password est à jour.'
		);
		$results[] = $data;
	endif;	

	// Output JSON
	
	wp_send_json($results);
}

add_action('wp_ajax_nopriv_fluxi_password_user', 'fluxi_password_user');
add_action('wp_ajax_fluxi_password_user', 'fluxi_password_user');

 