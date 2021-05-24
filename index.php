<?php

require('./inc/config.php');
require('./inc/autoloader.php');

define('OC_HOSTNAME', $_SERVER['HTTP_HOST']);
define('OC_PATH', dirname($_SERVER['SCRIPT_NAME']) . '/');
define('OC_GET', substr($_SERVER['REQUEST_URI'], strlen(OC_PATH)));

$OC = new OC();