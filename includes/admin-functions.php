<?php

if ( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * For namespacing purposes. Contains the register settings and add settings 
 * fields and their coresponding functions as well as the sanitization function.
 * Uses the admin_init hook to initialize the jsm_custom_login_admin_init function.
 */
class Jsm_Custom_Login_Functions {

	/**
	 * Calls the hook admin_init to Register settings, add settings section 
	 * and add the settings field.
	 */
	public function __construct(){
		add_action( 'admin_init', array( $this, 'jsm_custom_login_admin_init' ) );
	}

	/**
	 * Register the settings, create settings section, add settings fields.
	 * @return void
	 */
	public function jsm_custom_login_admin_init(){
		global $pagenow;
		register_setting( 'jsm_custom_login_options', 'jsm_custom_login_options', array( $this, 'jsm_custom_logo_sanitization' ) );
		add_settings_section( 'jsm_custom_login_main', 'Custom Login Settings', array( $this, 'jsm_custom_login_section_text' ), 'simple-login-customizer' );
		add_settings_field( 'jsm_custom_logo_upload_options',  '<i class="fa fa-upload"></i> Logo Upload', array( $this, 'jsm_custom_login_logo_upload' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'text_string' ) );
		add_settings_field( 'jsm_custom_logo_size',  '<i class="fa fa-arrows-alt"></i> Set Logo Size', array( $this, 'jsm_custom_login_logo_size' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'logo_size' ) );
		add_settings_field( 'jsm_custom_login_logo_preview', '<i class="fa fa-picture-o"></i> Logo Preview', array( $this, 'jsm_custom_login_setting_logo_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );
		add_settings_field( 'jsm_custom_login_logo_link_url', '<i class="fa fa-link"></i> Logo Link URL', array( $this, 'jsm_custom_login_setting_link_url' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'link_url' ) );
		add_settings_field( 'jsm_custom_login_logo_hover_title', '<i class="fa fa-globe"></i> Logo Image Title', array( $this, 'jsm_custom_login_setting_hover_title' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'hover_title' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_color', '<i class="fa fa-paint-brush"></i> Background Color', array( $this, 'jsm_custom_login_setting_bk_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_color' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_image', '<i class="fa fa-upload"></i> Background Image', array( $this, 'jsm_custom_login_setting_bk_image' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_image' ) );
		add_settings_field( 'jsm_custom_login_bk_image_preview', '<i class="fa fa-picture-o"></i> Background Image Preview', array( $this, 'jsm_custom_login_setting_bk_image_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );
		add_settings_field( 'jsm_custom_login_logo_text_color', '<i class="fa fa-paint-brush"></i> Text Color', array( $this, 'jsm_custom_login_setting_text_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_color', '<i class="fa fa-paint-brush"></i> Link Text Color', array( $this, 'jsm_custom_login_setting_link_text_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'link_text_color' ) );
		add_settings_field( 'jsm_custom_login_css', '<i class="fa fa-css3"></i> Custom CSS', array( $this, 'jsm_custom_login_setting_css_input' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'text_area' ) );

		// Replace text in logo upload.
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			add_filter( 'gettext', array( $this, 'jsm_custom_login_replace_thickbox_text' )  , 1, 3 );
		}
	}

	/**
	 * Explanations about the logo customizing section. Simple text description
	 * of the settings section jsm_custom_login_main
	 * @return html
	 */
	public function jsm_custom_login_section_text() {
		echo '<p>Settings to customize the login page.</p>';
	}

	/**
	 * Display and fill the text area for the custom css. This text is sanitized using
	 * the wp_kses() function.  This is redundant due to the variable $text_string being 
	 * run through the esc_html() function. Code sniffer calls for the return value to be escaped.
	 * @return html
	 */
	public function jsm_custom_login_setting_css_input() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_area' );
		$content = "<textarea id='text_area' name='jsm_custom_login_options[text_area]' type='textarea' rows='14' cols='50'>" . esc_attr( $text_string ) . '</textarea>';
		$allowed = array(
						'textarea' => array(
							'id' => array(),
							'name' => array(),
							'type' => array(),
							'rows' => array(),
							'cols' => array()
						)
					);
		echo wp_kses( $content, $allowed );
	}

	/**
	 * Display and fill the form field for the logo link url. This
	 * uses wp_kses() to sanitize the output.  The value variable is run through
	 * the esc_attr() function as well.
	 * @return html
	 */
	public function jsm_custom_login_setting_link_url() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_url' );
		$content = "<input id='link_url' name='jsm_custom_login_options[link_url]' type='text' value='" . esc_attr( $text_string ) . "' style='width:40%;'>";
		$content .= '<span class="description"><br />http://example.com</span>';
		$allowed = array(
						'input' => array(
							'id' => array(),
							'name' => array(),
							'type' => array(),
							'value' => array(),
							'style' => array()
						),
						'span' => array(
							'class' => array()
						),
						'br' => array()
					);
		echo wp_kses( $content, $allowed );
	}

	/**
	 * Display and fill the form field for the hover logo title.  The 
	 * value variable is escaped using esc_attr().
	 * @return html
	 */
	public function jsm_custom_login_setting_hover_title() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'hover_title' );
		echo "<input id='hover_title' name='jsm_custom_login_options[hover_title]' type='text' value='" . esc_attr( $text_string ). "' style='width:40%;'>";
	}

	/**
	 * Get the background color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_color' );
		$output = "<input id='bk_color' class='color' name='jsm_custom_login_options[bk_color]' type='text' value='" . esc_attr( $text_string ). "' style='width:40%;'>";
		$output .= "<br /><span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * The Text Color Option
	 * @return html
	 */
	public function jsm_custom_login_setting_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_color' );
		$output = "<input id='text_color' class='color' name='jsm_custom_login_options[text_color]' type='text' value='" . esc_attr( $text_string ). "' style='width:40%;'>";
		$output .= "<br /><span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Link text color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_link_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_text_color' );
		$output = "<input id='link_text_color' class='color' name='jsm_custom_login_options[link_text_color]' type='text' value='" . esc_attr( $text_string ). "' style='width:40%;'>";
		$output .= "<br /><span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Logo Upload
	 * @return html
	 */
	public function jsm_custom_login_logo_upload(){
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_string' );

		?>
	        <input type="text" id="text_string" name="jsm_custom_login_options[text_string]" value="<?php echo esc_attr( esc_url( $text_string ) ); ?>" />
	        <input id="upload_logo_button" type="button" class="button" value="<?php echo 'Upload Logo'; ?>" />
	        <span class="description"><?php echo 'Upload an image for the logo.'; ?></span>
	        <span class="description"><?php echo '<br />Image URL'; ?></span>
	    <?php
	}

	/**
	 * Callback to collect the logo size and display it if it is set.
	 * @return [type] [description]
	 */
	public function jsm_custom_login_logo_size() {
		$options = get_option( 'jsm_custom_login_options' );
		$logo_height = $this->jsm_custom_login_isset( $options, 'logo_height' );
		?>
	        <span class="description">Height</span><input type="text" id="logo_height" name="jsm_custom_login_options[logo_height]" value="<?php echo esc_attr( $logo_height ); ?>" />
	        <span class="description"><?php echo '<br />Example: 100px'; ?></span>
	    <?php
	}

	/**
	 * Show the current logo for preview.  
	 * @return html 
	 */
	public function jsm_custom_login_setting_logo_preview() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_string' );
		?>
		<div id="upload_logo_preview" style="min-height: 100px;">
	    	<img style="max-width:100%;" src="<?php echo esc_attr( $text_string ); ?>" />
		</div>
		<?php
	}

	/**
	 * Background image upload.
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_image() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_image' );
		$this->jsm_custom_login_isset( $options, 'image_pos' );
		?>
			<input type="text" id="bk_image" name="jsm_custom_login_options[bk_image]" value="<?php echo esc_url( $text_string ); ?>" />
			<select name="jsm_custom_login_options[image_pos]" id="image_pos" class="jsm_select">
				<option value="center" <?php echo ( $options['image_pos'] == 'center' ) ? 'selected' : ''; ?>>Center</option>
				<option value="repeat" <?php echo ( $options['image_pos'] == 'repeat' ) ? 'selected' : ''; ?>>Repeat</option>
			</select>
			<input id="upload_bk_image_button" type="button" class="button" value="<?php echo 'Upload Image'; ?>" />
			<span class="description"><?php echo '<br />Image URL'; ?></span>
		<?php
	}

	/**
	 * Show the current background image
	 * @return html 
	 */
	public function jsm_custom_login_setting_bk_image_preview() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_image' );
		?>
		<div id="upload_bk_image_preview" style="min-height: 100px;">
	    	<img style="max-width:100%;" src="<?php echo esc_url( $text_string ); ?>" />
		</div>
	<?php
	}

	/**
	 * Change text in file upload to make sense with what action that is taking place. The thickbox
	 * button shows 'Insert into Post' which is not user friendly since they are uploading an image
	 * to the settings page.  This filters the 'Insert into Post' and changes it to 'Select Image'.
	 * @param  string  the text of the thickbox
	 * @return string
	 */
	public function jsm_custom_login_replace_thickbox_text( $translated_text, $text, $domain ) {
		if ( 'Insert into Post' == $text ) {
	        $referer = strpos( wp_get_referer(), 'media-upload.php' );
	        if ( false != $referer ) {
	            return 'Select Image';
	        }
	    }
	    return $translated_text;
	}

	/**
	 * Text Validation
	 * @param  mixed
	 * @return string
	 */
	public function jsm_custom_login_validate_options( $input ) {
		$valid = array();
		$valid['text_string'] = preg_replace( '/[^a-zA-Z]/', '', $input['text_string'] );
		return $valid;
	}


	/**
	 * Sanitization of inputs. This function makes sure none of the inputs are harmful. It takes
	 * the input array of the form fields and uses various apropriate sanitization funtions to clean the 
	 * inputs.
	 * @param  array 
	 * @return array
	 */
	public function jsm_custom_logo_sanitization( $input ){
		$arg_array = get_option( 'jsm_custom_login_options' );

		// For size of the Logo
		$arg_array['logo_height'] = esc_html( $input['logo_height'] );

		// For background Color
		$arg_array['bk_color'] = $this->jsm_sanitize_hex_color( $input['bk_color'] );

		// Logo upload URL
		$arg_array['text_string'] = $input['text_string'];
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['text_string'] ) ) && ( $arg_array['text_string'] != '' ) ) {
  			add_settings_error( 'jsm_custom_login_options', 'invalid-url-logo', 'You have entered an invalid logo URL.' );
		}

		// Link url
		$arg_array['link_url'] = esc_url_raw( $input['link_url'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['link_url'] ) ) && ( $arg_array['link_url'] != '' ) ) {
  			add_settings_error( 'jsm_custom_login_options', 'invalid-url-link', 'You have entered an invalid link URL.' );
		}

		// Text for the title
		$arg_array['hover_title'] = esc_html( $input['hover_title'] );

		// Background image URL
		$arg_array['bk_image'] = esc_url_raw( $input['bk_image'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['bk_image'] ) ) && ( $arg_array['bk_image'] != '' ) ) {
  			add_settings_error( 'jsm_custom_login_options', 'invalid-url-bk-image', 'You have entered an invalid background image URL.' );
		}

		// Text color hex value.
		$arg_array['text_color'] = $this->jsm_sanitize_hex_color( $input['text_color'] );

		// CSS test_area sanitization
		$arg_array['text_area'] = wp_kses( $input['text_area'], $allowed );

		// Custom CSS link text color hex value
		$arg_array['link_text_color'] = $this->jsm_sanitize_hex_color( $input['link_text_color'] );

		// Image position repeat or center.
		if ( ( $input['image_pos'] != 'repeat' ) && ( $input['image_pos'] != 'center' ) ) {
			$arg_array['image_pos'] = 'repeat';
		}
		else {
			$arg_array['image_pos'] = esc_html( $input['image_pos'] );
		}
		return $arg_array;
	}

	/**
	 * Check the array element to make sure it is set.  If it is not make it empty.
	 * This avoids errors on install.
	 * @param  [array] $array_key 
	 * @return [string]
	 */
	public function jsm_custom_login_isset( &$options, $key ){
		if ( isset( $options[$key] ) ){
			return $options[$key];
		}
		else {
			// Add default settings here
			switch ($key) {
				case 'link_text_color':
					$options[$key] = '00A5E2';
					break;
				case 'link_url':
					$options[$key] = 'yoursite.com';
					break;
				case 'hover_title':
					$options[$key] = 'L7 Login Customizer';
					break;
				case 'text_color':
					$options[$key] = '000';
					break;
				case 'logo_height':
					$options[$key] = '200px';
					break;
				default: 
					$options[$key] = '';
			}
			return $options[$key];
		}
	}

	/**
	 * Cleans color hex value. Removes all unwanted characters. And uses esc_html to make sure
	 * the data is not harmful.
	 * @param  [string] $string an option array element hex value
	 * @return [string]         the sanitized hex value
	 */
	private function jsm_sanitize_hex_color( $string ) {
		$string = esc_html( substr( str_replace( '#', '', $string ), 0, 6 ) );
		return $string;
	}
}
