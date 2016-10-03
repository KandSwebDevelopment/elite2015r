<?php
/**
 * KandS Extra Product Fields - This builds the output for the specification table on the
 * product info page
 *
 * Portions Copyright (c) 2002 osCommerce
 *  From Steven Mewhirter admin@kandsweb.com
 * license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 *
 * issue 1.0  March 2011
 */

$tech_specs = new ProductData;
$result = '';
$specs_array = $tech_specs->get_product_specs($_GET['products_id']);

if(sizeof($specs_array)> 0) {
    if($_SESSION['department'] == 2){
      //Reorder the specs
      $specs_array = $tech_specs->reOrder($specs_array);
    }
    $result = '<div class="tecSpecBox">';
        //<!--<div id="tecSpecHeading">Technical Specifications</div>-->
        $result .= '<div class="tecSpec">';
            //items not to be displayed
            //$skip_array = array('products_id', 'manufactures_code');

            if(is_array($specs_array)){
              foreach($specs_array as $key => $value){
                $result .= '<div class="tecSpecItem">';
                $result .= '<div class="tecSpecName">';
                $result .= $key;
                $result .= '</div><div class="tecSpecValue">';
                $result .= $value;
                $result .= '</div>';
                $result .= '<br class="clearBoth" />';
                $result .= '</div>';
              }
            }
            
            //<!--<div id="tecSpecItem"><br />&nbsp;<br /></div>-->
        $result .='</div>';
    $result .='</div>';
} ?>
