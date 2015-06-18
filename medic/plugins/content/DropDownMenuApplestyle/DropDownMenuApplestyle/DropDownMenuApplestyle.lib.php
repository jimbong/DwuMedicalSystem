<?php
/**
* DropDownMenuApplestyle plugin
* This plugin allows you to insert the Drop Down Menu Apple style
* from www.dynamicdrive.com with the tags
* {DropDown_Menu_Apple_style} ... {/DropDown_Menu_Apple_style}.
* Author: kksou
* Copyright (C) 2006-2008. kksou.com. All Rights Reserved
* License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
* Website: http://www.kksou.com/php-gtk2/Joomla-Gadgets/
* v1.0 October 8, 2008
*/

class DropDownMenuApplestyle_base extends DropDownMenu_base {

	function setup_css() {
		$this->menu = '';

		$this->menu = "\n\n<!-- \nPowered by Joomla Gadgets from kksou.com
http://www.kksou.com/php-gtk2/Joomla-Gadgets/
-->\n\n";

		#$this->z .= "<span class=\"z1\"><a href=\"http://www.kksou.com/php-gtk2/Joomla-Gadgets/\" style=\"color:#aaa;text-decoration: none;font-family:Tahoma, Arial, Helvetica, sans-serif;font-size:7pt;font-weight: normal;\">Powered by JoomlaGadgets</a></span>";
		$this->z = "<p align=\"right\" style=\"padding:0 0 0 0;margin:0 0 0 0\"><a href=\"http://www.kksou.com/php-gtk2/Joomla/Drop-Down-Menu-Apple-style.php\" style=\"color:#aaa;text-decoration: none;font-family:Tahoma, Arial, Helvetica, sans-serif;font-size:7pt;font-weight: normal;\">Powered by JoomlaGadgets</a></p>";
		$this->z .= $this->menu;

		if (!defined('DropDownMenuApplestyle_js')) {

			define('DropDownMenuApplestyle_js', 1);

			/* list($downarrow_width, $downarrow_height, $downarrow_type, $downarrow_attr) = getimagesize(dirname(__FILE__).'/'.$this->down_arrow);
			list($rightarrow_width, $rightarrow_height, $rightarrow_type, $rightarrow_attr) = getimagesize(dirname(__FILE__).'/'.$this->right_arrow);
			if (!preg_match('/(true|false)/i', $this->reveal_animate)) $this->reveal_animate = 'false';

			$this->menu .= "
<script type=\"text/javascript\">
var gifbase = '$this->base_url2';
var downarrow = '$this->down_arrow';
var downarrow_width = $downarrow_width;
var downarrow_height = $downarrow_height;
var rightarrow = '$this->right_arrow';
var rightarrow_width = $rightarrow_width;
var rightarrow_height = $rightarrow_height;
var reveal_animate = $this->reveal_animate;
</script>
"; */

			$this->menu .= "
<script type=\"text/javascript\">
<!--
/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var gifbase = '{$this->base_url2}/';
-->
</script>
";
			$this->menu .= '<script type="text/javascript" src="'.$this->base_url2.'/ddlevelsmenu.js"></script>';

/*$css1 = $this->get_css($this->css1);
$css2 = $this->get_css($this->css2);
$css3 = $this->get_css($this->css3);*/

/*$css4 = '';

if (preg_match('/(\d+)/', $this->menu_width, $matches)) {
	$this->menu_width = $matches[1];
}

if ($this->menu_width==0) $this->menu_width = '';

if ($this->menu_width!='') {
	if ($this->menu_width>100) $this->menu_width = '100';
	$css4 .= "div.DropDownMenuApplestyle .mattblackmenu ul{
	width: {$this->menu_width}%;
}
";
}

if ($this->menu_border_bottom_css!='') {
	$css4 .= "div.DropDownMenuApplestyle .mattblackmenu ul{
	border-bottom: $this->menu_border_bottom_css;
}
";
}*/

/*$menubar_style = '';
$this->menubar_style = 'border';
if (preg_match('/border/', $this->menubar_style))
	$menubar_style .= "border-bottom: 1px solid gray;\n";
if (preg_match('/bg/', $this->menubar_style))
	$menubar_style .= "background: rgb(146,178,255) url({$base}/silvergradient.gif) repeat-x center left;\n";
$this->menubar_style2 = $menubar_style;*/

/*if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
	$bg = "background-color: rgb(146,178,255);";

} else {
	$bg = "background-image: url(/t41/mambots/content/ddlevelsfiles/silvergradientover.gif);";
}
$css4 .= "
.ddsubmenustyle li a:hover{
$bg
color: white;
}
";*/

			#$css_file = dirname(__FILE__).'/DropDownMenuApplestyle.css';
			#$css = $this->get_css($this->css);
			/*$css = file_get_contents(dirname(__FILE__).'/ddlevelsmenu-base.css');
			$css .= file_get_contents(dirname(__FILE__).'/ddlevelsmenu-sidebar.css');
			$css .= file_get_contents(dirname(__FILE__).'/ddlevelsmenu-topbar.css');*/

			$css = $this->get_css('ddlevelsmenu-base.css');
			$css .= $this->get_css('ddlevelsmenu-sidebar.css');
			$css .= $this->get_css('ddlevelsmenu-topbar.css');

			if(defined('_VALID_MOS')) {
				$codes .= "<style type=\"text/css\">\n<!--".$css."-->\n</style>";
			} else {
				JFactory::getDocument()->addStyleDeclaration($css);
			}

/*
$this->menu .= '
<style type="text/css">

.b1 {
margin: 0 0 0 0;
text-align: right;
}

'.$css.'
</style>
'; */
		}
	}

	function get_css($file) {
		$css_file = dirname(__FILE__).'/'.$file;
		$css = file_get_contents($css_file);

		#$css = str_replace('{$menu_width}', $this->menu_width, $css);
		#$css = str_replace('{$menu_height}', $this->menu_height, $css);
		$css = str_replace('{$base}', $this->base_url2, $css);
		return $css;
	}
}

?>
