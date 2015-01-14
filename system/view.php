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
	 * replacement variables
	 *
	 * @var array
	 */
	public static $vars = array();

	/**
	 * prepares the html content and delivers it to the client.
	 * 
	 * @param boolean
	 */
	public static function render($route, $ajax = false) {
	
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') $ajax = true;

		$request = Config::routes('request');

		if ($route === 'error') {

			// append to log channel messages
			log('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

			// set correct headers
			header('Status: 404 Not Found');
			
			// set error template
			$template = 'http_404';
			
		} else {
			$template = (!empty($route['template']) ? $route['template'] : 'page');
		}

		$theme = self::get_theme();
		$vars = (!empty($route['variables']) ? array_merge(self::$vars, $route['variables']) : self::$vars);

		$vars['sitename'] = Config::env('sitename');
		$vars['title'] = ((!empty($request)) && (!empty($route)) ? $sitename . ' | ' . $route['name'] : $sitename);
		$vars['name'] = $route['name'];
		$vars['slug'] = $route['slug'];
		// start collecting the output
		ob_start();
		
			extract($vars);
			require SYS . 'theme_functions'. EXT;
		
			if (!$ajax) require TPLDIR . 'inc_header' . EXT;
			#require TPLDIR . $route['template'] . EXT;
			require TPLDIR . $template . EXT;
			
			if (!$ajax) require TPLDIR . 'inc_footer' . EXT;
		
		return ob_get_clean();
	}
	
	public static function get_theme(){
		if (is_readable($file = PATH . 'themes' . DS . Config::env('theme') . DS . 'theme.json')){
			return json_decode(file_get_contents($file),true);
		} else {
			Error::exception('Theme file not found at ' . $file);

			// append to log channel error
			log('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 2);
		}
	}
}
