<?php
/*
  Plugin Name: Website Authority Checker
  Plugin URI: http://www.prepostseo.com/
  Description: WordPress Authority Checker plugin: This plugin will allow you to add authority checker tools directly to your website with no limit of usage.
  Version: 1.0
  Author: Ahmad Sattar
  Author URI: http://www.prepostseo.com/
  License: GPLv3+
*/

/*
Copyright (C) 2015 Ahmad Sattar, prepostseo.com (me AT prepostseo.com) 

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'WAC_VERSION' ) )
	define("WAC_VERSION", "1.0");

if(strlen(@get_option('wac_moz_key')) > 10)
	define("WAC_MOZ_KEY", get_option('wac_moz_key'));

if(strlen(@get_option('wac_moz_secret')) > 10)
	define("WAC_MOZ_SECRET", get_option('wac_moz_secret'));	

if(strlen(@get_option('wac_moz_valid')) > 1)
	define("WAC_MOZ_VALID", get_option('wac_moz_valid'));
	
class WAC_Authority_Checker{
	
	function __construct() {
		 add_action( 'admin_menu', array( $this, 'wac_wpa_add_menus' ) );
	}
	
	function  wac_wpa_add_menus()
	{
		 add_menu_page( 'Website Authority Checker', 'Authority Checker', 'manage_options', 'website-authority-checker', array(
                          __CLASS__,
                         'wac_wpa_files_path'
                        ), plugins_url('imgs/logo.png', __FILE__),'14.6');
		
		
		
	}
	
	
	
	function wac_wpa_files_path()
	{
		include('settingPage.php');
	}
	
	
	
	
	
	
	
	
	 /*
     * Actions perform on activation of plugin
     */
    function wac_wpa_install() {
	
    	
		if(strlen(@get_option('wac_moz_key')) < 10)
		{
			@add_option('wac_moz_key', "");
			@update_option('wac_moz_key', "");
			
			@add_option('wac_moz_secret', "");
			@update_option('wac_moz_secret', "");
			
			@add_option('wac_moz_valid', "");
			@update_option('wac_moz_valid', "");
			
		}
		
		
	}
	
	

	
}
new WAC_Authority_Checker();










register_activation_hook( __FILE__, array( 'WAC_Authority_Checker', 'wac_wpa_install' ) );



function wac_main_actions()
{
	include_once("actions.php");
}
add_action( 'wp_head', 'wac_top' );

add_action('init', 'wac_main_actions');

function wac_wp_style() {
		wp_register_style( 'wac_main_css', plugin_dir_url(__FILE__) . 'wac_style.css', false, '1.5' );
		wp_enqueue_style( 'wac_main_css' );
		
	}
add_action( 'wp_enqueue_scripts', 'wac_wp_style' );

function wac_top() {
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'wac_main_fn', plugin_dir_url(__FILE__) . 'js/wac.fn.js?v=1.0', array('jquery'));
		if(strlen(@get_option('wac_moz_key')) < 10)
		{
			add_action( 'admin_notices', 'wacNotification' );
		}
}





function wacNotification()
{
	if(strlen(@get_option('wac_moz_key')) < 10)
	{
		$msg = 'Website Authority Checker Plugin installed successfully, To activate this plugin please check <a href="'.admin_url().'admin.php?page=website-authority-checker">Setting Page</a> and follow instructions';
?>
	<div class="notice notice-warning is-dismissible">
		<p><?php _e( $msg, 'sample-text-domain' ); ?></p>
	</div>
<?php
	}
}

add_shortcode( 'authority-checker-tool', 'wac_shortcode' );

function wac_shortcode() {
	include('wac-boxhtml.php');
	return $wac_boxHtml;
}
