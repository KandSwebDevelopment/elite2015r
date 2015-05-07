<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=product_info.<br />
 * Displays details of a typical product
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_info_display.php 16242 2010-05-08 16:05:40Z ajeh $
 */
 //require(DIR_WS_MODULES . '/debug_blocks/product_info_prices.php');
?>




<div class="centerColumn" id="productGeneral">

<!--bof Form start-->
<?php echo zen_draw_form('cart_quantity', zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('action')) . 'action=add_product', $request_type), 'post', 'enctype="multipart/form-data"') . "\n"; ?>
<!--eof Form start-->

<?php if ($messageStack->size('product_info') > 0) echo $messageStack->output('product_info'); ?>

<!--bof Category Icon -->
<?php if ($module_show_categories != 0) {?>
<?php
/**
 * display the category icons
 */
require($template->get_template_dir('/tpl_modules_category_icon_display.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_category_icon_display.php'); ?>
<?php } ?>
<!--eof Category Icon -->

<!--bof Prev/Next top position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 1 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next top position-->
<?php
		require(DIR_WS_MODULES . zen_get_module_directory('family_count'));
?>

<div id="p-left">
<!--bof Main Product Image -->
<?php
	if (zen_not_null($products_image)) {
	?>
<?php
/**
 * display the main product image
 */
	 require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
<?php
	}
?>
<!--eof Main Product Image-->
</div>

<div id="p-right">
<!--bof Product Name-->
<h1 id="productName" class="productGeneral"><?php echo $products_name; ?></h1>
<!--eof Product Name-->

<div id="cart">
<!---->
<div id="productOrderHeading">Pricing and Availability</div>
<div id="productOrderDetails">
		<?php
		if(STORE_STATUS==0){
				require($template->get_template_dir('/tpl_modules_price.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_price.php');
		}

		/*    //KandS - get_trade_price takes a pID and returns an array which contains the following information based on customer logged in, customer verfied,
				//and the status of the ABCD in the show_price field for the product
				//$trade_price[0] = PUBLIC or TRADE
				//            [1] = The rrp value
				//            [2] = 'RRP' - This is not needed at present
				//            [3] = The your price value

		////////////////////////////////////////////////////////////////////////////////////////////////
		$pricing_letter='';
		$account_status=0;
		$output='';
		switch(TRUE){
				case $_SESSION['customer_id']==NULL:
						//Not logged in
						$pricing_letter='A';
						$account_status=0;    //No Account & Not Auth
						break;
				case $_SESSION['customers_authorization']==2:
						//Trade account but not verified
						$pricing_letter='A';
						$account_status=0;
						break;
				case $_SESSION['customers_authorization']==0:
								//Trade Account Verified
						switch($_SESSION['customer_trade_type']){
						case $trade_types_array[1]:
								$pricing_letter='B';
								$account_status=2;
								break;
						case $trade_types_array[2]:
								$pricing_letter='C';
								$account_status=2;
								break;
						default:    //Other
								$pricing_letter='D';
								$account_status=2;
								break;
				}
		}
		if($pricing_letter==''){
				//$output .= '{'.$pricing_letter.'.'.$account_status.'}';
				$output.= '</br >Contact us for pricing and availability information.<br />';
		}else{
				//$output .= '{'.$pricing_letter.'.'.$account_status.'}';
				$output.= get_price($pricing_letter, $_GET['products_id'],$account_status);
		}*/

		//$contact_us_button = '<br /><a href="' . zen_href_link(FILENAME_CONTACT_US, 'pId='.$_GET['products_id']) . '">' .  kas_image_button('make_enquiry.jpg','Enquire about this item') . '</a>';
		$contact_us_button = '<br /><a href="' . zen_href_link(FILENAME_CONTACT_US, 'pId='.$_GET['products_id']) . '">' .  zen_image_button('make_enquiry.jpg','Enquire about this item') . '</a>';
		$text_model = (($flag_show_product_info_model == 1 and $products_model !='') ? '<b>' . TEXT_PRODUCT_MODEL . $products_model . '</b><br />' : '');


		$display_qty = '<div id="ajQiCL">' . PRODUCTS_ORDER_QTY_TEXT_IN_CART .'<span id="ajQiC">'. $_SESSION['cart']->get_quantity($_GET['products_id']) . '</span></div>' ;

		if ($products_qty_box_status == 0 or $products_quantity_order_max== 1) {
				// hide the quantity box and default to 1
				$the_button = '<input type="hidden" name="cart_quantity" value="1" />' . zen_draw_hidden_field('products_id', (int)$_GET['products_id'],'id="products_id"') . kas_image_submit(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT);
		} else {
				// show the quantity box
				$the_button = kas_image_submit('add-to-basket-button.jpg', BUTTON_IN_CART_ALT);
				//$the_button = zen_image_submit('add-to-basket-button.jpg', BUTTON_IN_CART_ALT,'onclick="javascript:ajax_add_to_cart('.$_GET['products_id'].',1,\'product_info\')"');
				$the_button .='<div>'.PRODUCTS_ORDER_QTY_TEXT .
																		'<input type="text" name="cart_quantity" value="' . (zen_get_buy_now_qty($_GET['products_id'])) . '" maxlength="6" size="4" id="piqb"/></div>'.
																		zen_get_products_quantity_min_units_display((int)$_GET['products_id']).
																		zen_draw_hidden_field('products_id', (int)$_GET['products_id'],'id="product_id"');
		}
		$display_button = zen_get_buy_now_button($_GET['products_id'], $the_button);

				///////////////////////////////////////////////////////////////////////////
				?>
<?php
		echo '<div id="productStockCode"><b>Please quote the following </b><span id="psc">'.$text_model.'</span></div>';
		echo $contact_us_button.'<br/><br/>';
		if(STORE_STATUS==0){
				if($canAdd){
						echo zen_draw_hidden_field('boxQty', $product_info->fields['box_quantity'],'id="boxQty"');
						echo '<div class="basketbuttons">';
						echo $display_button;
						echo $display_qty;
								// BOF ZX AJAX Add to Cart 2/2
								echo '<span id="loadBar"></span><br /><span id="button_cart"></span>';
								// EOF ZX AJAX Add to Cart 2/2
								echo '<span id="boxQtyError"></span>';
						echo '</div><br class="clearBoth"/>';
				}
		}
?>


</div>
<!---->
</div>
<br class="clearBoth" /> 
<hr id="product-divider" />




<link rel="stylesheet" type="text/css" href="<?php $template->get_template_dir('.css',DIR_WS_TEMPLATE, $current_page_base,'css') . '/jquery.bxslider.css'  ?> " />'</link>
<script src="<?php echo $template->get_template_dir('jquery.bxslider.min.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.bxslider.min.js' ?>" type="text/javascript"></script>
<script src="<?php echo $template->get_template_dir('easyResponsiveTabs.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/easyResponsiveTabs.js' ?>" type="text/javascript"></script>
<script type="text/javascript">
		$(document).ready(function () {
				$('#horizontalTab').easyResponsiveTabs({
						type: 'default', //Types: default, vertical, accordion           
						width: 'auto', //auto or any width like 600px
						fit: true,   // 100% fit in a container
						activate: function(event) { // Callback function if tab is switched
								var $tab = $(this);
								var $name = $('span', $info);
								//$name.text($tab.text());
								//$info.show();
						}
				});

				$('#verticalTab').easyResponsiveTabs({
						type: 'vertical',
						width: 'auto',
						fit: true
				});
				$('.familySlider').bxSlider({
						minSlides: 2,
						maxSlides: 4,
						slideWidth: 200,
						slideMargin: 10,
						infiniteLoop: false,
						hideControlOnEnd: true
				});
		});
</script>


 <div id="horizontalTab">
		<ul class="resp-tabs-list">
				<li>Description</li>
				<li>Specification</li>
				<li>Family Items</li>
				<li>You may also like</li>
		</ul>
				
				
		<div class="resp-tabs-container">
		
				<div>
						<!--bof Product description -->
						<?php if ($products_description != '') { ?>
						<div id="productDescription" class="productGeneral biggerText"><?php echo stripslashes($products_description); ?></div>
						<?php } 
						echo '<div id="productDescriptionBS">'. $product_info->fields['bulbs_s1'].'</div>';
						if(zen_not_null($product_info->fields['info'])&&$product_info->fields['info']!=0){
								echo'<div id="productDescriptionInfo">'. $product_info->fields['info'].'</div>';
						}
						if($product_info->fields['product_nonreturn']==1){
								echo '<div class="nonreturnPi">'.zen_image('includes/templates/kands/images/non-returnable.jpg','',75).'</div><br class="clearBoth">';
						}?>
						<!--eof Product description -->
				</div>
				
				
				<div>
						<!--bof Product specs  -->
						<?php
							require($template->get_template_dir('/tpl_modules_specification.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specification.php');
						?>
						<!--eof Product specs -->
				</div>
						<div>
								<!-- other page -->
								<?php
								/////////////////FAMILY ITEMS
								require($template->get_template_dir('/tpl_modules_family_items.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_family_items.php');
								
								 ?>   
								<!-- other page -->
						</div>
						<div>
								<!--Custom tab -->
								Custom tab<br><br><br>
						</div>

		</div>
 </div>


<!--</div>-->
<?php
	require($template->get_template_dir('/tpl_modules_family_items.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_family_items.php');

?>

<br class="clearBoth" />

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_pinterest_pinit"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4ff326d22d3b21a9"></script>
<!-- AddThis Button END -->

<!--bof Quantity Discounts table -->
<?php
	if ($products_discount_type != 0) { ?>
<?php
/**
 * display the products quantity discount
 */
 require($template->get_template_dir('/tpl_modules_products_quantity_discounts.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_products_quantity_discounts.php'); ?>
<?php
	}
?>
<!--eof Quantity Discounts table -->

<!--bof Additional Product Images -->
<?php
/**
 * display the products additional images
 */
	require($template->get_template_dir('/tpl_modules_additional_images.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_additional_images.php'); ?>
<!--eof Additional Product Images -->

<!--bof Prev/Next bottom position -->
<?php if (PRODUCT_INFO_PREVIOUS_NEXT == 2 or PRODUCT_INFO_PREVIOUS_NEXT == 3) { ?>
<?php
/**
 * display the product previous/next helper
 */
 require($template->get_template_dir('/tpl_products_next_previous.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_products_next_previous.php'); ?>
<?php } ?>
<!--eof Prev/Next bottom position -->

<hr id="product-divider" />

<!--bof Tell a Friend button -->
<?php
	if ($flag_show_product_info_tell_a_friend == 1) { ?>
<div id="productTellFriendLink" class="buttonRow forward"><?php echo ($flag_show_product_info_tell_a_friend == 1 ? '<a href="' . zen_href_link(FILENAME_TELL_A_FRIEND, 'products_id=' . $_GET['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_TELLAFRIEND, BUTTON_TELLAFRIEND_ALT) . '</a>' : ''); ?></div>
<?php
	}
?>
<!--eof Tell a Friend button -->

<!--bof Reviews button and count-->
<?php
	if ($flag_show_product_info_reviews == 1) {
		// if more than 0 reviews, then show reviews button; otherwise, show the "write review" button
		if ($reviews->fields['count'] > 0 ) { ?>
<div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?></div>
<br class="clearBoth" />
<p class="reviewCount"><?php echo ($flag_show_product_info_reviews_count == 1 ? TEXT_CURRENT_REVIEWS . ' ' . $reviews->fields['count'] : ''); ?></p>
<?php } else { ?>
<div id="productReviewLink" class="buttonRow back"><?php echo '<a href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array())) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?></div>
<br class="clearBoth" />
<?php
	}
}
?>
<!--eof Reviews button and count -->


<!--bof Product date added/available-->
<?php
	if ($products_date_available > date('Y-m-d H:i:s')) {
		if ($flag_show_product_info_date_available == 1) {
?>
	<p id="productDateAvailable" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_AVAILABLE, zen_date_long($products_date_available)); ?></p>
<?php
		}
	} else {
		if ($flag_show_product_info_date_added == 1) {
?>
			<p id="productDateAdded" class="productGeneral centeredContent"><?php echo sprintf(TEXT_DATE_ADDED, zen_date_long($products_date_added)); ?></p>
<?php
		} // $flag_show_product_info_date_added
	}
?>
<!--eof Product date added/available -->

<!--bof Product URL -->
<?php
	if (zen_not_null($products_url)) {
		if ($flag_show_product_info_url == 1) {
?>
		<p id="productInfoLink" class="productGeneral centeredContent"><?php echo sprintf(TEXT_MORE_INFORMATION, zen_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($products_url), 'NONSSL', true, false)); ?></p>
<?php
		} // $flag_show_product_info_url
	}
?>
<!--eof Product URL -->

<!--bof also purchased products module-->
<?php require($template->get_template_dir('tpl_modules_also_purchased_products.php', DIR_WS_TEMPLATE, $current_page_base,'templates'). '/' . 'tpl_modules_also_purchased_products.php');?>
<!--eof also purchased products module-->

<!--bof Form close-->
</form>
<!--bof Form close-->
</div>