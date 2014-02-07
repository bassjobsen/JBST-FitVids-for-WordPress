<?php
/*
Plugin Name: JBST FitVids for WordPress
Plugin URI: https://github.com/bassjobsen/JBST-FitVids-for-WordPress/
Description: This plugin makes videos responsive using the FitVids jQuery plugin on WordPress.
Version: 1.0.0
Tags: videos, fitvids, responsive
Author: Bass Jobsen
Author URI: http://bassjobsen.weblogs.fm/
License: GPLv2
*/

/*  Copyright 2013 Bass Jobsen (email : bass@w3masters.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// protect yourself
if ( !function_exists( 'add_action') ) {
	echo "Hi there! Nice try. Come again.";
	exit;
}

class fitvids_wp {
	// when object is created
	function __construct() {
		add_action('admin_menu', array($this, 'menu')); // add item to menu
		add_action('wp_enqueue_scripts', array($this, 'fitvids_scripts')); // add fit vids to site
	}

	// make menu
	function menu() {
		add_submenu_page('themes.php', 'FitVids for WordPress', 'FitVids', 'switch_themes', __FILE__,array($this, 'settings_page'), '', '');
	}

	// create page for output and input
	function settings_page() {
		?>
	    <div class="icon32" id="icon-themes"><br></div>
	    <div id="fitvids-wp-page" class="wrap">
	    
	    <h2>FitVids for WordPress</h2>
	    
	    <?php
	    // $_POST needs to be sanitized by version 1.0
	   	if( isset($_POST['submit']) && check_admin_referer('fitvids_action','fitvids_ref') ) {
			  $fitvids_wp_message = '';


   		update_option('fitvids_wp_selector', esc_js(trim($_POST['fitvids_wp_selector'])));
        update_option('fitvids_wp_custom_selector',  esc_js(trim($_POST['fitvids_wp_custom_selector'])));
	   		

	   		echo '<div id="message" class="updated below-h2"><p>FitVids is updated. ', $fitvids_wp_message ,'</p></div>';
	   	}
	    ?>
	    
	    <form method="post" action="<?php echo esc_attr($_SERVER["REQUEST_URI"]); ?>">
		  <?php
		  wp_nonce_field('fitvids_action','fitvids_ref');
		  $checked = '';
	    if(get_option('fitvids_wp_jq') == 'true') { $checked = 'checked="checked"'; }
	    ?>

      <table class="form-table">
	    <tbody>
	    <tr>
		  <td>

			<h3 style="font-weight: bold;"><label for="fitvids_wp_selector">Enter jQuery Selector</label></h3>
			<p>Add a CSS selector for FitVids to work. <a href="http://www.w3schools.com/jquery/jquery_selectors.asp" target="_blank"> Need help?</a></p>
			<p><em>jQuery(" <input id="fitvids_wp_selector" value="<?php echo get_option('fitvids_wp_selector','.entry-content'); ?>" name="fitvids_wp_selector" type="text"> ").fitVids();</em></p>

			<h3 style="font-weight: bold;"><label for="fitvids_wp_custom_selector">Enter FitVids Custom Selector</label></h3>
			<p>Add a custom selector for FitVids if you are using videos that are not supported by default. <a href="https://github.com/davatron5000/FitVids.js#add-your-own-video-vendor" target="_blank"> Need help?</a></p>
			<p><em>jQuery().fitVids({ customSelector: " <input id="fitvids_wp_custom_selector" value="<?php echo stripslashes(get_option('fitvids_wp_custom_selector','')); ?>" name="fitvids_wp_custom_selector" type="text"> "});</em></p>


			<p class="submit"><input type="submit" name="submit" class="button-primary" value="Save Changes" /></p>

		  </td>
	    </tr>
	    </tbody>
      </table>
	    </form>
	    
	    </div>
	    
	    <?php }
    
    // add FitVids to site
    function fitvids_scripts() {
   	// add fitvids
    	wp_register_script( 'fitvids', plugins_url('/jquery.fitvids.js', __FILE__), array('jquery'), '1.0', true);    	
    	wp_enqueue_script( 'fitvids');
    	$fitvids_settings = array (
    	'wp_selector'=>get_option('fitvids_wp_selector','.entry-content'),
    	'wp_custom_selector'=>get_option('fitvids_wp_custom_selector','')
    	);
    	wp_localize_script('fitvids', 'fitvids_settings',$fitvids_settings);
    } // end fitvids_scripts

} // end fitvids_wp obj

new fitvids_wp();
