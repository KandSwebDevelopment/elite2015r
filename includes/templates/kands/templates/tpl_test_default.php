
Test page
<script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/modernizr.custom.js' ?>" type="text/javascript"></script>
		<div class="container demo-1">  
			<!-- Codrops top bar -->

			<div class="main clearfix">

				<div class="column">
				
				
					<div id="dl-menu" class="dl-menuwrapper">
						<button class="dl-trigger">Open Menu</button>
<!--        <ul class="dl-menu">
            <li><a href="#">Sub-Item 1</a></li>
            <li><a href="#">Sub-Item 2</a></li>
            <li><a href="#">Sub-Item 3</a></li>
            <li><a href="#">Lighting  </a></li>
        </ul>-->
<!--<ul class="dl-menu">
<li><a href="#">Lighting</a></li>
<li><a href="#">Interiors</a></li>
<li><a href="#">Sale Items</a></li>
<li><a href="#">Clearance Items</a></li>
</ul>  -->          
<!--<ul class="dl-menu">
							<li>
								<a href="#">Top 1</a>
								<ul class="dl-submenu">
									<li>
										<a href="#">Sub 1-1</a>
										<ul class="dl-submenu">
											<li><a href="#">Sub 1-1-1</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Sub 1-2</a>
										<ul class="dl-submenu">
											<li><a href="#">Sub 1-2-1</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Sub 1-3</a>
										<ul class="dl-submenu">
											<li><a href="#">Sub 1-3-1</a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li>
								<a href="#">Top 2</a>
								<ul class="dl-submenu">
									<li><a href="#">Sub 2-1</a></li>
								</ul>
							</li>
							<li>
								<a href="#">Top 3</a>
								<ul class="dl-submenu">
									<li>
										<a href="#">Sub 3-1</a>
										<ul class="dl-submenu">
											<li><a href="#">Sub 3-1-1</a></li>
										</ul>
									</li>
									<li>
										<a href="#">Sub 3-2</a>
										<ul class="dl-submenu">
											<li>
												<a href="#">Sub 3-2-1</a>
												<ul class="dl-submenu">
													<li><a href="#">Upholstered Beds</a></li>
													<li><a href="#">Divans</a></li>
													<li><a href="#">Metal Beds</a></li>
													<li><a href="#">Storage Beds</a></li>
													<li><a href="#">Wooden Beds</a></li>
													<li><a href="#">Children's Beds</a></li>
												</ul>
											</li>
											<li><a href="#">Bedroom Sets</a></li>
											<li><a href="#">Chests &amp; Dressers</a></li>
										</ul>
									</li>
									<li><a href="#">Home Office</a></li>
									<li><a href="#">Dining &amp; Bar</a></li>
									<li><a href="#">Patio</a></li>
								</ul>
							</li>
							<li>
								<a href="#">Jewelry &amp; Watches</a>
								<ul class="dl-submenu">
									<li><a href="#">Fine Jewelry</a></li>
									<li><a href="#">Fashion Jewelry</a></li>
									<li><a href="#">Watches</a></li>
									<li>
										<a href="#">Wedding Jewelry</a>
										<ul class="dl-submenu">
											<li><a href="#">Engagement Rings</a></li>
											<li><a href="#">Bridal Sets</a></li>
											<li><a href="#">Women's Wedding Bands</a></li>
											<li><a href="#">Men's Wedding Bands</a></li>
										</ul>
									</li>
									
								</ul>
							</li>
						</ul>-->
						
						
						 <?php

 // load the UL-generator class and produce the menu list dynamically from there
 require_once (DIR_WS_CLASSES . 'mobile_categories_tree.php');
 $zen_CategoriesUL = new kas_mobile_ul_generator;
 $menulist = $zen_CategoriesUL->buildTree();
 echo $menulist;
?>                        
						
						
						
					</div><!-- /dl-menuwrapper -->
				</div>
			</div>
		</div><!-- /container -->
		<script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.dlmenu.js' ?>"></script>
		<script>
			$(function() {
				$( '#dl-menu' ).dlmenu();
			});
		</script>