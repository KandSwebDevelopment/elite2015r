<?php
  ////////////////////////////////////////////////////////////////////////////////
//KandS add for elite for easypopulate
////////////////////////////////////////////////////////////////////////////////

function format_description(&$description){
  $start_pos = strpos($description, '*');
  $end_pos = strpos($description, '**');
  if(!$start_pos >0 && !$end_pos > 0)return;
  $pre_string = substr($description, 0 , $start_pos);
  $post_string = substr($description, ($end_pos+2));
  $list_string = substr($description, $start_pos, ($end_pos-$start_pos));
  $list_array = explode('*', $list_string);
  $html_list = '';
  foreach($list_array as $key => $value){
    if($value){
      $html_list .= '<li>' . $value . '</li>';
    }
  }
  if($html_list){
    $html_list = '<ul>' . $html_list . '</ul>';
  }
  $description = $pre_string . $html_list . $post_string;
}

/**
* Get products id from products model
*
* @param {string|string[]} $mID
*/
function ep_pID_mID($mID){
  $mID = zen_db_prepare_input($mID);
  global $db;
  $sql = "select products_id, products_model from " . TABLE_PRODUCTS . " where products_model = '$mID' LIMIT 1";
  $result = $db->Execute($sql);

  if ($result->RecordCount() > 0) {
    return $result->fields['products_id'];
  }
  return NULL;

}

function ep_delete_xsell($pID){
  $sql = "DELETE FROM " . TABLE_PRODUCTS_MXSELL . "1 WHERE (products_id = $pID) OR (xsell_id = $pID)";
  ep_query($sql);
}
//EOF KandS
?>
