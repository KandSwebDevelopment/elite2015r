<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=contact_us.<br />
 * Displays contact us page form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_contact_us_default.php 3651 2006-05-22 05:18:52Z ajeh $
 */
?>
<div class="centerColumn">
		<h1 id="siteMapHeading">Store Location</h1>
		<div id="map_define"><?php
			include $define_page; ?>
		</div>

		<div id="directionsPrint">
			<div class="mapInput">
				<h2>Get Directions</h2>
				Enter your postcode: <input type="text" id='postcode' name="postcode" value="">
				<?php echo '<a href=""/>'.zen_image_button("pixel_black.gif",'Get Directions','id="getDir"').'</a>'; ?>
				<?php //echo zen_draw_separator('pixel_trans.gif','10%', 1);?>
				<div class="mapPrint">
					<input type="button" onclick="printDiv('directionsPrint')" value="Print Directions" class="cssButton"/>
				</div>
			</div>
			<div id="warnings_panel"></div>
			<div id="route"></div>
			<div id="map" style="width: <?php echo GOOGLEMAP_WIDTH ; ?>; height: <?php echo GOOGLEMAP_HEIGHT ; ?>; border:solid black 1px;" ></div>
		</div>
</div>