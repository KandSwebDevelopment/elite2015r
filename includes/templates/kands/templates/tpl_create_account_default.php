<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=create_account.<br />
 * Displays Create Account form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_create_account_default.php 5523 2007-01-03 09:37:48Z drbyte $
 */
?>
<div id="fuzz">
<div class=tac_popup id="tac_popup">
<h4>Trade Partner Terms & Conditions</h4>
<p>By logging in using your email and unique password you are agreeing to our Terms & Conditions.
Usage is soley for the intended Trade Partner and cannot be passed or used to anyone else.
Any misuse of access will result in termination of your Trade Partner account.
As a trade partner any transactions are treated as business to business and fall under our standard Terms & Conditions (see Conditions of Use)</p>

<h4>Cancellation and Returns</h4>
<p>Goods ordered by any customer and supplied correctly by the company will not normally be accepted for return.
In such cases where the company is in agreement to accept goods to be returned the company reserves the right to make a handling charge for goods returned to stock.
Any such goods must be in their original packing, unused and un-damaged.
Special orders or goods made to the customers specifications cannot be returned.</p>

<div class="tac_popup_a" onclick="tac_accept()">Accept</div>
<div class="tac_popup_d" onclick="tac_decline()">Decline</div>
</div></div>
<div class="centerColumn" id="createAcctDefault">
<div class="listAreaTop">
<h1 id="productListHeading">User Registration</h1>
</div>
<div id="bodyWrap" class="accCreate">
<?php
echo zen_draw_form('create_account', zen_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post') . zen_draw_hidden_field('action', 'process') . zen_draw_hidden_field('email_pref_html', 'email_format'); ?>
<h4 id="createAcctDefaultLoginLink"><?php echo sprintf(TEXT_ORIGIN_LOGIN, zen_href_link(FILENAME_LOGIN, zen_get_all_get_params(array('action')), 'SSL')); ?></h4>

<div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION . '&nbsp;&nbsp;&nbsp;'; ?></div>
<br class="clearBoth" />

<?php
		//Inital state, just show the how to proceed

		if(!isset($_GET['t'])){
?>
<fieldset>
<legend>Account Type</legend>

<?php require($template->get_template_dir('tpl_modules_create_account.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_create_account.php'); ?>

</fieldset>
<div class="buttonRow forward"><?php echo '<a href="'. zen_href_link(zen_back_link(true)). '"/>'. zen_image_button('Any','Back').'</a>'; ?></div>

<?php
		}//End of 'how to proceed
		
		//Type 1,  home user
		if(isset($_GET['t'])&& $_GET['t']==1){
		
?>
<fieldset>
<legend>Home User Account</legend>

<?php require($template->get_template_dir('tpl_modules_create_home_account.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_create_home_account.php'); ?>

</fieldset>

<!--<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SUBMIT, BUTTON_SUBMIT_ALT); ?></div>-->
<div class="buttonRow forward"><?php echo zen_image_button(BUTTON_IMAGE_SUBMIT, BUTTON_SUBMIT_ALT, 'onclick="check_form(create_account);"'); ?></div>

<?php
		}//End of Home user
		//Type 2,  home user
		if(isset($_GET['t'])&& $_GET['t']==2){
		
?>
<fieldset>
<legend>Business User Account</legend>

<?php require($template->get_template_dir('tpl_modules_create_business_account.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_create_business_account.php'); ?>
<?php //require($template->get_template_dir('tpl_modules_create_business_account.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_create_accountOld.php'); ?>

</fieldset>

<!--<div class="buttonRow forward"><?php echo zen_image_submit(BUTTON_IMAGE_SUBMIT, BUTTON_SUBMIT_ALT); ?></div>-->
<div class="buttonRow forward"><?php echo zen_image_button(BUTTON_IMAGE_SUBMIT, BUTTON_SUBMIT_ALT, 'onclick="check_form(create_account);"'); ?></div>

<?php
		}//End of Home user
?>





</form>
</div>