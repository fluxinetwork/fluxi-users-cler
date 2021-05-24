<?php
/*
* LE SCRIPT PRINCIPAL DE CONFIRMATION EST DANS LE TEMPLATE :theme/page-templates/user-confirmation.php
*/

/* 
 * Confirm registration 
 * GET $vars[] = 'confirme_utilisateur';
 * GET $vars[] = 'utilisateur';
   -> GET VARS IN "THEME/FUNCTIONS/CONFIG.PHP"
 */
function confirm_user_registration (){
	// Test get vars
	if( !empty($_GET['confirme_utilisateur']) && !empty($_GET['utilisateur'])):

		$get_token_user = filter_var($_GET['confirme_utilisateur'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$mail_user = filter_var($_GET['utilisateur'], FILTER_SANITIZE_EMAIL);
		// If email is registered
		if( email_exists( $mail_user )) :
			$the_user = get_user_by('email', $mail_user);
			$the_user_id = $the_user->ID;
			$stored_token = get_user_meta( $the_user_id, 'token_activation', true );
			//$account_state = get_field('disable_account', 'user_'.$the_user_id); 		
			$account_state = get_user_meta($the_user_id, 'disable_account', true );
			$refer_url = home_url();

			if( !is_user_logged_in () ): 
				$connexion_link = '<a href="connexion" class="js-popin-show">Connectez-vous</a>';
			else: 
				$connexion_link = '';	
			endif;

			// If token is confirmed
			if( $stored_token == $get_token_user ):
				// If user is not already activate
				if( $account_state == true ):
					$date = new DateTime();
					
					// Acompte security
					update_user_meta( $the_user_id, 'disable_account', false );
					update_user_meta( $the_user_id, 'account_status', 'Confirmé' );
					update_user_meta( $the_user_id, 'confimation_date', $date->getTimestamp() );

					// Prepare notification to admin adherent for validation 
					$if_adherent = get_user_meta( $the_user_id, 'if_adherent', true );
					$structure_adherente = get_user_meta( $the_user_id, 'structure_adherente', true );

					// Test si adherent et il y a une structure sélectionnée
					if($if_adherent == 'oui' && $structure_adherente):

						$id_admin_adh = get_adherent_admin_user_id($structure_adherente);
						$data_admin_adh = get_userdata($id_admin_adh);
						$mail_admin_adh = $data_admin_adh->user_email;
						$nom_prenom_admin_adh = $data_admin_adh->last_name.' '.$data_admin_adh->first_name;
						$nom_prenom_membre_adh = $the_user->last_name.' '.$the_user->first_name;

						/////////
						// Send notification to admin adherent for validation
						$mail_vars_notif_email_admin_adh = array($nom_prenom_admin_adh,$nom_prenom_membre_adh,$mail_user,$refer_url, get_footer_mail());
						notify_by_mail (array($mail_admin_adh), 'Le CLER <' . CONTACT_GENERAL . '>', $nom_prenom_membre_adh.' demande à être rattaché(e) à votre structure', true, FU_PLUGIN_DIR . '/mails/validation-admin-adherent.php', $mail_vars_notif_email_admin_adh);
					endif;

					$result_message = '<span class="success">Votre compte utilisateur est maintenant activé. '.$connexion_link.'</span>';
				else:		

					$result_message = '<span class="success">Votre compte utilisateur est déjà activé. '.$connexion_link.'</span>';
				endif;				
			else:
				$result_message = '<span class="error">La vérification ne se passe pas correctement.<br>Essayez de cliquer à nouveau sur le lien que vous avez reçu par email.<br>Si le problème persite <a href="'.home_url().'/creation-utilisateur/">créez un nouveau compte utilisateur</a> ou contactez-nous.</span>';
			endif;

		else:
			$result_message = '<span class="error">Cette adresse email n\'est rattachée a aucun compte utilisateur.<br>Nous vous invitons à <a href="'.home_url().'/creation-utilisateur/">créer un nouveau compte utilisateur</a>.</span>';
		endif;

		// Output
		echo '<div class="notify">'.$result_message.'</div>';		
			
	else:		
		echo 'Vous n\'avez visiblement pas le droit d\'être sur cette page.<br> Nous vous invitons à retourner vers <a href="'.home_url().'">la page d\'accueil</a>.';
	endif;

}
