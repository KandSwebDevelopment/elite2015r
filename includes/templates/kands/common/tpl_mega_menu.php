<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: tpl_drop_menu.php  2005/06/15 15:39:05 DrByte Exp $
//

?>


<div id="mega-wrapper"><!-- bof mega-wrapper -->

    <ul class="mega-menu menu_red"><!-- bof mega-menu -->
       
    <li class="home-li"><?php echo '<a class="drop" href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'; ?><?php echo HEADER_TITLE_CATALOG; ?></a></li>

        <li class="categories-li"><a href="<?php echo zen_href_link(FILENAME_DEFAULT); ?>" class="drop"><?php echo HEADER_TITLE_CATEGORIES; ?></a><!-- bof cateories    -->
     
            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">
                   <div class="levels">
                       <?php

 // load the UL-generator class and produce the menu list dynamically from there
 require_once (DIR_WS_CLASSES . 'categories_ul_generator.php');
 $zen_CategoriesUL = new zen_categories_ul_generator;
 $menulist = $zen_CategoriesUL->buildTree(true);
 $menulist = str_replace('"level4"','"level5"',$menulist);
 $menulist = str_replace('"level3"','"level4"',$menulist);
 $menulist = str_replace('"level2"','"level3"',$menulist);
 $menulist = str_replace('"level1"','"level2"',$menulist);
 $menulist = str_replace('<li class="submenu">','<li class="submenu">',$menulist);
 $menulist = str_replace("</li>\n</ul>\n</li>\n</ul>\n","</li>\n</ul>\n",$menulist);
 echo $menulist;
?>                        
                   </div>   
                </div>
               </div>
        </li><!-- eof categories  -->
        

     <li class="manufacturers-li"><a href="<?php echo zen_href_link(FILENAME_DEFAULT); ?>" class="drop"><?php echo HEADER_TITLE_MANUFACTURERS; ?></a><!--bof shop by brand   -->
            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">

              <ul >
               <?php
		
  $show_manufacturers= true;

// for large lists of manufacturers uncomment this section
/*
  if (($_GET['main_page']==FILENAME_DEFAULT and ($_GET['cPath'] == '' or $_GET['cPath'] == 0)) or  ($request_type == 'SSL')) {
    $show_manufacturers= false;
  } else {
    $show_manufacturers= true;
  }
*/

// Set to true to display manufacturers images in place of names
define('DISPLAY_MANUFACTURERS_IMAGES',false);

