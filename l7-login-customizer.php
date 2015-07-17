<?php
/**
 * Plugin Name: l7 Login Customizer
 * Plugin URI: layer7web.com
 * Description: For customizing the login, logout, and register pages.
 * Version: 2.0.2
 * Author: Jeff Mattson
 * Author URI: https://github.com/jeffreysmattson
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
Copyright 2015 Jeffrey S. Mattson (email : jeff@fusioncode.org)
This program is free software; you can redistribute it and/ or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

See the GNU General Public License for more details. You should
have received a copy of the GNU General Public License along with this
program; if not, write to the Free Software Foundation, Inc.,
51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( is_admin() ){
	include_once( plugin_dir_path( __FILE__ ) . 'includes/admin-functions.php' );
	include_once( plugin_dir_path( __FILE__ ) . 'includes/options-page.php' );
}

if ( ! class_exists( 'JsmGenericClass' )  ) {
	class JsmGenericClass {

		/**
		 * Adds action to insert the custom css into the head of the login page. Adds filters to
		 * change the header URL as well as the header title and creates a settings link for the
		 * plugin page for easy navigation. 
		 */
		public function __construct() {
			add_action( 'login_head', array( $this, 'jsm_my_login_head' ) );
			add_filter( 'login_headerurl', array( $this, 'jsm_my_login_logo_url' ), 15 );
			add_filter( 'login_headertitle', array( $this, 'my_login_logo_url_title' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'jsm_custom_login_options_enqueue_scripts' ) );

			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", array( $this, 'jsm_custom_login_settings_link') );
		}

		/**
		 * Takes an array of links for the plugin page and adds a new one to it
		 * for the plugins page.
		 * @param  [array] $links The wordpress array of links for the plugins page.
		 * @return [array]        Returns the link array with the new link added.
		 */
		function jsm_custom_login_settings_link( $links ) {
			$url = explode( '/', plugin_basename( __FILE__ ) );
			$plugin_name = $url[0];
			$settings_link = '<a href="options-general.php?page=' . esc_attr( $plugin_name ) . '/includes/options-page">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		/**
		 * The uploader js.  Uploader pop-up box. 
		 * Enqueue the script only on the simple login settings page.
		 * @return void
		 */
		public function jsm_custom_login_options_enqueue_scripts() {
			$url = explode( '/', plugin_basename( __FILE__ ) );
			$plugin_name = $url[0];
			$settings_page_url = 'settings_page_' . $plugin_name . '/includes/options-page';
			wp_register_script( 'jsm-upload', plugins_url( 'js/jsm-upload.js', __FILE__ ) , array('jquery', 'media-upload', 'thickbox') );
			wp_register_script( 'jsm-color', plugins_url( 'js/color/jscolor.js', __FILE__ ) , array('jquery') );
			if ( $settings_page_url == get_current_screen() -> id ) {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'jsm-upload' );
				wp_enqueue_script( 'jsm-color' );
			}

			// For Font Awesome on Settings page
			wp_register_style( 'jsm-font-awesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
			wp_register_style( 'jsm-main-css' , plugins_url( 'css/jsm-main.css', __FILE__ ) );
			if ( $settings_page_url == get_current_screen() -> id ) {
				wp_enqueue_style( 'jsm-font-awesome' );
				wp_enqueue_style( 'jsm-main-css' );
			}
		}

		/**
		 * This is where are the settings are applied. The settings are inserted into
		 * the content variable and returned as sanitized <style> code.
		 * @return [html]  Sanitized css inside of <style> tags
		 */
		public function jsm_my_login_head() {
			$options = get_option( 'jsm_custom_login_options' );

			// CSS values for the logo image
			$login_logo_image_css_values = 'background-image: url(' . $options['text_string'] . ');';

			// Size for logo image
			$login_logo_image_css_values .= 'background-size:' . $options['logo_height'] . ';';

			// Logo image container
			$login_logo_image_container_css_values = 'height:' . $options['logo_height'] . ' !important;';
			$login_logo_image_container_css_values .= 'width:inherit;';

			// Login form background
			$login_logo_form_background = 'background-color:#' . $options['form_background'] . ';';
			$login_logo_form_background .= 'padding:30px 0 0;';
			$login_logo_form_background .= 'margin-top:30px;';

			// Link hover color
			$login_logo_form_link_hover = 'color:#' . $options['link_text_hover_color'] . ';';

			// If the text field is not blank. Then add the hex value.
			if ( $options['link_text_color'] != '' ){
				$login_link_css_values = 'color:#' . $options['link_text_color'] . ';';
			}

			// Color of the background
			if ( $options['bk_color'] != '' ){
				$body_html_css_values .= 'background-color:#' . $options['bk_color'] . ';';
			}

			// Color of the text in the login form.
			if ( $options['text_color'] != '' ){
				$login_label_css_values = 'color:#' . $options['text_color'] . ' !important;';
			}

			// If the text field is not blank add the image URL. If repeat/center is selected
			if ( $options['bk_image'] != '' ){
				$body_html_css_values .= 'background-image: url(' . $options['bk_image'] . ') !important;';
				if ( 'repeat' == $options['image_pos'] ){
					$body_html_css_values .= 'background-repeat: repeat;';
				}
				else {
					$body_html_css_values .= 'background-repeat: no-repeat;  background-position: center;';
				}
			}

			$css_content = array();

			// The body and html tags.
			$css_content['body_html'] = 'body, html {' . $body_html_css_values . '}';

			// The login nav and back to blog a links.
			$css_content['login_link'] = '.login #nav a, .login #backtoblog a {' . $login_link_css_values . '}';

			// The login nav and back to blog a links color on hover.
			$css_content['login_link'] = '.login #nav a:hover, .login #backtoblog a:hover {' . $login_logo_form_link_hover . '}';

			// The login label
			$css_content['login_label'] = '.login label {' . $login_label_css_values . '}';

			// Login logo image.
			$css_content['login_logo_image'] = 'body.login #login h1 a {' . $login_logo_image_css_values . '}';

			// Login logo container
			$css_content['login_logo_container'] = '.login h1 a {' . $login_logo_image_container_css_values . '}';

			// Login Form Background set to transparent
			$css_content['login_form'] = '.login form {background:transparent;}';

			// Login Message
			$css_content['login_message'] = '.login .message {background:transparent;}';

			// Form Background
			$css_content['form_background'] = '#login {' . $login_logo_form_background . '}';

			ob_start();
			?>
			<style>
				<?php
				foreach ( $css_content as $css ){
					echo esc_html( $css );
				}
				?> 
			</style>
			<?php
			$content .= ob_get_contents();
			ob_end_clean();

			$allowed = array(
						'style' => array(),
						);
			echo wp_kses( $content, $allowed );
		}

		/**
		 * The link address of the logo
		 * @return [string]
		 */
		public function jsm_my_login_logo_url() {
			$options = get_option( 'jsm_custom_login_options' );
			$text_string = $options['link_url'];
			return $text_string;
		}

		/**
		 * The title of the image
		 * @return [string]
		 */
		public function my_login_logo_url_title() {
			$options = get_option( 'jsm_custom_login_options' );
			$text_string = $options['hover_title'];
			return $text_string;
		}
	}
}

// Initialize Plugin Object
if ( class_exists( 'JsmGenericClass' ) && ! isset( $JsmGenericClass ) ) {
	$JsmGenericClass = new JsmGenericClass();
}

// Create Settings Page
if ( class_exists( 'Jsm_Settings_Display' ) && ! isset( $jsm_settings_display ) ) {
	$jsm_settings_display = new Jsm_Settings_Display();
}

// Create the Settings Function Object
if ( class_exists( 'Jsm_Custom_Login_Functions' ) && ! isset( $jsm_custom_login_functions ) ) {
	$jsm_custom_login_functions = new Jsm_Custom_Login_Functions();
}

