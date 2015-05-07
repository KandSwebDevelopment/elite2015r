<?php
/**
*	Module for determining what prices to show
*
* @uses $trades_type_array defined in includes/extra_datafiles/kas_definations.php
* @param: None passed in but depends on the following
* 					$_SESSION['customer_id']
* 					$_SESSION['customers_authorization']	-	0=Auth, 2=Not Auth
* 					$_SESSION['customer_trade_type']			-	B,C,D
* 					These are set when the customer logs in
* 				Also the following are used
* 					These are the list of available prices
* 					$products_price, $products_web_price, $products_rrp, $products_rate_1, $products_rate_2, $products_rate_3,
* 					$product_cable used as price for multi qtys
* 				Also
* 					$products_show_price,
* 					$master_show_price	These 2 are flags
* 					$multi_quantity
* 					$multi_price
*/
//Defines
define('SALE_MIA',30);
define('CLEARANCE_MIA',31);
/*define('RRP',1);
define('WEB',2);
define('MULTI',3);
define('TRADE',4);
define('SALE',5);
define('MSG',1);
define('VAL',2);
define('CSS',3);*/


//Declare output text for different senerios
$message_array[0]='Contact us for pricing and availability information.';
$message_array[1]='Recomended Retail Price %s';
$message_array[11]='Recomended Retail Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[2]='Retail Price %s';
$message_array[12]='Retail Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[3]='Our Price %s';
$message_array[13]='Our Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[4]='Your Trade Price %s';
$message_array[4]='Your Trade Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[10]='Your account is pending approval please <br />
										contact us for your price and availability information.';
$message_array[20]='Only %s when you buy %s or more';
$message_array[21]='Only %s <span class="vatDat">(+VAT)</span> when you buy %s or more<br/><span class="vatDat">%s Including VAT</span>';
$message_array[30]='Sale Price %s';
$message_array[40]='Sale Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[31]='Clearance Price %s';
$message_array[41]='Clearance Price %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[32]='You Pay %s';
$message_array[34]='You Pay %s <span class="VatDat">(+VAT)<br/>%s Including VAT</span>';
$message_array[33]='<span class="offRRP">%s%% Off RRP</span>';
$message_array[50]='<span class="boxQty">This item is only available in quantities of %d</span>';

//These are the available css styles which are used when displaying the prices
$style_array[0]='cui';
$style_array[1]='rrp';
$style_array[2]='wp';
$style_array[3]='tradePrice';
$style_array[4]='multiPrice';
$style_array[5]='salePrice';
$style_array[6]='clearancePrice';
$style_array[7]='youPay';
$style_array[8]='offRRp';
$style_array[20]='noPrice';
$style_array[99]='strikeThrough';

$default_no_price = '<div class="'.$style_array[20].'">'. $message_array[0]. '</div>';

/**
* The result is put in $output an array in the following format
*
*$output[RRP]		[MSG]		First text line - RRP/Retail price
* 					 		[VAL]										- RRP/Retail price
* 					 		[CSS]										- Css style
* $output[WEB]	[MSG]		Second price -  Our/Your price
* 					  	['val']
* 					  	['style']
* $output[MULTI][MSG]		Additional text line - For multi qtys
* 					 		['val']
* 					 		['style']
* $output[TRADE]['text']		Trade text line - If item has a trade price
* 					 		['val']
* 					 		['style']
* $output[SALE]['text']		Sale/clearance text line - If item has a sale or clearance price
* 					 		['val']
* 					 		['style']
*/

$canAdd = true;

$output = null;
//Check if store is not selling, if so put default text and no pricing
if(STORE_STATUS!=0){
	echo $default_no_price;
	$canAdd=false;
	return;
}

