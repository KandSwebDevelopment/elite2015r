<?php
/**
 * Page Template
 *
 * Main index page<br />
 * Displays greetings, welcome text (define-page content), and various centerboxes depending on switch settings in Admin<br />
 * Centerboxes are called as necessary
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_index_default.php 3464 2006-04-19 00:07:26Z ajeh $
 */
?>
<div class="centerColumn" id="indexDefault">
<h1 id="indexDefaultHeading"><?php echo HEADING_TITLE; ?></h1>


<!-- Sale banner -->
<style type="text/css">

</style>

<div style="background-color: #eee; padding: 8px;">
We currently have a vacancy for an Electrican/Handyman. An application form can be collected instore or 
<span class="cssButton1"><a href="media/form1eh.pdf" download="">Download it here!</a></span>

</div>
<!--
<div style="padding-left:25% ;" ><img src="images/banners/Easter2016F.jpg" border="0" alt="" width="70%"></div>

<br>
<div ><a href="https://selfbuild.ticketbud.com/belfast2016?pc=ELITE" target="_blank"><img src="images/banners/Self-build-16a.png" border="0" alt="" width="950px"></a></div>
-->

<?php
    //echo $_SERVER['DOCUMENT_ROOT'];
?>



<?php if (DEFINE_MAIN_PAGE_STATUS >= 1 and DEFINE_MAIN_PAGE_STATUS <= 2) { ?>
<?php

?>
<div id="indexDefaultMainContent"><?php require($define_page); ?></div>
<?php } ?>


<div>
....
</div>


<?php
  $show_display_category = $db->Execute(SQL_SHOW_PRODUCT_INFO_MAIN);
  while (!$show_display_category->EOF) {
?>


<script type="text/javascript" src="<?php echo HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_TEMPLATE; ?>jscript/jquery.carouFredSel-6.0.2.js"></script>

<script type="text/javascript"><!--
	$(document).ready(function() {
	    $("#foo1").carouFredSel({
		auto: false,
		  align: "center",
		  padding: [0, 25, 0, 20],
		  width: "100%",
		  height: "197px",
		  items: {
                visible: 4,
		    minimum: 1
		    },
		  scroll: {
                fx: "directscroll"
		    },
		  prev    : {     
                button  : "#foo1_prev",
		    },
		  next    : { 
                button  : "#foo1_next",
		    }
	      });
	  });     
      --></script>


<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_FEATURED_PRODUCTS') { ?>
<?php
/**
 * display the Featured Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
<?php } ?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_SPECIALS_PRODUCTS') { ?>
<?php
/**
 * display the Special Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_specials_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_specials_default.php'); ?>
<?php } ?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_NEW_PRODUCTS') { ?>
<?php
/**
 * display the New Products Center Box
 */
?>
<?php require($template->get_template_dir('tpl_modules_whats_new.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_whats_new.php'); ?>
<?php } ?>

<?php if ($show_display_category->fields['configuration_key'] == 'SHOW_PRODUCT_INFO_MAIN_UPCOMING') { ?>
<?php
/**
 * display the Upcoming Products Center Box
 */
?>
<?php include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_UPCOMING_PRODUCTS)); ?><?php } ?>


<?php
  $show_display_category->MoveNext();
} // !EOF
?>
</div>