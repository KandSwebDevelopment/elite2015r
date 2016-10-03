<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=create_account.<br />
 * Displays Create Account form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sun Aug 19 09:47:29 2012 -0400 Modified in v1.5.1 $
 */
?>

<?php if ($messageStack->size('create_account') > 0) echo $messageStack->output('create_account'); ?>
<br class="clearBoth" />


<div class="accCreateField">
Select the account type you require
<br><br>
<div class="buttonRow"><?php echo '<a href="'. zen_href_link(FILENAME_CREATE_ACCOUNT,'t=1'). '"/>'. zen_image_button('Any','&nbsp;&nbsp;&nbsp;&nbsp;Home user&nbsp;&nbsp;&nbsp;&nbsp;').'</a>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;

<?php echo '<a href="'. zen_href_link(FILENAME_CREATE_ACCOUNT,'t=2'). '"/>'. zen_image_button('Any','Buisness user').'</a>'; ?></div>
</div>
<br class="clearBoth" />



