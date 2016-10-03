<?php
/**
 * @package admin
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: denied.php 18698 2011-05-04 14:50:06Z wilt $
 */

require('includes/application_top.php');
$do_search = false;
$got_product = false;

if(isset($_POST['search_oid'])&&zen_not_null($_POST['search_oid'])){
	$field = "products_model";
	$search_value =  zen_db_prepare_input($_POST['search_oid']);
	$do_search = true;
} else if (isset($_POST['search_mid'])&&zen_not_null($_POST['search_mid'])){
	$field = "manufactures_code";
	$search_value =  zen_db_prepare_input($_POST['search_mid']);
} else if (isset($_POST['search_pid'])&&zen_not_null($_POST['search_pid'])){
	$field = "products_id";
	$do_search = true;
	$search_value =  zen_db_prepare_input($_POST['search_pid']);
}

if($do_search){	
	$sqlP = "SELECT * FROM " . TABLE_PRODUCTS . " WHERE  $field = '$search_value'";
	$rs_products = $db->Execute($sqlP);
	
	if(!$rs_products->EOF){
		$got_product=true;
		$pInfo = new objectInfo($rs_products->fields);

		$sqlPef = "SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE  products_id = " . $pInfo->products_id;
		$rs_pef = $db->Execute($sqlPef);
		$pefInfo = new objectInfo($rs_pef->fields);
		
		$sqlPd = "SELECT * FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE  products_id = " . $pInfo->products_id;
		$rs_pd = $db->Execute($sqlPd);
		$pdInfo = new objectInfo($rs_pd->fields);
		
		$sqlM = "SELECT * FROM " . TABLE_MANUFACTURERS . " WHERE  manufacturers_id = " . $pInfo->manufacturers_id;
		$rs_M = $db->Execute($sqlM);
		$pMInfo = new objectInfo($rs_M->fields);
		
		$sqlX = "SELECT * FROM products_xsell WHERE  products_id = " . $pInfo->products_id;
		$rs_X = $db->Execute($sqlX);
		while(!$rs_X->EOF){
			$pXInfo[] = $rs_X->fields['xsell_id'];
			$rs_X->MoveNext();
		}
		$xSells = '';
		if(sizeof($pXInfo)>0){
			$xSells = implode(',',$pXInfo);
		}
		
		$sqlCat = "SELECT * FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE  products_id = " . $pInfo->products_id;
		$rs_Cat = $db->Execute($sqlCat);
		$pCatInfo='';
		while(!$rs_Cat->EOF){
			$pCatInfo .= $rs_Cat->fields['categories_id'];
			$rs_Cat->MoveNext();
			if(!$rs_Cat->EOF)$pCatInfo .= ", ";
		}
	}
}


