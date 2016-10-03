<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_main_product_image.php 18698 2011-05-04 14:50:06Z wilt $
 */
?>
<?php //require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_MAIN_PRODUCT_IMAGE)); ?>

<script src="<?php echo $template->get_template_dir('jquery.magnific-popup.js',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/jquery.magnific-popup.js' ?>" type="text/javascript">
$.extend(true, $.magnificPopup.defaults, {
  tClose: 'Close (Esc)', 
  tLoading: 'Loading...%curr% of %total%', // Text that is displayed during loading. Can contain %curr% and %total% keys
//  gallery: {
//    tPrev: 'Previous (Left arrow key)', // Alt text on left arrow
//    tNext: 'Next (Right arrow key)', // Alt text on right arrow
//    tCounter: '%curr% of %total%' // Markup for "1 of 7" counter
//  },
  image: {
    tError: '<a href="%url%">The image</a> could not be loaded.' // Error message when image could not be loaded
  }
//  ajax: {
//    tError: '<a href="%url%">The content</a> could not be loaded.' // Error message when ajax request failed
//  }
});
</script>

<div id="productMainImage" class="centeredContent back">

<a class="mainImage" href=<?php echo '"'.DIR_WS_IMAGES. $products_image.'">'.zen_image(DIR_WS_IMAGES.$products_image, $products_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT);?><br>View Large</a>

<noscript>
<?php
  //echo '<a href="' . zen_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $_GET['products_id']) . '" target="_blank">' . zen_image($products_image_medium, $products_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT) . '<br /><span class="imgLink">' . TEXT_CLICK_TO_ENLARGE . '</span></a>';
?>
</noscript>
</div>

<script type="text/javascript">
    $(document).ready(function() {
      $('.mainImage').magnificPopup({
          type:'image',
          closeOnContentClick: true
      });
    });
</script>
