<script src="<?php echo $template->get_template_dir('',DIR_WS_TEMPLATE, $current_page_base,'jscript') . '/accordion.js' ?>" type="text/javascript"></script>
<script type="text/javascript">
$("html").addClass("sm");
$("html").addClass("js");
$(function() {
	$("#acc_menu").accordion({
		collapsible: true,
		initShow: '#current'
	});
	$("html").removeClass("js");
	$("html").removeClass("sm");
});

</script>

<?php
/**
 * tpl_accordion_categories_tree.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * $Id: tpl_cc_accept.php v 1.2.1 2008-04-10
 *
 * Created by Steven Mewhirter
 * KandS Web Development 2012
 */

 //*********************************************************************************************************
 //The jquery code to initilise the accordion is in the file includes/templates/KandS/common/html_header.php
 //*********************************************************************************************************

$top = get_children(0);
$paths = explode('_',$cPath);
$current = $paths[sizeof($paths)-1];
if($current=='')$current = 0;

$content = '<div id="acc_menu">'.PHP_EOL.
'<ul class="accordion">'.PHP_EOL;

for($l1=0;$l1m=sizeof($top),$l1<$l1m;$l1++){
	$selected = ($l1==0&&$current==0)?'id="current"':'';
	$content.= '<li '.$selected.'>'.$top[$l1]['text'].PHP_EOL;
	//$content.= '<li '.'>'.$top[$l1]['text'].PHP_EOL;
	$content.= build_child($top[$l1]['id'],1,$current);
	$content.= '</li>'.PHP_EOL;
}

//End of <div id="accordion_top">
$content.='</ul>'.PHP_EOL;

$content.=  '</div>';
//Add size chart
//$content.='<hr>';
//$content.= '<a class="menuSC" href="'.zen_href_link('page_2').'">Size Chart</a>';
//$content.='<hr>';
$content .= '<div class="loading" id="magic"><h4>Loading Categories</h4>
	<img src="images/loading-gif-animation.gif" width="80" height="80" /><br /></div>';
?>




