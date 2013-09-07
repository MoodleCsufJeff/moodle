<?php 
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree){
    // menu's position setting
	//if the number of menu would be increase then the query would be change
    //TODO  in the condition array another condition would be apply
    // this else if code should be in short form
    
	$settings->add(new admin_setting_heading('theme_cromatic_layout_header', get_string('pagelayoutsettings', 'theme_cromatic'), ''));
     // Display logo or heading
    $name = 'theme_cromatic/displaylogo';
    $title = get_string('displaylogo','theme_cromatic');
    $description = get_string('displaylogodesc', 'theme_cromatic');
    $default = '0';
    $choices = array(0=>get_string('heading', 'theme_cromatic'), 1=>get_string('mylogo', 'theme_cromatic'));
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting); 
    
    // Logo file setting
    $name = 'theme_cromatic/logo';
    $title = get_string('logo','theme_cromatic');
    $description = get_string('logodesc', 'theme_cromatic');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_URL);
    $settings->add($setting);
      
    // Tag line setting
	$name = 'theme_cromatic/tagline';
	$title = get_string('tagline','theme_cromatic');
	$description = get_string('taglinedesc', 'theme_cromatic');
	$setting = new admin_setting_configtext($name, $title, $description, '');
	$settings->add($setting);
	 
    $settings->add(new admin_setting_heading('theme_cromatic_color_setting', get_string('colorsettings', 'theme_cromatic'), ''));
     
    // main theme color setting
    $name = 'theme_cromatic/themecolor';
    $title = get_string('themecolor','theme_cromatic');
    $description = get_string('themecolordesc', 'theme_cromatic');
    $default = '#638b2e';
    $previewconfig = array('selector'=>'html', 'style'=>'themeColor');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);
    
    // link color setting
	$name = 'theme_cromatic/linkcolor';
	$title = get_string('linkcolor','theme_cromatic');
	$description = get_string('linkcolordesc', 'theme_cromatic');
	$default = '#28728a';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
    
	// link hover color setting
	$name = 'theme_cromatic/linkhover';
	$title = get_string('linkhover','theme_cromatic');
	$description = get_string('linkhoverdesc', 'theme_cromatic');
	/*edit by suman*/
	$default = '#536b36';
	$previewconfig = NULL;
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
	$settings->add($setting);
}
?>