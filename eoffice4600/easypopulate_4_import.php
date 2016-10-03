<?php
// $Id: easypopulate_4_import.php, v4.0.28 01-03-2015 mc12345678 $

// BEGIN: Data Import Module
if ( isset($_GET['import']) ) {
	$time_start = microtime(true); // benchmarking
	$display_output .= EASYPOPULATE_4_DISPLAY_HEADING;

	$file = array('name' => $_GET['import']);
	$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_LOCAL_FILE_SPEC, $file['name']);
	
	$ep_update_count = 0; // product records updated 
	$ep_import_count = 0; // new products records imported
	$ep_error_count = 0; // errors detected during import
	$ep_warning_count = 0; // warning detected during import

	// When updating products info, these values are used for exisint data
	// This allows a reduced number of columns to be used on updates 
	// otherwise these would have to be exported/imported every time
	// this ONLY applies to when a column is MISSING
	$default_these = array();
        $default_these[] = 'v_products_id';
	$default_these[] = 'v_products_image';
	$default_these[] = 'v_products_type';
	$default_these[] = 'v_categories_id';
	$default_these[] = 'v_products_price';
  if (count($custom_fields) > 0) {
		foreach ($custom_fields as $field) {
			$filelayout[] = 'v_'.$field;
		}
	}
	$default_these[] = 'v_products_quantity';
	$default_these[] = 'v_products_weight';
	$default_these[] = 'v_products_discount_type';
	$default_these[] = 'v_products_discount_type_from';
	$default_these[] = 'v_product_is_call';
	$default_these[] = 'v_products_sort_order';
	$default_these[] = 'v_products_quantity_order_min';
	$default_these[] = 'v_products_quantity_order_units';
	$default_these[] = 'v_products_priced_by_attribute'; // 4-30-2012
	$default_these[] = 'v_product_is_always_free_shipping'; // 4-30-2012
	$default_these[] = 'v_date_added';
	$default_these[] = 'v_date_avail'; // chadd - this should default to null not "zero" or system date
	$default_these[] = 'v_instock';
	$default_these[] = 'v_tax_class_title';
	$default_these[] = 'v_manufacturers_name';
	$default_these[] = 'v_manufacturers_id';
	$default_these[] = 'v_products_status'; // added by chadd so that de-activated products are not reactivated when the column is missing
	// metatags switches also need to be pulled, 11-08-2011
	$default_these[] = 'v_metatags_products_name_status';
	$default_these[] = 'v_metatags_title_status';
	$default_these[] = 'v_metatags_model_status';
	$default_these[] = 'v_metatags_price_status';
	$default_these[] = 'v_metatags_title_tagline_status';

  $xsell_master_array = array();
  
	$file_location = DIR_FS_CATALOG.$tempdir.$file['name'];
	// Error Checking
	if (!file_exists($file_location)) {
		$display_output .='<font color="red"><b>ERROR: Import file does not exist:'.$file_location.'</b></font><br/>';
	} else if ( !($handle = fopen($file_location, "r"))) {
		$display_output .= '<font color="red"><b>ERROR: Cannot open import file:'.$file_location.'</b></font><br/>';
	}
	
	// Read Column Headers
	if ($raw_headers = @fgetcsv($handle, 0, $csv_delimiter, $csv_enclosure)) {
/*		$header_search = array("ARTIST","TITLE","FORMAT","LABEL",
			"CATALOG_NUMBER","UPC","PRICE","RETAIL",
			"WHOLESALE",	"GENRE","RELEASE_DATE","EXCLUSIVE",
			"WEIGHT","QTY","DISPLAY ON SITE","IMAGE",
			"DESCRIPTION", "TERRITORY","TRACKLISTING");
		
		$header_replace = array("v_artists_name","v_products_name_1","v_categories_name_1","v_record_company_name",
			"v_products_model","v_products_upc","v_products_price",	"v_products_group_a_price",
			"v_products_group_b_price","v_music_genre_name","v_date_avail","v_products_exclusive",
			"v_products_weight","v_products_quantity","v_status","v_products_image",
			"v_products_description_1", "v_territory","v_tracklisting");

		$raw_headers = str_replace($header_search, $header_replace, $raw_headers);
*/
		$filelayout = array_flip($raw_headers);
	

if ( ( strtolower(substr($file['name'],0,15)) <> "categorymeta-ep") && ( strtolower(substr($file['name'],0,7)) <> "attrib-") && ($ep_4_SBAEnabled != false ? ( strtolower(substr($file['name'],0,4)) <> "sba-") : true )) { //  temporary solution here... 12-06-2010
	
	// Main IMPORT loop For Product Related Data. v_products_id is the main key
	while ($items = fgetcsv($handle, 0, $csv_delimiter, $csv_enclosure)) { // read 1 line of data

		// bug fix 5-10-2012: when adding/updating a mix of old and new products and missing certain columns, 
		// an exising product's info is being put into a subsquently new product.
		// So, first clear old values...
		foreach ($default_these as $thisvar) {
			$$thisvar = '';
		}	

		// now do a query to get the record's current contents
		// chadd - 12-14-2010 - redefining this variable everytime it loops must be very inefficient! must be a better way!
		$sql = 'SELECT
			p.products_id					as v_products_id,
			p.products_type					as v_products_type,
			p.products_model				as v_products_model,
			p.products_image				as v_products_image,
			p.products_price				as v_products_price,';
		if (count($custom_fields) > 0) {
			foreach ($custom_fields as $field) {
				$sql .= 'pef.'.$field.' as v_'.$field.',';
			}
		}		
		$sql .= 'p.products_weight			as v_products_weight,
			p.products_discount_type		as v_products_discount_type,
			p.products_discount_type_from   as v_products_discount_type_from,
			p.product_is_call				as v_product_is_call,
			p.products_sort_order			as v_products_sort_order,
			p.products_quantity_order_min	as v_products_quantity_order_min,
			p.products_quantity_order_units	as v_products_quantity_order_units,
			p.products_priced_by_attribute	as v_products_priced_by_attribute,
			p.product_is_always_free_shipping	as v_product_is_always_free_shipping,
			p.products_date_added			as v_date_added,
			p.products_date_available		as v_date_avail,
			p.products_tax_class_id			as v_tax_class_id,
			p.products_quantity				as v_products_quantity,
			p.products_status				as v_products_status,
			p.manufacturers_id				as v_manufacturers_id,
			p.metatags_products_name_status	as v_metatags_products_name_status,
			p.metatags_title_status			as v_metatags_title_status,
			p.metatags_model_status			as v_metatags_model_status,
			p.metatags_price_status			as v_metatags_price_status,
			p.metatags_title_tagline_status	as v_metatags_title_tagline_status,
			subc.categories_id				as v_categories_id
			FROM '.
			TABLE_PRODUCTS.' as p,'.
			TABLE_CATEGORIES.' as subc,'.
			'product_extra_fields as pef,'.
			TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
			WHERE
			p.products_id      = ptoc.products_id AND
			p.products_id      = pef.products_id AND
			p.products_model   = '".addslashes($items[$filelayout['v_products_model']])."' AND
			ptoc.categories_id = subc.categories_id";
		$result	= ep_4_query($sql);
		$product_is_new = true;
		
//============================================================================
		// this gets default values for current v_products_model
		// inputs: $items array (file data by column #); $filelayout array (headings by column #); 
		// $row (current TABLE_PRODUCTS data by heading name)
				while ( $row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result) )) { // chadd - this executes once?? why use while-loop??
			$product_is_new = false; // we found products_model in database
			// Get current products descriptions and categories for this model from database
			// $row at present consists of current product data for above fields only (in $sql)

			// since we have a row, the item already exists.
			// let's check and delete it if requested   
			// v_status == 9 is a delete request  
			if ($items[$filelayout['v_status']] == 9) {
				$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_DELETED, $items[$filelayout['v_products_model']]);
				ep_4_remove_product($items[$filelayout['v_products_model']]);
				continue 2; // short circuit - loop to next record
			}
			
			// Create variables and assign default values for each language products name, description, url and optional short description
			foreach ($langcode as $key => $lang) {
				$sql2 = 'SELECT * FROM '.TABLE_PRODUCTS_DESCRIPTION.' WHERE products_id = '.$row['v_products_id'].' AND language_id = '.$lang['id'];
				$result2 = ep_4_query($sql2);
						$row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result2));
				// create variables (v_products_name_1, v_products_name_2, etc. which corresponds to our column headers) and assign data
				$row['v_products_name_'.$lang['id']] = ep_4_curly_quotes($row2['products_name']);
				
				// utf-8 conversion of smart-quotes, em-dash, and ellipsis
