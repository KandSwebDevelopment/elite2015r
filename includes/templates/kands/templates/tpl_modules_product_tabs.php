<script src="<?php echo $template->get_template_dir('jquery.bxslider.min.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.bxslider.min.js' ?>" type="text/javascript"></script>
<script src="<?php echo $template->get_template_dir('easyResponsiveTabs.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/easyResponsiveTabs.js' ?>" type="text/javascript"></script>
<script type="text/javascript">
		$(document).ready(function () {
				$('#horizontalTab').easyResponsiveTabs({
						type: 'default', //Types: default, vertical, accordion           
						width: 'auto', //auto or any width like 600px
						fit: true,   // 100% fit in a container
						activate: function(events) { // Callback function if tab is switched
								familySlider.reloadSlider();
								//event.stopPropagtion();
						}
				});

				$('#verticalTab').easyResponsiveTabs({
						type: 'vertical',
						width: 'auto',
						fit: true
				});
				var familySlider = $('.familySlider').bxSlider({
						minSlides: 1,
						maxSlides: 4,
						slideWidth: 150,
						slideMargin: 10,
						moveSlides: 1,
						infiniteLoop: false,
						hideControlOnEnd: true,
						adaptiveHeight: true
				});
		});
</script>


 <div id="horizontalTab">
		<ul class="resp-tabs-list">
				<li>Description</li>
				<li>Specification</li>
				<li>Family Items</li>
			 <!-- <li>You may also like</li>-->
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
						<!-- family items -->
						<div class="familySliderBox">
								<?php
								/////////////////FAMILY ITEMS
								require($template->get_template_dir('/tpl_modules_family_items.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_family_items.php');
								?>   
						</div>
						<!-- family items -->
				</div>
				
				<!--<div>
						<!--Custom tab -->
				<!--   Custom tab<br><br><br>-->
				<!--</div>-->

		</div>
 </div>