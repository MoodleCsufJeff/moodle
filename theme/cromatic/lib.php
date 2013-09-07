<?php
require_once($CFG->libdir.'/formslib.php');
/*
 * 
 */



class cromatic_dummy_page extends moodle_page {
    /**
     * REALLY Set the main context to which this page belongs.
     * @param object $context a context object, normally obtained with get_context_instance.
     */
    public function set_context($context) {
        if ($context === null) {
            // extremely ugly hack which sets context to some value in order to prevent warnings,
            // use only for core error handling!!!!
            if (!$this->_context) {
                $this->_context = get_context_instance(CONTEXT_SYSTEM);
            }
            return;
        }
        $this->_context = $context;
    }
}

/**
 * display the the menus according to position of menu
 * $apage contains the moodle $PAGE object
 * $siteadmin contains the site administration menu
 * $navigation contains the navigation menu
 * $hascustommenu contains the true or false value
 * $custommenu contains the custom menu
 */
function cromatic_position_menus ($siteadmin, $navigation, $hascustommenu, $custommenu,$home){
	global $PAGE;
     $menus= array();
	 //var_dump($PAGE->theme->settings);exit;	  
	//pos is the postion of menu 
 	if(!empty($PAGE->theme->settings->menu_administration)){
    		  $pos = $PAGE->theme->settings->menu_administration;   
    		  $menus[$pos] = 'menu_settings_nav';
    	 }
	 	  
 	if (!empty($PAGE->theme->settings->menu_navigation)){
    		$pos = $PAGE->theme->settings->menu_navigation; 
    		$menus[$pos] = 'menu_navigation';
    	}

    	if(!empty($PAGE->theme->settings->menu_custom)){
    		$pos = $PAGE->theme->settings->menu_custom;
    		$menus[$pos] = 'menu_custom';
    	}
    	
	echo $home;
	//echo $login_out;
	if(count($menus) >= 1){
		ksort($menus);
			foreach($menus as $stat=>$menupos){
				if($stat>0)
				switch($menupos){
					case 'menu_settings_nav':
				        echo $siteadmin;
				        break;
				    case 'menu_navigation':
				        echo $navigation;
				        break;
				    case 'menu_custom':
				    	if ($hascustommenu){
				        	//echo '<div id="custommenucontainer" class="mynavbar">'.$custommenu.'</div>';
				        	echo $custommenu;
				        	//$m->add(strftime("%A, %d %B %Y"),new moodle_url('/calendar/view.php'),get_string('today'),999999);
				        	//echo strftime("%A, %d %B %Y");
				    	}
				        break;
					
				}
				
			}
 	}
 	
}

/**
 * Themenu class
 *
 * This class is used to operate themenu that can be rendered for the page.
 * Themenu configured form custommenu (if exist). It provide user interface
 * to manage menu items and then converted onto moodle formate.
 *
 * To configure the themenu:
 *     Settings: Administration > Appearance > Themes > {Themename}
 *
 * @since     Moodle 2.01
 */
class cromatic_vm_menu{
	
	public static $displaylist=array();
	
