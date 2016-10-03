<?php
/**
 * accordion_categories_tree.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * $Id: cc_accept.php v 1.2 2006-09-06
 *
 * Created by Steven Mewhirter
 * KandS Web Development 2012
 */

// test if box should display
	$show_accordion = true;

	if ($show_accordion == true) {
			require($template->get_template_dir('tpl_accordion_categories_tree.php',DIR_WS_TEMPLATE, $current_page_base,'sideboxes'). '/tpl_accordion_categories_tree.php');

						$title =  'CATEGORIES';
					$title_link = false;

			require($template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base,'common') . '/' . $column_box_default);
 }
?>
