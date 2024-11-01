<?php
/*
Plugin Name: Adnimation
Plugin URI: http://www.adnimation.com/
Description: This plugin inserts the Adnimation code into your website for placing animated ads.
Author: Adnimation
Version: 1.0
Author URI: http://www.adnimation.com/
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
add_action( 'admin_init', 'apo_adnimation_admin_init' );

function apo_adnimation_admin_init() {
   /* Register our stylesheet. */
   wp_register_style( 'adnimationStylesheet', plugins_url('style.css', __FILE__) );
   wp_enqueue_style( 'adnimationStylesheet' );
}

// Hook for adding admin menus
add_action('admin_menu', 'apo_adnimation_add_pages');

// action function for above hook
function apo_adnimation_add_pages() {
    // Add a new submenu under Settings:
    add_options_page('Adnimation', 'Adnimation', 'manage_options', 'apo_adnimation_options', 'apo_adnimation_page');	
}

function apo_adnimation_page()
{
?>

<div class="wrap">
  <h2 class="logo"></h2>
  <p>
  <h3>Adnimation Plugin – Official</h3>
  This plugin inserts the Adnimation code into your website for placing animated ads.
  </p>
  <p><strong>Important:</strong> To enjoy animated ads, you need to first register with Adnimation and get approved, then log into your account, generate a code under Implementation, and paste it here below.</p>
  <form method="post" enctype="multipart/form-data" action="options.php">
    <?php
	settings_fields('apo_adnimation_options'); 
	do_settings_sections('apo_adnimation_section');
	?>
    <br>
    <p>For more information, visit <a href="http://www.adnimation.com/">Adnimation - Better Ads for All</a>.</p>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </form>
</div>
<?php
}

/**
 * Register the settings to use on the theme options page
 */
add_action( 'admin_init', 'apo_adnimation_register_settings' );
/**
 * Function to register the settings
 */
function apo_adnimation_register_settings()
{
    // Register the settings with Validation callback
    register_setting( 'apo_adnimation_options', 'apo_adnimation_options', 'apo_adnimation_validate_settings' );
	
    // Add settings section
	add_settings_section( 'apo_adnimation_text_one_section', '', 'apo_adnimation_display_section', 'apo_adnimation_section' );
	
    // Create textbox field	
	$js_args = array(
      'type'      => 'textarea',
      'id'        => 'js_textbox',
      'name'      => 'js_textbox',
      'desc'      => '',
      'std'       => '',
      'label_for' => 'js_textbox',
      'class'     => 'css_class'
    );
    add_settings_field( 'js_textbox', 'Adnimation Code', 'apo_adnimation__display_setting', 'apo_adnimation_section', 'apo_adnimation_text_one_section', $js_args );
	
	$on_off_args = array(
      'type'      => 'radio',
      'id'        => 'on_off_button',
      'name'      => 'on_off_button',
      'desc'      => '',
      'std'       => '',
      'label_for' => 'on_off_button',
      'class'     => 'css_class'
    );
    add_settings_field( 'on_off_button', 'Adnimation', 'apo_adnimation__display_setting', 'apo_adnimation_section', 'apo_adnimation_text_one_section', $on_off_args );
	
	$footer_link_args = array(
      'type'      => 'radio',
      'id'        => 'footer_link',
      'name'      => 'footer_link',
      'desc'      => '',
      'std'       => '',
      'label_for' => 'footer_link',
      'class'     => 'css_class'
    );
    add_settings_field( 'footer_link', 'Adnimation link', 'apo_adnimation__display_setting', 'apo_adnimation_section', 'apo_adnimation_text_one_section', $footer_link_args );

}

/**
 * Function to add extra text to display on each section
 */
function apo_adnimation_display_section($section){ 
}


/**
 * Function to display the settings on the page
 * This is setup to be expandable by using a switch on the type variable.
 * In future you can add multiple types to be display from this function,
 * Such as checkboxes, select boxes, file upload boxes etc.
 */
function apo_adnimation__display_setting($args)
{
    extract( $args );
    $option_name = 'apo_adnimation_options';
    $options = get_option( $option_name );
    switch ( $type ) {
          case 'radio':
              $options[$id] = stripslashes($options[$id]);
              $options[$id] = esc_attr( $options[$id]);
			  $res_on = $options[$id] == 'on' ? 'checked="checked"' : '';
			  $res_off = $options[$id] == 'off' ? 'checked="checked"' : '';
              echo "<input class='regular-text$class' type='radio' id='$id' name='" . $option_name . "[$id]' value='on' checked /> On &nbsp;&nbsp;";
			  echo "<input class='regular-text$class' type='radio' id='$id' name='" . $option_name . "[$id]' value='off'".$res_off." /> Off";
              echo ($desc != '') ? "<span class='description'>$desc</span>" : "";
          break;
		  
		  case 'textarea':
              $options[$id] = stripslashes($options[$id]);
              $options[$id] = esc_attr( $options[$id]);
              echo "<textarea class='regular-text$class' type='text' cols='50' rows='5' id='$id' name='" . $option_name . "[$id]'>$options[$id]</textarea>";
              echo ($desc != '') ? "<span class='description'>$desc</span>" : "";
          break;
    }
}

/**
 * Callback function to the register_settings function will pass through an input variable
 * You can then validate the values and the return variable will be the values stored in the database.
 */
function apo_adnimation_validate_settings($input)
{
  return $input;
}

// Add items to the header!
function apo_adnimation_add_js() {
	$options = get_option( 'apo_adnimation_options' );
	if($options['on_off_button']=='on' && $options['js_textbox']){
		echo $options['js_textbox'];
	}		
}
add_action('wp_head', 'apo_adnimation_add_js', 11);

// Add items to the footer
function apo_adnimation_add_footer_link() {
	$options = get_option( 'apo_adnimation_options' );
	if($options['footer_link']=='on'){
		echo '<p style="padding: 15px 25px;"><a href="http://www.adnimation.com/">Animated ads by Adnimation</a></p>';
	}
}
add_action('wp_footer', 'apo_adnimation_add_footer_link');

?>
