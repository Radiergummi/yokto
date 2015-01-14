<?
/**
 * yocto
 *
 * minimalist website framework designed for the most basic web apps and sites
 *
 * @package		yocto
 * @link		http://moritzfriedrich.com
 * @copyright	http://unlicense.org/
 */

// controls logging, available values: development, testing, production
define('ENV', 'development');
 
define('DS', DIRECTORY_SEPARATOR);
define('URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER['HTTP_HOST']);

define('PATH', dirname(__FILE__) . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require SYS . 'hooks' . EXT;
require SYS . 'config' . EXT;
require SYS . 'functions' . EXT;
require SYS . 'router' . EXT;
require SYS . 'parser' . EXT;
require SYS . 'view' . EXT;
require SYS . 'error' . EXT;
require SYS . 'init' . EXT;
