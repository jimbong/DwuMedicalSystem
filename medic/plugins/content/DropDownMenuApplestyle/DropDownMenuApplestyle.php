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
* v1.01 October 10, 2008 allows multiple menus in the same page
* v1.02 October 12, 2008 some bug fix. improved css
* v1.53 December 9, 2008
- Fixed the warning message "Undefined variable: module on line 43"
- Fixed the warning message "Undefined offset: 0 on line 55"
* v1.54 December 16, 2008 Fixed the warning message "Undefined property: Plugin_DropDownMenuApplestyle::$menu_height in DropDownMenuApplestyle.lib.php on line 126"
* v1.55 May 6, 2010 conform to joomla.org guidelines
* v2.56 Feb 7, 2012 support for Joomla 1.6, 1.7 and 2.5
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport( 'joomla.event.plugin' );

$lib = dirname(__FILE__).'/DropDownMenuApplestyle/ddlevelsmenu.lib.php';
$lib2 = dirname(__FILE__).'/DropDownMenuApplestyle/DropDownMenuApplestyle.lib.php';
require_once($lib);
require_once($lib2);

class plgContentDropDownMenuApplestyle extends JPlugin {

	function plgContentDropDownMenuApplestyle( &$subject, $params ) {
		parent::__construct( $subject, $params );
 	}

	function onContentPrepare( $context, &$row, &$params, $limitstart ) {

		$plugin = new Plugin_DropDownMenuApplestyle($row, $this);

		return true;
	}
}

class Plugin_DropDownMenuApplestyle extends DropDownMenuApplestyle_base {

	function Plugin_DropDownMenuApplestyle( &$row, &$obj ) {

		$regex = '%\{DropDown_Menu_Apple_style\s*(.*?)\s*\}(\n|\r\n|</p>|<p>|&nbsp;|<br\s/>)*(.*?)(\n|\r\n|</p>|<p>|&nbsp;|<br\s/>)*\{/DropDown_Menu_Apple_style\}%is';

		$module = 'DropDownMenuApplestyle';
		$this->get_param($module, '1.7', $obj);

		#$plugin =& JPluginHelper::getPlugin('content', $module);
		#$pluginParams = new JParameter( $plugin->params );
		$pluginParams = $obj->params;
		if ( !$pluginParams->get( 'enabled', 1 ) ) {
			$row->text = preg_replace( $regex, '', $row->text );
			return true;
		}

		$contents = $row->text;
		if (preg_match_all( $regex, $contents, $matches_array, PREG_SET_ORDER )) {
			$count = count( $matches_array[0] );
			if ($count==0) return true;
			foreach($matches_array as $matches) {
				$this->process($row, $matches);
			}
			#$row->text = preg_replace( $regex, '', $row->text );
		}
		return true;
	}

}

?>
