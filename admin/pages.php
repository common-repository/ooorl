<?php
function ooorl_main_settings(){
   global $ooorl;    
   add_meta_box( 'ooorl-enter-meta-box', __( 'Enter Site', 'ooorl' ), 'ooorl_enter_meta_box', $ooorl->main_page, 'enter', 'high' );
   add_meta_box( 'ooorl-leave-meta-box', __( 'Leave Site', 'ooorl' ), 'ooorl_leave_meta_box', $ooorl->main_page, 'leave', 'high' );
   add_meta_box( 'ooorl-info-meta-box', __( 'Plugin Info', 'ooorl' ), 'ooorl_info_meta_box', $ooorl->main_page, 'info', 'high' );
   
}

function ooorl_main_page(){
  global $ooorl;

	$plugin_data = get_plugin_data( OOORL_DIR . 'ooorl.php' ); ?>

	<div class="wrap">
		
        <?php if ( function_exists( 'screen_icon' ) ) screen_icon(); ?>
        
		<h2><?php _e( 'OOORL Settings', 'ooorl' ); ?></h2>
        <?php if ( isset( $_GET['updated'] ) && 'true' == esc_attr( $_GET['updated'] ) ) ooorl_updated_message(); ?>	
        <form id="ooorl" method="post"> 
		<div id="poststuff">			               
				<div class="metabox-holder">
					<div class="post-box-container column-1 enter"><?php do_meta_boxes( $ooorl->main_page, 'enter', $plugin_data ); ?></div>
                    <div class="post-box-container column-2 info"><?php do_meta_boxes( $ooorl->main_page, 'info', $plugin_data ); ?></div>   					
		            <div class="post-box-container column-1 leave"><?php do_meta_boxes( $ooorl->main_page, 'leave', $plugin_data ); ?></div>
		       	</div>						
		</div><!-- #poststuff -->
		<br class="clear">
        <input class="button button-primary" type="submit" value="<?php _e('save'); ?>" name="ooorl_settings" />
        </form>
	</div><!-- .wrap -->  	
<?php
}
?>