	/**
     * Returns the custom menu if one has been set
     *
     * A themenu can be configured by browsing to
     *    Settings: Administration > Appearance > Themes > {themename}
     *
     * @return string
     */
	public function custom_menu() {
	    global $CFG;
	    if (empty($CFG->custommenuitems)) {
	        return '';
	    }
	    //$custommenu = new custom_menu();
	        
	       // return $this->render_custom_menu($custommenu);
	        $custommenu = new custom_menu($CFG->custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }
    
 /**
 * Convert submitted value (after changing the position)
 *  of themenu data in moodle formate (\n seperated string).
 *  
 *  @param array $data menu detail(name,url,parent....)
 *  @return string new line separate menu node list
 */

function cromatic_convert_menu_to_moodle_menu($data){
	$menustring=""; $menupath=array();$hyphen=0;
	
	foreach($data as $menunode){
	
		$menupath=cromatic_serch_array_value($menunode['menuid']);
		
		if($menunode['pmenuid']!=0){
			$hyphen=cromatic_count_depth_of_menu($data,$menunode['pmenuid'],$c=1);
		}
		while($hyphen>0){
			$menustring .='-';
			$hyphen--;
		}
		$menustring .= $menupath->name;
		if($menupath->link)
			$menustring .= "|".$menupath->link;
		if($menupath->title)
			$menustring .= "|".$menupath->title;
		$menustring .="\n";	
		unset($menupath);
	}
	return trim($menustring);
}

/**
 * 
 * Count depth of given node by iterating parent
 * @param array $menu (themenu saved data)
 * @param int $pid parent id of node
 * @param int $ctr count depth(or parent)
 * @return int degree of depth
 */


function cromatic_count_depth_of_menu($menu,$pid,$ctr){
	$node=$menu['menuid:'.$pid];
	if($node['pmenuid']){
		$ctr+=1;
		$ctr=cromatic_count_depth_of_menu($menu,$node['pmenuid'],$ctr);
	}
	return $ctr;
}
	


/**
 * 
 * Search for id in themenu(vmmenu) array of object
 * and then return that node wihich id match
 * @param array $menuarray
 * @param int $mid menu node id
 * @return array $value menu node 
 */

function cromatic_serch_array_value($mid){
	$vm=new cromatic_vm_menu();
	$menuarray=$vm->custom_menu();
	foreach($menuarray as $value){
		if($value->id==$mid)
		 return $value;
	}	
	
}

/**
 * Convet moodle menu in an array and then serach node for deletion
 * Then delete node from array and convert into string(moodle menu formate)
 * @param int $id menu node id 
 * @return string $str menu list
 */
function cromatic_delete_menu($id){
	global $DB;$str="";
	$i=$id-1;
	
	$mmenu = $DB->get_record('config', array('name'=>'custommenuitems'));
	$menunode = explode("\n", $mmenu->value);
	//var_dump($menunode);
	foreach($menunode as $name){
		if($name!=$menunode[$i])
			$str .= $name."\n";
	}
	//echo $str;exit;
	return trim($str);
}
    
	function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $menuurl = $data['menuurl'];
        
        if($menuurl!='#'){
	        if(!$url=parse_url($menuurl)){
	        	$errors['menuurl']="Invalid url";
	        }
	        if(!empty($url['scheme']) && empty($url['host'])){
	        	$errors['menuurl']="Invalid url";
	        }
        }
        return $errors;
    }
    
}

/**
 * 
 * Count no of (-) dash from menu node
 * @param string $string menu node list
 * @return int $i not of dash
 */
function cromatic_split_menu_line ($string) {
	$len = strlen($string);
	for ($i=0; $i<$len; $i++) {
		if ($string[$i] != '-') {
		 	return $i;
		}
	}
	return false;
}

//****************************change in css**********************************************************

function cromatic_user_settings($css, $theme) {

	// Set the main theme color
    if (empty($theme->settings->themecolor)) {
        $themecolor = '#638b2e'; // default theme color 
        $hdrcolor = '#54702e';   // default header color
        $themegrdc = '#82a158';  // default theme/header color
        $blockhdrc = '#aac08d';  // default block header color
        $menuc = '#3c541c';      // default menu color
        $forumcolor = '#dae3cd'; // default forum color
        $forumchildcolor = '#ecf1e6';  //default forumchild color
        $settingpgc = '#fcfdfb';       //default setting page/bg color
        
    } else {
        $themecolor = $theme->settings->themecolor;
        $themegrdc=0.80;
  		$themegrdc= cromatic_colourBrightness($themecolor,$themegrdc);
  		$hdrcolor=-0.8;
  		$hdrcolor= cromatic_colourBrightness($themecolor,$hdrcolor);
        $blockhdrc=0.55;
  		$blockhdrc= cromatic_colourBrightness($themecolor,$blockhdrc);
  		$menuc=-0.6;
  		$menuc= cromatic_colourBrightness($themecolor,$menuc);
  		$forumcolor=0.24;
  		$forumcolor= cromatic_colourBrightness($themecolor,$forumcolor);
  		$forumchildcolor=0.12;
  		$forumchildcolor= cromatic_colourBrightness($themecolor,$forumchildcolor);
  		$settingpgc=0.02;
  		$settingpgc= cromatic_colourBrightness($themecolor,$settingpgc);
    }
    $css = cromatic_set_themecolor($css, $themecolor);
    $css = cromatic_set_hdrcolor($css, $hdrcolor);
    $css = cromatic_set_themegrdc($css, $themegrdc);
    $css = cromatic_set_blockhdrc($css, $blockhdrc);
    $css = cromatic_set_menuc($css, $menuc);
    $css = cromatic_set_forumcolor($css, $forumcolor);
    $css = cromatic_set_forumchildcolor($css, $forumchildcolor);
    $css = cromatic_set_settingpgc($css, $settingpgc);
    
     //set the link color
    if (!empty($theme->settings->linkcolor)) {
        $linkcolor = $theme->settings->linkcolor;
    } else {
        $linkcolor = null;
    }
    $css = cromatic_set_linkcolor($css, $linkcolor);

	// Set the link hover color
    if (!empty($theme->settings->linkhover)) {
        $linkhover = $theme->settings->linkhover;
    } else {
        $linkhover = null;
    }
    $css = cromatic_set_linkhover($css, $linkhover);
    
    return $css;
}



