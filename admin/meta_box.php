<?php
function ooorl_info_meta_box(){
  echo "<p><a href='http://ooorl.com'>OOORL.COM - PopUp Url</a></p>";
  echo "<p>Shorten your url with the Pop Short Url.</p>"; 
}
function ooorl_leave_meta_box(){
  $ooorl_opts = get_option(OOORL_OPTIONS);
  $leave_url = $ooorl_opts['leave_redirect_url']; 
?>
  <table class="form-table">
      <tr>
    		<th>
            	<label for="leave_url"><?php _e( 'Input your redirect url:', 'ooorl' ); ?></label> 
            </th>
            <td>
            	<input id="leave_url" name="leave_url" type="text" value="<?php if(!empty($leave_url)){echo $leave_url;} ?>" />
            </td>
		</tr>	
  </table>
<?php	
}

function ooorl_enter_meta_box(){
  $ooorl_opts = get_option(OOORL_OPTIONS);
  $enter_url = $ooorl_opts['enter_redirect_url']; 
  $page = $ooorl_opts['page'];
  $default = $ooorl_opts['default'];
 
?>
  <table class="form-table">
      <tr>
            <th>
                <label for="page"><?php _e( 'Redirection happends when:', 'ooorl' ); ?></label>
            </th>
            <td>
                
                 
                     <input  type="radio" <?php if(isset($default) && $default == 'true'){echo 'checked="checked"';} ?> value="true" name="default">
                       All pages
                 
                <br />
                
                
                 
                     <input type="radio" <?php if(isset($default) && $default == 'false'){echo 'checked="checked"';} ?> value="false" name="default">
                       Specified pages
                 
                <br />
                <textarea rows="4" name="page"><?php if(!empty($page)){echo $page; } ?></textarea>
	        <br />
		      <span>You can input multiple pages(the url after your host name) ,and separate them by comma.</span>
                
            </td>
       </tr> 
       
       <tr>
    		<th>
            	<label for="enter_url"><?php _e( 'Input your redirect url:', 'ooorl' ); ?></label> 
            </th>
            <td>
            	<input id="enter_url" name="enter_url" type="text" value="<?php if(!empty($enter_url)){echo $enter_url;} ?>" />
            </td>
		</tr>	
  </table>
<?php
}