if(isset($_GET['action'])&&$_GET['action']=='update'){
	extract($_POST);
	
	/// Products table data
	
	$sql_data_array = array(
		'products_quantity'       => 1,  
		'products_model'          => $products_model,
		'products_image'          => $products_image,
		'products_price'          => $products_price,
		'products_weight'         => $products_weight,
		'products_status'         => $products_status,
		'manufacturers_id'        => $manufacturers_id,
		'products_status'         => $products_status
	);
	
	$p1 = zen_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = $products_id");
	
	/// Products description table data
	
	$sql_data_array = array(
		'products_name'           => $products_name,  
		'products_description'    => $products_description
	);
	
	$p2 = zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = $products_id");
	
	/// Manufacrurers table data
	
	//$sql_data_array = array(
//		'products_name'           => $products_name,  
//		'products_description'    => $products_description
//		);
//	
//	zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = $products_id");
	
	/// Products extra fields table data
	
	$sql_data_array = array(
		'dimensions_height'         => $dimensions_height,  
		'dimensions_width'          => $dimensions_width,
		'dimensions_depth'          => $dimensions_depth,
		'product_dia'               => $product_dia,
		'product_min_drop'          => $product_min_drop,
		'product_max_drop'          => $product_max_drop,
		'product_length'            => $product_length,
		'product_recess'            => $product_recess,  
		'bulbs_qty'                 => $bulbs_qty,
		'bulbs_type'                => $bulbs_type,
		'bulbs_watts'               => $bulbs_watts,
		'bulbs_cap'                 => $bulbs_cap,
		'bulbs_included'            => $bulbs_included,
		'ip_rating'                 => $ip_rating,
		'manufactures_code'         => $manufactures_code,
		'product_colour'            => $product_colour,
		'product_style'             => $product_style,
		'product_finish'            => $product_finish,
		'product_material'          => $product_material,
		'product_safety_class'      => $product_safety_class,
		'product_options'           => $product_options,
		'product_voltage'           => $product_voltage,
		'product_guarantee'         => $product_guarantee,
		'product_nonreturn'         => $product_nonreturn,  
		'product_transformer'       => $product_transformer,
		'product_driver'            => $product_driver,
		'product_cut_out'           => $product_cut_out,
		'product_surface_temp'      => $product_surface_temp,
		'product_cable'             => $product_cable,
		'product_carriage'          => $product_carriage,
		'product_statements'        => $product_statements,
		'product_tilt'              => $product_tilt,
		'product_variant'           => $product_variant,
		'product_priority'          => $product_priority,
		'family_caption'            => $family_caption,
		'now_price'                 => $now_price,
		'rrp'                       => $rrp,
		'rate_1'                    => $rate_1,
		'rate_2'                    => $rate_2,
		'rate_3'                    => $rate_3,
		'bulbs_s1'                  => $bulbs_s1,
		'bulbs_s1'                  => $bulbs_s1,
		'info'                      => $info,
		'show_price'                => $show_price,
		'web_price'                 => $web_price,
		'lumens'                    => $lumens,
		'colour_temp'               => $colour_temp,
		'energy_class'              => $energy_class,
		'cri'                       => $cri,
		'hours'                     => $hours,
		'multi_quantity'            => $multi_quantity,
		'multi_price'               => $multi_price,
		'sale_price'                => $sale_price,
		'trade_multi_price'         => $trade_multi_price,
		'trade_multi_quantity'      => $trade_multi_quantity,
		'box_quantity'              => $box_quantity,
		'bulb_finish'               => $bulb_finish,
		'bulb_shape'                => $bulb_shape,
		'bulb_dimmable'             => $bulb_dimable
	);
	
	$p3 = zen_db_perform(TABLE_PRODUCTS_EXTRA_FIELDS, $sql_data_array, 'update', "products_id = $products_id");
	
	
	///Cross sell Table data
	
	$xSell = explode(',',$xsells);
	$xsell_pass = true;
	if(sizeof($xSell)>0){
		$xsell_pass = false;
		$db->execute('delete from products_xsell where products_id = "'.$products_id.'"');
		$insert_array = array();
		for($i=0;$i<sizeof($xSell);$i++){
			$insert_array = array('products_id' => $products_id,
			'xsell_id' => $xSell[$i],
			'sort_order' => 1);
			$r = zen_db_perform('products_xsell', $insert_array);
		}
		if(is_object($r)){
			$xsell_pass = true;
		}
	}
	
	///products to Categories table data
	$cats = explode(',',$categories_id);
	$cats_pass = true;
	if(sizeof($cats)>0){
		$db->execute('delete from products_to_categories where products_id = "'.$products_id.'"');
		$insert_array = array();
		for($i=0;$i<sizeof($cats);$i++){
			$insert_array = array('products_id' => $products_id,
			'categories_id' => $cats[$i]);
			$c = zen_db_perform('products_to_categories', $insert_array);
			if(!is_object($c)){
				$cats_pass = false;
			}
		}
	}
	
	
	if(is_object($p1)& is_object($p2)& is_object($p3) & $xsell_pass){
		$message = "Product $products_model $products_name <b>has been updated</b>";
		$messageStack->add_session($message, 'success');
	}else{
		$message = "Update <b>FAILED</b>";
		$messageStack->add_session($message, 'error');
	}
	zen_redirect(zen_href_link(FILENAME_PRODUCT_EDITOR,  ''));
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<link rel="stylesheet" type="text/css" href="includes/admin_access.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
	<!--
	function init()
	{
		cssjsmenu('navbar');
		if (document.getElementById)
		{
			var kill = document.getElementById('hoverJS');
			kill.disabled = true;
		}
	}
	// -->
</script>
<style type="text/css">
.toprow{background-color: #ddd; font-size: 1.4em; text-align: center;}
</style>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<br>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<?php //if($messageStack->size('productEditor')>0){
	//echo "<tr><td>".$messageStack->output('productEditor')."</td></tr>";
//}?>
	<tr>
	<td width="10px">&nbsp;</td>
	<td class="pageHeading" colspan="5">Product Search</td>
	
	<tr><td>
	<?php
		echo zen_draw_form('search','product_editor');	?>
		<td width="100px">Our Code 
		<?php
			echo zen_draw_input_field('search_oid');
		?>
		</td>
		<td width="180px">Manufacture Code 
		<?php
			echo zen_draw_input_field('search_mid');
		?>
		</td>
		<td width="100px">Product ID 
		<?php
			echo zen_draw_input_field('search_pid');
		?>
		</td>
		<td width="60%">  
		<?php
			echo zen_image_submit('button_search.gif', '');
		?>
		</td>
	</tr>
	</form>
</table>
<br>
<?php
if($got_product){	
	echo zen_draw_form("productinfo", FILENAME_PRODUCT_EDITOR, 'action=update', 'post', 'enctype="multipart/form-data"');
	echo zen_draw_hidden_field("products_id",$pInfo->products_id);
?>	
<table border="1" cellpadding="4" width="1550px">
	<tr class="toprow">
					<td width="150px"><b><?php echo $pdInfo->products_id;?></b></td>
					<td width="450px"><?php echo $pdInfo->products_name;?></td>
					<td width="150px"><?php echo $pInfo->products_model;?></td>
					<td width="200px" ><?php echo $pefInfo->manufactures_code;?></td>
					<td width="250px" ><?php echo $pMInfo->manufacturers_name;?></td>
					<td ><?php echo $pInfo->products_last_modified;?></td>
	</tr>
</table>
<table border="2"><tr><td>
	<table >
		<tr>
			<td width="150px" colspan="2">Our Code <?php echo zen_draw_input_field('products_model', $pInfo->products_model, zen_set_field_length(TABLE_PRODUCTS, 'products_model'))?><br></td>
			<td width="150px" colspan="2">Manufactures Code <?php echo zen_draw_input_field('manufactures_code', $pefInfo->manufactures_code, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'manufactures_code'))?></td>
			<td width="150px" colspan="3">Image <?php echo zen_draw_input_field('products_image', $pInfo->products_image, zen_set_field_length(TABLE_PRODUCTS, 'products_image'))?></td>
			<td width="150px" colspan="3">Product Name <?php echo zen_draw_input_field('products_name', $pdInfo->products_name, zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_name'))?></td>
		</tr>
		<tr>
			<td width="150px" colspan="5">Product Description <?php echo zen_draw_textarea_field('products_description', true, 80,2, $pdInfo->products_description)?></td>
			<td width="150px" colspan="2">Product Price <?php echo zen_draw_input_field('products_price', $pInfo->products_price, zen_set_field_length(TABLE_PRODUCTS, 'products_price'))?></td>
			<td width="150px" colspan="1">Show Price <?php echo zen_draw_input_field('show_price', $pefInfo->show_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'show_price'))?></td>
			<td width="150px" colspan="2">RRP Price <?php echo zen_draw_input_field('rrp', $pefInfo->rrp, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'rrp'))?></td>
		</tr>
		<tr>
			<td width="150px" colspan="2">Web Price <?php echo zen_draw_input_field('web_price', $pefInfo->web_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'web_price'))?></td>
			<td width="150px" colspan="2">Rate 1 <?php echo zen_draw_input_field('rate_1', $pefInfo->rate_1, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'rate_1'))?></td>
			<td width="150px" colspan="2">Rate 2 <?php echo zen_draw_input_field('rate_2', $pefInfo->rate_2, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'rate_2'))?></td>
			<td width="150px" colspan="2">Rate 3 <?php echo zen_draw_input_field('rate_3', $pefInfo->rate_3, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'rate_3'))?></td>
			<td width="150px" colspan="2">Weight <?php echo zen_draw_input_field('products_weight', $pInfo->products_weight, zen_set_field_length(TABLE_PRODUCTS, 'products_weight'))?><br></td>
		</tr>
		<tr>
			<td width="150px" colspan="2">Manufacturer ID <?php echo zen_draw_input_field('manufacturers_id', $pMInfo->manufacturers_id, zen_set_field_length(TABLE_MANUFACTURERS, 'manufacturers_id'))?></td>
			<td width="150px" colspan="3">Categories <?php echo zen_draw_input_field('categories_id', $pCatInfo, 'size="50" maxlength="50"')?></td>
			<td width="150px" colspan="1">Status <?php echo zen_draw_input_field('products_status', $pInfo->products_status, zen_set_field_length(TABLE_PRODUCTS, 'products_status'))?><br></td>
			<td width="150px" colspan="1">Product Style <?php echo zen_draw_input_field('product_style', $pefInfo->product_style, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_style'))?></td>
			<td width="150px" colspan="2">Product Finish <?php echo zen_draw_input_field('product_finish', $pefInfo->product_finish, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_finish'))?></td>
			<td width="150px" colspan="2">Product Material <?php echo zen_draw_input_field('product_material', $pefInfo->product_material, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_material'))?></td>
	</tr>
		<tr>
			<td width="150px" colspan="2">Product Colour <?php echo zen_draw_input_field('product_colour', $pefInfo->product_colour, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_colour'))?></td>
			<td width="150px" colspan="1">Bulb Qty <?php echo zen_draw_input_field('bulbs_qty', $pefInfo->bulbs_qty, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_qty'))?></td>
			<td width="150px" colspan="2">Bulb Type <?php echo zen_draw_input_field('bulbs_type', $pefInfo->bulbs_type, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_type'))?></td>
			<td width="150px" colspan="1">Bulb Wattage <?php echo zen_draw_input_field('bulbs_watts', $pefInfo->bulbs_watts, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_watts'))?></td>
			<td width="150px" colspan="1">Bulb Cap <?php echo zen_draw_input_field('bulbs_cap', $pefInfo->bulbs_cap, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_cap'))?></td>
			<td width="150px" colspan="1">Bulb Included <?php echo zen_draw_input_field('bulbs_included', $pefInfo->bulbs_included, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_included'))?></td>
			<td width="150px" colspan="1">Lumens <?php echo zen_draw_input_field('lumens', $pefInfo->lumens, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'lumens'))?></td>
			<td width="150px" colspan="1">Colour Temp <?php echo zen_draw_input_field('colour_temp', $pefInfo->colour_temp, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'colour_temp'))?></td>
		</tr>
		<tr>
			<td width="150px" colspan="3">Bulb Information <?php echo zen_draw_input_field('bulbs_s1', $pefInfo->bulbs_s1, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulbs_s1'))?></td>
			<td width="150px" colspan="1">Energy Class <?php echo zen_draw_input_field('energy_class', $pefInfo->energy_class, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'energy_class'))?></td>
			<td width="150px" colspan="1">CRI <?php echo zen_draw_input_field('cri', $pefInfo->cri, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'cri'))?></td>
			<td width="150px" colspan="1">Bulb Hrs <?php echo zen_draw_input_field('hours', $pefInfo->hours, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'hours'))?></td>
			<td width="150px" colspan="3">Information <?php echo zen_draw_input_field('info', $pefInfo->info, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'info'))?></td>
			<td width="150px" colspan="1"></td>
		</tr>
		<tr>
			<td width="150px" colspan="2">Bulb Finish <?php echo zen_draw_input_field('bulb_finish', $pefInfo->bulb_finish, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulb_finish'))?></td>
			<td width="150px" colspan="3">Bulb Shape <?php echo zen_draw_input_field('bulb_shape', $pefInfo->bulb_shape, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulb_shape'))?></td>
			<td width="150px" colspan="1">Dimmable <?php echo zen_draw_input_field('bulb_dimmable', $pefInfo->bulb_dimmable, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'bulb_dimmable'))?></td>
			<td width="150px" colspan="3">Cross Sell <?php echo zen_draw_input_field('xsells', $xSells, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'info'))?></td>
			<td width="150px" colspan="1"></td>
		</tr>
		<tr>
			<td width="150px" colspan="2">Height <?php echo zen_draw_input_field('dimensions_height', $pefInfo->dimensions_height, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'dimensions_height'))?></td>
			<td width="150px" colspan="2">Width <?php echo zen_draw_input_field('dimensions_width', $pefInfo->dimensions_width, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'dimensions_width'))?></td>
			<td width="150px" colspan="2">Depth <?php echo zen_draw_input_field('dimensions_depth', $pefInfo->dimensions_depth, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'dimensions_depth'))?></td>
			<td width="150px" colspan="2">Diameter <?php echo zen_draw_input_field('product_dia', $pefInfo->product_dia, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_dia'))?></td>
			<td width="150px" colspan="2">Min Drop <?php echo zen_draw_input_field('product_min_drop', $pefInfo->product_min_drop, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_min_drop'))?></td>
		</tr>
		<tr>
			<td width="150px" colspan="2">Max Drop <?php echo zen_draw_input_field('product_max_drop', $pefInfo->product_max_drop, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_max_drop'))?></td>
			<td width="150px" colspan="2">Length <?php echo zen_draw_input_field('product_length', $pefInfo->product_length, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_length'))?></td>
			<td width="150px" colspan="2">Recess <?php echo zen_draw_input_field('product_recess', $pefInfo->product_recess, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_recess'))?></td>
			<td width="150px" colspan="1">NonReturn <?php echo zen_draw_input_field('product_nonreturn', $pefInfo->product_nonreturn, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_nonreturn'))?></td>
			<td width="150px" colspan="1">IP Rating <?php echo zen_draw_input_field('ip_rating', $pefInfo->ip_rating, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'ip_rating'))?></td>
			<td width="150px" colspan="1">Voltage <?php echo zen_draw_input_field('product_voltage', $pefInfo->product_voltage, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_voltage'))?></td>
			<td width="150px" colspan="1">Guarantee <?php echo zen_draw_input_field('product_guarantee', $pefInfo->product_guarantee, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_guarantee'))?></td>
		</tr>
		<tr>
			<td width="150px" colspan="5">Product Options <?php echo zen_draw_textarea_field('product_options', true, 80,2, $pefInfo->product_options)?></td>
			<td width="150px" colspan="1">Saftey Class <?php echo zen_draw_input_field('product_safety_class', $pefInfo->product_safety_class, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_safety_class'))?></td>
			<td width="150px" colspan="1">Transformer <?php echo zen_draw_input_field('product_transformer', $pefInfo->product_transformer, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_transformer'))?></td>
			<td width="150px" colspan="1">Driver <?php echo zen_draw_input_field('product_driver', $pefInfo->product_driver, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_driver'))?></td>
			<td width="150px" colspan="1">Cut Out <?php echo zen_draw_input_field('product_cut_out', $pefInfo->product_cut_out, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_cut_out'))?></td>
			<td width="150px" colspan="1">Surface Temp <?php echo zen_draw_input_field('product_surface_temp', $pefInfo->product_surface_temp, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_surface_temp'))?></td>
		</tr>
		<tr>
		<td width="150px" colspan="1">Cable <?php echo zen_draw_input_field('product_cable', $pefInfo->product_cable, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_cable'))?></td>
		<td width="150px" colspan="1">Carriage <?php echo zen_draw_input_field('product_carriage', $pefInfo->product_carriage, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_carriage'))?></td>
		<td width="150px" colspan="1">Tilt <?php echo zen_draw_input_field('product_tilt', $pefInfo->product_tilt, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_tilt'))?></td>
		<td width="150px" colspan="1">Priority <?php echo zen_draw_input_field('product_priority', $pefInfo->product_priority, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'product_priority'))?></td>
		<td width="150px" colspan="2">Family Caption <?php echo zen_draw_input_field('family_caption', $pefInfo->family_caption, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'family_caption'))?></td>
		<td width="150px" colspan="3">Now Price <?php echo zen_draw_input_field('now_price', $pefInfo->now_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'now_price'))?></td>
	</tr>
		<tr>
			<td width="150px" colspan="5">Product Statements <?php echo zen_draw_textarea_field('product_statements', true, 80,2, $pefInfo->product_statements)?></td>
			<td width="150px" colspan="5">Product Variant <?php echo zen_draw_textarea_field('product_variant', true, 80,2, $pefInfo->product_variant)?></td>
		</tr>
		<tr>
		<td width="150px" colspan="1">Multi Qty <?php echo zen_draw_input_field('multi_quantity', $pefInfo->multi_quantity, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'multi_quantity'))?></td>
		<td width="150px" colspan="1">Multi Price <?php echo zen_draw_input_field('multi_price', $pefInfo->multi_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'multi_price'))?></td>
		<td width="150px" colspan="1">Trade Multi Qty <?php echo zen_draw_input_field('trade_multi_quantity', $pefInfo->trade_multi_quantity, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'trade_multi_quantity'))?></td>
		<td width="150px" colspan="2">Trade Multi Price <?php echo zen_draw_input_field('trade_multi_price', $pefInfo->trade_multi_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'trade_multi_price'))?></td>
		<td width="150px" colspan="3">Sale Price <?php echo zen_draw_input_field('sale_price', $pefInfo->sale_price, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'sale_price'))?></td>
		<td width="150px" colspan="1">Box Qty <?php echo zen_draw_input_field('box_quantity', $pefInfo->box_quantity, zen_set_field_length(TABLE_PRODUCTS_EXTRA_FIELDS, 'box_quantity'))?></td>
		<td width="150px" colspan="1"></td>
	</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
<!--			<td colspan="1">&nbsp;</td>-->
			<td colspan="4">&nbsp;</td>
			<td colspan="2"><?php echo '<br><a href="' . zen_href_link(FILENAME_PRODUCT_EDITOR, '') . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;&nbsp;'. zen_image_submit('button_save.gif', IMAGE_SAVE);?></td>
		</tr>
</table>
</td></tr></table>
<?php
	echo "</form>";
}//end of if($got_product)
?>


<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
