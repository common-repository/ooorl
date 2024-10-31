<?php
//set cookie
function ooorl_setcookie() {
	setcookie('ooorl_'. COOKIEHASH, 'checked', time() + 30000000, COOKIEPATH);	
	
}
//create bit.ly url
function ooorl_bitly($url)
{
	//login information
	$login = 'darell';	//your bit.ly login
	$apikey = 'R_7edc48413e51301369ad7a52be587262'; //bit.ly apikey
	$format = 'json';	//choose between json or xml
	$version = '2.0.1';

	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$apikey.'&format='.$format;

	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);

	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

function ooorl_get_url($url){
   // EDIT THIS: the query parameters
   $format = 'json';				// output format: 'json', 'xml' or 'simple'

   // EDIT THIS: the URL of the API file
   $api_url = 'http://ooorl.com/shorturl/yourls-api.php';

   // Init the CURL session
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $api_url);
   curl_setopt($ch, CURLOPT_HEADER, 0);            // No header in the result
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return, do not echo result
   curl_setopt($ch, CURLOPT_POST, 1);              // This is a POST request
   curl_setopt($ch, CURLOPT_POSTFIELDS, array(     // Data to POST
		'url'      => $url,		
		'format'   => $simple,
		'action'   => 'shorturl'		
	));

   // Fetch and return content
   $data = curl_exec($ch);
   curl_close($ch);
  
   return $data;

}

function ooorl_external_link_info_content($str, $arg = 1) {
	if (!isset($str)) return $str;
	if (!$arg) return $str;
	return preg_replace_callback('#<a\s([^>]*\s*href\s*=[^>]*)>#i', 'ooorl_external_link_info_replace', $str);
}

function ooorl_external_link_check_local($href, $blogurl) {
	$schemes = array('http','ftp');
	$isext = false;
	foreach($schemes as $scheme) {
		if (stripos($href, $scheme) !== false) { $isext = true; }
/* var $href mit href prefix, zB.: href="http://wordpress.org/extend/plugins/wp-js-external-link-info/" */
	}
	if ($isext) {
		$local = ((strpos($href, $blogurl)) || (stripos($href, get_option('redirect_exclude'))));
	} else {
		$local = true;
	}
	return $local;
}

function ooorl_external_link_info_replace($matches) {
	$blogurl = get_bloginfo('home') . '/';
    $ooorl_opts = get_option(OOORL_OPTIONS); 	
    $redirect_url = $ooorl_opts['leave_redirect_url'];
    $redirect_file = OOORL_DIR . 'redirect.php'; 
	$str = $matches[1];
	preg_match_all('/[^=[:space:]]*\s*=\s*"[^"]*"|[^=[:space:]]*\s*=\s*\'[^\']*\'|[^=[:space:]]*\s*=[^[:space:]]*/', $str, $attr);
	$href_arr = preg_grep('/^href\s*=/i', $attr[0]);
	if (count($href_arr) > 0) {
         	$href = array_pop($href_arr);
		if ($href) {
			$local = ooorl_external_link_check_local($href, $blogurl);
                         if ( ( (get_option('redirect_notblank') < 2)
                             && ( (get_option('redirect_notblank') < 1) || (!is_user_logged_in()) ) )
			    && ($local === false) && ($href{6} != "#") ) {
                 		$blank = 'target="_blank"';
			}
		} else {
                 	$local = TRUE;
                 }

		if ( ($local === false) && ($href{6} != "#")
		  && ( (get_option('redirect_questsonly') != 'true') || (!is_user_logged_in()) ) ) {
  			$href = str_replace('?','%3F',$href);
  			$href = str_replace('&','%26',$href);
                         $href = preg_replace('/^(href\s*=\s*[\'"]?)/i', '\1' . $redirect_file . '?blog=' . WP_JS_BLOGNAME . '&url=', $href);
		}
		$attr = preg_grep('/^href\s*=/i', $attr[0], PREG_GREP_INVERT);
	}
    	return "<a href='" . $redirect_url . "'>";
}
?>