//Switch according to the current page. This gives the option to have different
//routines to for different displays on different pages
 switch($current_page){
	 case 'product_info':
	 	//Product info page
	 	//Is user logged in
	 	if($_SESSION['customer_id']==NULL){
	 		//****************************************************************************
			//***************** Not logged in ********************************************
			//****************************************************************************
			if(show_price_for('A')){
				getTopPrice();
				getBottomPrice();
				getMulti_price();
				getSale_Price();
			}elseif(zen_not_null($product_info->fields['now_price'])||zen_not_null($product_info->fields['sale_price'])) {
				//we need to get the top price otherwise % discount wont work
				getTopPrice();
				getSale_Price();
				unset($output['RRP']);
				unset($output['WEB']);
			}else{
				echo $default_no_price;
				$canAdd=false;
				return;
			}
	 	}else{
	 		/////////////////////////////////////////////////////////////////////////////
			//*****************************Logged in*************************************
			/////////////////////////////////////////////////////////////////////////////
			switch($_SESSION['customers_authorization']){//0 when auth, 2 when not
				case 0:
					////***********************************************************************
					//***************** Trade Account verified ********************************
					//*************************************************************************
					switch($_SESSION['customer_trade_type']){
						 case $trade_types_array[1]:
						 	///////// Electrician
					 		if(show_price_for('B')){
						 		getTopPrice(true);
						 		getTradePrice($_SESSION['customer_trade_type']);
						 		getTrade_Multi_price();
								getSale_Price(true);
							}elseif(zen_not_null($product_info->fields['now_price'])||zen_not_null($product_info->fields['sale_price'])) {
								//we need to get the top price otherwise % discount wont work
								getTopPrice();
								getSale_Price(true);
								unset($output['RRP']);
								unset($output['WEB']);
							}else{
								echo $default_no_price;
								$canAdd=false;
								return;
							}
						 	break;
						 case $trade_types_array[2]:
						 	///////// Interior designer
					 		if(show_price_for('C')){
						 		getTopPrice(true);
						 		getTradePrice($_SESSION['customer_trade_type']);
						 		getTrade_Multi_price();
								getSale_Price(true);
							}elseif(zen_not_null($product_info->fields['now_price'])||zen_not_null($product_info->fields['sale_price'])) {
								//we need to get the top price otherwise % discount wont work
								getTopPrice();
								getSale_Price(true);
								unset($output['RRP']);
								unset($output['WEB']);
							}else{
								echo $default_no_price;
								$canAdd=false;
								return;
							}
						 	break;
						 case $trade_types_array[3]:
						 	///////// Other
					 		if(show_price_for('D')){
						 		getTopPrice(true);
						 		getTradePrice($_SESSION['customer_trade_type']);
						 		getTrade_Multi_price();
								getSale_Price(true);
							}elseif(zen_not_null($product_info->fields['now_price'])||zen_not_null($product_info->fields['sale_price'])) {
								//we need to get the top price otherwise % discount wont work
								getTopPrice();
								getSale_Price(true);
								unset($output['RRP']);
								unset($output['WEB']);
							}else{
								echo $default_no_price;
								$canAdd=false;
								return;
							}
						 	break;
						 default:
						 	//something went wrong if we get to here so default to public prices
					}
					break;
				case 2:
					////***********************************************************************
					//******** Trade account NOT verified OR a public account *****************
					//*************************************************************************
					if(show_price_for('A')){
						getTopPrice();
						getBottomPrice();
					}
					$output[5]=$message_array[10];
			}
	 	}
		/////////////Output the prices/////////////////////////////////////////
		//Top price line - rrp
	 	if($output[RRP][VAL]>$output[WEB][VAL]){
	 		echo '<div class="'.$output[RRP][CSS].'">';
			if(!zen_not_null($output[RRP][TOTAL])){
				echo sprintf($output[RRP][MSG],$currencies->format($output[RRP][VAL]));
			}else{
				echo sprintf($output[RRP][MSG],$currencies->format($output[RRP][VAL]),$currencies->format($output[RRP][TOTAL]));
			}
			echo '</div>';
		}

		//Bottom price line - our/web
		if(zen_not_null($output[WEB])){
			echo '<div class="'.$output[WEB][CSS].'">';
			if(!zen_not_null($output[RRP][TOTAL])){
				echo sprintf($output[WEB][MSG],$currencies->format($output[WEB][VAL]));
			}else{
					echo sprintf($output[RRP][MSG],$currencies->format($output[RRP][VAL]),$currencies->format($output[RRP][TOTAL]));
			}
			echo '</div>';
		}

		//Account info
		if(zen_not_null($output[TRADE][MSG])){
			echo '<div class="'.$output[TRADE][CSS].'">';
			echo $output[TRADE][MSG];
			echo '</div>';
		}

		//Multi qty price
		if(zen_not_null($output[MULTI])){
			echo '<div class="'.$output[MULTI][CSS].'">';
			echo $output[MULTI][MSG];
			echo '</div>';
		}
		//Sale price
		if(zen_not_null($output[SALE])){
			echo '<div class="'.$output[SALE][CSS].'">';
			echo $output[SALE][MSG];
			echo '</div>';
		}
		//Box only quantities
		if($product_info->fields["box_quantity"]>0){
			echo sprintf($message_array[50],$product_info->fields["box_quantity"]);
		}
	 break;
 }

