<?php
	
class kas_mobile_ul_generator {
		var $root_category_id = 0,
		$max_level = 0,
		$data = array(),
		$parent_group_start_string = '<ul%s>',
		$parent_group_end_string = '</ul>',
		$child_start_string = '<li>',
		$child_end_string = '</li>',
		$spacer_string = '
',
		$spacer_multiplier = 1;
		
		function kas_mobile_ul_generator($load_from_database = true){
			global $languages_id, $db;
			$this->data = array();
			$categories_query = "select c.categories_id, cd.categories_name, c.parent_id
									from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
									where c.categories_id = cd.categories_id
									and c.categories_status=1 " .
											" and cd.language_id = '" . (int)$_SESSION['languages_id'] . "' " .
											" order by c.parent_id, c.sort_order, cd.categories_name";
			$categories = $db->Execute($categories_query);
			while (!$categories->EOF) {
					$this->data[$categories->fields['parent_id']][$categories->fields['categories_id']] = array('name' => $categories->fields['categories_name'], 'pid' => $categories->fields['parent_id']);
					$categories->MoveNext();
			}
		}
		
		function buildBranch($parent_id, $submenu=true, $parent_link=''){
						global $db;
						$result .= '<ul class="'.(($submenu==true) ? 'dl-submenu' : 'dl-menu').'">' ;

			if (($this->data[$parent_id])) {
				foreach($this->data[$parent_id] as $category_id => $category) {
					$category_link = $parent_link . $category_id;
					if (($this->data[$category_id])) {
							$result .= '<li><a href="#">';
					} else {
							$result .= '<li><a href="'.(zen_href_link(FILENAME_DEFAULT, 'cPath=' . $category_link)). '">';
					}
					$result .= $category['name'];
					$result .= '</a>';
		
					if (($this->data[$category_id]) && (($this->max_level == '0') || ($this->max_level > $level+1))) {
							$result .= $this->buildBranch($category_id, true, $category_link . '_');
					}
					$result .= $this->child_end_string;
				}//foreach
			}
						if($parent_id!=0)
								$result .= $this->parent_group_end_string;            

						
			return $result;
		}
		
		function buildTree(){
				$result =  $this->buildBranch($this->root_category_id, false);

						//Static items
						$result .= '<li><a href="#">Search</a>';
						$result .= '<ul class="dl-submenu">';
						$result .= '<li><a href="#">';
						$result .= '<form method="get" action="http://localhost/elite2015r/index.php?main_page=advanced_search_result" name="quick_find_header">'.
						'<input type="hidden" value="advanced_search_result" name="main_page">
<input type="hidden" value="1" name="search_in_description">
<input class="search-header-box" type="text" onfocus="if (this.value == "Enter search keywords here") this.value = ""; onblur="if (this.value == "") this.value = "Enter search keywords here"; onfocus="if (this.value == "Enter search keywords here") this.value = ""; value="Enter search keywords here" maxlength="30" size="6" name="keyword">
</form>';
						$result .= '</a></li>';
						//$result .= '<li><a href="#">bbbbb</a></li>';
						$result    .= '</ul></li>';
						
						$result .= '<li><a href="#">'.HEADER_TITLE_INFORMATION.'</a><ul class="dl-submenu">';
						$result .= '<li><a href="'. zen_href_link("map").'">Store Location</a></li>';
						$result .= '<li><a href="'. zen_href_link(FILENAME_ABOUT_US).'">'. BOX_INFORMATION_ABOUT_US.'</a></li>';
						$result .= '<li><a href="'. zen_href_link(FILENAME_SITE_MAP).'">'. BOX_INFORMATION_SITE_MAP.'</a></li>';
						$result .= '<li><a href="'. zen_href_link(FILENAME_LOGIN).'">'. HEADER_TITLE_LOGIN.'</a></li>';
						//$result .= '<li><a href="'. zen_href_link(FILENAME_CREATE_ACCOUNT).'">'. HEADER_TITLE_CREATE_ACCOUNT.'</a></li>';
						//$result .= '<li><a href="#">'. HEADER_TITLE_CREATE_ACCOUNT.'</a></li>';
						$result .= '<li><a href="'. zen_href_link(FILENAME_PRIVACY).'">'. BOX_INFORMATION_PRIVACY.'</a></li>';
						$result .= '<li><a href="'. zen_href_link(FILENAME_CONDITIONS).'">'. BOX_INFORMATION_CONDITIONS.'</a></li>';
						$result .= '</a></li></ul></li>';
						
						
						$result .= $this->parent_group_end_string;            
						return $result;
		}
				
}
?>