/**
 * Sets the color variable in CSS
 *
 */
function cromatic_set_themecolor($css, $themecolor) {
    $tag = '[[setting:themecolor]]';
    $css = str_replace($tag, $themecolor, $css);
    return $css;
}
function cromatic_set_hdrcolor($css, $hdrcolor) {
    $tag = '[[setting:hdrcolor]]';
    $css = str_replace($tag, $hdrcolor, $css);
    return $css;
}
function cromatic_set_themegrdc($css, $themegrdc) {
    $tag = '[[setting:themegrdc]]';
    $css = str_replace($tag, $themegrdc, $css);
    return $css;
}
function cromatic_set_blockhdrc($css, $blockhdrc) {
    $tag = '[[setting:blockhdrc]]';
    $css = str_replace($tag, $blockhdrc, $css);
    return $css;
}
function cromatic_set_menuc($css, $menuc) {
    $tag = '[[setting:menuc]]';
    $css = str_replace($tag, $menuc, $css);
    return $css;
}

function cromatic_set_forumcolor($css, $forumcolor) {
    $tag = '[[setting:forumcolor]]';
    $css = str_replace($tag, $forumcolor, $css);
    return $css;
}
function cromatic_set_forumchildcolor($css, $forumchildcolor) {
    $tag = '[[setting:forumchildcolor]]';
    $css = str_replace($tag, $forumchildcolor, $css);
    return $css;
}
function cromatic_set_settingpgc($css, $settingpgc) {
    $tag = '[[setting:settingpgc]]';
    $css = str_replace($tag, $settingpgc, $css);
    return $css;
}
 
function cromatic_set_linkcolor($css, $linkcolor) {
    $tag = '[[setting:linkcolor]]';
    $replacement = $linkcolor;
    if (is_null($replacement)) {
        $replacement = '#28728a';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function cromatic_set_linkhover($css, $linkhover) {
    $tag = '[[setting:linkhover]]';
    $replacement = $linkhover;
    if (is_null($replacement)) {
        $replacement = '#536b36';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}


function cromatic_get_js_module() {
    global $PAGE;
    return array(
        'name' => 'theme_cromatic',
        'fullpath' => '/theme/cromatic/yui/slider.js',
        'requires' => array('base', 'node', 'slider', 'dd-drag','cookie'),
    );
}



function cromatic_colourBrightness($hex, $percent) {
 // Work out if hash given
 $hash = '';
 if (stristr($hex,'#')) {
  $hex = str_replace('#','',$hex);
  $hash = '#';
 }
 /// HEX TO RGB
 $rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
 //// CALCULATE
 for ($i=0; $i<3; $i++) {
  // See if brighter or darker
  if ($percent > 0) {
   // Lighter
   $rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
  } else {
   // Darker
   $positivePercent = $percent - ($percent*2);
   
   $rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
  }
  // In case rounding up causes us to go to 256
  
  
  if ($rgb[$i] > 255) {
  	//print_r($rgb[$i]);
   $rgb[$i] = 255;
  }
  if ($rgb[$i] < 0) {
  	   	$rgb[$i] = 1;
  }
 }
 //// RBG to Hex
 $hex = '';
 for($i=0; $i < 3; $i++) {
  // Convert the decimal digit to hex
  $hexDigit = dechex($rgb[$i]);
  // Add a leading zero if necessary
  if(strlen($hexDigit) == 1) {
  $hexDigit = "0" . $hexDigit;
  }
  // Append to the hex string
  $hex .= $hexDigit;
 }
 return $hash.$hex;
}