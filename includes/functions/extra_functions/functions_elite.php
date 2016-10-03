<?php

function get_filter_dropdown($filter){
	global $db;
	$filter_array = array();
	$filter_array[] = array('id' => -1, 'text'=> 'All');

	if($cID == -1) return $filter_array;

	 $query = "select * from filter_options_values where foptions_group = '" . (int)$filter . "'
														order by foptions_name";

		$rs_filters = $db->Execute($query);

		while (!$rs_filters->EOF) {
			$filter_array[] = array('id' => $rs_filters->fields['foptions_value'], 'text'=> $rs_filters->fields['foptions_name']);
			$rs_filters->MoveNext();
		}
		return $filter_array;
	
}



//This creates an email to us when someone signs up
function create_account_email($data){

}


function get_price($for, $pID, $account_status){
/**THIS HAS BEEN MADE REDUNDANT SINCE JUNE 2014 AND IS NOW HANDLED IN tpl_modules_price.php
*
* put your comment there...
*
* @param mixed $for							is the letter for which price list to use
* @param mixed $pID
* @param mixed $account_status	0 = Show nothing, 1 Only show public, 2 show public & trade
* @return mixed
*/
		global $db, $currencies, $products_rate_1,$products_rate_2,$products_rate_3,$products_web_price,$products_price,$products_rrp;
		$rs=$db->execute("SELECT show_price FROM product_extra_fields WHERE products_id = $pID");
		if($rs->EOF)return '';
		$p=strpos($rs->fields['show_price'],$for);
		if($p===FALSE)return '<div class="cui">Contact us for pricing and availability information.</div>';
		$output='';
		switch(substr($rs->fields['show_price'],$p,1)){
				case 'A': //A
						if($account_status==0){
								/*if($products_rrp>0){
										$output = '<div class="rrp">Recomended Retail Price '.$currencies->format($products_rrp).'</div>';
										$output .= '<div class="yp">Web Price '.$currencies->format($products_web_price).'</div>';
										$output .= '<div class="cui">Contact us for availability information.</div>';
								}else{
										if($products_web_price>0){
												$output = '<div class="rp">Retail Price '.$currencies->format($products_web_price).'</div>';
												$output .= '<div class="cui">Contact us for availability information.</div>';
										}else{*/
												$output = '<div class="cui">Contact us for pricing and availability information.</div>';
										//}
								//}
						 }else{
							 if($products_rrp>0){
										$output = '<div class="rrp">Recomended Retail Price '.$currencies->format($products_rrp).'</div>';
										//$output .= '<div class="yp">Your Price '.$currencies->format($products_web_price).'</div>';
										//$output .= '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
								}else{
										if($products_web_price>0){
												$output = '<div class="rp">Retail Price '.$currencies->format($products_web_price).'</div>';
											 //$output .= '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
										}else{
												$output = '<div class="cui">Contact us for pricing and availability information.</div>';
										}
								}
						}
						break;
						case 'B':
						case 'C':
						case 'D':
								if($account_status==0){
										$output = '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
								}
								if($account_status==1){
										if($products_rrp>0){
												$output = '<div class="rrp">Recomended Retail Price '.$currencies->format($products_rrp).'</div>';
												$output .= '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
										}else{
												if($products_web_price>0){
														$output = '<div class="rp">Retail Price '.$currencies->format($products_web_price).'</div>';
														$output .= '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
												}else{
														$output = '<div class="cui">Your account is pending approval please <br />contact us for your price and availability information.</div>';
												}
										}
								}else{
										switch(substr($rs->fields['show_price'],$p,1)){
												case 'B':
														$trade_rate = $products_rate_1;
														break;
												case 'C':
														$trade_rate = $products_rate_2;
														break;
												case 'D':
														$trade_rate = $products_rate_3;
										}
										if($products_rrp>0){
												$output = '<div class="rrp">Recomended Retail Price '.$currencies->format($products_rrp).'</div>';
												if($trade_rate>0){
														$output .= '<div class="tradePrice">Your Price '.$currencies->format($trade_rate).'</div>';
														$output .= '<div class="cua">Contact us for availability information.</div>';
												}else{
														$output .= '<div class="cui">contact us for your price and availability information.</div>';
												}
										}else{
												$output = '<div class="rp">Retail Price '.$currencies->format($products_web_price).'</div>';
												if($trade_rate>0){
														$output .= '<div class="tradePrice">Your Price '.$currencies->format($trade_rate).'</div>';
														$output .= '<div class="cui">Contact us for availability information.</div>';
												}else{
														$output .= '<div class="cui">contact us for your price and availability information.</div>';
												}
										}
								}
								break;
		}
		return $output;
}

