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
	public function __construct($additional_routes = array()) {
		$routes = Config::routes();
		$this->routes = array_merge($additional_routes, $routes, $this->get_routes('content'));
	}

	/**
	 * match the request to an existing route
	 *
	 * @param array
	 * @param string
	 */
	public function match() {
		$request = rtrim(ltrim(htmlspecialchars($_SERVER['REQUEST_URI']),'/'),'/');
		$routes = $this->routes;

		// if the request is contained in our routes, set match
		foreach ($routes as $route => $meta) {
			if ($request === $meta['slug']) {

				// store request in config 
				Config::set('env','request',$request);
				
				$this->match = $routes[$route];

				break;

			} else {

				// if no route is found, return the array for our error page
				$this->match = $routes[0];
			}
		}

		if ($this->match === 'error' && Config::debug('loglevel') === 'development') {
		
			// if we've got an error and debugging enabled, log the routing error
			log('The request ' . $request . ' could not be matched.', 1);
		}		

		return $this->match;
	}

	/**
	 * get all files in content dir recursively
	 *
	 * @param array
	 * @param string
	 */
	public function get_routes($directory, $ext = '') {
	    $array_items = array();
	    if($handle = opendir($directory)){
	        while(false !== ($file = readdir($handle))){
	            if(preg_match("/^(^\.)/", $file) === 0){
	                if(is_dir($directory. "/" . $file)){
	                    $array_items = array_merge($array_items, $this->get_routes($directory. "/" . $file, $ext));
	                } else {
	                    $file = ltrim($directory . "/" . $file,'content/');
						$array_items[]['slug'] = preg_replace('/\\.[^.\\s]{2,3}$/', '', $file);
	                }
	            }
	        }
	        closedir($handle);
	    }
	    return $array_items;
	}
}
