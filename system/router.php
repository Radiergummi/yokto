<?php

/**
 * Router class.
 */
class Router
{
	/**
	 * route function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $input
	 * @return View
	 */
	public static function route($input) {

		/**
		 * Prepare the input to be usable:
		 * - Trim leading and trailing slash
		 * - Separate uri and query string
		 *
		 */
		list($uri, $parameters) = explode('?', ltrim(rtrim($input, '/'), '/'), 2);
		Config::set('app.uri', $uri);
		Config::set('app.parameters', $parameters);

		/**
		 * if the route is an error
		 *
		 */
		if ($uri == '404') {
			Config::set('routes.error.httpStatusCode', '404');
			return new View('error', Config::get('routes.error')); 
		}

		/**
		 * if the route is defined in the routes config
		 *
		 */
		if (array_key_exists($route = strtolower($uri), Config::get('routes'))) return new View($route, Config::get('routes.' . $route));
		
		/**
		 * if a file matches the route within the content folder
		 *
		 */
		if (is_file($file = PUB . $uri . EXT)) return new View($uri, []);
		
		/**
		 * if the URL was called without a resource identifier
		 *
		 */
		if ($uri == '' || $uri == '/') return new View('start', []);
		
		/**
		 * if no rule matches, show error page
		 *
		 */
			Config::set('routes.error.httpStatusCode', '404');
			return new \View('error', Config::get('routes.error'));
	}
}
