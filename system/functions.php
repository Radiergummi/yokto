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
function render_view($type, $id) {
	global $vars, $error;
	switch($type) {
		case 'index':
			$vars['{$custom_text}'] = Hook::hello_world();
			$content = get_template('page_start');
		break;

		case 'page':
			$content = get_template('page');
		break;

		case 'error':
			header('Status: 404 Not Found');
			$content = '<div id="error">' . $error['http_404'] . '</div>';
		break;
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
	global $error;
	$template;
	$path = PATH . TDIR . $name . EXT;
	if (file_exists($path)) {
		$template = file_get_contents($path);
	} else {
		$template = '<div id="error">' . $error['template_not_found'] . '</div>';
	}
	return $template;
}
