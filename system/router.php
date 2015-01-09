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

// the checked route
$valid;
 
// the request string is exploded into requested nodes to support sub categories in the future
$request = explode('/',ltrim(htmlspecialchars($_SERVER['REQUEST_URI']),'/'));

// if the request is contained in our routes array in config, set route
foreach ($routes as $route => $meta) {
	if ($request[0] === $meta['slug']) {
		$valid = $routes[$route]; // if correct route is found, return the array containing its meta info
		break;
	} else {
		$valid = $routes[0]; // if no route is found, return the array for our error page
	}
}

$view = new View($valid, $request);

$view->render();
