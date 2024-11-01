<?php
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) 
{ 
die('Direct Access not permitted...'); 
}

$_nonce_security = wp_create_nonce('wac-authority-plugin-security');
$wac_boxHtml = '<div class="wac_mainBox">
	<span id="pluginDir" style="display:none;">'.plugin_dir_url(__FILE__).'</span>
	<div class="wac_title">
    	Enter Url Below to check Website Authority Details:
    </div>
	
    <input type="hidden" id="wac_nonce_security" name="nonce_security" value="'.$_nonce_security.'"  />
	<div class="form-sec">
    	<input type="url" name="url" placeholder="Enter url here" id="wac-input-url" class="wac-input" />
    </div>
     <div class="form-sec" style="text-align:center;">
     	<span class="wac-sub-btn active" id="wac-check-btn">Check Authority</span>
     </div>
     <div class="form-sec" id="wac-result-title" style="text-align:center;font-size:24px; display:none;">
     	 --- Results ---
     </div>
     <div class="wac-results-sec" style="display:none;">
     	<table width="100%" style="margin-bottom:0px;">
        	
        	<tr>
            	<td width="30%" class="title">
                	Webapge URL
                </td>
                <td width="70%" id="wac-url">
                	
                </td>
            </tr>
            <tr>
            	<td class="title">Domain Authority</td>
                <td id="wac-da" class="wac-value">50</td>
            </tr>
            <tr>
            	<td class="title">Page Authority</td>
                <td id="wac-pa" class="wac-value">50</td>
            </tr>
            <tr>
            	<td class="title">Moz Rank</td>
                <td id="wac-mr" class="wac-value">50</td>
            </tr>
            <tr>
            	<td class="title">Links Equity</td>
                <td id="wac-le" class="wac-value">500</td>
            </tr>
			<tr>
            	<td class="title">IP Address</td>
                <td id="wac-ip" class="wac-value">500</td>
            </tr>
        </table>
     </div>
</div>';