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

	if ($route['slug'] === 'error') {

		// append to log channel messages
		syslog('Error 404 - Page not found: "' . URL . DS . $request . '" requested from client ' . $_SERVER['REMOTE_ADDR'] . '.', 0);

		// set correct headers
		header('Status: 404 Not Found');

		// include error 404-template
		$content = get_template('http_404');
	} else {

		// set title to sitename only for index, append site name to other pages
		$template_vars['{$title}'] = ((!empty($request)) && (!empty($route)) ? SITENAME . ' | ' . $route['name'] : SITENAME);
		$template_vars['{$name}'] = $route['name'];
		$template_vars['{$custom_text}'] = Hook::hello_world();
		$content = get_template($route['template']);
	}

	$header = get_template('inc_header');
	$footer = get_template('inc_footer');
	
	$output = str_replace(array_keys($template_vars), array_values($template_vars), $header . $content . $footer);
	
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
	$path = PATH . TPLDIR . $name . EXT;
	if (file_exists($path)) {
		$template = file_get_contents($path);
	} else {
		$error_message = 'The requested template "' . $template . '" could not be retrieved. Please check your routes array in the config file.';
		if (!LOG){
			$template = '<div id="error">' . $error_message . '</div>';
		} else {
			syslog($error_message,'3');
			$template = '<div id="error">Oops! There went something wrong. Please notify the owner of this site.</div>';
		}
	}
	syslog('test',2);
	return $template;
}

/**
 * includes all
 * 
 * 
 */

/**
 * appends a message to the log. 
 * 
 * @param string $message the message to log
 * @param int $channel [1,2,3] the channel to log to, defaults to 1 = messages.
 */
function syslog($message,$channel = 1) {
	$file = array('messages','debug','error');
	error_log(date('r',$time) . ': ' . $message . PHP_EOL, 3, LOGDIR . $file[$channel] . '.log');
}

/**
 * stops the code whereever inserted, dumps given variable in readable form
 * ! neat formatting stolen from http://php.net/manual/de/function.debug-backtrace.php#111355
 * 
 * @param mixed $data the variable to dump
 */
 function debug($data = 'no data given') {
	$debug = array_reverse(debug_backtrace());
	$trace;
    foreach ($debug as $k => $v) { 
        array_walk($v['args'], function (&$item, $key) { 
            $item = var_export($item, true); 
        }); 

        $trace .= '<div><span>#' . $k . '    ' . $v['file'] . ' (<b>' . $v['line'] . '</b>):</span> ' . (isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "</div>\n"; 
    } 
	echo '<style>pre{margin:1rem;padding:1rem;background:#fafafa;border:1px solid #c0c0c0;border-radius:3px;box-shadow:0 1px 5px rgba(0,0,0,.2);white-space:pre-line}h1{display:block;margin:0 0 20px;padding:0 0 .5rem;border-bottom:1px solid #ccc}.info_self{float:right;padding:4px 10px;background:#BCFF95;border-radius:3px;border:1px solid #ccc;}.dump{margin:5px 0;padding:5px 1rem;background:#eee;border:1px solid #ccc;border-radius:3px;}.dump>h3{margin:5px 0}.trace{margin-top:1rem;padding-left:22px;line-height:calc(1rem + 4px);text-indent:-22px;word-wrap:break-word}.trace>div:last-of-type{color:#999}.trace>div:last-of-type>span{background:rgba(100,200,105,.44);color:#555}.trace span{padding:2px 6px;background:rgba(31,20,218,.22);border-radius:3px}</style>';
	echo '<pre><div class="info_self"> Halted at line ' . $debug[0][line] . '.</div><h1>Debug Info:</h1>';
	echo '';
	echo '<div class="dump"><h3>Dumped ' . gettype($data) . ':</h3>';
	print_r($data);
	echo '</div><div class="trace">';
	print_r($trace);
	echo '</div></pre>';
	exit();
 }
