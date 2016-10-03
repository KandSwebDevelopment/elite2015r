<?php
/* Page Template - tpl_footer_menu.php
*Display the Footer Menu
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer_menu.php 1.0 5/9/2009 Clyde Jones $
*/
?>



<script type="text/javascript">
    $(document).ready(function() {
        jQuery(".content1").hide();
        //toggle the componenet with class msg_body                                                                                                                                                                              
        jQuery(".heading").click(function()
                                 {
                                   jQuery(this).next(".content1").slideToggle(500);
                                   jQuery(this).toggleClass("minus");
                                 });
      });


</script>



<div id="footer-menu">

<div id="footer-one" class="layer1">
<?php echo CUSTOMER_SERVICE; ?>
</div>




<div id="footer-three" class="layer1">
<?php if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>

<?php echo IMPORTANT; ?>
<?php require($template->get_template_dir('tpl_ezpages_footer_menu.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_footer_menu.php'); ?>
<?php echo IMPORTANT_END; ?>
<?php } ?>
</div>

<div id="social-media">
<h2>Follow Us</h2>
<a href="<?php echo FACEBOOK; ?>" target="_blank"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.FACEBOOK_ICON ?>"  alt="facebook link" class="smi facebook" /></a>
<a href="<?php echo TWITTER; ?>" target="_blank"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.TWITTER_ICON ?>"  alt="twitter link" class="smi twitter" /></a>
<a href="<?php echo YOUTUBE; ?>" target="_blank"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.YOUTUBE_ICON ?>"  alt="youtube link" class="smi youtube" /></a>
<a href="<?php echo PINTEREST; ?>" target="_blank"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.PINTEREST_ICON ?>"  alt="pinterest link" class="smi pinterest" /></a>
<a href="<?php echo GOOGLE; ?>" target="_blank"><img src="<?php  echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'images').'/'.GOOGLE_ICON ?>"  alt="google link" class="smi google" /></a>
</div>




</div>




<br class="clearBoth" />