/**
* vat_split
* @returns array(net, vat, total)
*
* @param mixed $gross
*/
function vat_split($gross){
		$values['net'] = $gross/1.2;
		$values['vat'] = $gross - $values['net'];
		$values['total'] = $gross;
		return $values;
}
/**
*Return trades array in format for using in a dropdown list
*
* @param None required
*
* @returns Array containing each of the trade types
*/
//
function trades_pulldown_array(){
		global $trade_types_array;
		$ta = array();
		$ta[]=array('id'=>'0', 'text'=>'Please Select');
		foreach($trade_types_array as $idx => $trade){
				$ta[]=array('id'=>$trade,'text'=>$trade);
		}
		return $ta;
}

/**
*  Return array of product id's where all product models has the same base code ie C21*'
*  @param ProductID
*/
function get_family_items($pID){
	global $db;
	$family_array = array();
	$res = $db->Execute("SELECT products_model FROM " . TABLE_PRODUCTS . " WHERE products_id = '" . $pID . "'");
	if(!$res->EOF){
		$base_model = substr($res->fields['products_model'],0,8);
		$res = $db->Execute("SELECT products_id FROM " . TABLE_PRODUCTS . " WHERE products_model LIKE '" . $base_model . "%'");
		while(!$res->EOF){
			$family_array[] = $res->fields['product_id'];
		}
	}
}

function get_slideshow_array(){
	$image_array = array();
	$base_name = DIR_WS_IMAGES . "slideshow/*.{jpg,gif,png}";
	foreach(glob($base_name, GLOB_BRACE) as $file){
		$image_array[] = $file;
	}
	return $image_array;
}

function put_slideshow(){
	$images = get_slideshow_array();
	if(sizeof($images)>0){
		?>
		<div class="slideshow">
		<?php
		//var_dump($images);
		echo '<div>';
		echo zen_image($images[0],'','',300, '');
		echo '</div>';

		for($pos=1; $pos < sizeof($images); $pos++){
			echo zen_image($images[$pos],'','',300);
		}
	}
	?>
	</div>
	<?php
}

//Return model ID from given product ID
function mid_to_pid($mID){
	$mID = zen_db_prepare_input($mID);
	global $db;
	$sql = "select products_id, products_model from " . TABLE_PRODUCTS . " where products_model = '$mID' LIMIT 1";
	$result = $db->Execute($sql);

	if ($result->RecordCount() > 0) {
		return $result->fields['products_id'];
	}
	return NULL;
}

function family_quick_count($mID){
	global $db;
	$base_code = substr($mID,0,8);
	$sql = "select COUNT(products_model)as total from " . TABLE_PRODUCTS . " where products_model LIKE '$base_code%' AND products_status = 1 LIMIT 1";
	$result = $db->Execute($sql);
	if(!$result->EOF)  return $result->fields['total'];
	return 0;
}

//Checks if a 'family caption' is to be used instead of normal family count for the family info tooltip
//Returns the string which is to be used in the family info tooltip
//input - model ID
function get_family_caption($mID){
	$mID = zen_db_prepare_input($mID);
	global $db;
	$sql = "SELECT fc.caption
					FROM products p
					JOIN product_extra_fields pef
						ON pef.products_id = p.products_id
					JOIN family_captions fc
						ON fc.id = pef.family_caption
					WHERE p.products_model = '$mID'";
	$result = $db->Execute($sql);

	if ($result->RecordCount() > 0) {
		return $result->fields['caption'];
	}
	return NULL;
}

