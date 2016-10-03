<?php
/**  KANDS ---------------------------------------------
 * @package Mobile menu - 
 * @copyright Copyright 2015 KandS Web Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license  GNU Public License V2.0
 * @version $Id: 1.00
 * 
 * Uses class 'mobile_categories_tree'
 */
?> 
 <script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/modernizr.custom.js' ?>" type="text/javascript"></script>

 <div id="dl-container">
 
		<div id="dl-menu" class="dl-menuwrapper">
				<button class="dl-trigger">Open Menu</button>
				 <?php
				 // load the UL-generator class and produce the menu list dynamically from there
				 require_once (DIR_WS_CLASSES . 'mobile_categories_tree.php');
				 $CategoriesUL = new kas_mobile_ul_generator;
				 $menulist = $CategoriesUL->buildTree();
				 echo $menulist;
				?>                        
		</div><!-- /dl-menuwrapper -->
				
		<!--<a class="m-cart" href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.M_CART_IMAGE ?>" alt="m cart" /></a>-->
		
		<!--<a class="m-cart" href="<?php echo zen_href_link(FILENAME_DEFAULT, '', 'NONSSL'); ?>"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.M_HOME_IMAGE ?>" alt="m cart" /></a>-->

		<?php echo '<a class="m-home" href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'; ?><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.M_HOME_IMAGE ?>"  alt=" m home" /></a>


</div> <!--dl-container--> 

<script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.dlmenu.js' ?>"></script>
<script>
		$(function() {
				$( '#dl-menu' ).dlmenu();
		});
</script>

