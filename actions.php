<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}



if(!empty($_POST['wac_check_authority']))
{
	if(!check_ajax_referer( 'wac-authority-plugin-security', 'nonce_security'))
	{
		wp_die();
	}
	
	if(!empty($_POST['url']) and filter_var($_POST['url'], FILTER_VALIDATE_URL))
	{
		
		$wac_moz_key = @get_option("wac_moz_key");
		$wac_moz_secret = @get_option("wac_moz_secret");
		$wac_moz_valid = @get_option("wac_moz_valid");
		if($wac_moz_valid == 'true')
		{
			include('lib/checkAuthority.php');
			$data = wac_getDomainDetails(esc_url($_POST['url']), $wac_moz_key, $wac_moz_secret);
			echo  json_encode($data);
		} else {
			echo 'Invalid Key Used, Check setting page to solve it';
		}
		exit;	
	} else {
		echo 'Invalid URL used';
		exit;
	}
}


