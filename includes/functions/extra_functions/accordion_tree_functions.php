<?php
function get_children($parent){
	global $db;
	$result = array();
	$rs=$db->Execute("SELECT c.categories_id, cd.categories_name FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
						WHERE c.categories_id = cd.categories_id AND c.categories_status = 1 AND c.parent_id = ".$parent . " ORDER BY c.sort_order");
	while(!$rs->EOF){
		$result[] = array('id'=>$rs->fields['categories_id'],'text'=>$rs->fields['categories_name']);
		$rs->MoveNext();
	}
	return $result;
}

function build_child($parent_id, $level,$current_category){
	$this_level = get_children($parent_id);
	if(sizeof($this_level)> 0){
		$content.= '<ul class="child_'.$level.'">'.PHP_EOL;
		 for($i=0;$im=sizeof($this_level),$i<$im;$i++){
			 $path = implode('_', array_reverse(explode('_',zen_get_generated_category_path_ids($this_level[$i]['id'],'category'))));
			 $link = zen_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_reverse(explode('_',zen_get_generated_category_path_ids($this_level[$i]['id'],'category')))));
			 $selected = $current_category==$this_level[$i]['id']?' id="current"':'';
			 if(has_children($this_level[$i]['id'])){
				$content.= '<li'.$selected.'><a href="#">'.$this_level[$i]['text'].'</a>'.PHP_EOL;
				$content.= build_child($this_level[$i]['id'],$level+1,$current_category);
				$content.='</li>'.PHP_EOL;
			 }else{
				 $content.= '<li'.$selected.'><a href="'.$link.'">'.$this_level[$i]['text'].'</a></li>'.PHP_EOL;
			 }
		}
		$content.= '</ul>'.PHP_EOL;
	}else{
		$content.= '<ul class="child_'.$level.'">'.PHP_EOL;
		$content.= '</ul>'.PHP_EOL;
		//no children so its a  the link
		//$content.= '<li>'.PHP_EOL;
		//$content.= '<a href="####">'.$this_level[$i]['text'].'</a>'.PHP_EOL;
		//$content.= '</li>'.PHP_EOL;
	}
	
	return $content;
}

function has_children($id){
	//return false;
	global $db;
	$rs = $db->Execute("SELECT * FROM ".TABLE_CATEGORIES." WHERE parent_id =".$id);
	return !$rs->EOF;
}
?>
