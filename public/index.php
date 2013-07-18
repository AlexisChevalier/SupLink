<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 04/02/13
 * File: index.php
 */
//Default session start
session_name("HeavenShortener");
session_start();

//Load Config
require_once('../config/' . 'config.php');

/**
 * Constantes du site
 */
define('SERVER_ROOT', Config::Get('server_root'));
define('SITE_ROOT', Config::Get('site_root'));
define('BEFORE_ARGUMENTS', Config::Get('before_real_url'));
define('DEFAULT_CONTROLLER', Config::Get('default_controller'));
define('MODE', Config::Get('mode'));

if (MODE == 'DEVEL') {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

/**
 * Include des Core Classes - Core Models
 */

require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Db.php');
require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Mail.php');
require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'View.php');
require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'InputLoader.php');
require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'FlashMessages.php');

//Composer
require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

//Twig
Twig_Autoloader::register();

//End Composer

/**
 * Appel du routeur
 */


require_once(SERVER_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Router.php');