//Determins if the item is on promition and if so returns a fromated string
//Inputs: $pid -> product id
//        $page -> Use the variable $current_page when calling this function - Purpose - to have different formating for the product info page and the product listing page
function product_promotion($pid, $page, $cpa){
	global $db, $currencies, $cPath_array;
	$out = '';
	$sql = "SELECT now_price, rrp FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE products_id = $pid";
	$result = $db->Execute($sql);
	if(!$result->EOF){
		//Get the text strings to be used for the output based on wheither its Promotion, Sale (cats 270&271) or Clearence (cats 273&274)
		if(is_array($cPath_array)){
			$cpa = array_reverse($cPath_array);
			switch($cpa[0]){
				case 270:
				case 271:
					//Sale
					$pStatus = 'S';	//sale
					$PREFIX=SALE_PREFIX;
					$NAME=SALE_NAME;
					$POSTFIX = SALE_POSTFIX;
					$SHOW_PERCENT = SALE_SHOW_PERCENT;
					$PERCENT_NEAREST = SALE_PERCENT_NEAREST;
					break;
				case 273:
				case 274:
					//Clearence
					$pStatus = 'C';	//Clearence
					$PREFIX=PROMOTION_PREFIX;
					$NAME=PROMOTION_NAME;
					$POSTFIX = PROMOTION_POSTFIX;
					$SHOW_PERCENT = PROMOTION_SHOW_PERCENT;
					$PERCENT_NEAREST = PROMOTION_PERCENT_NEAREST;
					break;
				default:
					$pStatus = 'P';	//Promotion
			}

		}
		//Check now price is not blank
		if($result->fields['now_price']!=NULL && $result->fields['now_price']!='' && $result->fields['now_price']!=0){
			$now_price = format_now_price($result->fields['now_price'],'price');
			//Get the was price - Look RRP first and use it if valid, otherwise use the product default price
			if($result->fields['rrp']!=NULL && $result->fields['rrp']!='' && $result->fields['rrp']!=0){
				$was_price = format_now_price($result->fields['rrp'],'price');
			}
			if($was_price == NULL || $was_price=='' || $was_price==0){
				$was_price=zen_get_products_base_price($pid);
			}

			if($page == 'product_info'){
				//Product info page
				$out = "<div id='promotion_box'><br />".$PREFIX . "<br /><div id='promotion_name'>" . $NAME . "</div>";
				//Get the amount of discount as a percentage
				$discount_string = calulateDiscount($was_price,$result->fields['now_price']);
				if($now_price != ''){
					$out .= "<div id='promotion_was'>Was " . $currencies->display_price($was_price,0) . " </div>";
					$out .= "<div id='discountPrecent'>".$discount_string.'</div>';
					$out .= "<div id='promotion_now'>Now " . $currencies->display_price(format_now_price($result->fields['now_price'],'price'),0)."</div>";
				}
				//$out .= "<div id='now_price_text'>". format_now_price($result->fields['now_price'],'text') . "</div> <div>".$POSTFIX."</div>";
					$out .= '</div>';

			}elseif($page=='promotions' || $page=='advanced_search_result' || $page=='products_all' || $page=='products_new'){
				//Promotions listing, Advanced Search Results, Products All and Products New pages
				if($now_price!=""){
					$out = "<div id='promotion_was_l'>Was " . $currencies->display_price($was_price,0) . " </div>";
					$out .="<div id='promotion_now_l'>Now " .$currencies->display_price(format_now_price($result->fields['now_price'],'price'),0)."</div><br />";
				}
				$out .= "<div id='promotion_now_l'>". format_now_price($result->fields['now_price'],'text') . "</div>";

			}elseif($page=='index'||$page='sales_page'){
				//Product listing page
				if($now_price!=""){
					$out = "<div id='promotion_was_l'>Was " . $currencies->display_price($was_price,0) . " </div><div id='promotion_now_l'>Now " .$currencies->display_price(format_now_price($result->fields['now_price'],'price'),0)."</div><br />";
				}
				$out .= "<div id='now_price_text'>". format_now_price($result->fields['now_price'],'text') . "</div>";

			}elseif($page =='xxxxxxxxxxxx'){

			}else{
				$out = "Error - no format for call with page $page (functions_elite.php-278)";
			}
		}
	}
	return $out;
}

