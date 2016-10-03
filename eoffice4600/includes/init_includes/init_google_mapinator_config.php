<?php
  if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
  }
  $zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
  // add upgrade script
  if (defined('GOOGLEMAP_MODULE_VERSION')) { // does not exist prior to v0.13
    $gm_version = GOOGLEMAP_MODULE_VERSION;
    while ($gm_version != '1.1') {
      switch($gm_version) {
        case '0.13 10-01-2012':
          // perform upgrade
          if (file_exists(DIR_WS_INCLUDES . 'installers/google_mapinator/1_0.php')) {
            include_once(DIR_WS_INCLUDES . 'installers/google_mapinator/1_0.php');
            $messageStack->add('Updated Google Mapinator to v1.0', 'success');
            $gm_version = '1.0';
          }
          break;
        case '1.0 16-03-2012':
          // perform upgrade
          if (file_exists(DIR_WS_INCLUDES . 'installers/google_mapinator/1_1.php')) {
            include_once(DIR_WS_INCLUDES . 'installers/google_mapinator/1_1.php');
            $messageStack->add('Updated Google Mapinator to v1.1', 'success');
            $gm_version = '1.1';
          }
          break;                              
        default:
          $gm_version = '1.1';
          // break all the loops
          break 2;      
      }
    }
  } else {
    // begin update to version 1.1
    // do a new install
    if (file_exists(DIR_WS_INCLUDES . 'installers/google_mapinator/new_install.php')) {
      include_once(DIR_WS_INCLUDES . 'installers/google_mapinator/new_install.php');
      $messageStack->add('Added Google Mapinator Configuration', 'success');
    } else {
      $messageStack->add('New installation file missing, please make sure you have uploaded all files in the package.', 'error');
    }
  }
  
  if ($zc150) { // continue Zen Cart 1.5.0
    // add configuration menu
    if (!zen_page_key_exists('Googlemapinator')) {
      $configuration = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'GOOGLEMAP_MODULE_VERSION' LIMIT 1;");
      $configuration_group_id = $configuration->fields['configuration_group_id'];
      if ((int)$configuration_group_id > 0) {
        zen_register_admin_page('Googlemapinator',
                                'BOX_CONFIGURATION_GOOGLEMAPINATOR', 
                                'FILENAME_CONFIGURATION',
                                'gID=' . $configuration_group_id, 
                                'configuration', 
                                'Y',
                                $configuration_group_id);
          
        $messageStack->add('Enabled Google Mapinator Configuration menu.', 'success');
      }
    }
  }