if ($show_manufacturers) {

// only check products if requested - this may slow down the processing of the manufacturers sidebox
  if (PRODUCTS_MANUFACTURERS_STATUS == '1') {
    $manufacturer_sidebox_query = "select distinct m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
                            from " . TABLE_MANUFACTURERS . " m
                            left join " . TABLE_PRODUCTS . " p on m.manufacturers_id = p.manufacturers_id
                            where m.manufacturers_id = p.manufacturers_id and p.products_status= 1
                            order by manufacturers_name";
  } else {
    $manufacturer_sidebox_query = "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
                            from " . TABLE_MANUFACTURERS . " m
                            order by manufacturers_name";
  }

  $manufacturer_sidebox = $db->Execute($manufacturer_sidebox_query);

  if ($manufacturer_sidebox->RecordCount()>0) {
    $number_of_rows = $manufacturer_sidebox->RecordCount()+1;

// Display a list
    $manufacturer_sidebox_array = array();
//		kuroi: commented out to avoid starting list with text scrolling list entries such as "reset" and "please select"
//    if (!isset($_GET['manufacturers_id']) || $_GET['manufacturers_id'] == '' ) {
//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_ALL);
//    } else {
//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_MANUFACTURERS);
//    }

    while (!$manufacturer_sidebox->EOF) {
      $manufacturer_sidebox_name = ((strlen($manufacturer_sidebox->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturer_sidebox->fields['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturer_sidebox->fields['manufacturers_name']);
	  $manufacturer_sidebox_image = $manufacturer_sidebox->fields['manufacturers_image'];
      $manufacturer_sidebox_array[] =
		array('id' => $manufacturer_sidebox->fields['manufacturers_id'],
			  'text' => DISPLAY_MANUFACTURERS_IMAGES ?
				zen_image(DIR_WS_IMAGES . $manufacturer_sidebox_image, $manufacturer_sidebox_name) :
				$manufacturer_sidebox_name);
      $manufacturer_sidebox->MoveNext();
    }
      
  }
} // $show_manufacturers
				for ($i=0;$i<sizeof($manufacturer_sidebox_array);$i++) {
      $content = '';
	  $content .= '<li ><a class="hide" href="' . zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer_sidebox_array[$i]['id']) . '">';
      $content .= $manufacturer_sidebox_array[$i]['text'];
      $content .= '</a></li>' . "\n";
      echo $content;
	}
  ?>
		    </ul>
		</div>
            </div>
        </li><!-- eof shop by brand    -->
        
        

          <li class="information-li"><a href="<?php echo zen_href_link(FILENAME_DEFAULT); ?>" class="drop"><?php echo HEADER_TITLE_INFORMATION; ?></a><!-- bof information -->
 
	    <div class="dropdown_info">
        
		<div class="col_1">
            	    <h3><?php echo TITLE_GENERAL; ?></h3>
                    <ul>
			<li><a href="<?php echo zen_href_link(FILENAME_ABOUT_US); ?>"><?php echo BOX_INFORMATION_ABOUT_US; ?></a></li>
                	<?php if (DEFINE_SITE_MAP_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_SITE_MAP); ?>"><?php echo BOX_INFORMATION_SITE_MAP; ?></a></li>
                	<?php } ?>
              		<?php if (MODULE_ORDER_TOTAL_GV_STATUS == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_GV_FAQ, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_GV; ?></a></li>
                	<?php } ?>
                	<?php if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_DISCOUNT_COUPON, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_DISCOUNT_COUPONS; ?></a></li>
                	<?php } ?>
               		<?php if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_UNSUBSCRIBE, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_UNSUBSCRIBE; ?></a></li>
                	<?php } ?>
                     </ul>   
                 </div>
    
		<div class="col_1">
                     <h3><?php echo TITLE_CUSTOMERS; ?></h3>
                     <ul>
			<?php if ($_SESSION['customer_id']) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'); ?>"><?php echo TITLE_NEWSLETTERS; ?></a></li>
                	<?php } else { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CREATE_ACCOUNT; ?></a></li>
                	<?php } ?>
                    	<li><a href="<?php echo zen_href_link(FILENAME_CONTACT_US, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_CONTACT; ?></a></li>
                        <?php if (DEFINE_SHIPPINGINFO_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_SHIPPING); ?>"><?php echo BOX_INFORMATION_SHIPPING; ?></a></li>
                        <?php } ?>
                        <?php if (DEFINE_PRIVACY_STATUS <= 1)  { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_PRIVACY); ?>"><?php echo BOX_INFORMATION_PRIVACY; ?></a></li>
                        <?php } ?>
                        <?php if (DEFINE_CONDITIONS_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_CONDITIONS); ?>"><?php echo BOX_INFORMATION_CONDITIONS; ?></a></li>
                        <?php } ?>
                     </ul>   
                 </div>

            	 <div class="col_1">
                      <h3><?php echo TITLE_EZ_PAGES; ?></h3>
		      <ul> 
		      <?php require(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . 'ezpages_drop_menu.php'); ?>
		      </ul>
            	 </div>
           </div>

  	</li><!-- eof information -->
 
    
     
		      <li class="contactus-li"><a href="<?php echo zen_href_link(FILENAME_CONTACT_US, '', 'NONSSL'); ?>" class="drop"><?php echo HEADER_TITLE_CONTACT_US;?></a><!-- bof contact form -->
                       
            <div class="dropdown_2columns">
                
                <div class="col_2 firstcolumn">
   
		   <h2><?php echo TITLE_CONTACT_US;?></h2>

		   <p><?php echo TEXT_CONTACT_US;?></p>
    
                    <div id="contact_form">
                    
                        <div class="message">
                            <div id="alert"></div>
                        </div>
				  <?php echo zen_draw_form('contact_us', zen_href_link(FILENAME_CONTACT_US, 'action=send')); ?>
                        
                        <label for="contactname">Name<span class="required"> *</span></label>
                        <input name="contactname" type="text" id="contactname" size="30" value=""/>

			<br class="clearBoth" />
                        <label for="email">Email<span class="required"> *</span></label>
                        <input name="email" type="text" id="email" size="30" value="" />

			<br class="clearBoth" />
                        <label for="enquiry">Message<span class="required"> *</span></label>
                        <textarea name="enquiry" cols="40" rows="3"  id="enquiry"></textarea>
                        
                        

                           <div class="form_buttons">
                            <input type="submit" class="button" id="submit" value="Submit" />
                            </div>

                        </form>
                    
                    </div>

                </div>
            
            </div>
</li><!-- eof contact form -->

    




    </ul><!-- eof mega-menu -->

</div><!-- eof mega-wrapper -->
