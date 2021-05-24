<?php

require('./inc/config.php');
require('./inc/autoloader.php');

define('OC_HOSTNAME', $_SERVER['HTTP_HOST']);
define('OC_PATH', dirname($_SERVER['SCRIPT_NAME']) . '/');
define('OC_GET', substr($_SERVER['REQUEST_URI'], strlen(OC_PATH)));

define('DB_HOST',			'127.0.0.1');
define('DB_USERNAME',		'root');
define('DB_PASSWORD',		'');
define('DB_DB_NAME',		'omnichat');
define('DB_TABLE_PREFIX',	'');

$OC = new OC();