//This function splits the promotion now price into the two parts, price or text
//$part is either 'price' or 'text' and this determines the part to be returned
function format_now_price($now_price_text, $part='price'){
	$now_price_text = str_replace('?','',$now_price_text);
	if(strpos($now_price_text, '*')===FALSE){
		if($part=='price'){
			return $now_price_text;
		}else{
			return '';
		}
	}
	if($part=='price'){
		return substr($now_price_text,0,strpos($now_price_text,'*'));
	}else{
		return substr($now_price_text,strpos($now_price_text,'*')+1);
	}
}

//Counts number if items that are on promotion
function count_promotion_items(){
	global $db;
	$sql="SELECT * FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " WHERE now_price > 0";
	$result = $db->Execute($sql);
	$num_rows = $result->RecordCount();
	return $num_rows;
}

//Returns the image xref for the given orginal
function get_image_xref($orginal){
	global $db;
	if(defined('XREF') && XREF == true){ 
			$sql = "SELECT xref FROM image_xref WHERE orginal = '$orginal'";
			$result = $db->Execute($sql);
			if(!$result->EOF){
				return $result->fields['xref'];
			}
	}
	return $orginal;
}

///This is not used ????? I think////////////////////////////////////////////////////
//function convert($str,$ky='145785421'){                                            //
//  if($ky=='')return $str;                                                          //
//  $kl=strlen($ky)<32?strlen($ky):32;                                               //
//  $k=array();for($i=0;$i<$kl;$i++){																								 //
//  $k[$i]=ord($ky{$i})&0x1F;}																											 //
//  $j=0;for($i=0;$i<strlen($str);$i++){                                             //
//  $e=ord($str{$i});                                                                //
//  $str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e);                                         //
//  $j++;$j=$j==$kl?0:$j;}                                                           //
//  return $str;                                                                     //
//}                                                                                  //
/////////////////////////////////////////////////////////////////////////////////////

function encode($string,$key='145785421') {
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		for ($i = 0; $i < $strLen; $i++) {
				$ordStr = ord(substr($string,$i,1));
				if ($j == $keyLen) { $j = 0; }
				$ordKey = ord(substr($key,$j,1));
				$j++;
				$hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
		}
		return $hash;
}

function decode($string,$key='145785421') {
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		for ($i = 0; $i < $strLen; $i+=2) {
				$ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
				if ($j == $keyLen) { $j = 0; }
				$ordKey = ord(substr($key,$j,1));
				$j++;
				$hash .= chr($ordStr - $ordKey);
		}
		return $hash;
}

if(!function_exists(str_split)){
		function str_split ($string) {

			// don't proceed if the string is empty
			if (empty ($string) || strlen ($string) < 1) return false;

			// check to see if PHP 5+ really exists
			// if so, use it's str_split function. :)
			if (function_exists ('str_split')) {
					return str_split ($string);
			}

			// PHP 4 version
			// we'll store the result in this array
			$arr_string = array();
			// iterate over all the string's characters
			for ($i=0; $i<strlen($string); $i++) {
					// push current character onto our return array
					$arr_string[] = $string{$i};
			}
			// return finished array
			return $arr_string;
	}
}

//Function CalulateDiscount
//Given $was price and $now price it returns the % of discount
function calulateDiscount($was, $now, $nearest=5, $option='', $flag=''){
	if(($was==0||$was==''||$was==NULL)||($now==0||$now==''||$now==NULL))return '';
		$percent = (($was-$now)/$was)*100;

		if($percent%$nearest==0){
			return (int)$percent.'% off';
		}
		$percent=roundDownToNearest($percent,$nearest);
		return 'Over '.$percent.'% off';
}

function roundUpToNearest($value,$nearest=5) {
		return round(($value+$nearest/2)/$nearest)*$nearest;
}
function roundDownToNearest($value, $nearest=5) {
		return floor($value / $nearest) * $nearest;
}

