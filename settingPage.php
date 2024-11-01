<?php

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}
	
	
	
	function wac_getPage($url, $param = '')
	{
		
		if(!check_ajax_referer( 'wac-authority-plugin-security', 'nonce_security'))
		{
			wp_die();
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); // Define target site
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Return page in string
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // Follow redirects
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		if(!empty($param['postData']))
		{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param['postData']);
		}
		$page = curl_exec($ch);
		
		return $page;
		
	}
	
	
	
	
	$wac_moz_key = @get_option("wac_moz_key");
	$wac_moz_secret = @get_option("wac_moz_secret");
	
	if(!empty($_POST['submit']) and !empty($_POST['wac_moz_key']) and !empty($_POST['wac_moz_secret']))
	{
		$wac_moz_key = sanitize_text_field($_POST['wac_moz_key']);
		$wac_moz_secret = sanitize_text_field($_POST['wac_moz_secret']);
		@update_option('wac_moz_key', $wac_moz_key);
		@update_option('wac_moz_secret', $wac_moz_secret);
		$validate = 1;
	}
	
	
	if(!empty($validate))
	{
		
		include('lib/checkAuthority.php');
		$data = wac_getDomainDetails("https://www.facebook.com", $wac_moz_key, $wac_moz_secret);
		
		if(!empty($data['domain_auth']) and is_numeric($data['domain_auth']))
		{
			@update_option('wac_moz_valid', "true");
		} else {
			@update_option('wac_moz_valid', "false");
		}
	}
	
	$wac_moz_valid = @get_option("wac_moz_valid");
	
	
	
	

	

?>

<style>
.wac-setting-table {
		border-collapse:collapse;
		width:800px;
		background:#fff;
		margin-top:40px;
	}
	.wac-setting-table td {
		padding:10px;
		border:1px solid #ccc;
		box-shadow: 0px 0px 10px 2px #EBEBEB inset;
		
	}
	.wac-setting-table tr td:first-child {
		font-weight:700;
		color: #156780;
	}
</style>

<?php if($wac_moz_valid == "true"): ?>



<form method="post" action="">
<table  class="wac-setting-table">
	<tr>
    	<td colspan="2" style="background:#EBEBEB; color:#000; font-size:16px; text-align:center;">
        	<span style="line-height:30px; text-shadow:1px 1px 1px #fff; margin-right:-70px;">- Plugin Setting -</span> 
        </td>
    </tr>
    
    <tr>
        <td colspan="2" style="color:#000;">
         <span  style="color:green;">Great!</span> Every thing Looks fine.  <br>
         Please place the following short code where you want to display authority checker tool. <br>
         <p style="text-align:center;">
         	<code>[authority-checker-tool]</code>
         </p>
        </td>
    </tr>
    
    
    <tr>
        <td width="30%">Moz Access ID</td>
        <td>
                <input type="text" name="wac_moz_key" value="<?php echo @esc_html($wac_moz_key); ?>" style="width:90%; border:1px solid #156780; padding:10px;" />
        </td>
    </tr>
    <tr>
        <td width="30%">Moz Secret Key</td>
        <td>
                <input type="text" name="wac_moz_secret" value="<?php echo @esc_html($wac_moz_secret); ?>" style="width:90%; border:1px solid #156780; padding:10px;" />
        </td>
    </tr>
	<tr>
        <td colspan="2" align="center">
            <input type="submit" name="submit" value="Save Changes" class="button-primary" />
        </td>
    </tr>
	
        
    

</table>
</form>



<?php else: ?>



<form method="post" action="">
<table  class="wac-setting-table">
	<tr>
    	<td colspan="2" style="background:#EBEBEB; color:#000; font-size:16px; text-align:center;">
        	<span style="line-height:30px; text-shadow:1px 1px 1px #fff; margin-right:-70px;">- Plugin Setting -</span> 
        </td>
    </tr>
    
    <?php if(!empty($wac_moz_key)): ?>
    <tr>
        <td colspan="2" style="color:#F95657;">
           Please Use Valid moz Access ID and Moz Secret Key. If you having problem in Validating 
           API Keys please <a href="https://www.prepostseo.com/contact" target="_blank">let us know</a> and we will help you to solve this issue.
            
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td colspan="2" style="color:#000;">
        	Please follow instructions to fully functional authority checker tool. <br>
            <ol>
            	<li>Create Account at Moz (<a href="https://moz.com/community/join" target="_blank">Check here to create account</a>) ,  
                if you are already a member you can <a href="https://moz.com/login" target="_blank">login at moz</a>.</li>
            	<li>Activate your account by checking your email address.</li>
                <li>Go to the <a href="https://moz.com/products/api/keys" target="_blank">Moz API keys page</a> and click on “<span style="color:red;">Manage Mozscape API Key</span>” button.</li>
                <li>Then Click on “<span  style="color:green;">Generate Key</span>” Button.</li>
                <li>Now Copy <i style="color:#828282;">Access ID</i> and <i style="color:#828282;">Secret Key</i> from that page and paste it in the input boxes below and click on <i style="color:#828282;">Save Changes</i> Button</li>
            </ol>
            
        </td>
    </tr>
    
    
    <tr>
        <td width="30%">Moz Access ID</td>
        <td>
                <input type="text" name="wac_moz_key" value="<?php echo @esc_html($wac_moz_key); ?>" style="width:90%; border:1px solid #156780; padding:10px;" />
        </td>
    </tr>
    <tr>
        <td width="30%">Moz Secret Key</td>
        <td>
                <input type="text" name="wac_moz_secret" value="<?php echo @esc_html($wac_moz_secret); ?>" style="width:90%; border:1px solid #156780; padding:10px;" />
        </td>
    </tr>
	<tr>
        <td colspan="2" align="center">
            <input type="submit" name="submit" value="Save Changes" class="button-primary" />
        </td>
    </tr>
	
        
    

</table>
</form>


<?php
endif;
?>