//****************************************************************************
//***************** Functions ************************************************
//****************************************************************************
 function getTopPrice($show_vat=false){
 	 global $message_array, $output, $style_array,$products_rrp,$products_web_price, $products_price;
	 if($products_rrp> $products_web_price){
	 	 //Use rrp
		 if(!$show_vat){
	 	 	$output[RRP][VAL]=$products_rrp;
	 	 	$output[RRP][MSG]=$message_array[1];
		 }else{
	 	 	$output[RRP][MSG]=$message_array[11];
	 	 	$vat_split = vat_split($products_rrp);
	 	 	$output[RRP][VAL]= $vat_split['net'];
	 	 	$output[RRP][TOTAL]= $vat_split['total'];
		 }
	 	 $output[RRP][CSS]=$style_array[1];
	 	 return;
	 }else{
	 	 //Use web/our_price
	 	 if(!$show_vat){
	 	 	$output[RRP][MSG]=$message_array[2];
	 	 	$output[RRP][VAL]= $products_price;
		 }else{
	 	 	$output[RRP][MSG]=$message_array[12];
	 	 	$vat_split = vat_split($products_price);
	 	 	$output[RRP][VAL]= $vat_split['net'];
	 	 	$output[RRP][TOTAL]= $vat_split['total'];
		 }
	 	 $output[RRP][CSS]=$style_array[1];
	 }
 }
 function getBottomPrice($show_vat=false){
 	 global $message_array, $output, $cssOutput, $style_array,$products_rrp,$products_web_price, $products_price;
	 if($products_web_price < $products_price && zen_not_null($products_web_price)){
	 	 //Use web_price (from p.e.f)
	 	 if(!$show_vat){
	 		 $output[WEB][MSG]=$message_array[3];
	 		 $output[WEB][VAL]=$products_web_price;
	 		 $output[WEB][CSS]=$style_array[2];
		 }else{
	 		 $output[WEB][MSG]=$message_array[13];
	 	 	 $vat_split = vat_split($products_web_price);
	 	 	 $output[WEB][VAL]= $vat_split['net'];
	 	 	 $output[WEB][TOTAL]= $vat_split['total'];
		 }
	 	 return;
	 }else{
	 	 //Use Our_price (products price from products table)
	 	 if(!$show_vat){
	 		 $output[WEB][MSG]=$message_array[3];
	 		 $output[WEB][VAL]=$products_price;
		 }else{
	 	 	$output[WEB][MSG]=$message_array[13];
	 	 	$vat_split = vat_split($products_price);
	 	 	$output[WEB][VAL]= $vat_split['net'];
	 	 	$output[WEB][TOTAL]= $vat_split['total'];
		 }
	 	 $output[WEB][CSS]=$style_array[2];
	 }
 }

/**
* All formating of the output must be done here and built into a single string
*  in $output[TRADE][MSG]
*
* @param mixed $trade
*/
function getTradePrice($trade){
	global $output, $trade_types_array, $message_array, $products_rate_1, $products_rate_2, $products_rate_3, $cssOutput, $style_array, $currencies;
	switch($trade){
		case $trade_types_array[1]:
      $trade_rate = $products_rate_1;
      break;
    case $trade_types_array[2]:
      $trade_rate = $products_rate_2;
      break;
    case $trade_types_array[3]:
    	$trade_rate = $products_rate_3;
	}
	if(!zen_not_null($trade_rate)){
		//No trade price
		//$output[WEB]=null;
	 	$output[TRADE][MSG]= $message_array[10];
	 	$output[RRP][CSS]=$style_array[0];
	}else{
		//We have got a trade price
		$vatsplit = vat_split($trade_rate);
		$output[TRADE][MSG]=sprintf($message_array[4],$currencies->format($vatsplit['net']),$currencies->format($vatsplit['total']));
	 	$output[TRADE][VAL]= $trade_rate;
	 	$output[TRADE][CSS]=$style_array[3];
	}
}

function getTrade_Multi_price(){
	global $product_info, $currencies, $message_array, $output, $cssOutput, $style_array;
	if((zen_not_null($product_info->fields['trade_multi_price'])&&$product_info->fields['trade_multi_price']!=0)&&(zen_not_null($product_info->fields['trade_multi_quantity'])&&$product_info->fields['trade_multi_quantity']!=0)){
		$vat_split=vat_split($product_info->fields['trade_multi_price']);
		$output[MULTI][MSG] = sprintf($message_array[21],$currencies->format($vat_split['net']),$product_info->fields['trade_multi_quantity'],$currencies->format($vat_split['total']));
		$output[MULTI][CSS]=$style_array[4];
	}

}

