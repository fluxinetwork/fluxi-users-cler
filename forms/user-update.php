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
	$the_user_id = $current_user->ID;
	$response_message = '';

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

		        // Fonction				
				$fonction = filter_var($_POST['fonction'], FILTER_SANITIZE_STRING);		        
		        update_user_meta( $the_user_id, 'fonction', $fonction );

		        // Structure
		        $if_adherent = filter_var($_POST['if_adherent'], FILTER_SANITIZE_STRING);
		        if($if_adherent === 'oui'):

		        	$id_structure_adherente = filter_var($_POST['structure_adherente'], FILTER_SANITIZE_NUMBER_INT);

					// Test if there is previous structure value
		        	$last_structure_id = (get_user_meta($current_user->ID, 'structure_adherente', true)) ? get_user_meta($current_user->ID, 'structure_adherente', true) : false;

		        	if($last_structure_id != $id_structure_adherente):
		        		// Update user statut
						update_user_meta( $the_user_id, 'role_utilisateur_structure', 'Statut en cours de validation' );
						///////////////////////////////
						////// Send notification to admin adherent for validation
						if($if_adherent == 'oui' && $id_structure_adherente):
							$id_admin_adh = get_adherent_admin_user_id($id_structure_adherente);
							$data_admin_adh = get_userdata($id_admin_adh);
							$mail_admin_adh = $data_admin_adh->user_email;
							$nom_prenom_admin_adh = $data_admin_adh->last_name.' '.$data_admin_adh->first_name;
							$nom_prenom_membre_adh = $nom.' '.$prenom;

							/////////
							// Send notification to admin adherent for validation
							$mail_vars_notif_email_admin_adh = array($nom_prenom_admin_adh,$nom_prenom_membre_adh,$mail_user,$refer_url, get_footer_mail());
							notify_by_mail (array($mail_admin_adh), 'Le CLER <' . CONTACT_GENERAL . '>', $nom_prenom_membre_adh.' demande à être rattaché(e) à votre structure', true, FU_PLUGIN_DIR . '/mails/validation-admin-adherent.php', $mail_vars_notif_email_admin_adh);

							$response_message = ' Un mail vient d\'être envoyé à l\'administrateur de votre structure.';
						
						endif; 
					else:
						// Pas de changement de structure, pas d'envoie de mail
		        	endif;

		        	// Update structure relation
		        	update_user_meta( $the_user_id, 'structure_adherente', $id_structure_adherente );

		        	// Reset "autre structure"
		        	update_user_meta( $the_user_id, 'autre_nom_structure', '' );

		        else:
		        	$autre_nom_structure = filter_var($_POST['autre_nom_structure'], FILTER_SANITIZE_STRING);
		        	if($autre_nom_structure):
		        		update_user_meta( $the_user_id, 'autre_nom_structure', $autre_nom_structure );
		        	endif;
		        endif;

		        update_user_meta( $the_user_id, 'if_adherent', $if_adherent );

				if ( $current_user->user_email != $email ):		
					
					// Send security mail
					$mail_vars_update_email = array($email, $prenom, $refer_url, get_footer_mail());
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
		
	else:
		$data = array(
			'validation' => 'success',
			'redirect' => home_url().'/mon-profil/',
			'message' => 'Votre profil est à jour.'.$response_message
		);
		$results[] = $data;
	endif;		

	$results[] = $data;

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

 