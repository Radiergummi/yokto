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
 
 
class View {
	
	/**
	 * the route to create a view for
	 *
	 * @var array
	 */
	public $route = array();
	
	/**
	 * the original request
	 *
	 * @var string
	 */
	public $request;
	
	/**
	 * replacement variables
	 *
	 * @var array
	 */
	public static $vars = array(
		'title' => SITENAME,
		'siteurl' => URL,
		'assetdir' => 'assets'
	);

	/**
	 * Constructor
	 *
	 * @param array
	 * @param string
	 */
	public function __construct($route) {
		$this->route = $route;
		$this->request = $route['request'];
		$this->vars = array_merge($this->vars, $route['variables']);
	}
	
	/**
	 * prepares the html content and delivers it to the client.
	 * 
	 * @param boolean
	 */
	public static function render($route, $ajax = false) {
		$request = $route['request'];
		$vars = array_merge(self::$vars, $route['variables']);
		if ($route['slug'] === 'error') {

			// append to log channel messages
			syslog('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

			// set correct headers
			header('Status: 404 Not Found');
		}

		// set title to sitename only for index, append site name to other pages
		$vars['title'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $route['name'] : SITENAME);
		$vars['name'] = $route['name'];
		$vars['slug'] = $route['slug'];

		ob_start();
		
		extract($vars);
		
		if (!$ajax) require TPLDIR . 'inc_header' . EXT;
		require TPLDIR . $route['template'] . EXT;
		if (!$ajax) require TPLDIR . 'inc_footer' . EXT;
		
		return ob_get_clean();
	}
}