/*				$text = $row2['products_description'];
				$text = str_replace(
 					array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
 					array("'", "'", '"', '"', '-', '--', '...'),  $text);
				$row['v_products_description_'.$lang['id']] = $text; // description assigned				
*/
				$row['v_products_description_'.$lang['id']] = ep_4_curly_quotes($row2['products_description']); // description assigned				
				
				//$row['v_products_description_'.$lang['id']] = $row2['products_description']; // description assigned
				// if short descriptions exist
				if ($ep_supported_mods['psd'] == true) {
					$row['v_products_short_desc_'.$lang['id']] = ep_4_curly_quotes($row2['products_short_desc']);
				}
				$row['v_products_url_'.$lang['id']] = $row2['products_url']; // url assigned
			}
			
			// Default values for manufacturers name if exist
			// Note: need to test for '0' and NULL for best compatibility with older version of EP that set blank manufacturers to NULL
			// I find it very strange that the Manufacturer's Name is NOT multi-lingual, but he URL IS!
			if (($row['v_manufacturers_id'] != '0') && ($row['v_manufacturers_id'] != '') ) { // if 0, no manufacturer set
				$sql2 = 'SELECT manufacturers_name FROM '.TABLE_MANUFACTURERS.' WHERE manufacturers_id = '.$row['v_manufacturers_id'];
				$result2 = ep_4_query($sql2);
						$row2 = ($ep_uses_mysqli ? mysqli_fetch_array($result2) : mysql_fetch_array($result2));
				$row['v_manufacturers_name'] = $row2['manufacturers_name']; 
					} else {
				$row['v_manufacturers_name'] = '';  // added by chadd 4-7-09 - default name to blank
			}
			
			// Get tax info for this product
			// We check the value of tax class and title instead of the id
			// Then we add the tax to price if $price_with_tax is set to true
			$row_tax_multiplier = ep_4_get_tax_class_rate($row['v_tax_class_id']);
			$row['v_tax_class_title'] = zen_get_tax_class_title($row['v_tax_class_id']);
			if ($price_with_tax) {
				$row['v_products_price'] = round($row['v_products_price'] + ($row['v_products_price'] * $row_tax_multiplier / 100),2);
			}
			
			// $$thisvar creates a variable named $thisvar and sets the value to $row value ($v_products_price = $row['v_products_price'];), 
			// which is the existing value for these fields in the database before importing the updated information
			foreach ($default_these as $thisvar) {
				$$thisvar = $row[$thisvar];
			}
		} // while ( $row = mysql_fetch_array($result) )
