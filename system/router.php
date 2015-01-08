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

// if we have a request string and valid route, set title to specific string
$template_vars['{$title}'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $routes[$route]['title'] : SITENAME);

// if route name is in available routes array, show content
if ($route != 'error') {
	$template_vars['{$name}'] = $routes[$route]['title'];
	render_view($routes[$route]);
}

// else, throw error 404
else {
	render_view('error', $request);
}
