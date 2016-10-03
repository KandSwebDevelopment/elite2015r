<?php

require(DIR_WS_MODULES . zen_get_module_directory('family_images_c.php'));


  if($images_array != NULL){
//    echo '<div id="productFamilyHeading">Family Items</div>';
//    echo '<div id="productFamily">';
//
//    set the carousel vars
//    $carousel_id='family_carousel';
    $carousel_class='familySlider';

    //Loop through items
    require($template->get_template_dir('tpl_carousel_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_carousel_display.php');
//    echo '</div>';
    ?>

    <?php
    echo ' <br class="clearBoth" />';
  }
?>