//============================================================================
	
		// basic error checking
		
		// inputs: $items; $filelayout; $product_is_new
		// chadd - this first condition cannot exist since we short-circuited on delete above
		if ($items[$filelayout['v_status']] == 9 && zen_not_null($items[$filelayout['v_products_model']])) {
			// cannot delete product that is not found
			$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_DELETE_NOT_FOUND, $items[$filelayout['v_products_model']]);
			continue;
		}
		
		// NEW products must have a 'categories_name_'.$lang['id'] column header, else error out
		if ($product_is_new == true) {
			if ( zen_not_null($items[$filelayout['v_products_model']]) ) { // must have products_model
				// new products must have a categories_name to be added to the store.
				$categories_name_exists = false; // assume no column defined
				foreach ($langcode as $key => $lang) {
					// test column headers for each language
					if (zen_not_null(trim($items[$filelayout['v_categories_name_'.$lang['id']]])) ) { // import column found
						$categories_name_exists = true;
					}
				}
				if ( !$categories_name_exists ) {
					// let's skip this new product without a master category..
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_CATEGORY_NOT_FOUND, $items[$filelayout['v_products_model']], ' new');
					$ep_error_count++;
					continue; // error, loop to next record
				}
			} else {
				// minimum test for new product - model(already tested below), name, price, category, taxclass(?), status (defaults to active)
				// to add
			}
		} else { // Product Exists
			// I don't see why this is necessary
			/*		
			if (!zen_not_null(trim($items[$filelayout['v_categories_name_1']])) && isset($filelayout['v_categories_name_1'])) {
				// let's skip this existing product without a master category but has the column heading
				// or should we just update it to result of $row (it's current category..)??
				$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_CATEGORY_NOT_FOUND, $items[$filelayout['v_products_model']], '');
				foreach ($items as $col => $summary) {
					if ($col == $filelayout['v_products_model']) continue;
					$display_output .= print_el_4($summary);
				}
				continue; // error, loop to next record
			}
			*/
			
		} // End data checking

        //**************************************************************************
        //*************** DEAL WITH MULIPLE CATEGORIES******************************
        //**************************************************************************
        $additional_categories = explode('*', $items[$filelayout['v_categories_name_1']]);
        $items[$filelayout['v_categories_name_1']] = $additional_categories[0];



		// Assign new values, i.e. $v_products_model = new import value over writing existing data pulled above
		// This loop goes through all the fields in the import file and sets each corresponding variable.
		// Variables not set here are either set in the loop above for existing product records
		// $key is column heading name, $value is column number for the heading..
		foreach ($filelayout as $key => $value) {
			$$key = $items[$value];
		}
	
		// chadd - 12-13-2010 - this should allow you to update descriptions only for any given language of the import file!
		// cycle through all defined language codes, but only update those languages defined in the import file
		// Note 11-08-2011: I may remove the "smart_tags_4" function for better performance.
		foreach ($langcode as $lang) {
			$l_id = $lang['id'];
			// products meta tags
			if ( isset($filelayout['v_metatags_title_'.$l_id ]) ) { 
				$v_metatags_title[$l_id] = ep_4_curly_quotes($items[$filelayout['v_metatags_title_'.$l_id]]);
			}
			if ( isset($filelayout['v_metatags_keywords_'.$l_id ]) ) { 
				$v_metatags_keywords[$l_id] = ep_4_curly_quotes($items[$filelayout['v_metatags_keywords_'.$l_id]]);
			}
			if ( isset($filelayout['v_metatags_description_'.$l_id ]) ) { 
				$v_metatags_description[$l_id] = ep_4_curly_quotes($items[$filelayout['v_metatags_description_'.$l_id]]);
			}
			
			// products name, description, url, and optional short description
			// smart_tags_4 ... removed - chadd 11-18-2011 - will look into this feature at a future date
			// chadd 12-09-2011 - added some error checking on field lengths
			// 4-10-2012 - changed to handle name, description and url independently
			if (isset($filelayout['v_products_name_'.$l_id ])) { // do for each language in our upload file if exist
				// check products name length and display warning on error, but still process record
				$v_products_name[$l_id] = ep_4_curly_quotes($items[$filelayout['v_products_name_'.$l_id]]);
				if (mb_strlen($v_products_name[$l_id]) > $products_name_max_len) { 
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_NAME_LONG, $v_products_model, $v_products_name[$l_id], $products_name_max_len);
					$ep_warning_count++;
				}
			} else { // column doesn't exist in the IMPORT file
				// and product is new
				if ($product_is_new) {
					$v_products_name[$l_id] = "";
				}
			}
			if (isset($filelayout['v_products_description_'.$l_id ])) { // do for each language in our upload file if exist
				// utf-8 conversion of smart-quotes, em-dash, en-dash, and ellipsis
				$v_products_description[$l_id] = ep_4_curly_quotes($items[$filelayout['v_products_description_'.$l_id]]);
				//if ($ep_supported_mods['psd'] == true) { // if short descriptions exist
//					$v_products_short_desc[$l_id] = ep_4_curly_quotes($items[$filelayout['v_products_short_desc_'.$l_id]]);
//				}
			} else { // column doesn't exist in the IMPORT file
				// and product is new
				if ($product_is_new) {
					$v_products_description[$l_id] = "";
					// if short descriptions exist
					if ($ep_supported_mods['psd'] == true) {
						$v_products_short_desc[$_id] = "";
					}
				}
			}
			if (isset($filelayout['v_products_url_'.$l_id])) { // do for each language in our upload file if exist
				$v_products_url[$l_id] = $items[$filelayout['v_products_url_'.$l_id]];
				// check products url length and display warning on error, but still process record
				if (mb_strlen($v_products_url[$l_id]) > $products_url_max_len) { 
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_URL_LONG, $v_products_model, $v_products_url[$l_id], $products_url_max_len);
					$ep_warning_count++;
				}
			} else { // column doesn't exist in the IMPORT file
				// and product is new
				if ($product_is_new) {
					$v_products_url[$l_id] = "";	
				}
			}
		}

		// Note: 11-08-2011 this section needs careful review		
		// we get the tax_clas_id from the tax_title - from zencart??
		// on screen will still be displayed the tax_class_title instead of the id....
		if (isset($v_tax_class_title)) {
			$v_tax_class_id = ep_4_get_tax_title_class_id($v_tax_class_title);
		}
		// we check the tax rate of this tax_class_id
		$row_tax_multiplier = ep_4_get_tax_class_rate($v_tax_class_id);
	
		// And we recalculate price without the included tax...
		// Since it seems display is made before, the displayed price will still include tax
		// This is same problem for the tax_clas_id that display tax_class_title
		if ($price_with_tax == true) {
			$v_products_price = round( $v_products_price / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 4);
		}

		// if $v_products_quantity is null, set it to: 0
		if (trim($v_products_quantity) == '') {
			$v_products_quantity = 0; // new products are set to quanitity '0', updated products are set with default_these() values
		}

		// date variables - chadd ... these should really be products_date_available and products_date_added for clarity
		// date_avail is only set to show when out of stock items will be available, else it is NULL
		// 11-19-2010 fixed this bug where NULL wasn't being correctly set
		$v_date_avail = ($v_date_avail) ? "'".date("Y-m-d H:i:s",strtotime($v_date_avail))."'" : "NULL";
		
		// if products has been added before, do not change, else use current time stamp
		$v_date_added = ($v_date_added) ? "'".date("Y-m-d H:i:s",strtotime($v_date_added))."'" : "CURRENT_TIMESTAMP";

		// default the stock if they spec'd it or if it's blank
		// $v_db_status = '1'; // default to active
		$v_db_status = $v_products_status; // changed by chadd to database default value 3-30-09
		if ($v_status == '0') { // request deactivate this item
			$v_db_status = '0';
		}
		if ($v_status == '1') { // request activate this item
			$v_db_status = '1';
		}

		// deactivate zero quantity products, if configuration variable is true
		if (EASYPOPULATE_4_CONFIG_ZERO_QTY_INACTIVE == 'true' && $v_products_quantity == 0) {
			$v_db_status = '0';
		}

		// check for empty $v_products_image
		// if the v_products_image column exists and no image is set, then
		// apply the default "no image" image.
		if (trim($v_products_image) == '') {
			$v_products_image = PRODUCTS_IMAGE_NO_IMAGE;
		}

        // check size of $v_products_image
        if (mb_strlen($v_products_image) > $products_image_max_len) {
            $display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_IMAGE_LONG, $v_products_model, $products_model_max_len);
            $ep_error_count++;
            //continue; // short-circuit on error
        }
        
		// check size of v_products_model, loop on error
		if (mb_strlen($v_products_model) > $products_model_max_len) {
			$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_PRODUCTS_MODEL_LONG, $v_products_model, $products_model_max_len);
			$ep_error_count++;
			continue; // short-circuit on error
		}
		
		// BEGIN: Manufacturer's Name
		// convert the manufacturer's name into id's for the database
		if ( isset($v_manufacturers_name) && ($v_manufacturers_name != '') && (mb_strlen($v_manufacturers_name) <= $manufacturers_name_max_len) ) {
			$sql = "SELECT man.manufacturers_id AS manID FROM ".TABLE_MANUFACTURERS." AS man WHERE man.manufacturers_name = '".addslashes($v_manufacturers_name)."' LIMIT 1";
			$result = ep_4_query($sql);
					if ( $row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result) )) {
				$v_manufacturers_id = $row['manID']; // this id goes into the products table
			} else { // It is set to autoincrement, do not need to fetch max id
				$sql = "INSERT INTO ".TABLE_MANUFACTURERS." (manufacturers_name, date_added, last_modified)
					VALUES ('".addslashes($v_manufacturers_name)."', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
				$result = ep_4_query($sql);
            if ($result) {
              zen_record_admin_activity('Inserted manufacturer ' . addslashes($v_manufactureres_name) . ' via EP4.', 'info');
            }
						$v_manufacturers_id = ($ep_uses_mysqli ? mysqli_insert_id($db->link) : mysql_insert_id()); // id is auto_increment, so can use this function
				
				// BUG FIX: TABLE_MANUFACTURERS_INFO need an entry for each installed language! chadd 11-14-2011
				// This is not a complete fix, since we are not importing manufacturers_url
				foreach ($langcode as $lang) {
					$l_id = $lang['id'];
					$sql = "INSERT INTO ".TABLE_MANUFACTURERS_INFO." (manufacturers_id, languages_id, manufacturers_url)
						VALUES ('".addslashes($v_manufacturers_id) . "',".(int)$l_id.",'')"; // seems we are skipping manufacturers url
					$result = ep_4_query($sql);
              if ($result) {
                zen_record_admin_activity('Inserted manufacturers info ' . (int)$v_manufacturers_id . ' via EP4.', 'info');
              }
				}
			}
		} else { // $v_manufacturers_name == '' or name length violation
			if (mb_strlen($v_manufacturers_name) > $manufacturers_name_max_len) {
				$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_MANUFACTURER_NAME_LONG, $v_manufacturers_name, $manufacturers_name_max_len);
				$ep_error_count++;
				continue;
			}
			$v_manufacturers_id = 0; // chadd - zencart uses manufacturer's id = '0' for no assisgned manufacturer
		} // END: Manufacturer's Name
	
