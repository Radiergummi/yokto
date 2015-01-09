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
	public $vars = array(
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
	public function __construct($route, $request) {
		$this->route = $route;
		$this->request = $request;
		$this->vars = array_merge($this->vars, $route['variables']);
	}
	
	/**
	 * prepares the html content without header and footer for easy inline inclusion
	 * 
	 * @param array
	 * @param string
	 */
	public function render_ajax($route, $request) {
		$route = $this->route;
		$request = $this->request;
		
		if ($route['slug'] === 'error') {

			// append to log channel messages
			syslog('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

			// set correct headers
			header('Status: 404 Not Found');

		}

			// set title to sitename only for index, append site name to other pages
			$this->vars['title'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $route['name'] : SITENAME);
			$this->vars['name'] = $route['name'];


		ob_start();
		
		extract($this->vars);
		
		require TPLDIR . 'inc_header' . EXT;
		require TPLDIR . $route['template'] . EXT;
		require TPLDIR . 'inc_footer' . EXT;
		
		return ob_get_contents();

	}
	
	/**
	 * prepares the html content and delivers it to the client.
	 * 
	 * @param array
	 * @param string
	 */
	function render() {
		$route = $this->route;
		$request = $this->request;

		if ($route['slug'] === 'error') {

			// append to log channel messages
			syslog('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

			// set correct headers
			header('Status: 404 Not Found');
			
		}

		// set title to sitename only for index, append site name to other pages
		$this->vars['title'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $route['name'] : SITENAME);
		$this->vars['name'] = $route['name'];

		ob_start();
		
		extract($this->vars);
		
		require TPLDIR . 'inc_header' . EXT;
		require TPLDIR . $route['template'] . EXT;
		require TPLDIR . 'inc_footer' . EXT;
		
		return ob_get_contents();
	}
}
