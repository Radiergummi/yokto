<? namespace System;
/**
 * picoFrame v0.2
 *
 * minimalist website framework designed for the most basic web apps and sites
 *
 * @package		picoFrame
 * @link		http://moritzfriedrich.com
 * @copyright	http://unlicense.org/
 */

 
 
/**
 * Hooks are a way to extend pico by implementing custom, string returning functions.
 */
class Hook {

	/**
	 * example hook.
	 *
	 * @return string
	 */
	public static function hello_world(){
		return 'Hello World!';
	}
}