////////////////////////////////////////////////////////////////////////////////
//  KandS Data checking
////////////////////////////////////////////////////////////////////////////////
        //*************** IMAGE ************************************************
        $ext = strtolower(trim($v_products_image));
        $ext = substr($ext, strlen($ext)-4,4);
        //Check extension
        if($ext != '.jpg' && $ext != '.gif'){
            $v_products_image .= '.jpg';
					}
        //Check for illegal chars - at present only + is not allowed. This is a requirement of image handler
        if (ereg("\+", $v_products_image)) {
            $oifn = $v_products_image;
            $v_products_image = ereg_replace("\+", "-", $v_products_image);
            $display_output .= sprintf(EASYPOPULATE_DISPLAY_INVALID_IMAGENAME, $items[$filelayout['v_products_model']], $oifn, $items[$filelayout['v_products_image']]);
						}	
		
        //*************** DESCRIPTION ******************************************
        format_description($v_products_description[1] );

        //*************** PROCESS MY FIELDS WITH SPECIAL CHARS******************
        //Product variant
        if(strpos($v_product_variant,'*') > 0){
          $v_product_variant = str_replace('*', '<br />', $v_product_variant);
		}
		
        //*************** Set to NULL ******************************************
        if($v_now_price==''||$v_now_price==0)$v_now_price=NULL;
        if($v_sale_price==''||$v_sale_price==0)$v_sale_price=NULL;
        if($v_family_caption==''||$v_family_caption==0)$v_family_caption=NULL;
        if($v_manufacturers_id==''||$v_manufacturers_id==0)$v_manufacturers_id=NULL;
        if($v_products_weight==''||!zen_not_null($v_products_weight))$v_products_weight=0;
        if($v_box_quantity==''||!zen_not_null($v_box_quantity))$v_box_quantity=0;
        if($v_products_image=='')$v_products_image=PRODUCTS_IMAGE_NO_IMAGE;
        
		
////////////////////////////////////////////////////////////////////////////////    
// BEGIN: CATEGORIES2 ==========================================================

        $categories_name_exists = false; // assume no column defined
        foreach ($langcode as $key => $lang) {
            // test column headers for each language
            if (zen_not_null(trim($items[$filelayout['v_categories_name_'.$lang['id']]])) ) { // import column found
                $categories_name_exists = true; // at least one language column defined
            }
            }
        if ($categories_name_exists) { // we have at least 1 language column
            // chadd - 12-14-2010 - $categories_names_array[] has our category names
            $categories_delimiter = "*"; // add this to configuration variables
            // get all defined categories
            foreach ($langcode as $key => $lang) {
                // iso-8859-1
                // $categories_names_array[$lang['id']] = explode($categories_delimiter,$items[$filelayout['v_categories_name_'.$lang['id']]]); 
                // utf-8 
                $categories_names_array[$lang['id']] = mb_split('\x2A',$items[$filelayout['v_categories_name_'.$lang['id']]]); 
            
                // get the number of tokens in $categories_names_array[]
                $categories_count[$lang['id']] = count($categories_names_array[$lang['id']]);

            } // foreach
        }
        // start with first defined language... (does not have to be 1)
        $lid = $langcode[1]['id'];    
        $v_categories_name_var = 'v_categories_name_'.$lid; // $$v_categories_name_var >> $v_categories_name_1, $v_categories_name_2, etc.
        if (isset($$v_categories_name_var)) { // does column header exist?
            // start from the highest possible category and work our way down from the parent
            $v_categories_id = 0;
            //$theparent_id = 0; // 0 is top level parent
            // $categories_delimiter = "^"; // add this to configuration variables
            for ( $category_index=0; $category_index<$categories_count[$lid]; $category_index++ ) {
                $thiscategoryname = ep_4_curly_quotes($categories_names_array[$lid][$category_index]); // category name - 5-3-2012 added curly quote fix
                $sql = "SELECT categories_id FROM ".TABLE_CATEGORIES." WHERE categories_id = $thiscategoryname LIMIT 1";
                    $result = ep_4_query($sql);
                $row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result));
                if ( $row != '' ) { // category exists
                    $v_categories_id = $thiscategoryname;
                    //==================================================================================================================================
                    // Assign product to category 
                    //$result_incategory = ep_4_query('SELECT
//                        '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id,
//                        '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id
//                        FROM
//                        '.TABLE_PRODUCTS_TO_CATEGORIES.'
//                        WHERE
//                        '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id='.$v_products_id.' AND
//                        '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id='.$v_categories_id);
//                        if (($ep_uses_mysqli ? mysqli_num_rows($result_incategory) : mysql_num_rows($result_incategory)) == 0) { // nope, this is a new category for this product
//                            $res1 = ep_4_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id)
//                                VALUES ("'.$v_products_id.'", "'.$v_categories_id.'")');
//                            if ($res1) {
//                              zen_record_admin_activity('Product ' . (int)$v_products_id . ' copied as link to category ' . (int)$v_categories_id . ' via EP4.', 'info');
//                            }
//                        } else { // already in this category, nothing to do!
//                        }
                    //==================================================================================================================================
                } else { // otherwise add new category
                    // get next available categoies_id
                    $display_output .= "<br>Error: Category ".$thiscategoryname." undefined for item: ".$items[0];
                }
            } // ( $category_index=0; $category_index<$catego.....
        } // (isset($$v_categories_name_var))
// END: CATEGORIES2 ===============================================================================================           		
		
		// insert new, or update existing, product
