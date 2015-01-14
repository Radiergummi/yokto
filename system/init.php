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


/**
 * Error handling
 */
set_exception_handler(array('System\Error', 'exception'));
set_error_handler(array('System\Error', 'native'));
register_shutdown_function(array('System\Error', 'shutdown'));


$route = (new Router($routes))->match();
$response = View::render($route);

echo $response;
