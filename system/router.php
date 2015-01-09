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


class Router {
	
	/**
	 * the available routes
	 *
	 * @var array
	 */
	protected $routes;

	/**
	 * the verified route
	 *
	 * @var array
	 */
	protected $match;

	/**
	 * Constructor
	 *
	 * @param array
	 * @param string
	 */
	public function __construct($routes) {
		$this->routes = $routes;
	}

	/**
	 * match the request to an existing route
	 *
	 * @param array
	 * @param string
	 */
	public function match($request) {

		// the request string is exploded into requested nodes to support sub categories in the future
		$request = explode('/',ltrim(htmlspecialchars($_SERVER['REQUEST_URI']),'/'));

		$routes = $this->routes;

		// if the request is contained in our routes array in config, set route
		foreach ($routes as $route => $meta) {
			if ($request[0] === $meta['slug']) {

				// if correct route is found, return the array containing its meta info
				$routes[$route]['request'] = $request;
				$this->match = $routes[$route];

				break;

			} else {

				// if no route is found, return the array for our error page
				$this->match = $routes[0];
			}
		}
		
		return $this->match;
	}
}