if ($v_products_model != "") { // products_model exists!
	// First we check to see if this is a product in the current db.
	$result = ep_4_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE (products_model = '".addslashes($v_products_model)."') LIMIT 1");
			if (($ep_uses_mysqli ? mysqli_num_rows($result) : mysql_num_rows($result)) == 0)  { // new item, insert into products
				$v_date_added	= ($v_date_added == 'NULL') ? CURRENT_TIMESTAMP : $v_date_added;
				$sql			= "SHOW TABLE STATUS LIKE '".TABLE_PRODUCTS."'";
				$result			= ep_4_query($sql);
						$row			= ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result));
				$max_product_id = $row['Auto_increment'];
				if (!is_numeric($max_product_id) ) {
					$max_product_id = 1;
				}
				$v_products_id = $max_product_id;
					$v_products_type = 1; // 1 = standard product
				
				$query = "INSERT INTO ".TABLE_PRODUCTS." SET
					products_model					= '".addslashes($v_products_model)."',
					products_type     		        = '".(int)$v_products_type."',
					products_price					= '".$v_products_price."',";
                    
				$query .= "products_image			= '".addslashes($v_products_image)."',
					products_weight					= '".$v_products_weight."',
					products_discount_type          = '".$v_products_discount_type."',
					products_discount_type_from     = '".$v_products_discount_type_from."',
					product_is_call                 = '".$v_product_is_call."',
					products_sort_order             = '".$v_products_sort_order."',
					products_quantity_order_min     = '".$v_products_quantity_order_min."',
					products_quantity_order_units   = '".$v_products_quantity_order_units."',
					products_priced_by_attribute	= '".$v_products_priced_by_attribute."',
					product_is_always_free_shipping	= '".$v_product_is_always_free_shipping."',
					products_tax_class_id			= '".$v_tax_class_id."',
					products_date_available			= $v_date_avail, 
					products_date_added				= $v_date_added,
					products_last_modified			= CURRENT_TIMESTAMP,
					products_quantity				= '".$v_products_quantity."',
					master_categories_id			= '".$v_categories_id."',
					manufacturers_id				= '".$v_manufacturers_id."',
					products_status					= '".$v_db_status."',
					metatags_title_status			= '".$v_metatags_title_status."',
					metatags_products_name_status	= '".$v_metatags_products_name_status."',
					metatags_model_status			= '".$v_metatags_model_status."',
					metatags_price_status			= '".$v_metatags_price_status."',
					metatags_title_tagline_status	= '".$v_metatags_title_tagline_status."'";	
				$result = ep_4_query($query);
				if ($result == true) {
					// need to change to an log file, this is gobbling up memory! chadd 11-14-2011
              zen_record_admin_activity('New product ' . (int)$v_products_id . ' added via EP4.', 'info');
					if ($ep_feedback == true) {
						$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_NEW_PRODUCT, $v_products_model);
					}
					$ep_import_count++;
				} else {
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_NEW_PRODUCT_FAIL, $v_products_model);
					$ep_error_count++;
					continue; // new categories however have been created by now... Adding into product table needs to be 1st action?
				}
				// needs to go into log file chadd 11-14-2011
				if ($ep_feedback == true) {
					foreach ($items as $col => $summary) {
						if ($col == $filelayout['v_products_model']) continue;
						$display_output .= print_el_4($summary);
					}
				}
			} else { 
                ///////////////////////////////////////////////////////////////////////////
                // existing product, get the id from the query and update the product data
				// if date added is null, let's keep the existing date in db..
                ///////////////////////////////////////////////////////////////////////////
				$v_date_added = ($v_date_added == 'NULL') ? $row['v_date_added'] : $v_date_added; // if NULL, use date in db
				$v_date_added = zen_not_null($v_date_added) ? $v_date_added : CURRENT_TIMESTAMP; // if updating, but date added is null, we use today's date
						$row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result));
				$v_products_id = $row['products_id'];
						$row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result)); 
				// CHADD - why is master_categories_id not being set on update???
				$query = "UPDATE ".TABLE_PRODUCTS." SET
					products_price = '".$v_products_price."',";

				$query .= "products_image			= '".addslashes($v_products_image)."',
					products_weight					= '".$v_products_weight."',
					products_discount_type			= '".$v_products_discount_type."',
					products_discount_type_from		= '".$v_products_discount_type_from."',
					product_is_call					= '".$v_product_is_call."',
					products_sort_order				= '".$v_products_sort_order."',
					products_quantity_order_min		= '".$v_products_quantity_order_min."',
					products_quantity_order_units	= '".$v_products_quantity_order_units."',
					products_priced_by_attribute	= '".$v_products_priced_by_attribute."',
					product_is_always_free_shipping	= '".$v_product_is_always_free_shipping."',
					products_tax_class_id			= '".$v_tax_class_id."',
					products_date_available			= $v_date_avail,
					products_date_added				= $v_date_added,
					products_last_modified			= CURRENT_TIMESTAMP,
					products_quantity				= '".$v_products_quantity."',
					manufacturers_id				= '".$v_manufacturers_id."',
                    master_categories_id            = '".$v_categories_id."',
					products_status					= '".$v_db_status."',
					metatags_title_status			= '".$v_metatags_title_status."',
					metatags_products_name_status	= '".$v_metatags_products_name_status."',
					metatags_model_status			= '".$v_metatags_model_status."',
					metatags_price_status			= '".$v_metatags_price_status."',
					metatags_title_tagline_status	= '".$v_metatags_title_tagline_status."'".
					" WHERE (products_id = '".$v_products_id."')";
				
				$result = ep_4_query($query);
				if ($result == true) {
              zen_record_admin_activity('Updated product ' . (int)$v_products_id . ' via EP4.', 'info');
					// needs to go into a log file chadd 11-14-2011
					if ($ep_feedback == true) {
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_UPDATE_PRODUCT, $v_products_model);
						foreach ($items as $col => $summary) {
							if ($col == $filelayout['v_products_model']) continue;
							$display_output .= print_el_4($summary);
						}
					}				
					$ep_update_count++;				
				} else {
					$display_output .= sprintf(EASYPOPULATE_4_DISPLAY_RESULT_UPDATE_PRODUCT_FAIL, $v_products_model);
					$ep_error_count++;
				}
			}

            if (isset($v_categories_id)){
                //delete all entries in the products to categories table for the product id
                $sql = "delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $v_products_id . "'";
                ep_4_query($sql);
                $display_output .= 'In categories ';

                foreach($additional_categories as $key => $value){
                    if($value!=""){
                        $res1 = ep_4_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id) VALUES ("' . $v_products_id . '", "' . $value . '")');
                        $display_output .= ', '. $value;
                    }
                }
            }
            
     
		
            ////////////////////////////////////////////////////////////////////////////
            //                                                                        //
            //    INSERT NEW / UPDATE    PRODUCTS EXTRA FIELDS                        //
            //                                                                        //
            ////////////////////////////////////////////////////////////////////////////
			
            $e_sql = "SELECT products_id FROM product_extra_fields WHERE products_id = $v_products_id";
             $result = ep_4_query($e_sql);
            $row = ($ep_uses_mysqli ? mysqli_fetch_array($result) : mysql_fetch_array($result));
            if ($row==0) {

            if($v_now_price=='')$v_now_price=NULL;

            $e_query = "INSERT INTO product_extra_fields (
            products_id, manufactures_code, product_style, product_finish, product_material, product_colour,
            bulbs_qty, bulbs_watts, bulbs_type, bulbs_cap, bulbs_included,
            dimensions_height, dimensions_width, dimensions_depth, product_dia,
            product_min_drop, product_max_drop, product_length, product_recess,
            product_nonreturn, ip_rating, product_voltage, product_guarantee,
            product_options, product_safety_class, product_transformer, product_driver,
            product_cut_out, product_surface_temp, product_cable, product_carriage,
            product_statements, product_tilt, product_variant, product_priority,
            family_caption, now_price, show_price, rrp, rate_1, rate_2, rate_3, bulbs_s1,
             info, web_price, multi_quantity, multi_price, lumens, colour_temp,
             energy_class, cri, hours, sale_price, trade_multi_price, trade_multi_quantity,
             bulb_finish, bulb_shape, bulb_dimmable
             ) VALUES (
              '".zen_db_input($v_products_id)."',
              '".zen_db_input($v_manufactures_code)."',
              '".zen_db_input($v_product_style)."',
              '".zen_db_input($v_product_finish)."',
              '".zen_db_input($v_product_material)."',
              '".zen_db_input($v_product_colour)."',
              '".zen_db_input($v_bulbs_qty)."',
              '".zen_db_input($v_bulbs_watage)."',
              '".zen_db_input($v_bulb_type)."',
              '".zen_db_input($v_bulb_cap)."',
              '".zen_db_input($v_bulb_inc)."',
              '".zen_db_input($v_dimensions_height)."',
              '".zen_db_input($v_dimensions_width)."',
              '".zen_db_input($v_dimensions_depth)."',
              '".zen_db_input($v_product_dia)."',
              '".zen_db_input($v_product_min_drop)."',
              '".zen_db_input($v_product_max_drop)."',
              '".zen_db_input($v_product_length)."',
              '".zen_db_input($v_product_recess)."',
              '".zen_db_input($v_product_nonreturn)."',
              '".zen_db_input($v_ip_rating)."',
              '".zen_db_input($v_product_voltage)."',
              '".zen_db_input($v_product_guarantee)."',
              '".zen_db_input($v_product_options)."',
              '".zen_db_input($v_product_saftey_class)."',
              '".zen_db_input($v_product_transformer)."',
              '".zen_db_input($v_product_driver)."',
              '".zen_db_input($v_product_cut_out)."',
              '".zen_db_input($v_product_surface_temp)."',
              '".zen_db_input($v_product_cable)."',
              '".zen_db_input($v_product_carriage)."',
              '".zen_db_input($v_product_statements)."',
              '".zen_db_input($v_product_tilt)."',
              '".zen_db_input($v_product_variant)."',
              '".zen_db_input($v_priortity)."',
              '".zen_db_input($v_family_caption)."',
              '".zen_db_input($v_now_price)."',
              '".zen_db_input($v_show_price)."',
              '".zen_db_input($v_rrp)."',
              '".zen_db_input($v_rate_1)."',
              '".zen_db_input($v_rate_2)."',
              '".zen_db_input($v_rate_3)."',
              '".zen_db_input($v_bulbs_s1)."',
              '".zen_db_input($v_info)."',
              '".zen_db_input($v_web_price)."',
              '".zen_db_input($v_multi_quantity)."',
              '".zen_db_input($v_multi_price)."',
              '".zen_db_input($v_lumens)."',
              '".zen_db_input($v_colour_temp)."',
              '".zen_db_input($v_energy_class)."',
              '".zen_db_input($v_cri)."',
              '".zen_db_input($v_hours)."',
              '".zen_db_input($v_sale_price)."',
              '".zen_db_input($v_trade_multi_price)."',
              '".zen_db_input($v_trade_multi_quantity)."',
              '".zen_db_input($v_bulb_finish)."',
              '".zen_db_input($v_bulb_shape)."',
              '".zen_db_input($v_bulb_dimmable)."'
              )";
              //insert extra fields
              $result = ep_4_query($e_query);
            }else{
            //********************************************
            //Update record in products extra fields
            //********************************************

            if($v_now_price=='')$v_now_price=NULL;
            $e_query = 'UPDATE product_extra_fields SET '.
              'manufactures_code ="'.     zen_db_input($v_manufactures_code) . '"' .
              ', product_style ="' .      zen_db_input($v_product_style) . '"' .
              ', product_finish ="' .     zen_db_input($v_product_finish) . '"' .
              ', product_material ="' .   zen_db_input($v_product_material) . '"' .
              ', product_colour ="' .     zen_db_input($v_product_colour) . '"' .
              ', bulbs_qty ="' .          zen_db_input($v_bulbs_qty) . '"' .
              ', bulbs_watts ="' .        zen_db_input($v_bulbs_watage)  . '"' .
              ', bulbs_type ="' .         zen_db_input($v_bulb_type) . '"' .
              ', bulbs_cap ="' .          zen_db_input($v_bulb_cap) . '"' .
              ', bulbs_included ="' .     zen_db_input($v_bulbs_inc) . '"' .
              ', dimensions_height ="' .  zen_db_input($v_dimensions_height) . '"' .
              ', dimensions_width ="' .   zen_db_input($v_dimensions_width) . '"' .
              ', dimensions_depth ="' .   zen_db_input($v_dimensions_depth) . '"' .
              ', product_dia ="' .        zen_db_input($v_product_dia) . '"' .
              ', product_min_drop ="' .   zen_db_input($v_product_min_drop) . '"' .
              ', product_max_drop ="' .   zen_db_input($v_product_max_drop) . '"' .
              ', product_length ="' .     zen_db_input($v_product_length) . '"' .
              ', product_recess ="' .     zen_db_input($v_product_recess) . '"' .
              ', product_nonreturn ="' .  zen_db_input($v_product_nonreturnable) . '"' .
              ', ip_rating ="' .          zen_db_input($v_ip_rating) . '"' .
              ', product_voltage ="' .    zen_db_input($v_product_voltage) . '"' .
              ', product_guarantee ="' .  zen_db_input($v_product_guarantee)  . '"' .
              ', product_options ="' .    zen_db_input($v_product_materials) . '"' .
              ', product_safety_class ="' . zen_db_input($v_product_saftey_class) . '"' .
              ', product_transformer ="' . zen_db_input($v_product_transformer) . '"' .
              ', product_driver ="' .     zen_db_input($v_product_driver) . '"' .
              ', product_cut_out ="' .    zen_db_input($v_product_cut_out) . '"' .
              ', product_surface_temp ="' . zen_db_input($v_product_surface_temp) . '"' .
              ', product_cable ="' .      zen_db_input($v_product_cable) . '"' .
              ', product_tilt ="' .       zen_db_input($v_product_tilt) . '"' .
              ', product_variant ="' .    zen_db_input($v_product_variant) . '"' .
              ', product_priority ="' .   zen_db_input($v_priortity) . '"' .
              ', family_caption ="' .     zen_db_input($v_family_caption) . '"' .
              ', now_price ="' .          zen_db_input($v_now_price) . '"' .
              ', product_options ="' .    zen_db_input($v_product_options) . '"' .
              ', product_carriage ="' .   zen_db_input($v_product_carrage) . '"' .
              ', product_statements ="' . zen_db_input($v_product_statements) . '"' .
              ', show_price ="' .         zen_db_input($v_show_price) . '"' .
              ', rrp ="' .                zen_db_input($v_rrp) . '"' .
              ', rate_1 ="' .             zen_db_input($v_rate_1) . '"' .
              ', rate_2 ="' .             zen_db_input($v_rate_2) . '"' .
              ', rate_3 ="' .             zen_db_input($v_rate_3) . '"' .
              ', bulbs_s1 ="' .           zen_db_input($v_bulbs_s1) . '"' .
              ', info ="' .               zen_db_input($v_info) . '"' .
              ', web_price ="' .          zen_db_input($v_web_price) . '"' .
              ', multi_quantity ="' .     zen_db_input($v_multi_quantity) . '"' .
              ', multi_price ="' .        zen_db_input($v_multi_price) . '"' .
              ', lumens ="' .             zen_db_input($v_lumens) . '"' .
              ', colour_temp ="' .        zen_db_input($v_colour_temp) . '"' .
              ', energy_class ="' .       zen_db_input($v_energy_class) . '"' .
              ', cri ="' .                zen_db_input($v_cri) . '"' .
              ', hours ="' .              zen_db_input($v_hours) . '"' .
              ', sale_price ="' .         zen_db_input($v_sale_price) . '"'.
              ', trade_multi_price ="' .  zen_db_input($v_trade_multi_price) . '"'.
              ', trade_multi_quantity ="'.zen_db_input($v_trade_multi_quantity) . '"'.
              ', bulb_finish ="' .        zen_db_input($v_bulb_finish) . '"'.
              ', bulb_shape ="' .         zen_db_input($v_bulb_shape) . '"'.
              ', bulb_dimmable ="' .      zen_db_input($v_bulb_dimmable) . '"
                WHERE
                  (products_id = "'. $v_products_id . '")';

              $result = ep_4_query($e_query);
			
}	
			
			
            // BEGIN: Products Descriptions
            // the following is common in both the updating an existing product and creating a new product // mc12345678 updated to allow omission of v_products_description in the import file.
            if (isset($v_products_name)) {
                foreach ($v_products_name as $key => $name) {
                    $sql = "SELECT * FROM " . TABLE_PRODUCTS_DESCRIPTION . " WHERE
                        products_id = " . $v_products_id . " AND
                        language_id = " . $key;
                    $result = ep_4_query($sql);

					if (($ep_uses_mysqli ? mysqli_num_rows($result) : mysql_num_rows($result)) == 0) {
                        $sql = "INSERT INTO " . TABLE_PRODUCTS_DESCRIPTION . " (
                            products_id,
                            language_id,
                            products_name, " .
                            ((isset($filelayout['v_products_description_' . $key]) || ( isset($filelayout['v_products_description_' . $key]) && $product_is_new) ) ? " products_description," : "");
                        if ($ep_supported_mods['psd'] == true) {
                            $sql .= " products_short_desc,";
                        }
                        $sql .= " products_url )
                            VALUES (
                            '" . $v_products_id . "',
                            " . $key . ",
                            '" . addslashes($name) . "', " .
                            ((isset($filelayout['v_products_description_' . $key]) || ( isset($filelayout['v_products_description_' . $key]) && $product_is_new) ) ? "'" . addslashes($v_products_description[$key]) . "'," : "");
                        if ($ep_supported_mods['psd'] == true) {
                            $sql .= "'" . addslashes($v_products_short_desc[$key]) . "',";
                        }
                        $sql .= "'" . addslashes($v_products_url[$key]) . "')";
                        $result = ep_4_query($sql);
                        if ($result) {
                          zen_record_admin_activity('New product ' . (int)$v_products_id . ' description added via EP4.', 'info');
                        }
                    } else { // already in the description, update it
                        $sql = "UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
       products_name        ='".addslashes($name)."', " .
                            ((isset($filelayout['v_products_description_' . $key]) || ( isset($filelayout['v_products_description_' . $key]) && $product_is_new) ) ? "products_description ='" . addslashes($v_products_description[$key]) . "'," : "");
                        if ($ep_supported_mods['psd'] == true) {
                            $sql .= " products_short_desc = '" . addslashes($v_products_short_desc[$key]) . "',";
                        }
                        $sql .= " products_url='" . addslashes($v_products_url[$key]) . "'
       WHERE products_id = '" . $v_products_id . "' AND language_id = '" . $key . "'";
                        $result = ep_4_query($sql);
                        if ($result) {
                          zen_record_admin_activity('Updated product ' . (int)$v_products_id . ' description via EP4.', 'info');
                        }
                    }
                }
            } // END: Products Descriptions End	


			
			/* Specials - if a null value in specials price, do not add or update. If price = 0, let's delete it */
			if (isset($v_specials_price) && zen_not_null($v_specials_price)) {
				if ($v_specials_price >= $v_products_price) {
					$specials_print .= sprintf(EASYPOPULATE_4_SPECIALS_PRICE_FAIL, $v_products_model, substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 10));
					// available function: zen_set_specials_status($specials_id, $status)
					// could alternatively make status inactive, and still upload..
					continue;
				}
				// column is in upload file, and price is in field (not empty)
				// if null (set further above), set forever, else get raw date
				$has_specials = true;
				
				// using new date functions - chadd
				$v_specials_date_avail = ($v_specials_date_avail == true) ? date("Y-m-d H:i:s",strtotime($v_specials_date_avail)) : "0001-01-01";
				$v_specials_expires_date = ($v_specials_expires_date == true) ? date("Y-m-d H:i:s",strtotime($v_specials_expires_date)) : "0001-01-01";
				
				// Check if this product already has a special
				$special = ep_4_query("SELECT products_id FROM ".TABLE_SPECIALS." WHERE products_id = ".$v_products_id);
																
					if (($ep_uses_mysqli ? mysqli_num_rows($special) : mysql_num_rows($special)) == 0) { // not in db
					if ($v_specials_price == '0') { // delete requested, but is not a special
						$specials_print .= sprintf(EASYPOPULATE_4_SPECIALS_DELETE_FAIL, $v_products_model, substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 10));
						continue;
					}
					// insert new into specials
					$sql = "INSERT INTO ".TABLE_SPECIALS."
						(products_id,
						specials_new_products_price,
						specials_date_added,
						specials_date_available,
						expires_date,
						status)
						VALUES (
						'".(int)$v_products_id."',
						'".$v_specials_price."',
						now(),
						'".$v_specials_date_avail."',
						'".$v_specials_expires_date."',
						'1')";
					$result = ep_4_query($sql);
            if ($result) {
              zen_record_admin_activity('Inserted special ' . (int)$v_products_id . ' via EP4.', 'info');
            }
					$specials_print .= sprintf(EASYPOPULATE_4_SPECIALS_NEW, $v_products_model, substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 10), $v_products_price , $v_specials_price);
				} else { // existing product
					if ($v_specials_price == '0') { // delete of existing requested
						$db->Execute("DELETE FROM ".TABLE_SPECIALS." WHERE products_id = '".(int)$v_products_id."'");
						$specials_print .= sprintf(EASYPOPULATE_4_SPECIALS_DELETE, $v_products_model);
						continue;
					}
					// just make an update
					$sql = "UPDATE ".TABLE_SPECIALS." SET
						specials_new_products_price	= '".$v_specials_price."',
						specials_last_modified		= now(),
						specials_date_available		= '".$v_specials_date_avail."',
						expires_date				= '".$v_specials_expires_date."',
						status						= '1'
						WHERE products_id			= '".(int)$v_products_id."'";
					$result = ep_4_query($sql);
          if ($result) {
            zen_record_admin_activity('Updated special ' . (int)$v_products_id . ' via EP4.', 'info');
          }
					$specials_print .= sprintf(EASYPOPULATE_4_SPECIALS_UPDATE, $v_products_model, substr(strip_tags($v_products_name[$epdlanguage_id]), 0, 10), $v_products_price , $v_specials_price);
				} // we still have our special here
			} // end specials for this product
			
			// this is a test chadd - 12-08-2011
			// why not just update price_sorter after each product?
			// better yet, why not ONLY call if pricing was updated
			// ALL these affect pricing: products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call
			zen_update_products_price_sorter($v_products_id);
      
      /////////////////////////////////////////////////////////////////////////////////////
      ////                                                                               //
      ////    Cross Sell - Store values for processing after the file loop has finished  //
      /////////////////////////////////////////////////////////////////////////////////////
      if($v_xsell != ''){                                                          //
        $xsell_master_array[$v_products_model]=zen_db_prepare_input($v_xsell);     //
      }                                                                            //
      ///////////////////////////////////////////////////////////////////////////////
			
		} else {
			// this record is missing the product_model
			$display_output .= EASYPOPULATE_4_DISPLAY_RESULT_NO_MODEL;
			foreach ($items as $col => $summary) {
				if ($col == $filelayout['v_products_model']) continue;
				$display_output .= print_el_4($summary);
			}
		} // end of row insertion code
	} // end of Main While Loop
  
    //////////////////////////////////////////////////////////////////////////////////////
    ////    Cross Sell - process                                                      ////
    /////////////////////////////////////////////////////////////////////////////////////

    //Delete any entries in xsell table for all items
    //  ob_implicit_flush(true);
    // ob_end_flush();
    foreach($xsell_master_array as $xOrgModel => $xvalues){
      $values = NULL;
      //echo 'D Loop'.$xOrgModel.'-----'.$xvalues.'<br />';
      $xvalues_array = explode(';',$xvalues);
      foreach($xvalues_array as $key => $xvalue){
        if(strpos($xvalue, '*')>0){
          ///wildcard in family code, look up family items and insert them all
          $c_xvalue = trim($xvalue, '*');
          $rs_family = $db->Execute("SELECT products_model FROM " . TABLE_PRODUCTS . " WHERE products_model LIKE '" . $c_xvalue . "%'");
          while(!$rs_family->EOF){
            $values[] = $rs_family->fields['products_model'];
            $rs_family->MoveNext();
          }
        }else{
          //No wildcard so just use the value as given
          $values[] = $xvalue;
        }
      }
      //Get the pID for the base model
      $xpid = ep_pID_mID($xOrgModel);
      if(is_array($values)){
        foreach($values as $value){
          //Get the pID for the item to xsell with base NOTE here $mId is not model id but product id
          $mId = ep_pID_mID($value);
    //echo 'Del Loop -'.$value.'<br />';
    //echo '.';
          //Check product to xsell with exists
          if($mId != NULL){
            //Query db to see if an entry for this xsell exists in the xsell table
            $result = $db->Execute("SELECT * FROM " . TABLE_PRODUCTS_XSELL . " WHERE (products_id = $xpid AND xsell_id = $mId) OR (products_id = $mId AND xsell_id = $xpid)");
            //$row =  mysql_fetch_array($result);
            if(!$result->EOF){
              //xsell exists in table so delete it
              $sql = "DELETE FROM " . TABLE_PRODUCTS_XSELL . " WHERE (products_id = $xpid  AND xsell_id = $mId )OR (products_id = $mId AND xsell_id = $xpid)";
    //echo 'Deleting - '.$sql.'<br />';
    //echo '.';
              $db->Execute($sql);
            }
          }
        }
      }
    }//end Delete
  //echo '<br />';
    //Now insert xsell values
    ob_implicit_flush(true);
    ob_end_flush();

    foreach($xsell_master_array as $xOrgModel => $xvalues){
      $values = NULL;
      //Get the pID for the base model
      $xpid = ep_pID_mID($xOrgModel);
      //Put list of xsell values into array
      //echo 'D Loop'.$xOrgModel.'-----'.$xvalues.'<br />';
      $xsell_array = explode(';', $xvalues);
      foreach($xsell_array as $value){
        $one_way = false;
        $values = array();
        //Check for one way indicator
        if(strpos($value, '!')>0){
          $one_way = true;
          $value = trim($value, '!');
        }
        if(strpos($value, '*')>0){
          ///wildcard in family code, look up family items and insert them all
          $value = trim($value, '*');
          $rs_family = $db->Execute("SELECT products_model FROM " . TABLE_PRODUCTS . " WHERE products_model LIKE '" . $value . "%'");
          while(!$rs_family->EOF){
            $values[] = $rs_family->fields['products_model'];
            $rs_family->MoveNext();
          }
          if(sizeof($values)==0){
            //No family found
             $display_output .= "|<span style='color:Red'><b> xsell fail No family $value found</b></span>";
          }
        }else{
          //No wildcard so just use the value as given
          $values[] = $value;
        }
        //Now loop through all the values, if it is not xselling to a family this will be only 1 item
        foreach($values as $value){
          //Get the pID for the item to xsell with base NOTE here $mId is not model id but product id
          $mId = ep_pID_mID($value);
          //echo 'Add '.$value .'('.$mId.') with '.$xOrgModel.'('.$xpid.')<br />';
          //echo '.';
          //Avoid xselling the base item with itself
          if($v_products_id == $mId)continue;
          //Check product to xsell with exists
          if($mId != NULL){
            //Check to ensure item has not already been added in this run
            $result = $db->Execute("SELECT * FROM " . TABLE_PRODUCTS_XSELL . " WHERE (products_id = $xpid AND xsell_id = $mId)");
            if($result->EOF){
               //Add xsell
               $sql_1 = "INSERT INTO " . TABLE_PRODUCTS_XSELL . " (products_id, xsell_id, sort_order) VALUES ($xpid, $mId, 1)";
              //echo $sql_1.'<br />';
              //echo '.';
              $db->Execute($sql_1);
              $xsup = 1;
              $display_output .= "$xOrgModel xsell with $value<br/>";
            }
            //If xsell is two way
            if(!$one_way){
              //Check to ensure item has not already been added in this run
              $result = $db->Execute("SELECT * FROM " . TABLE_PRODUCTS_XSELL . " WHERE (products_id = $mId AND xsell_id = $xpid)");
              if($result->EOF){
                  //Add xsell
                  $sql_2 = "INSERT INTO " . TABLE_PRODUCTS_XSELL . " (products_id, xsell_id, sort_order) VALUES ($mId, $xpid, 1)";
                  //echo $sql_2.'<br />';
                  //echo '.';
                  $db->Execute($sql_2);
                  $xsup++;
                  $display_output .= "$value xsell with $xOrgModel<br/>";
              }
            }
          }else{
            //No xsell item to add base item to
            $display_output .= "<span style='color:Red'><b>$xOrgModel xsell fail No product $value </b></span><br/>";
          }
        }//end foreach($values as $value)
        $display_output .="-------------------------<br/>";
      }//eol foreach($xsell_array as $value)

    }/////////////////////////////////////////////////////////////////////////
    ///////////////////////// eof xsell  /////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
            

	} // conditional IF statement
	
	$display_output .= '<h3>Finished Processing Import File</h3>';
	
		$display_output .= '<br/>Updated records: '.$ep_update_count;
		$display_output .= '<br/>New Imported records: '.$ep_import_count;
		$display_output .= '<br/>Errors Detected: '.$ep_error_count;
		$display_output .= '<br/>Warnings Detected: '.$ep_warning_count;
	
		$display_output .= '<br/>Memory Usage: '.memory_get_usage(); 
		$display_output .= '<br/>Memory Peak: '.memory_get_peak_usage();
	
	// benchmarking
	$time_end = microtime(true);
	$time = $time_end - $time_start;	
		$display_output .= '<br/>Execution Time: '. $time . ' seconds.';
}	
	
	// specials status = 0 if date_expires is past.
	// HEY!!! THIS ALSO CALLS zen_update_products_price_sorter($v_products_id); !!!!!!
	if ($has_specials == true) { // specials were in upload so check for expired specials
		zen_expire_specials();
	}
	if (($ep_warning_count > 0) || ($ep_error_count > 0)) {
	$messageStack->add("File Import Completed with issues.", 'warning');
	} else {
	$messageStack->add("File Import Completed.", 'success');
	}
} // END FILE UPLOADS
?>