<?php
/**
 * @package admin
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: denied.php 18698 2011-05-04 14:50:06Z wilt $
 */

require('includes/application_top.php');
$filterOptionsArray = 

$gID = 0;
if(isset($_GET['gID']))$gID=$_GET['gID'];
if(isset($_GET['v']))$v=$_GET['v'];
if(isset($_GET['action']))$action=$_GET['action'];

if(!zen_not_null($v))$v=1;

$filterOptionNames  = array(0 => '', 1 => 'Style ', 2 => 'Finish ', 3 => 'Material ', 4 => 'Colour ') ;


switch ($_GET['list_order']) {
		case "description":
			$disp_order = "foptions_name";
			break;
		case "description-desc":
			$disp_order = "foptions_name DESC";
			break;
		break;
			case "value":
			$disp_order = "foptions_value";
		break;
			case "value-desc":
			$disp_order = "foptions_value DESC";
		break;
		default:
			$disp_order = "foptions_value";
}

if($gID>0){
	$sql = "SELECT * FROM filter_options_values WHERE foptions_group = $gID order by $disp_order";
	$rs_opt = $db->Execute($sql);
	
	if(zen_not_null($v)){
		$sql = "SELECT * FROM filter_options_values WHERE foptions_group = $gID AND foptions_value = $v";
		$sel_opt = $db->Execute($sql);

		//$mInfo_array = array($sel_opt->fields);
		$mInfo = new objectInfo($sel_opt->fields);
	}
	
}


	if (zen_not_null($action)) {
		switch ($action) {
			case 'insert':
			case 'save':
				if (isset($_GET['gID'])) 
					$foptions_group = zen_db_prepare_input($_GET['gID']);
				if(isset($_POST['gID']))
					$foptions_group = zen_db_prepare_input($_POST['gID']);
				$foptions_name = zen_db_prepare_input($_POST['foptions_name']);
				$foptions_value = zen_db_prepare_input($_POST['foptions_value']);

				$sql_data_array = array('foptions_group' => $foptions_group, 'foptions_name' => $foptions_name,'foptions_value' => $foptions_value);

				if ($action == 'insert') {
					zen_db_perform('filter_options_values', $sql_data_array);
					//$manufacturers_id = zen_db_insert_id();
				} elseif ($action == 'save') {
					zen_db_perform('filter_options_values', $sql_data_array, 'update', "foptions_group = $foptions_group  AND  foptions_value = $foptions_value");
				}

				zen_redirect(zen_href_link(FILENAME_FILTEROPTIONS_MANAGER,  'gID=' . $foptions_group));
				break;
			case 'deleteconfirm':
				$foptions_group = zen_db_prepare_input($_POST['gID']);
				$foptions_value = zen_db_prepare_input($_POST['foptions_value']);


				$db->Execute("delete from filter_options_values
											where foptions_group = $foptions_group AND foptions_value = $foptions_value");



				zen_redirect(zen_href_link(FILENAME_FILTEROPTIONS_MANAGER,  'gID=' . $foptions_group));
			
				break;
		}
	}





?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<link rel="stylesheet" type="text/css" href="includes/admin_access.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
	<!--
	function init()
	{
		cssjsmenu('navbar');
		if (document.getElementById)
		{
			var kill = document.getElementById('hoverJS');
			kill.disabled = true;
		}
	}
	// -->
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<!--<div id="pageWrapper">-->
<?php
	echo  ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, '&gID=1') . '">' . zen_image_button('button_style.gif', IMAGE_CANCEL) . '</a>'
			. ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, '&gID=2') . '">' . zen_image_button('button_finish.gif', IMAGE_CANCEL) . '</a>'
			. ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, '&gID=3') . '">' . zen_image_button('button_material.gif', IMAGE_CANCEL) . '</a>'
			. ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, '&gID=4') . '">' . zen_image_button('button_colour.gif', IMAGE_CANCEL) . '</a>';

?>







