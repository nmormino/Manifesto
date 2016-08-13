<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

$GLOBALS['start_time'] = microtime(true);
require 'vendor/autoload.php';

Manifesto\Library\Log::set('Script Begin '.$GLOBALS['start_time']);

//set the folder where our index.php is located
define('BASE_PATH', __DIR__.'/');

//define the application path
define('APP_PATH', BASE_PATH.'app/');

//define HAS_SSL
define('HAS_SSL', (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off'?FALSE:TRUE));

//define URL Protocol
define('URL_PROTOCOL', (HAS_SSL?'https':'http'));

//define base directory
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_DIR', ( $baseDir == '/' ? '' : $baseDir ));

//define the base URL
define('BASE_URL', $_SERVER['HTTP_HOST'].BASE_DIR.'/');

//initialize Bootstrap Class
require BASE_PATH.'bootstrap.php';
(new Manifesto\Bootstrap)->initialize();