<?php
/*
Plugin Name: Fluxi users - CLER
Plugin URI: 
Description: Système de gestion utilisateur personnalisé
Version: 1.0.0
Author: Yann Rolland
Author URI: http://yannrolland.com
License: CC BY-NC-ND 4.0 (Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International)
*/


// vars
define('FU_VERSION', '1.0.0');
define('FU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FU_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FU_PLUGIN_BASE_NAME', plugin_basename(__FILE__));
define('FU_PLUGIN_FILE', basename(__FILE__));
define('FU_PLUGIN_FULL_PATH', __FILE__);


if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('fluxiusers') ) :

	class fluxiusers {
		

		
		/*
		*  __construct
		*
		*  A dummy constructor to ensure Fluxi-users is only initialized once
		*
		*  @type	function
		*  @date	11/05/16
		*  @since	1.0.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		
		function __construct() {
			
			/* Do nothing here */
			
		}


		
		/*
		*  initialize
		*
		*  The real constructor to initialize Fluxi-users
		*
		*  @type	function
		*  @date	11/05/16
		*  @since	1.0.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		


		function initialize() {					
			
			// Include templates
			//require_once( FU_PLUGIN_DIR . 'assets/inc/add-page-templates.php' );	

			// Enqueue scripts
			add_action('wp_enqueue_scripts', 'register_fluxiusers_scripts',100);

			// actions
			add_action('init',	array($this, 'init'), 5);				
			
			// filters
			//add_filter('posts_where',		array($this, 'posts_where'), 10, 2 );
			//add_filter('posts_request',	array($this, 'posts_request'), 10, 1 );
			
		}
		

		
	   /*
		*  init
		*
		*  This function will run after all plugins and theme functions have been included
		*
		*  @type	action (init)
		*  @date	11/05/16
		*  @since	1.0.0
		*
		*  @param	N/A
		*  @return	N/A
		*/
		
		function init() {
			

			/*// Create pages
		    $page_definitions = array(
		        'connexion' => array(
		            'title' => __( 'Connexion', 'connexion' ),
		            'content' => '',
		            'template' => FU_PLUGIN_DIR . 'assets/inc/page-templates-parts/user-login.php',
		        ),
		        'creation-utilisateur' => array(
		            'title' => __( 'Création d\'un compte utilisateur', 'creation-utilisateur' ),
		            'content' => '',
		            'template' => 'user-registration.php',
		        ),
		        'mon-profil' => array(
		            'title' => __( 'Profil utilisateur', 'mon-profil' ),
		            'content' => '',
		            'template' => 'user-profil.php',
		        ),
		        'modifier-profil' => array(
		            'title' => __( 'Modifier mon profil', 'modifier-profil' ),
		            'content' => '',
		            'template' => 'user-profil-update.php',
		        ),
		        'recuperation-password' => array(
		            'title' => __( 'Récupération de password', 'recuperation-password' ),
		            'content' => '',
		            'template' => 'user-password-reset.php',
		        ),
		        'confirme-utilisateur' => array(
		            'title' => __( 'Validation du compte utilisateur', 'confirme-utilisateur' ),
		            'content' => '',
		            'template' => 'user-confirmation.php',
		        ),

				
		        'association' => array(
		            'title' => __( 'L\'association', 'association' ),
		            'content' => '',
		            'template' => '',
		        ),
		        'concours' => array(
		            'title' => __( 'Nos concours', 'concours' ),
		            'content' => '',
		            'template' => '',
		        ),
		        'le-reseau' => array(
		            'title' => __( 'Le réseau', 'le-reseau' ),
		            'content' => '',
		            'template' => '',
		        ),
		        'les-adherents' => array(
		            'title' => __( 'Les adhèrents', 'les-adherents' ),
		            'content' => '',
		            'template' => 'page-map-adherents.php',
		        ),
		        'adhesion' => array(
		            'title' => __( 'Adhésion', 'adhesion' ),
		            'content' => '',
		            'template' => 'page-manage-adherent.php',
		        ),
		        'gerer-evenement' => array(
		            'title' => __( 'Gestion événement', 'gerer-evenement' ),
		            'content' => '',
		            'template' => 'page-manage-event.php',
		        ),
		        'gerer-offre-emploi' => array(
		            'title' => __( 'Gestion offre d\'emploi', 'gerer-offre-emploi' ),
		            'content' => '',
		            'template' => 'page-manage-emploi.php',
		        ),
		        'offres-emploi' => array(
		            'title' => __( 'Offres d\'emploi', 'offres-emploi' ),
		            'content' => '',
		            'template' => 'page-tous-emploi.php',
		        ),
		        'evenements' => array(
		            'title' => __( 'Les événements', 'evenements' ),
		            'content' => '',
		            'template' => 'page-tous-events.php',
		        ), 
		        
		    );
		 
		    foreach ( $page_definitions as $slug => $page ) {
		        // Check that the page doesn't exist already
		        $query = new WP_Query( 'pagename=' . $slug );
		        if ( ! $query->have_posts() ) {
		            // Add the page using the data from the array above
		            $postID = wp_insert_post(
		                array(
		                    'post_content'   => $page['content'],
		                    'post_name'      => $slug,
		                    'post_title'     => $page['title'],
		                    'post_status'    => 'publish',
		                    'post_type'      => 'page',
		                    'ping_status'    => 'closed',
		                    'comment_status' => 'closed',
		                )
		            );
					
					//update_post_meta($postID, '_wp_page_template', $page['template']);
        
        			//update_option('awesome_page_id', $postID);


		        }
		    }*/					
				
		}
		
	}



	/*
	 * Register plugin php scripts
	 */

	require_once(  FU_PLUGIN_DIR . 'forms/user-login.php' );	
	require_once(  FU_PLUGIN_DIR . 'forms/user-registration.php' );
	require_once(  FU_PLUGIN_DIR . 'forms/admin-user-registration.php' );
	//require_once(  FU_PLUGIN_DIR . 'forms/user-confirmation.php' ); // Géré dans le theme	
	require_once(  FU_PLUGIN_DIR . 'forms/user-update.php' );	
	require_once(  FU_PLUGIN_DIR . 'forms/user-password-reset.php' );	


	/*
	 * Register plugin scripts
	 */

	function register_fluxiusers_scripts(){		

		if( is_page_template( 'page-templates/user-registration.php' ) || is_page_template( 'page-templates/user-profil-update.php' ) || is_page_template( 'page-templates/user-password-reset.php' ) ):
			wp_register_script( 'fluxiusers', plugin_dir_url( __FILE__ ) . 'assets/js/fluxiusers.js', array('jQuery', 'form-validator'), null, true );
		else:
			wp_register_script( 'fluxiusers', plugin_dir_url( __FILE__ ) . 'assets/js/fluxiusers.js', array('jQuery'), null, true );
		endif;			

		wp_localize_script( 'fluxiusers', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_script( 'fluxiusers' );

	}



	/*
	*  fluxiusers
	*
	*  The main function responsible for returning the one true fluxiusers Instance to functions everywhere.
	*  Use this function like you would a global variable, except without needing to declare the global.
	*
	*  Example: <?php $fluxiusers = fluxiusers(); ?>
	*
	*  @type	function
	*  @date	11/05/16
	*  @since	1.0.0
	*
	*  @param	N/A
	*  @return	(object)
	*/

	function fluxiusers() {

		global $fluxiusers;
		
		if( !isset($fluxiusers) ) {
		
			$fluxiusers = new fluxiusers();
			
			$fluxiusers->initialize();
			
		}
		
		return $fluxiusers;
		
	}


	// initialize
	fluxiusers();


endif; // class_exists check


?>