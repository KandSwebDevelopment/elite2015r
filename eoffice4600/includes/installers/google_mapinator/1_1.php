<?php
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1.1' WHERE configuration_key = 'GOOGLEMAP_MODULE_VERSION' LIMIT 1;");
  $configuration = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Google Mapinator Configuration' LIMIT 1;");
  $configuration_group_id = $configuration->fields['configuration_group_id'];
  if ($configuration_group_id > 0) {
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
      ('Map Width', 'GOOGLEMAP_WIDTH', '350px', 'Enter your map width, you can enter the width as px or % (350px , 50%)', " . $configuration_group_id . ", 18, NOW(), NOW(), NULL, NULL),
      ('Map Height', 'GOOGLEMAP_HEIGHT', '350px', 'Enter your map height, you can enter the height as px or % (350px , 50%)', " . $configuration_group_id . ", 19, NOW(), NOW(), NULL, NULL),
      ('Marker Image', 'GOOGLEMAP_MARKER_IMAGE', 'images/marker/house.png', 'Enter an image name here so you can have a custom marker on your map<br /> Use a <strong>png</strong> file for your image.<br />Leave blank if you don\'t want a custom marker.)', " . $configuration_group_id . ", 20, NOW(), NOW(), NULL, NULL);"
    );
  }