<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
<!-- body_text //-->
		<td width="100%" valign="top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
				<td width="100%">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td class="pageHeading"><?php echo $filterOptionNames[$gID]; ?></td>
						<td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
					</tr>
				</table>
				</td>
			</tr>
			
			<tr>
										<!-- Lefthand pane START  -->
				<td>
					<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top">
						<table border="0" width="100%" cellspacing="0" cellpadding="2">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent">
								
								<?php //echo 'Description'; 
								if(isset($gID))$parms = "&gID=$gID";
								if(isset($v))$parms .= "&v=$v";
								?>
									<?php echo (($_GET['list_order']=='description' or $_GET['list_order']=='description-desc') ? '<span class="SortOrderHeader">' . 'Description' . '</span>' : 'Description'); ?><br>
									<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=description'.$parms, '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='description' ? '<span class="SortOrderHeader">Asc</span>' : '<span class="SortOrderHeaderLink">Asc</b>'); ?></a>&nbsp;
									<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=description-desc'.$parms, '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='description-desc' ? '<span class="SortOrderHeader">Desc</span>' : '<span class="SortOrderHeaderLink">Desc</b>'); ?></a>
								
								</td>
								<td class="dataTableHeadingContent">
								<?php //echo 'Value'; ?>
									<?php echo (($_GET['list_order']=='value' or $_GET['list_order']=='value-desc') ? '<span class="SortOrderHeader">' . 'Value' . '</span>' : 'Value'); ?><br>
									<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=value'.$parms, '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='value' ? '<span class="SortOrderHeader">Asc</span>' : '<span class="SortOrderHeaderLink">Asc</b>'); ?></a>&nbsp;
									<a href="<?php echo zen_href_link(basename($PHP_SELF) . '?list_order=value-desc'.$parms, '', 'NONSSL'); ?>"><?php echo ($_GET['list_order']=='value-desc' ? '<span class="SortOrderHeader">Desc</span>' : '<span class="SortOrderHeaderLink">Desc</b>'); ?></a>
								
								</td>
								<td class="dataTableHeadingContent" align="right"><?php echo 'Action'; ?>&nbsp;</td>
								<?php if(is_object($rs_opt)){
									while (!$rs_opt->EOF) {
										echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID . '&action=edit') . '\'">' . "\n";
										echo ' <td class="dataTableContent">'. $rs_opt->fields['foptions_name'].'</td>';
										echo ' <td class="dataTableContent">'. $rs_opt->fields['foptions_value'].'</td>';
										?>
										<td class="dataTableContent" align="right">
									<?php echo '<a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID . '&action=edit') . '">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_EDIT) . '</a>'; ?>
									<?php echo '<a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID . '&action=delete') . '">' . zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_DELETE) . '</a>'; ?>
									<?php if (isset($mInfo) && is_object($mInfo) && ($rs_opt->fields['foptions_value'] == $mInfo->foptions_value)) { echo zen_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER,  'v=' . $rs_opt->fields['foptions_value']  . '&gID=' . $gID) . '">' . zen_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>
								</td>
										<?php
										echo '</tr>' . "\n";
										$rs_opt->MoveNext();
									}
								}
								?>
							</tr>
					
							<?php         //// Insert button
								if (empty($action)&&$gID>0) {
							?>
							<tr>
								<td align="right" colspan="3" class="smallText"><?php echo '<a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'gID=' . $gID . '&action=new') . '">' . zen_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
							</tr>
							<?php
								}
							?>
					
						</td>
					</tr>
					</table>
				</td>
																		<!-- Lefthand pane END  -->
																		<!-- Righthand pane START  -->
				<td width="25%" valign="top">
							
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr class="infoBoxHeading">
							<?php
								
								switch ($action) {
									case 'new':
										$heading[] = array('text' => '<b>New ' . $filterOptionNames[$gID] . ' Option Value</b>');

										$contents = array('form' => zen_draw_form('manufacturers', FILENAME_FILTEROPTIONS_MANAGER, 'action=insert', 'post', 'enctype="multipart/form-data"'));
										$contents[] = array('text' => 'Please fill out the following information for the new option value');
										$contents[] = array('text' => '<br>Description<br>' . zen_draw_input_field('foptions_name', '', zen_set_field_length('filter_options_values', 'foptions_name')));

										$contents[] = array('text' => '<br />Value &nbsp;' . zen_draw_input_field('foptions_value'));
										$contents[] = array('text' =>  zen_draw_hidden_field('gID',$gID));

										$contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value']  . '&gID=' . $_GET['gID']) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
	
										 break;
									case 'edit':
										$heading[] = array('text' => '<b>Edit ' .  $filterOptionNames[$gID] . ' Option Value</b>');

										$contents = array('form' => zen_draw_form('manufacturers', FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $mInfo->foptions_group . '&action=save', 'post', 'enctype="multipart/form-data"'));
										$contents[] = array('text' => 'Please make any necessary changes');
										$contents[] = array('text' => '<br />Description<br>' . zen_draw_input_field('foptions_name', htmlspecialchars($mInfo->foptions_name, ENT_COMPAT, CHARSET, TRUE), zen_set_field_length('filter_options_values', 'foptions_name')));

										$contents[] = array('text' => '<br />Value &nbsp;' . zen_draw_input_field('foptions_value', htmlspecialchars($mInfo->foptions_value, ENT_COMPAT, CHARSET, TRUE), zen_set_field_length('filter_options_values', 'foptions_value'). ' readonly'));

										$contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
									
										break;
									case 'delete':
										$heading[] = array('text' => '<b>Delete '. $filterOptionNames[$gID]. ' Option Value</b>');

										$contents = array('form' => zen_draw_form('manufacturers', FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $mInfo->foptions_group . '&action=deleteconfirm') . zen_draw_hidden_field('gID', $mInfo->foptions_group). zen_draw_hidden_field('foptions_value', $mInfo->foptions_value));
										$contents[] = array('text' => 'Are you sure you want to delete this option value?');
										$contents[] = array('text' => '<br>Description <b>' . $mInfo->foptions_name . '</b>');
										$contents[] = array('text' => '<br>Value <b>' . $mInfo->foptions_value . '</b>');


										$contents[] = array('align' => 'center', 'text' => '<br>' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $mInfo->foptions_group) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
										
										break;
									default:
										if (isset($mInfo) && is_object($mInfo)) {
												$heading[] = array('align' => 'center', 'text' => '<b>' . $filterOptionNames[$gID] . ' Option Value</b>');

												$contents[] = array('align' => 'center', 'text' => '<a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID  . '&action=edit') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . zen_href_link(FILENAME_FILTEROPTIONS_MANAGER, 'v=' . $rs_opt->fields['foptions_value'] . '&gID=' . $gID  . '&action=delete') . '">' . zen_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
												$contents[] = array('text' => '<br>' . $mInfo->foptions_name . ' ' . $mInfo->foptions_value);
										}
										break;
								}
								
								if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
									echo '            <td width="25%" valign="top">' . "\n";

									$box = new box;
									echo $box->infoBox($heading, $contents);

									echo '            </td>' . "\n";
								}              
							?>

						</tr>
					</table>
				</td>      
<!-- Righthand pane END  -->            
			</tr>				
		</td>						
	</tr>            
<!-- body_text_eof //-->
</table>


<!--</div>-->
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
