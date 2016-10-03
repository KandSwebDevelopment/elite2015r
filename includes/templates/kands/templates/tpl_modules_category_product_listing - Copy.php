<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_product_listing.php 3241 2006-03-22 04:27:27Z ajeh $
 * UPDATED TO WORK WITH COLUMNAR PRODUCT LISTING 04/04/2006
 * Modified for admin control of customer option by Glenn Herbert (gjh42) 2012-09-21   2012-11-17 grid sorter
 */
 include(DIR_WS_MODULES . zen_get_module_directory('category_product_listing.php'));
?>
<?php
	$check_for_alpha = $listing_sql;
	$check_for_alpha = $db->Execute($check_for_alpha);

	if ($do_filter_list || ($check_for_alpha->RecordCount() > 0 && PRODUCT_LIST_ALPHA_SORTER == 'true') || (defined('PRODUCT_LISTING_LAYOUT_STYLE_CUSTOMER') and PRODUCT_LISTING_LAYOUT_STYLE_CUSTOMER == '1')) {//form if list/grid enabled
	$form = zen_draw_form('filter', zen_href_link(FILENAME_DEFAULT), 'get');// . '<label class="inputLabel">' .TEXT_SHOW . '</label>';
?>

<?php
	echo $form;
	echo zen_draw_hidden_field('main_page', FILENAME_DEFAULT);
	echo zen_hide_session_id();
?>
<?php
	// draw cPath if known
	if (!$getoption_set) {
		echo zen_draw_hidden_field('cPath', $cPath);
	} else {
		// draw manufacturers_id
		echo zen_draw_hidden_field($get_option_variable, $_GET[$get_option_variable]);
	}

	// draw music_genre_id
	if (isset($_GET['music_genre_id']) && $_GET['music_genre_id'] != '') echo zen_draw_hidden_field('music_genre_id', $_GET['music_genre_id']);

	// draw record_company_id
	if (isset($_GET['record_company_id']) && $_GET['record_company_id'] != '') echo zen_draw_hidden_field('record_company_id', $_GET['record_company_id']);

	// draw typefilter
	if (isset($_GET['typefilter']) && $_GET['typefilter'] != '') echo zen_draw_hidden_field('typefilter', $_GET['typefilter']);

	// draw manufacturers_id if not already done earlier
	if ($get_option_variable != 'manufacturers_id' && isset($_GET['manufacturers_id']) && $_GET['manufacturers_id'] > 0) {
		echo zen_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
	}

	// draw sort
	echo zen_draw_hidden_field('sort', $_GET['sort']);

//-bof-lat9-Products Pagination
	if (isset($_GET['pagecount']) && zen_not_null($_GET['pagecount'])) echo zen_draw_hidden_field('pagecount', $_GET['pagecount']);
//-eof-lat9-Products Pagination

	// draw filter_id (ie: category/mfg depending on $options)
	if ($do_filter_list) {
		echo zen_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
	}
	
	if (defined('PRODUCT_LISTING_LAYOUT_STYLE_CUSTOMER') and PRODUCT_LISTING_LAYOUT_STYLE_CUSTOMER == '1') {
		echo '<div id="viewControl">View by:' . zen_draw_pull_down_menu('view', array(array('id'=>'rows','text'=>PRODUCT_LISTING_LAYOUT_ROWS),array('id'=>'columns','text'=>PRODUCT_LISTING_LAYOUT_COLUMNS)), (isset($_GET['view']) ? $_GET['view'] : (defined('PRODUCT_LISTING_LAYOUT_STYLE')? PRODUCT_LISTING_LAYOUT_STYLE: 'rows')), 'onchange="this.form.submit()"') . '</div>';  
	}
	// draw alpha sorter
	require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_PRODUCT_LISTING_ALPHA_SORTER));
?>
</form>
<?php
	}
?>

<?php
 require($template->get_template_dir('tpl_modules_filter.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_filter.php');
?>




<br class="clearBoth" />

<?php
/**
 * require the code for listing products
 */
 require($template->get_template_dir('tpl_modules_product_listing.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_product_listing.php');
?>

</div>
