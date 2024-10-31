<?php
/* Admin functions to set and save settings of the 
 * @package OOORL
*/
require_once('pages.php');
require_once('meta_box.php');
/* Initialize the theme admin functions */
add_action('init', 'ooorl_admin_init');

function ooorl_admin_init(){
			
    add_action('admin_menu', 'ooorl_settings_init');
    add_action('admin_init', 'ooorl_actions_handler');
    add_action('admin_init', 'ooorl_admin_style');
    add_action('admin_init', 'ooorl_admin_script');
    
}

function ooorl_settings_init(){
   global $ooorl; 
   $ooorl->main_page = add_menu_page( 'Ooorl', 'Ooorl', 'manage_options', 'ooorl', 'ooorl_main_page' );   
   /* Make sure the settings are saved. */
   add_action( "load-{$ooorl->main_page}", 'ooorl_main_settings');
}

function ooorl_admin_style(){
  $plugin_data = get_plugin_data( OOORL_DIR . 'ooorl.php' );
	
	wp_enqueue_style( 'ooorl-admin', OOORL_CSS . 'style.css', false, $plugin_data['Version'], 'screen' );	
    wp_enqueue_style( 'ooorl-new', OOORL_CSS . 'new.css', false, $plugin_data['Version'], 'screen' );       
}
function ooorl_admin_script(){}
function ooorl_actions_handler(){  
    if(isset($_POST['ooorl_settings'])){
	  $ooorl_opts = get_option(OOORL_OPTIONS);
	  $ooorl_opts['enter_redirect_url'] = $_POST['enter_url'];
	  $ooorl_opts['leave_redirect_url'] = $_POST['leave_url'];  
      $ooorl_opts['page'] = $_POST['page'];
      $ooorl_opts['default'] = $_POST['default']; 
	  update_option(OOORL_OPTIONS, $ooorl_opts);  
	  $redirect = admin_url( 'admin.php?page=ooorl&updated=true' ); 
      wp_redirect($redirect); 
  }
}
function ooorl_error_message(){
   echo '<div class="error">
		<p>Error</p>
  </div>';  
}

function ooorl_updated_message(){
   echo '<div class="updated fade">
		<p>Settings Updated</p>
  </div>';  	
}
?>
