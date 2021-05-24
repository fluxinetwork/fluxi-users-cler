<?php

/**
 * User registration form process
 * Must active admin-ajax.php in scripts.php
 */

/* 
 * Create an user
 */
function fluxi_create_user(){

	$mail_user = $_POST['email'];
	$toky_toky = $_POST['toky_toky'];
	// Global array
    $results = array();
    global $reg_errors;
	$reg_errors = new WP_Error;

	// Verify nonce
	if ( isset( $_POST['fluxi_new_user_nonce_field'] ) && wp_verify_nonce( $_POST['fluxi_new_user_nonce_field'], 'fluxi_new_user' )) :
		// Verify email & token
		if (filter_var($mail_user, FILTER_VALIDATE_EMAIL) && is_numeric($toky_toky) && $toky_toky == 9274565543):
			// Clean vars
			$nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
			$prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
			$email = $mail_user;
			//$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$password = $_POST['password'];

			// Structure
			$if_adherent = filter_var($_POST['if_adherent'], FILTER_SANITIZE_STRING);
			$fonction = filter_var($_POST['fonction'], FILTER_SANITIZE_STRING);

			// Create login
			$username = $prenom.''.$nom.'-'.mt_rand(1,10000);

			// Verify
			if ( username_exists( $username ) ):
    			//$reg_errors->add('user_name', 'Il faut changer votre login, il est déjà utilisé.');
    			$reg_errors->add('user_name', 'Il semble y avoir un problème. Veuillez renvoyer le formulaire.');
    		endif;
    		/*if ( ! validate_username( $username ) ):
			    $reg_errors->add( 'username_invalid', 'Votre login ne doit contenir que a-z, A-Z, 0-9, - et _ (minimum 5 caractères).' );
			endif;*/
			if ( email_exists( $email ) ):
			    $reg_errors->add( 'email', 'Cette adresse email est déjà utilisée. Vous pouvez <a href="'.home_url().'/recuperation-password/">récupérer votre mot de passe</a>.' );
			endif;			

			// Output errors
			if ( is_wp_error( $reg_errors ) && count( $reg_errors->get_error_messages() ) > 0):
 				$output_errors = '';
			    foreach ( $reg_errors->get_error_messages() as $error ) {
			        $output_errors .= $error . '<br>';
			    }
			    $datas_validate = array(
					'validation' => 'error',
					'message' => $output_errors
				);

			else:
				// Create user
				$userdata = array(
			        'user_login'    =>   $username,
			        'user_email'    =>   $email,
			        'user_pass'     =>   $password,
			        'first_name'    =>   $prenom,
			        'last_name'     =>   $nom,
			        'role'			=>	 'author'
		        );
		        $user = wp_insert_user( $userdata );

		        $the_user = get_user_by('email', $email);
				$the_user_id = $the_user->ID;
				$refer_url = home_url();
		        $validation_token = bin2hex(random_bytes(45));

		        update_user_meta( $the_user_id, 'token_activation', $validation_token );
		        update_user_meta( $the_user_id, 'account_status', 'Non confirmé' );
		        update_user_meta( $the_user_id, 'disable_account', true );

		        $url_lien_activation_compte = $refer_url.'/confirme-utilisateur/?utilisateur='.urlencode($email).'&confirme_utilisateur='.$validation_token;
		        update_user_meta( $the_user_id, 'lien_activation_compte', $url_lien_activation_compte );
		        

		        // Structure
		        $nom_structure_adherente = '';
		        update_user_meta( $the_user_id, 'if_adherent', $if_adherent );
		        update_user_meta( $the_user_id, 'fonction', $fonction );
		        if($if_adherent === 'oui'):
		        	$id_structure_adherente = filter_var($_POST['structure_adherente'], FILTER_SANITIZE_NUMBER_INT);
		        	$nom_structure_adherente = get_the_title($id_structure_adherente);
		        	update_user_meta( $the_user_id, 'structure_adherente', $id_structure_adherente );
		        	update_user_meta( $the_user_id, 'role_utilisateur_structure', 'Statut en cours de validation' );
		        else:
		        	$autre_nom_structure = filter_var($_POST['autre_nom_structure'], FILTER_SANITIZE_STRING);
		        	if($autre_nom_structure):
		        		update_user_meta( $the_user_id, 'autre_nom_structure', $autre_nom_structure );
		        	endif;
		        endif;

				// Send validation mail
				$mail_vars_registration = array($username, $email, $nom, $prenom, $refer_url, $validation_token, $password, get_footer_mail(),$if_adherent,$nom_structure_adherente);
				notify_by_mail (array($email), 'CLER - Réseau pour la transition énergétique <' . CONTACT_GENERAL . '>', 'Un dernier clic et votre compte est activé !', true, FU_PLUGIN_DIR . '/mails/user-registration.php', $mail_vars_registration );

		        // Output message
				$datas_validate = array(
					'validation' => 'success',
					'message' => 'Un email de confirmation vient de vous être envoyé. Veuillez cliquer sur le lien qu\'il contient pour valider la création de votre compte utilisateur.'
				);
			endif;

			// Array to output response json
			$results[] = $datas_validate;

		else:
			// If invalid mail
			$data = array(
				'validation' => 'error',
				'message' => 'Votre adresse email "'.$email.'" semble invalide.'
			);
			$results[] = $data;

		endif;

	else :
		// If invalid nonce
	  	$data = array(
			'validation' => 'error',
			'message' => 'Erreur dans l\'envoie du formulaire. Essayez de l\'envoyer à nouveau. Contacter-nous si le problème persiste.'
		);
		$results[] = $data;
	endif;

	// Output JSON
	wp_send_json($results);
}

add_action('wp_ajax_nopriv_fluxi_create_user', 'fluxi_create_user');
add_action('wp_ajax_fluxi_create_user', 'fluxi_create_user');

 