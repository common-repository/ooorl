<?php
/*
Plugin Name: Ooorl
Plugin URI: http://ooorl.com
Description: Provides short canonical permalinks to your posts and pages.
Author: Darell Sun
Version: 1.0.0
Author URI: http://wp-coder.net
*/

require_once('include/tools.php');
require_once('include/config.php');
/* Set up the plugin. */
add_action('plugins_loaded', 'ooorl_setup');  
/* Create wp_ooorl table when admin active this plugin*/
register_activation_hook(__FILE__,'ooorl_activation');

function ooorl_activation()
{
	$ooorl_opts = get_option(OOORL_OPTIONS);
	if(!empty($ooorl_opts)){
	   $ooorl_opts['version'] = OOORL_VERSION;
	   update_option(OOORL_OPTIONS, $ooorl_opts); 	
	}else{
	   $ooorl_opts = array(
		'version' => OOORL_VERSION,
		'enable' => 'true'		
	  );
	  // add the configuration options
	  add_option(OOORL_OPTIONS, $ooorl_opts);   	
	}	

}

/* 
 * Set up the social server plugin and load files at appropriate time. 
*/
function ooorl_setup(){
   /* Set constant path for the plugin directory */
   define('OOORL_DIR', plugin_dir_path(__FILE__));
   define('OOORL_ADMIN', OOORL_DIR.'/admin/');
   define('OOORL_INC', OOORL_DIR.'/include/');

   /* Set constant path for the plugin url */
   define('OOORL_URL', plugin_dir_url(__FILE__));
   define('OOORL_CSS', OOORL_URL.'css/');
   define('OOORL_JS', OOORL_URL.'js/');

   if(is_admin())
      require_once(OOORL_ADMIN.'admin.php');

   //set cookie
   add_action( 'init', 'ooorl_setcookie'); 
   /* wp_head action */
   add_action('wp_head', 'ooorl_wp_head');
   
   $ooorl_opts = get_option(OOORL_OPTIONS);
   $enter_url = $ooorl_opts['enter_redirect_url'];
   $leave_url = $ooorl_opts['leave_redirect_url'];
   $page = $ooorl_opts['page'];
   $default = $ooorl_opts['default'];
    
   if(!empty($enter_url)){
	  $processed_pages = array();
	  
	  $pages = explode(",", $page);
	  foreach($pages as $value){
		$processed_pages[] = trim($value);  
	  }
	  
	  $request_url = $_SERVER['REQUEST_URI'];
	  
	  //check whether user have visited
	  $checked = ( !empty($_COOKIE['ooorl_'.COOKIEHASH]) && 'checked' == $_COOKIE['ooorl_'.COOKIEHASH] ) ? true : false;	  
	  
	  if(!$checked){
		  
		  if($default == 'true'){
		    add_action('wp', 'ooorl_redirect');  
	      }else if($default == 'false'){
		    //var_dump($processed_pages);
		    //var_dump($request_url); 
		    if(in_array($request_url, $processed_pages)){ 
		      add_action('wp', 'ooorl_redirect');
	        }
	      }   
	    
	  }
	  
	  
	     
   }
   
   if(!empty($leave_url)){
	  add_filter('the_content', 'ooorl_external_link_info_content');   
   }
   
}

function ooorl_redirect(){
   $ooorl_opts = get_option(OOORL_OPTIONS); 	
   $redirect = $ooorl_opts['enter_redirect_url'];
   header ('Location: ' . $redirect);
}

function ooorl_wp_head($postID) {
	$link = get_permalink($postID);	
    //$bitly_url = ooorl_bitly($link);
    $ooorl_url = ooorl_get_url($link);
    $ooorl_opts = get_option(OOORL_OPTIONS);    
      echo '<link rel="shortlink" href="' . $ooorl_url . '" />';	
      //echo $_SERVER['REQUEST_URI'];
}
?>
