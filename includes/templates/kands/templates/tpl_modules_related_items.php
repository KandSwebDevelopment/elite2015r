<?php

// calculate whether any cross-sell products are configured for the current product, and display if relevant
// collect information on available cross-sell products for the current product-id
if (isset($_GET['products_id']) && SHOW_PRODUCT_INFO_COLUMNS_XSELL_PRODUCTS > 0 ) {
  $xsell_query = $db->Execute("select distinct p.products_id, p.products_image, pd.products_name
                                 from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                 where xp.products_id = '" . $_GET['products_id'] . "'
                                  and xp.xsell_id = p.products_id
                                  and p.products_id = pd.products_id
                                  and pd.language_id = '" . $_SESSION['languages_id'] . "'
                                  and p.products_status = 1
                                 order by xp.sort_order asc limit " . MAX_DISPLAY_XSELL);
  $num_products_xsell = $xsell_query->RecordCount();


  // don't display if less than the minimum amount set in Admin->Config->Minimum Values->Cross-Sell
  if ($num_products_xsell >= MIN_DISPLAY_XSELL && $num_products_xsell > 0) {

/**
 * require the list_box_content template to display the cross-sell info. This info was prepared in modules/xsell_products.php
 */
     $carousel_class='relatedSlider';
?>
<ul class="<?php echo $carousel_class;?>">

    <?php
    while(!$xsell_query->EOF){
      echo '<li>';
      echo '<a href="'.zen_href_link(zen_get_info_page($xsell_query->fields['products_id']),($cPath ? 'cPath='.$cPath.'&':'').'products_id='.$xsell_query->fields['products_id']).'"><img src="images/'.$xsell_query->fields['products_image'].'"></a>';
      echo '<br><span class="familyName">'.$xsell_query->fields['products_name'].'</span>';
      echo '</li>';
      $xsell_query->MoveNext();
    }
    ?>

</ul>


<!--</div>-->
<!-- eof: tpl_modules_xsell_products -->
<?php }
} ?>