function getMulti_price(){
	global $product_info, $currencies, $message_array, $output, $cssOutput, $style_array;
	if( zen_not_null($product_info->fields['multi_quantity'])&&zen_not_null($product_info->fields['multi_price'])&&$product_info->fields['multi_quantity']!=0&&$product_info->fields['multi_price']!=0){
		$output[MULTI][MSG] = sprintf($message_array[20],$currencies->format($product_info->fields['multi_price']),$product_info->fields['multi_quantity']);
		$output[MULTI][CSS]=$style_array[4];
	}
}

/**
* This will get either the sale price or the clearence price, if the sale price is blank
*
* All formating of the output must be done here and built into a single string
*  in $output[SALE][MSG]
*/
function getSale_Price($show_vat=false){
	global $product_info, $currencies, $message_array, $output, $cssOutput, $style_array;
	//Determine if its a sale price or a clearance price
	if(zen_not_null($product_info->fields['sale_price'])){
//Is a sale item
		$type = SALE_MIA;
		$field = $product_info->fields['sale_price'];
	}elseif(zen_not_null($product_info->fields['now_price'])){
//Is a clearence item
		$type = CLEARANCE_MIA;
		$field = $product_info->fields['now_price'];
	}else{
		return;
	}
//Will only reach  here if there is a sale or clearance price
//Now check for a % symbol
	if(strpos($field,'%')!==FALSE){
		//Is a percentage
		$pcVal = intval(str_replace("%", "", $field));
		//Now work out which price to take the discount off. This first checks the rrp and then the web price
		if(zen_not_null($output[RRP][VAL])){
			//Got rrp so thake discount off it
			$pcOff = ($output[RRP][VAL]/100)*$pcVal;	//% off as a int
			$output[SALE][MSG] = sprintf($message_array[33],$pcVal);
			$output[SALE][MSG] = $output[SALE][MSG].'<br/>';
			if(!$show_vat){
				$output[SALE][VAL] = $output[RRP][VAL]-$pcOff;
				$output[SALE][MSG] = $output[SALE][MSG].sprintf($message_array[32],$currencies->format($output[SALE][VAL]));
			}else{
				$vat_split = vat_split($output[RRP][VAL]-$pcOff);
				$output[SALE][VAL] = $vat_split['net'];
				$output[SALE][MSG] .= sprintf($message_array[34],$currencies->format($vat_split['net']),$currencies->format($vat_split['total']));
			}
		}elseif(zen_not_null($output[WEB][VAL])){
			//No rrp so use web price
			if(!$show_vat){
				$output[SALE][VAL] = $output[WEB][VAL]-$pcOff;
				$output[SALE][MSG] = $output[SALE][MSG].sprintf($message_array[32],$currencies->format($output[SALE][VAL]));
			}else{
				$vat_split=vat_split($output[WEB][VAL]-$pcOff);
				$output[SALE][VAL] = $vat_split['net'];
				$output[SALE][MSG] .= sprintf($message_array[34],$currencies->format($vat_split['net']),$currencies->format($vat_split['total']));
			}
		}else{
			//Sale price but not rrp or web price  - This is a problem as we have no price
			//to take the % off
			//So do nothing
		}
		$output[SALE][CSS] = $style_array[7];
	}
//Now check if there is a *
	elseif(strpos($product_info->fields['sale_price'],'*')){
		$bits = explode('*',$product_info->fields['sale_price']);
		if(is_numeric($bits[0])){
			//price before text
			$output[SALE][MSG] = $currencies->format($bits[0]);
			$output[SALE][MSG] .= '<br/><span class="saleText">'.$bits[1].'</span>';
		}elseif(is_numeric($bits[1])){
			//price after text
			$output[SALE][MSG] = '<span class="saleText">'.$bits[0].'</span><br/>';
			$output[SALE][MSG] .= $currencies->format($bits[1]);
		}else{
			//No price just text
			$output[SALE][MSG] = $bits[0].$bits[1];
		}
		//Set css style
		$output[SALE][CSS] = $style_array[7];

	}else{
		//Just plain price
		if(!$show_vat){
			$output[SALE][MSG] = sprintf($message_array[$type],$currencies->format($field));
	}else{
		$vat_split=vat_split($field);
		$output[SALE][MSG] = sprintf($message_array[$type+10],$currencies->format($vat_split['net']),$currencies->format($vat_split['total']));
	}
		$output[SALE][CSS] = $style_array[7];
	}
	//Remove any multi buy info as it will not be showen if there is a sale/clearance price
	unset($output[MULTI]);
}

/**
* This looks up the show price col data and determins if the price is to be shown
*
* @param mixed $type
*/
function show_price_for($type){
	global $products_show_price;
	if(strpos($products_show_price,$type)===FALSE){
		return false;
	}
	return true;
}
