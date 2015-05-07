<?php
/**
 * Footer Menu Definitions
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V3.0
 * @version $Id: footer_menu_deines.php 1.0 5/9/2009 Clyde Jones $
 */

/*BOF Menu Column 3 link Definitions*/
Define('TITLE_THREE', '<h2 class="hide">Customer Service</h2><h2 class="heading">Customer Service</h2>');
Define('CONTACT','<li><a href="' . zen_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a></li>');
Define('SHIPPING', '<li><a href="' . zen_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a></li>');
Define('ABOUT', '<li><a href="' . zen_href_link(FILENAME_ABOUT_US) . '">' . BOX_INFORMATION_ABOUT_US . '</a></li>');
Define('PRIVACY', '<li><a href="' . zen_href_link(FILENAME_PRIVACY) . '">' . BOX_INFORMATION_PRIVACY . '</a></li>');
Define('CONDITIONS','<li><a href="' . zen_href_link(FILENAME_CONDITIONS) . '">' . BOX_INFORMATION_CONDITIONS . '</a></li>');
/*EOF Menu Column 3 link Definitions*/

/*BOF Menu Column 4 link Definitions*/
Define('TITLE_FOUR', '<h2 class="hide">Links</h2><h2 class="heading">Links</h2>');
/*The actual links are determined by "footer links" set in EZ-Pages
*EOF Menu Column 4 link Definitions
*/

/*BOF Footer Menu Definitions*/
Define('CUSTOMER_SERVICE', TITLE_THREE . '<ul class="content1">' . CONTACT . SHIPPING . ABOUT . PRIVACY . CONDITIONS . '</ul>');
Define('IMPORTANT', TITLE_FOUR . '<ul class="content1">');
Define('IMPORTANT_END', '</ul>');
/*EOF Footer Menu Definitions*/

define('TWITTER_ICON', 'twitter.png');
define('FACEBOOK_ICON','facebook.png');
Define('YOUTUBE_ICON', 'youtube.png');
Define('PINTEREST_ICON', 'pintrest.png');
Define('GOOGLE_ICON', 'google_plus.png');
Define('BLOG_ICON', 'wordpress.png');
 
/*bof bottom footer urls*/
Define('FACEBOOK','http://www.facebook.com/Custom.Zen.Cart.Design');
Define('TWITTER', 'http://www.twitter.com/picaflorazul');
Define('YOUTUBE', 'http://www.youtube.com/user/ZenCartEasyHelp');
Define('PINTEREST', 'http://www.pinterest.com/picaflorazul');
Define('GOOGLE', 'https://plus.google.com/113609090217058276980/posts');
Define('BLOG', 'http://www.picaflor-azul.com/blog');

//EOF