/**
* Use this function for an image button when using CSS buttons, but require an actual
*  image button
*
* @param mixed $image
* @param mixed $alt
* @param mixed $parameters
* @param mixed $sec_class
*/
function kas_image_button($image, $alt = '', $parameters = '', $sec_class = '') {
	global $template, $current_page_base, $zco_notifier;

	// inject rollover class if one is defined. NOTE: This could end up with 2 "class" elements if $parameters contains "class" already.
	if (defined('IMAGE_ROLLOVER_CLASS') && IMAGE_ROLLOVER_CLASS != '') {
		$parameters .= (zen_not_null($parameters) ? ' ' : '') . 'class="rollover"';
	}

	$zco_notifier->notify('PAGE_OUTPUT_IMAGE_BUTTON');
	return zen_image($template->get_template_dir($image, DIR_WS_TEMPLATE, $current_page_base, 'buttons/' . $_SESSION['language'] . '/') . $image, $alt, '', '', $parameters);
}

	function kas_image_submit($image, $alt = '', $parameters = '', $sec_class = '') {
		global $template, $current_page_base, $zco_notifier;
		//if (strtolower(IMAGE_USE_CSS_BUTTONS) == 'yes' && strlen($alt)<30) return zenCssButton($image, $alt, 'submit', $sec_class /*, $parameters = ''*/ );
		$zco_notifier->notify('PAGE_OUTPUT_IMAGE_SUBMIT');

		$image_submit = '<input type="image" src="' . zen_output_string($template->get_template_dir($image, DIR_WS_TEMPLATE, $current_page_base, 'buttons/' . $_SESSION['language'] . '/') . $image) . '" alt="' . zen_output_string($alt) . '"';

		if (zen_not_null($alt)) $image_submit .= ' title=" ' . zen_output_string($alt) . ' "';

		if (zen_not_null($parameters)) $image_submit .= ' ' . $parameters;

		$image_submit .= ' />';

		return $image_submit;
	}

	/**
	* Returns the amount of tax from a total including tax
	*
	* @param mixed $value	= the value including tax
	* @param mixed $tax = tax rate percentage
	* @return int = amount of tax
	*/
	function tax_form_total($value, $tax){
		return $value-($value/(($tax/100)+1));
	}

	/**
	*	@function elite_basket_price
	* 	It works out which one of the million prices we have, to use for items basket
	* 	price
	* 	Orginal wrote for basket but may be usefull else where 9/7/14
	* @var pid - product it to get price
	* 		 Qty - quantity to get price for(to account for multi buys)
	* 		 unit - delfaults to true which returns the price each
	* 												 false returns the unit price * qty
	* @uses			$_SESSION['customer_id']
	* 					$_SESSION['customers_authorization']	-	0=Auth, 2=Not Auth
	* 					$_SESSION['customer_trade_type']			-	B,C,D
	* 					These are set when the customer logs in
	* 				Also the following are looked up in db for product
	* 					These are the list of available prices
	* 					$products_price, $products_web_price, $products_rrp, $products_rate_1,
	* 					$products_rate_2, $products_rate_3,
	* 					$products_show_price,  flag ABCD to detrimine who to show price to
	* 					$multi_quantity
	* 					$multi_price
	*/
	function elite_basket_price($pid, $qty, $unit=true){
		global $db;
		$rs=$db->Execute("SELECT pef.rrp, pef.web_price, pef.rate_1, pef.rate_2, pef.rate_3, pef.show_price, pef.multi_price, pef.multi_quantity, pef.trade_multi_price, pef.trade_multi_quantity, pef.sale_price, pef.now_price, p.products_price, p.products_quantity_order_units
											FROM product_extra_fields pef, products p
											WHERE pef.products_id = $pid AND p.products_id = pef.products_id");
		if(!$rs->EOF){
			extract($rs->fields);
if($_SESSION['customer_id']==NULL){
			//****************************************************************************
			//***************** Not logged in ********************************************
			//****************************************************************************
			$final = _basket_price($rrp,$pid,$rrp,$web_price,$products_price,$sale_price,$now_price,$multi_quantity,$multi_price);
		}else{
			/////////////////////////////////////////////////////////////////////////////
			//*****************************Logged in*************************************!"
			/////////////////////////////////////////////////////////////////////////////
			switch($_SESSION['customers_authorization']){//0 when auth, 2 when not
				case 0:
					////***********************************************************************
					//***************** Trade Account verified ********************************
					//*************************************************************************
					switch($_SESSION['customer_trade_type']){
						 case 'Electrican':
								$final = _basket_price($rate_1,$pid,$rrp,$web_price,$products_price,$sale_price,$now_price,$trade_multi_quantity,$trade_multi_price);
								break;
						 case 'Interior Designer':
								$final = _basket_price($rate_2,$pid,$rrp,$web_price,$products_price,$sale_price,$now_price,$trade_multi_quantity,$trade_multi_price);
							break;
						 default:
							///////// Other
							$final = _basket_price($rate_3,$pid,$rrp,$web_price,$products_price,$sale_price,$now_price,$trade_multi_quantity,$trade_multi_price);
					}
					$current_qty = $_SESSION['cart']->get_quantity($pid);
					if(($current_qty>=$trade_multi_quantity)&&$trade_multi_quantity!=0){
						if($final>$trade_multi_price)$final = $trade_multi_price;
					}
					break;
				case 2:
					////***********************************************************************
					//******** Trade account NOT verified OR a public account *****************
					//*************************************************************************
					$final = _basket_price($rrp,$pid,$rrp,$web_price,$products_price,$sale_price,$now_price,$trade_multi_quantity,$trade_multi_price);
			}
		}


		return $final;
	}
}

function _basket_price($sPrice,$pid,$rrp,$web_price, $products_price, $sale_price, $now_price,$multi_quantity,$multi_price){
	//Get the highest base price, used if there is a discount off
		$b = ($web_price<=$products_price?($products_price<$rrp?$rrp:$products_price):$web_price);
		if((($sPrice>$products_price&&zen_not_null($products_price)))||$sPrice==0)$sPrice=$products_price;
		if(($sPrice>$web_price&&zen_not_null($web_price))&&(zen_not_null($sPrice)))$sPrice=$web_price;
		$sale_price=sale_price2pounds($b,$sale_price);
		if($sPrice>$sale_price&&zen_not_null($sale_price))$sPrice=$sale_price;
		$now_price=sale_price2pounds($b,$now_price);
		if($sPrice>$now_price&&zen_not_null($now_price))$sPrice=$now_price;
		//Check for multi buy - Not logged in so no trade multi only general multi buy
		$qtyInCart = $_SESSION['cart']->get_quantity($pid);
		if(zen_not_null($multi_quantity)&&$qtyInCart>=$multi_quantity&&$multi_quantity!=0){
			//Use multi buy price for all these items
			if($sPrice>$multi_price)$sPrice=$multi_price;
		}
		return $sPrice;
}

/**
* sale_price2pounds
* This takes either a sale price or a now price, checks if either contain a % or text
* and works out the final sale/now price as a plain number
*
* @param $basePrice	-	The base price to take discount off
* @param $salePrice - sale/now price as it is in db
* @param $showVat	-	DO NOT set this, returns array - not acceptable to recipiant
* @return sale/now price
*/
function sale_price2pounds($basePrice, $salePrice){
	//Now check for a % symbol
	if(!zen_not_null($basePrice)||!zen_not_null(($salePrice)))return 0;
	if(strpos($salePrice,'%')!==FALSE){
		//Is a percentage
		$pcVal = intval(str_replace("%", "", $salePrice));
		//Now work out which price to take the discount off. This first checks the rrp and then the web price
			//Got rrp so thake discount off it
			$pcOff = ($basePrice/100)*$pcVal;	//% off as a int
				return $basePrice-$pcOff;
	}elseif(strpos($salePrice,'*')){
	//Now check if there is a *
		$bits = explode('*',$salePrice);
		if(is_numeric($bits[0])){
			//price before text
			return $bits[0];
		}elseif(is_numeric($bits[1])){
			//price after text
			return $bits[1];
		}else{
			//No price just text
			return 0;
		}
	}else{
		//Just plain price
		return $salePrice;
	}
}
?>
