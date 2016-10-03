<?php
$configuration = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Google Mapinator Configuration' ORDER BY configuration_group_id ASC;");
if ($configuration->RecordCount() > 0) {
  while (!$configuration->EOF) {
    $db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = " . $configuration->fields['configuration_group_id'] . ";");
    $db->Execute("DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_id = " . $configuration->fields['configuration_group_id'] . ";");
    $configuration->MoveNext();
  }
}
#$db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_group_id = 0;");
$db->Execute("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = '';");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('Google Mapinator Configuration', 'Set Google Mapinator Options', '1', '1');");
$configuration_group_id = $db->Insert_ID();

$db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = " . $configuration_group_id . " WHERE configuration_group_id = " . $configuration_group_id . ";");

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
  ('Module version', 'GOOGLEMAP_MODULE_VERSION', '0.13 10-01-2012', 'Zen4All', " . $configuration_group_id . ", 10, NOW(), NOW(), NULL, 'trim('),
  ('Information window', 'GOOGLEMAP_STORE_INFORMATION', 'Addittional information goes here', 'This information will be displayed on the google map page', " . $configuration_group_id . ", 11, NOW(), NOW(), NULL, 'zen_cfg_textarea('),
  ('Marker title', 'GOOGLEMAP_MARKER_TITLE', 'The store name goes here', 'This information will be displayed as the Google map marker title', " . $configuration_group_id . ", 12, NOW(), NOW(), NULL, 'zen_cfg_textarea('),
  ('Google Maps API Key', 'GOOGLEMAP_KEY', 'AIzaSyArMvuuEKqEf-cRbfk9hpeUI0hDx9CgSeg', 'The Google Maps API key can be obtained  from <a href=\"https://developers.google.com/maps/documentation/javascript/\" target=\"_blank\">Google</a>', " . $configuration_group_id . ", 13, NOW(), NOW(), NULL, NULL),
  ('Google Maps Latitude', 'GOOGLEMAP_LAT', '52.161512', 'enter your latitude (example 52.161512)', " . $configuration_group_id . ", 14, NOW(), NOW(), NULL, NULL),
  ('Google Maps Longitude', 'GOOGLEMAP_LNG', '5.367618', 'enter your longitude (example 5.367618)', " . $configuration_group_id . ", 15, NOW(), NOW(), NULL, NULL),
  ('Google Maps Zoom', 'GOOGLEMAP_ZOOM', '10', 'enter your default zoom level', " . $configuration_group_id . ", 16, NOW(), NOW(), NULL, NULL),
  ('Google Map Type Control', 'GOOGLEMAP_MAPTYPE', 'ROADMAP', 'Sets the default map type', " . $configuration_group_id . ", 17, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'ROADMAP\', \'SATELLITE\',\'HYBRID\',\'TERRAIN\'),');");