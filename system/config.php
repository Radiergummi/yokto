<? namespace System;
/**
 * yokto
 *
 * minimalist website framework designed for the most basic web apps and sites
 *
 * @package		yokto
 * @link		http://moritzfriedrich.com
 * @copyright	http://unlicense.org/
 */

// enable or disable logging
define('LOG', false);

// template folder path
define('TPLDIR', PATH . 'themes' . DS . 'kappa' . DS);

// log folder path
define('LOGDIR', SYS . 'log' . DS);

// available routes for this site

$routes = Config::routes();

class Config {
	/**
	 * holds the configuration
	 * 
	 * @var array
	 */
	public static $data = array();
	
	public static function set($file, $key, $value){
			static::$data[$file][$key] = $value;
	}
	
	public static function get($file, $value){
		if(is_readable($path = SYS . 'config' . DS . $file . EXT)) {
			static::$data[$file] = require $path;
		}
		
		return (isset($value) ? self::$data[$file][$value] : self::$data[$file]);
	}
	
	/**
	 * Returns a value from the config array using the
	 * method call as the array key reference
	 *
	 * @example Config::app('url');
	 *
	 * @param string
	 * @param array
	 *
	 * @return string|array
	 */
	public static function __callStatic($method, $arguments = '') {
		$key = $method;
		
		$value = (is_array($arguments) && ($arguments > 1) ? array_shift($arguments) : (string) $arguments);

		return self::get($key, $value);
	}
	
}
