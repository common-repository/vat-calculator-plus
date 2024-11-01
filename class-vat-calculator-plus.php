<?php
/**
 * VAT Calculator Plus plugin class file.
 *
 * @package   VatCalculatorPlus
 * @author    VAT Calculator Plus < >
 * @license   GPL2
 * @copyright 2014 VAT Calculator Plus
 */
 
class VatCalculatorPlus{

	protected static $version = '1.0.0';

	protected static $plugin_slug = 'vat-calculator-plus';

	protected static $instance = null;

	protected static $settingsPage = '';


	function vcp_vat_calc_shortcode( $atts ) {

		// Pull in shortcode attributes, set defaults and extract as local $variables
		extract(shortcode_atts( array(
		          'style'    => 1,
		          'position' => 0,
		          'rate'     => 0,
		          ), $atts ));

		// sanitize attributes 
		//$tag_slug = sanitize_text_field( $atts['tag'] );
		//$style = (int) $atts['style']; //force to an int
		//$position = (int) $atts['position']; //force to an int
		//$rate = (float) $atts['rate']; //force to a float

		// make vat calc snippet
		$link_allowed = self::get_link_allowed();
		if ($link_allowed){
			$content = "<script type=\"text/javascript\">VcpOptions = {Style:$style,Position:$position,Rate:$rate};</script><div id=\"VcpBlock\"><script type=\"text/javascript\" src=\"http://www.vatcalculatorplus.com/widget/js/VATCalcLoad.js\"></script><p><a href=\"http://www.vatcalculatorplus.com\" target=\"_blank\">VAT Calculator</A> powered by VATCalculatorPlus.com</p></div>";
			}else{
			$content = '';
			}
		return $content;
	}

	function add_plugin_options_page(){
		self::$settingsPage = add_options_page('VAT Calculator Plus', 'VAT Calculator Plus', 'manage_options', 'vat-calculator-plus-admin',	array($this, 'create_admin_page') );
	}

	function add_plugin_options(){
		register_setting('vcp_options','vcp-options'); // 3rd param would be validation function
		add_settings_section('vcp_permission', 'Permission', array($this,'echo_permission_section'), self::$plugin_slug);
		add_settings_field('allow-link', 'Allow link', array($this, 'echo_permission_field'), 
			self::$plugin_slug, 'vcp_permission');
	}

	function add_admin_scripts($hook){
		if( $hook != self::$settingsPage ) 
			return;
	 	wp_enqueue_script( 'admin.js', plugins_url( 'js/admin.js' , __FILE__ ), array( 'jquery' ) );
	}

	function add_plugins_page_link($actions, $file){
        	if (strpos($file, 'vat-calculator-plus') !== false){
			$actions['settings'] = '<a href="options-general.php?page=vat-calculator-plus-admin">Get Started</a>';
		}
		return $actions;
	}

	function echo_permission_section(){
		echo '<p>The VATCalculatorPlus.com service requires a link to VATCalculatorPlus.com.  Tick the box below to approve this link.  If you do not allow this, the ShortCode will not work.</p>';
	}

	function echo_permission_field(){
		$link_allowed = self::get_link_allowed();
		echo '<input name="vcp-options[allow-link]" id="allow-link" type="checkbox" '.checked(1, $link_allowed , false).'> Allow the widget to show a link to VATCalculatorPlus.com.';
	}

	function get_link_allowed(){
		$options = get_option('vcp-options');
		if (isset($options['allow-link'])){
			$link_allowed = true;
		}else{
			$link_allowed = false;
		}
	return $link_allowed;
	}

	function create_admin_page(){
		$link_allowed = self::get_link_allowed();
?>
<style>
#vcpShortCode{
transition:background 0.5s;
font-size:1.5em;
padding:0.35em 0.5em 0.5em 0.5em;
width:80%;
background:#FFF;
}
#rateEdit{width:6em}
<?php 
if ($link_allowed == false){
	echo '#vcpForm{opacity:0.4}';
}
?>
</style>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2>VAT Calculator Plus Settings</h2>			       

<form action="options.php" method="post">  
  <?php 
  settings_fields('vcp_options');
  do_settings_sections(self::$plugin_slug);
  submit_button(); 
  ?>
</form>
<br><hr><br>
<form name="vcpForm" id="vcpForm">
<p style="font-size:1.15em;"><strong>
<span style="color:#E33;">Important:</span> The VAT Calculator widget works using a ShortCode.  Set your options below and copy the ShortCode into your post of page.
</strong></p>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row">Settings</th>
<td>
<fieldset>
<label for="styleSelect">
Display Style 
<select name="styleSelect" id="styleSelect">
<option value="1" selected="selected">Normal</option>
<option value="0" >No Style</option>
</select>
&nbsp; Select "No Style" to style the calculator yourself using CSS.
</label>
<br>
<label for="positionSelect">
Position 
<select name="positionSelect" id="positionSelect">
<option value="0" selected="selected">Centered</option>
<option value="1" >Left</option>
<option value="2" >Right</option>
</select>
&nbsp; The Left and Right options will allow text to flow around the VAT calculator.
</label>
<label for="rateEdit">
VAT Rate (%) 
<input id="rateEdit" name="rateEdit" type="number" novalidate="" step="any" value="0" min="0" max="99">
&nbsp; Use 0 (zero) to allow your visitors to set their own VAT rate.
</label>
</fieldset>
</td>
</tr>
<tr valign="top">
<th scope="row">Your ShortCode</th>
<td>
<fieldset>
<p>Copy this ShortCode into your page or post:</p>
<br>
<input id="vcpShortCode" readonly type="text" value="[vatcalc]">
</fieldset>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<?php
	}

	private function __construct() {		
		add_shortcode( 'vatcalc', array($this, 'vcp_vat_calc_shortcode') ); // Create the shortcode
		if( is_admin() ) {
			add_action( 'admin_menu', array($this, 'add_plugin_options_page') );
			add_action( 'admin_enqueue_scripts', array($this, 'add_admin_scripts') );
			add_filter( 'plugin_action_links', array($this, 'add_plugins_page_link'), 10, 2 );
			add_action( 'admin_init', array($this, 'add_plugin_options') );
		}
	}


	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}