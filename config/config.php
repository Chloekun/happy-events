<?php
session_start();

define('ROOT_PATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH);

define('BASE_URL', 'http://localhost/happy-events/');
define('SITE_NAME', 'Happy Events');
define('ADMIN_EMAIL', 'mrsmkdbasa@gmail.com');
define('ADMIN_PHONE', '09195779787');

// Timezone settings
date_default_timezone_set('Asia/Manila');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>