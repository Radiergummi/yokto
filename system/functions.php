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
 * prepares the html content and delivers it to the client.
 * 
 * @param string $type the template name for this type of page.
 * @param mixed $id the unique identifier of the requested page.
 */
function render_view($route, $request) {
	
	// import template variables and error messages
	global $template_vars;


	if ($route === 'error') {

		// append to log channel messages
		syslog('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 1);

		// send correct headers
		header('Status: 404 Not Found');

		// include error 404-template
		$content = get_template('http_404');
	} else {
		// if we have a request string and valid route, set title to specific string
		$template_vars['{$title}'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $route['name'] : SITENAME);
		$template_vars['{$name}'] = $route['name'];
		$vars['{$custom_text}'] = Hook::hello_world();
		$content = get_template($route['template']);
	}

	$htmlheader = get_template('inc_header');
	$htmlfooter = get_template('inc_footer');
	
	$output = str_replace(array_keys($vars), array_values($vars), $htmlheader . $content . $htmlfooter);
	
	echo $output;
}

/**
 * checks if a template exists and tries to fetch its contents.
 * 
 * @param string $name specifies the name to look for in the file system.
 * @return the content of the template file or an appropriate error message.
 */
function get_template($name) {
	$template;
	$path = PATH . TDIR . $name . EXT;
	if (file_exists($path)) {
		$template = file_get_contents($path);
	} else {
		$error_message = 'The requested template could not be retrieved. Please check your routes array in the config file.';
		if (!LOG){
			$template = '<div id="error">' . $error_message . '</div>';
		} else {
			syslog($error_message,'3');
			$template = '<div id="error">Oops! There went something wrong. Please notify the owner of this site.</div>';
		}
	}
	return $template;
}
/**
 * appends a message to the log. 
 * 
 * @param string $message the message to log
 * @param int $channel [1,2,3] the channel to log to, defaults to 1 = messages.
 */
function syslog($message,$channel = 1) {
	$file = array('messages','debug','error');
	error_log(date('r',$time) . ': ' . $message . PHP_EOL, 3, SYS . $file[$channel] . '.log');
}
