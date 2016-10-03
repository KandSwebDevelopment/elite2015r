 <script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.resmenu.js' ?>" type="text/javascript"></script>

 <?php
	 require_once (DIR_WS_CLASSES . 'resMenu_categories_tree.php');
	 $CategoriesUL = new kas_resMenu_ul_generator();
 ?>
<div class="menu_container">
		<ul class="toresponsive" id="menu2">
				<li <?php if($this_is_home_page)echo 'class="current-menu-item"';?>><a href="<?php echo zen_href_link(FILENAME_DEFAULT, '') ?>">Home</a></li>
				<li><a href="<?php echo zen_href_link(FILENAME_DEFAULT, 'cPath=1');?>">Lighting</a>
					<?php
					$menulist = $CategoriesUL->buildBranch(1,true,'cPath=1_');
					echo $menulist;
					?>
				</li>
				<li>
						<a href="<?php echo zen_href_link(FILENAME_DEFAULT, 'cPath=2');?>">Furniture</a>
						<?php
					 $menulist = $CategoriesUL->buildBranch(2,true,'cPath=2_');
					 echo $menulist;
					 ?>
					 <!--<ul>
								<li><a href="#">Submenu with #</a>
										<ul>
												<li><a href="test.htm">Link</a></li>
										</ul>
								</li>
								<li><a href="test.htm">Link</a></li>
						</ul>-->
				</li>
				<li>
						<a>Info</a>
						<ul>
												<?php
						//$result = '<li><a href="#">'.HEADER_TITLE_INFORMATION.'</a></li>';
												$result = '<li><a href="'. zen_href_link('map').'">Store Location</a></li>';
												$result .= '<li><a href="'. zen_href_link(FILENAME_ABOUT_US).'">'. BOX_INFORMATION_ABOUT_US.'</a></li>';
												$result .= '<li><a href="'. zen_href_link(FILENAME_SITE_MAP).'">'. BOX_INFORMATION_SITE_MAP.'</a></li>';
												$result .= '<li><a href="'. zen_href_link(FILENAME_LOGIN).'">'. HEADER_TITLE_LOGIN.'</a></li>';
												//$result .= '<li><a href="'. zen_href_link(FILENAME_CREATE_ACCOUNT).'">'. HEADER_TITLE_CREATE_ACCOUNT.'</a></li>';
												$result .= '<li><a href="#">'. HEADER_TITLE_CREATE_ACCOUNT.'</a></li>';
												$result .= '<li><a href="'. zen_href_link(FILENAME_PRIVACY).'">'. BOX_INFORMATION_PRIVACY.'</a></li>';
												$result .= '<li><a href="'. zen_href_link(FILENAME_CONDITIONS).'">'. BOX_INFORMATION_CONDITIONS.'</a></li>';
												//$result .= '</a></li></ul></li>';
												echo $result;
											?>
						</ul>
				</li>
		</ul>
</div>

<script>		
		$(window).ready(function () {
				$('.toresponsive').ReSmenu();
		});
</script>