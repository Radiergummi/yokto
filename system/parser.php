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
 
 
class Parser {

	public static function markdown($input) {
		if (is_readable($file = SYS . 'thirdparty' . DS . Config::thirdparty('markdown-dir') . DS . Config::thirdparty('markdown-file') . EXT)) {
			require $file;
			
			return \Parsedown::instance()->text($input);

		} else {
			throw new \Exception('Thirdparty library "' . Config::thirdparty('markdown') . '" could not be located.');
		}
	}
	
	public static function json($input){
		if (is_readable($file = SYS . 'thirdparty' . DS . Config::thirdparty('json-dir') . DS . Config::thirdparty('json-file') . EXT)) {
			require $file;
			
			return \Parsedown::instance()->text($input);

		} else {
			throw new \Exception('Thirdparty library "' . Config::thirdparty('json') . '" could not be located.');
		}
	}
}
