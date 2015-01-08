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

 
// the request string
$request = ltrim(htmlspecialchars($_SERVER['REQUEST_URI']),'/');

// if the request is contained in our routes array in config, set route
$route = (array_key_exists($request,$routes) ? $request : 'error');

render_view($routes[$route], $request);
