=== L7 Login Customizer ===
Contributors: jeffreysmattson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J25EL9NHCB7XL
Tags: branding, custom login, login background, login logo, screen, style login, theme login, wp-login, css, change, custom, login, customize, register, logout, style, logo
Requires at least: 3.0.1
Tested up to: 4.2.3
Stable tag:2.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customize the login, logout, and register pages. Add a custom logo and background image easily.

== Description ==

Customize the login, logout, and register pages. Gives simple options to choose colors and to upload a custom logo giving your clients a fully cusomized experience.  Custom css can be used giving you complete control in customizing the login, logout, and register pages.

Users can:
<ul>
	<li>Add Custom Logo to Login Form</li>
	<li>Add Background Image</li>
	<li>Set Logo Height and Width</li>
	<li>Set Image Link</li>
	<li>Set Image Title</li>
	<li>Set Background Color</li>
	<li>Set Text Color</li>
	<li>Set Link Color</li>
	<li>Preview</li>
	<li>Add Custom CSS</li>
</ul>
<h4>Usefull CSS</h4>
<blockquote>
<h4>Distance between bottom of logo and top of form</h4>
<p>.login h1 a {<br />
&nbsp;&nbsp;height: 150px !important;<br />
}</p>
<h4>Hide registration, forgot password links</h4>
<p>.login #nav {<br />
&nbsp;&nbsp;display:none;<br />
}
</p>
</blockquote>

== Installation ==

Option 1
<ul>
	<li>Login as administrator on a Wordpress site</li>
	<li>Navigate to the plugins page</li>
	<li>Click Add plugin</li>
	<li>Search for "L7 Login Customizer"</li>
	<li>Install and activate the plugin</li>
</ul>
Option 2
<ul>
	<li>Download the latest ZIP Wordpress.org.</li>
	<li>Login to your Wordpress installation as an administrator</li>
	<li>Go to Plugins -> Add New -> Upload Plugin</li>
	<li>Upload the ZIP file</li>
	<li>Activate the Plugin after it is installed</li>
	<li>Make changes in settings -> Custom Login</li>
</ul>
	
== Frequently Asked Questions ==

<h4>When I select an image, no image appears in the settings.</h4>
<p>When choosing or uploading an image you must make sure that the URL is in the "Link URL" field in the upload form.  This can be done by simply clicking on the "File URL" button. Once you see the image URL in this field you can select your image.</p>
<h4>What size image is the best to upload as a logo?</h4>
<p>Depending on the image it can vary but a square image fits best without adding custom css. The height of the image is adjustable.</p>
<h4>If I use a plugin that changes the URL of the login will this plugin still work?</h4>
<p>Yes. This plugin currently is compatable with any plugin that changes the login url.</p>
<h4>Is this plugin compatable with WP-Members?</h4>
<p>This plugin is compatable with WP-Members although they have the option to use one of thier templates for login.  This will not customize that login page.</p>
<h4>After viewing the preview several times I am no longer able to view the login page. A notice is shown saying my ip has been banned and I am no loger able to view the login page.</h4>
<p>Due to the increase in brute force attacks on Wordpress sites, Some hosting providers have security measures in place that will not allow the loading of the login page a set number of times in a time period.  You will have to contact your hosting provider to have this changed. Usually you are only banned for a few minuets.</p>

== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png

== Changelog ==

= 2.0.9 =
<ul>
<li>Most stable release</li>
<li>Grouped Setting Options</li>
<li>Moved Color Picker from right to left</li>
</ul>

= 2.0.8 =
<ul>
<li>Most stable release</li>
<li>Added Bootstrap Colorpicker!</li>
<li>Added small form area setting</li>
<li>File structure change</li>
</ul>

= 2.0.7 =
<ul>
<li>Preview bug fixed</li>
</ul>

= 2.0.6 =
<ul>
<li>Refactoring</li>
<li>Install bug corrected.</li>
</ul>

= 2.0.5 =
<ul>
<li>Made the WP logo visable on after install</li>
</ul>

= 2.0.4 =
<ul>
<li>Fixed link color bug</li>
</ul>

= 2.0.3 =
<ul>
<li>Fixed Custom CSS bug</li>
</ul>

= 2.0.2 =
<ul>
<li>Added more customization options</li>
</ul>

= 2.0.1 =
<ul>
<li>Fixed remove data on uninstall bug.</li>
</ul>

= 2.0.0 =
<ul>
<li>Fixed install bug.</li>
</ul>
