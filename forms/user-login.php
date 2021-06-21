<?php

/*
 * Remove the load auth check for monitoring whether the user is still logged in. 
 */
remove_action( 'admin_enqueue_scripts', 'wp_auth_check_load' );

/**
 * User login form redirections process
 * 
 */

// Redirect to custom login page
function redirect_user() {
  if ( ! is_user_logged_in() && is_page( 'login' ) ) {
    $return_url = esc_url( home_url( '/connexion/' ) );
    wp_redirect( $return_url );
    exit;
  }
}
add_action( 'template_redirect', 'redirect_user' );

function login_failed() {
    $login_page  = home_url( '/connexion/' );
    wp_redirect( $login_page . "?login=requis" );
    exit;
}
//add_action( 'wp_login_failed', 'login_failed' );
 
function verify_username_password( $user, $username, $password ) {
    $login_page  = home_url( '/connexion/' );
    if( $username == '' || $password == '' ) {
        wp_redirect( $login_page . "?login=requis" );
        exit;
    }
}
add_filter( 'authenticate', 'verify_username_password', 1, 3);


function redirect_login_page() {
	
    $login_page  = home_url( '/connexion/' );
    $page_viewed = basename($_SERVER['REQUEST_URI']);
 
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','redirect_login_page');

/*
// Logout
function logout_page() {
    $login_page  = home_url();
    //wp_redirect( $login_page . "?login=logout" );
	 wp_redirect( $login_page );
    exit;
}
add_action('wp_logout','logout_page');
*/
// Disable password reset
function disable_password_reset() { 
	return false;
}
add_filter ( 'allow_password_reset', 'disable_password_reset' );


/*
 * Login process
 */
function fluxi_login_user(){

	$identifiant = $_POST['identifiant'];
	$password = $_POST['password'];

	// Global array
    $results = array();    

	// Verify nonce
	if ( isset( $_POST['fluxi_login_user_nonce_field'] ) && wp_verify_nonce( $_POST['fluxi_login_user_nonce_field'], 'fluxi_login_user' )) :
		// Verify empty fields
		if( isset($_POST['identifiant']) && !empty($_POST['identifiant']) && isset($_POST['password']) && !empty($_POST['password']) ):
			// Verify existing user
			if( email_exists($identifiant) || username_exists($identifiant) ):

				if( email_exists($identifiant) ):
					$user_infos = get_user_by('email', $identifiant);					
				else:				
					$user_infos = get_user_by('login', $identifiant);
				endif;

				// Verify if account is actived
				if( get_user_meta($user_infos->ID, 'disable_account', true) != true ):

					// Verify password
					if ( $user_infos && wp_check_password( $password, $user_infos->data->user_pass, $user_infos->ID) ):				
						// Login
						$creds = array(
						    'user_login'    => $identifiant,
						    'user_password' => $password,
						    'rememember'    => true
						);

						// If --> HTTP
						//$user = wp_signon( $creds, false );

						// If --> HTTPS
						$user = wp_signon( $creds, true );
						
						if ( is_wp_error( $user ) ) :
						    // Return if wordpress error
						    $data = array(
								'validation' => 'error',
								'message' => 'Vos identifiants ne correspondent pas.'
							);	
						else:
							// Connexion & redirect
							if(wp_get_referer() == '/connexion/' || wp_get_referer() == '/confirme-utilisateur/' || wp_get_referer() == '/connexion/?login=requis'){
								$redirect = home_url();
							}else{
								$redirect = wp_get_referer();
							}

							$data = array(
								'validation' => 'success',
								'redirect' => $redirect,
								'message' => 'Vous êtes maintenant connecté.'
							);

						endif;
					else:
						// Password not match
						$data = array(
							'validation' => 'error',
							'message' => 'Vos identifiants ne correspondent pas.'
						);	
					endif;
				else:
					// Inactive account 
					$data = array(
						'validation' => 'error',
						'message' => 'Votre compte semble inactif. Si vous venez de créer votre compte vous devez avoir reçu un email qui permet de le valider. Cliquez sur le lien qu\'il contient'
					);		

				endif;
			else:
				// Login not match
			    $data = array(
					'validation' => 'error',
					'message' => 'Vos identifiants ne correspondent pas.'
				);	
			endif;
		else:
			// Empty fields
			$data = array(
				'validation' => 'error',
				'message' => 'Vous devez renseigner tous les champs.'
			);	
		endif;		

	else :
		// If invalid nonce
	  	$data = array(
			'validation' => 'error',
			'message' => 'Erreur dans l\'envoie du formulaire. Essayez de l\'envoyer à nouveau. Contacter-nous si le problème persiste.'
		);
		
	endif;

	// Output JSON
	$results[] = $data;	
	wp_send_json($results);
}

add_action('wp_ajax_nopriv_fluxi_login_user', 'fluxi_login_user');
add_action('wp_ajax_fluxi_login_user', 'fluxi_login_user');