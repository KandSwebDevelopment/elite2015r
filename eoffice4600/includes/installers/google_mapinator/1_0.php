<?php
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1.0 16-03-2012' WHERE configuration_key = 'GOOGLEMAP_MODULE_VERSION' LIMIT 1;");