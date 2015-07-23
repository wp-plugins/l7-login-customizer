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
		add_settings_section( 'jsm_custom_login_main', 'L7 Login Customizer', array( $this, 'jsm_custom_login_section_text' ), 'simple-login-customizer' );
		add_settings_field( 'jsm_custom_logo_upload_options',  '<i class="fa fa-upload"></i> Logo Upload', array( $this, 'jsm_custom_login_logo_upload' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'text_string' ) );
		add_settings_field( 'jsm_custom_logo_size',  '<i class="fa fa-arrows-alt"></i> Set Logo Size', array( $this, 'jsm_custom_login_logo_size' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'logo_size' ) );
		add_settings_field( 'jsm_custom_login_logo_preview', '<i class="fa fa-picture-o"></i> Logo Preview', array( $this, 'jsm_custom_login_setting_logo_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );
		add_settings_field( 'jsm_custom_login_logo_link_url', '<i class="fa fa-link"></i> Logo Link URL', array( $this, 'jsm_custom_login_setting_link_url' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'link_url' ) );
		add_settings_field( 'jsm_custom_login_logo_hover_title', '<i class="fa fa-globe"></i> Logo Image Title', array( $this, 'jsm_custom_login_setting_hover_title' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'hover_title' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_color', '<i class="fa fa-paint-brush"></i> Page&#8217;s Background Color', array( $this, 'jsm_custom_login_setting_bk_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_color' ) );
		add_settings_field( 'jsm_custom_login_form', '<i class="fa fa-paint-brush"></i> Form&#8217;s Background Color', array( $this, 'jsm_custom_login_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'form_background' ) );
		add_settings_field( 'jsm_custom_login_small_form', '<i class="fa fa-paint-brush"></i> Small Form&#8217;s Background Color', array( $this, 'jsm_custom_login_small_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'small_form_background' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_image', '<i class="fa fa-upload"></i> Background Image', array( $this, 'jsm_custom_login_setting_bk_image' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_image' ) );
		add_settings_field( 'jsm_custom_login_bk_image_preview', '<i class="fa fa-picture-o"></i> Background Image Preview', array( $this, 'jsm_custom_login_setting_bk_image_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );
		add_settings_field( 'jsm_custom_login_logo_text_color', '<i class="fa fa-paint-brush"></i> Text Color', array( $this, 'jsm_custom_login_setting_text_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_color', '<i class="fa fa-paint-brush"></i> Link Text Color', array( $this, 'jsm_custom_login_setting_link_text_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'link_text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_hover_color', '<i class="fa fa-paint-brush"></i> Link Text Hover Color', array( $this, 'jsm_custom_login_setting_link_text_hover_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'link_text_hover_color' ) );

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
		echo '<p>Change settings here to customize the login, logout, and registration pages.</p>';
	}

	/**
	 * Logo Upload. This contains the logo upload text field, logo upload button
	 * and short description.
	 * @return html
	 */
	public function jsm_custom_login_logo_upload(){
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_string' );
        $content = "<div class='input-group' style='width:40%;'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_logo_button' type='button'>Select</button>
        				</span>
        				<input id='text_string' name='jsm_custom_login_options[text_string]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Logo url here or click select' />
        			</div>";
        $content .= "<span class='description'>Upload or choose an image for the logo.</span>";
	 	echo $content;
	}

	/**
	 * Callback to collect the logo size and display it if it is set.
	 * @return [type] [description]
	 */
	public function jsm_custom_login_logo_size() {
		$options = get_option( 'jsm_custom_login_options' );
		$logo_height = $this->jsm_custom_login_isset( $options, 'logo_height' );	
	    $content = "<div class='input-group' style='width:40%;'><span class='input-group-addon'><i>Height</i></span><input id='logo_height' name='jsm_custom_login_options[logo_height]' type='text' value='" . esc_attr( $logo_height ) . "' class='form-control' /></div>";
	    $content .= "<span class='description'>Example: 100px</span>";
	    echo $content;
	}

	/**
	 * Show the current logo for preview.  Displays the currently set logo.  
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
	 * Display and fill the form field for the logo link url.  This is the where the user is directed
	 * when they click on the logo. This uses wp_kses() to sanitize the output.  
	 * The value variable is run through the esc_attr() function as well.
	 * @return html
	 */
	public function jsm_custom_login_setting_link_url() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_url' );
		$content = "<div class='input-group' style='width:40%;'><input id='link_url' name='jsm_custom_login_options[link_url]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' /></div>";
		$content .= '<span class="description">http://example.com</span>';
		$allowed = array(
						'input' => array(
							'id' => array(),
							'name' => array(),
							'type' => array(),
							'value' => array(),
							'style' => array(),
							'class' => array(),
						),
						'span' => array(
							'class' => array()
						),
						'br' => array(),
						'div' => array(
							'class' => array(),
							 'style' => array()
						),
					);
		echo wp_kses( $content, $allowed );
	}

	/**
	 * Display and fill the form field for the Logo image title.  The 
	 * value variable is escaped using esc_attr().
	 * @return html
	 */
	public function jsm_custom_login_setting_hover_title() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'hover_title' );
		$output = "<div class='input-group' style='width:40%;'><input id='hover_title' name='jsm_custom_login_options[hover_title]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' /></div>";
		echo $output;
	}

	/**
	 * The entire page's background color. Page's Background Color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_color' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='bk_color' name='jsm_custom_login_options[bk_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Option for the background color of the large form. This is the big container
	 * that contains all of the links and login fields.  Set to #fff as default in 
	 * the css. 
	 * @return html 
	 */
	public function jsm_custom_login_forms_background() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'form_background' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='form_background' name='jsm_custom_login_options[form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Small form option for background color. This is the part of the
	 * form that does not include the logo or the other links on the bottom.
	 * This is the text fields container with class .login form.
	 * @return html 
	 */
	public function jsm_custom_login_small_forms_background() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'small_form_background' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='small_form_background' name='jsm_custom_login_options[small_form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Background image upload.
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_image() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_image' );
		$this->jsm_custom_login_isset( $options, 'image_pos' );
		$content = "<div class='input-group' style='width:40%;'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_bk_image_button' type='button'>Select</button>
        				</span>
        				<input id='bk_image' name='jsm_custom_login_options[bk_image]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Image url here or click select.' />
					</div>
					<div name='jsm_custom_login_options[image_pos]' class='input-group' style='width:40%;'>	
						<select class='form-control' id='image_pos' name='jsm_custom_login_options[image_pos]'>
						  <option value='repeat' ";
		$content .= ( $options['image_pos'] == 'repeat' ) ? 'selected' : '';
		$content .= ">Repeat</option>
						  <option value='center' ";
		$content .= ( $options['image_pos'] == 'center' ) ? 'selected' : '';
		$content .= ">Center</option>
						</select>	
        			</div>";
	 	echo $content;
	}

	/**
	 * Show the current background image.  Displays the currently chosen and saved background image.
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
	 * The Text Color Option
	 * @return html
	 */
	public function jsm_custom_login_setting_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_color' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='text_color' name='jsm_custom_login_options[text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Link text color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_link_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_text_color' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='link_text_color' name='jsm_custom_login_options[link_text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Link text hover color. For the links to register and return to homepage.
	 * @return html 
	 */
	public function jsm_custom_login_setting_link_text_hover_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_text_hover_color' );
		$output = "<div class='input-group color-picker' style='width:40%;'><input id='link_text_hover_color' name='jsm_custom_login_options[link_text_hover_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /><span class='input-group-addon'><i></i></span></div>";
		$output .= "<br /><span class='description'>6 digit hex color code</span>";
		echo $output;
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

		// Form Background hex value.
		$arg_array['form_background'] = $this->jsm_sanitize_hex_color( $input['form_background'] );

		// Form Background hex value.
		$arg_array['small_form_background'] = $this->jsm_sanitize_hex_color( $input['small_form_background'] );

		// CSS test_area sanitization
		$arg_array['text_area'] = wp_kses( $input['text_area'], $allowed );

		// Custom CSS link text color hex value
		$arg_array['link_text_color'] = $this->jsm_sanitize_hex_color( $input['link_text_color'] );

		// Custom CSS link text color hex value
		$arg_array['link_text_hover_color'] = $this->jsm_sanitize_hex_color( $input['link_text_hover_color'] );

		// Image position repeat or center.
		if ( ( 'repeat' != $input['image_pos'] ) && ( 'center' != $input['image_pos'] ) ) {
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
					$options[$key] = '999999';
					break;
				case 'link_url':
					$options[$key] = '';
					break;
				case 'hover_title':
					$options[$key] = 'Logo Hover Title';
					break;
				case 'text_color':
					$options[$key] = '777';
					break;
				case 'logo_height':
					$options[$key] = '100px';
					break;
				case 'form_background':
					$options[$key] = '';
					break;
				case 'small_form_background':
					$options[$key] = '';
					break;
				case 'link_text_hover_color':
					$options[$key] = '00a0d2';
					break;
				default:
					$options[$key] = '';
			}
			return $options[$key];
		}
	}

	/**
	 * Simple html sanitization for the hex value, could be rgba.
	 * @param  string $string the color hex value, could be rgba
	 * @return string         sanitized string value.
	 */
	private function jsm_sanitize_hex_color( $string ) {
		return esc_html( $string );
	}
}
