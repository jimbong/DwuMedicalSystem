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

#defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class DropDownMenu_base {

	function process(&$row, $matches) {

		$this->menutype = 'topbar';
		if (preg_match('/menutype:(topbar|sidebar)/', $matches[1], $matches2)) {
			$this->menutype = $matches2[1];
		}

		$this->w3c = 1;
		if (preg_match('/w3c=(\d+%?)/', $matches[1], $matches2)) {
			$this->w3c = $matches2[1];
		}

		$str = $this->fix_str2($matches[3]);

		/*
		if (preg_match('/revealanimate:(true|false)/i', $matches[1], $matches2)) {
			$this->reveal_animate = $matches2[1];
		}

		$this->css = 'ddlevelsmenu.css';
		if (preg_match('/css:([^\s]*)/', $matches[1], $matches2)) {
			$this->css = $matches2[1];
		}

		if (preg_match('/width:(\d+)\%?/', $matches[1], $matches2)) {
			if ($matches2[1]!='') $this->menu_width = $matches2[1];
		}
		*/

		$this->clear_both = '';
		if (preg_match('/clear_both:(\d+)\%?/', $matches[1], $matches2)) {
			if ($matches2[1]!='') $this->clear_both = $matches2[1];
		}

		$this->menu_id = 'ddtopmenubar';
		if (preg_match('/menu_id:([^\s}]+)/', $matches[1], $matches2)) {
			if ($matches2[1]!='') $this->menu_id = 'ddmenu_'.$matches2[1];
			#print "bp123. menu_id = $this->menu_id<br>";
		}

		$this->setup_css();
		$str2 = $this->process_menu($str);

		$row->text = str_replace($matches[0], $this->menu.$str2, $row->text);
	}

	/*<div class="ddmenu123">

<div id="ddtopmenubar" class="mattblackmenu">
<ul>
<li><a href="http://www.dynamicdrive.com">Home</a></li>
<li><a href="http://www.dynamicdrive.com/new.htm" rel="ddsubmenu1">DHTML 123</a></li>
<li><a href="http://www.dynamicdrive.com/style/" rel="ddsubmenu2">CSS</a></li>
<li><a href="http://www.dynamicdrive.com/forums/">Forums</a></li>
<li><a href="http://tools.dynamicdrive.com/" rel="ddsubmenu3">Web Tools</a></li>
</ul>
</div>

*/

	function process_menu($str) {
		$lines = explode("\n", $str);
		$i = 0;
		$submenu_on = 0;
		$str2 = '';

		$lines2 = array();
		foreach($lines as $line) {
			if (trim($line)!='') $lines2[] = $line;
		}

		$has_submenu = array();
		$level1 = 0;
		for ($i=0; $i<count($lines2); ++$i) {
			$line = $lines2[$i];
			if (!preg_match('/^[-\+]+\s/', $line)) {
				$level1 = $i;
				$has_submenu[$i] = 0;
			} else {
				$has_submenu[$level1] = 1;
			}
		}

		$level1 = '';
		$level2 = '';
		$n = 0;
		$prev_n = 0;
		$k = 0;
		for ($i=0; $i<count($lines2); ++$i) {
			$line = $lines2[$i];
			if (trim($line)=='') continue;
			if (preg_match('/^([-\+]+)\s+(.*)/', $line, $matches)) {
				$n = strlen($matches[1]);
				$padding = str_pad('',($n-1)*2,' ', STR_PAD_LEFT);
				$prev_padding = str_pad('',($prev_n-1)*2,' ', STR_PAD_LEFT);
				if ($n>$prev_n) {
					if ($prev_n==0) {
						$level2 .= "<ul id=\"{$this->menu_id}_ddsubmenu$k\" class=\"ddsubmenustyle\">\n<li>$matches[2]";
					} else {
						$level2 .= "\n$padding<ul>\n$padding<li>$matches[2]";
					}
				} elseif ($n<$prev_n) {
					for ($j=$prev_n; $j>$n; --$j) {
						$level2 .= "</li>\n$prev_padding</ul>\n";
					}
					$level2 .= "</li>\n$padding<li>$matches[2]";
				} elseif ($n==$prev_n)  {
					$level2 .= "</li>\n$padding<li>$matches[2]";
				}
				$prev_n = $n;

			} else {
				for ($j=$prev_n; $j>0; --$j) {
					$level2 .= "</li>\n</ul>\n";
				}
				$prev_n = $j;
				if ($has_submenu[$i]) {
					if (preg_match('%<a\s(.*?)>(.*?</a>)%s', $line, $matches)) {
						++$k;
						$level1 .= "<li><a $matches[1] rel=\"{$this->menu_id}_ddsubmenu$k\">$matches[2]</li>\n";
					}
				} else {
					$level1 .= "<li>$line</li>";
				}
			}
		}

		for ($j=$prev_n; $j>0; --$j) {
			$level2 .= "</li>\n</ul>\n";
		}

		if ($level1=='') return;

		if ($this->menutype == 'sidebar') $menu_class = 'markermenu';
		else $menu_class = 'mattblackmenu';

		$str2 = '';
		if ($this->w3c) $str2 .= '</p>';
		$str2 .= '<div class="DropDownMenuApplestyle">

<div id="'.$this->menu_id.'" class="'.$menu_class.'">
<ul>
'. $level1.'</ul>
</div>';

		if ($this->clear_both) $str2 .= '<div class=DropDownMenuApplestyle_after></div>';
		$str2 .= '
<script type="text/javascript">
<!--
ddlevelsmenu.setup("'.$this->menu_id.'", "'.$this->menutype.'") //ddlevelsmenu.setup("mainmenuid", "topbar|sidebar");
-->
</script>
';

		$str2 .= $level2;
		$str2 .= '</div>';
		if ($this->w3c) $str2 .= '<p>';

		#$str2 .= "<br clear=all />";

		return $str2;
	}

	function fix_str($str) {
		$str = preg_replace(array('%&lt;\?php(\s|&nbsp;|<br\s/>|<br>|<p>|</p>)%s', '/\?&gt;/s', '/-&gt;/'), array('<?php ', '?>', '->'), $str);
		return $str;
	}

	function fix_str2($str) {
		$str = str_replace('<br>', "\n", $str);
		$str = str_replace('<br />', "\n", $str);
		$str = str_replace('<p>', "\n", $str);
		$str = str_replace('</p>', "\n", $str);
		$str = str_replace('&#39;', "'", $str);
		$str = str_replace('&quot;', '"', $str);
		$str = str_replace('&lt;', '<', $str);
		$str = str_replace('&gt;', '>', $str);
		$str = str_replace('&amp;', '&', $str);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = str_replace('&#160;', "\t", $str);
		$str = str_replace("\r", "", $str);
		$str = str_replace("\n\n", "\n", $str);
		$str = str_replace(chr(hexdec('C2')).chr(hexdec('A0')), '', $str);
		return $str;
	}

	function get_param($module, $ver, $obj=null) {
		if ($ver=='1.0') {
			$this->get_param10($module);
		} elseif ($ver=='1.5') {
			$this->get_param15($module);
		} elseif ($ver=='1.6' || $ver=='1.7' || $ver=='2.5') {
			$this->get_param16($module, $obj);
		}

		/*if ($this->menu_width>100) $this->menu_width = '100';
		if ($this->down_arrow=='') $this->down_arrow = 'arrow-down.gif';
		if ($this->right_arrow=='') $this->right_arrow = 'arrow-right.gif';
		if ($this->reveal_animate=='') $this->reveal_animate = 'false';
		if ($this->reveal_animate=='0') $this->reveal_animate = 'false';
		elseif ($this->reveal_animate=='1') $this->reveal_animate = 'true';*/
	}

	function get_param10($module) {
		/*global $database, $_MAMBOTS;

		if ( !isset($_MAMBOTS->_content_mambot_params[$module]) ) {
			$query = "SELECT params"
			. "\n FROM #__mambots"
			. "\n WHERE element = '$module'"
			. "\n AND folder = 'content'"
			;
			$database->setQuery( $query );
			$database->loadObject($mambot);
			$_MAMBOTS->_content_mambot_params[$module] = $mambot;
		}

		$mambot = $_MAMBOTS->_content_mambot_params[$module];
		$botParams = new mosParameters( $mambot->params );
		$this->reveal_animate = $botParams->def( 'reveal_animate', '0' );
		$this->menu_width = $botParams->def( 'width', '100' );
		$this->down_arrow = $botParams->def( 'down_arrow', 'arrow-down.gif' );
		$this->right_arrow = $botParams->def( 'right_arrow', 'arrow-right.gif' );
		$this->menu_border_bottom_css = $botParams->def( 'menu_border_bottom_css', '1px solid gray' );*/

		global $mosConfig_live_site;
		$this->base_url = $mosConfig_live_site."/mambots/content";
		$this->base_url2 = $mosConfig_live_site."/mambots/content/DropDownMenuApplestyle";
	}

	function get_param15($module) {
		/*$plugin =& JPluginHelper::getPlugin('content', $module);
		$pluginParams = new JParameter( $plugin->params );
		$this->reveal_animate = $pluginParams->get('reveal_animate');
		$this->menu_width = $pluginParams->get('width');
		$this->down_arrow = $pluginParams->get('down_arrow');
		$this->right_arrow = $pluginParams->get('right_arrow');
		$this->menu_border_bottom_css = $pluginParams->get('menu_border_bottom_css');*/

		$mosConfig_live_site = JURI::base();
		$this->base_url = $mosConfig_live_site."plugins/content";
		$this->base_url2 = $mosConfig_live_site."plugins/content/DropDownMenuApplestyle";
	}

	function get_param16($module, $obj) {
		#$plugin =& JPluginHelper::getPlugin('content', $module);
		#$pluginParams = new JParameter( $plugin->params );
		/*$pluginParams = $obj->params;
		$this->reveal_animate = $pluginParams->get('reveal_animate');
		$this->menu_width = $pluginParams->get('width');
		$this->down_arrow = $pluginParams->get('down_arrow');
		$this->right_arrow = $pluginParams->get('right_arrow');
		$this->menu_border_bottom_css = $pluginParams->get('menu_border_bottom_css');*/

		$mosConfig_live_site = JURI::base();
		$this->base_url = $mosConfig_live_site."plugins/content";
		#$this->base_url2 = $mosConfig_live_site."plugins/content/DropDownMenuApplestyle";
		$this->base_url2 = $mosConfig_live_site."plugins/content/DropDownMenuApplestyle/DropDownMenuApplestyle";
	}

}

?>
