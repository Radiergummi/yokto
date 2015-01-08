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

 // enable or disable logging
define('LOG', false);
 
// site name
define('SITENAME', 'Pico test instance');

// template folder path
define('TPLDIR', PATH . 'templates' . DS);

// log folder path
define('LOGDIR', SYS . 'log' . DS);

// available routes for this site.
// every route has its own array which is extendable (like the functionally unecessary comments-enabled)
$routes = array(
	array(
		'slug' => 'error',
		'name' => 'Error: Page not found',
		'template' => 'http_404',
		'comments-enabled' => false,
		'variables' => array(
			'{$hello_world}' => Hook::hello_world(),
			'{$foo}' => 'bar'
		)
	),
	array(
		'slug' => '',
		'name' => 'Start',
		'template' => 'page_start',
		'comments-enabled' => false,
		'variables' => array(
			'{$hello_world}' => Hook::hello_world(),
			'description' => 'foo bar stuff'
		)
	),
	array(
		'slug' => 'foo-bar',
		'name' => 'foo bar!',
		'template' => 'page',
		'comments-enabled' => true,
		'variables' => array(
			'{$hello_world}' => Hook::hello_world(),
			'{$foo}' => 'bar'
		)
	),
	array(
		'slug' => 'portfolio',
		'name' => 'Example Page',
		'template' => 'page_portfolio',
		'comments-enabled' => false,
		'variables' => array(
			'{$hello_world}' => Hook::hello_world(),
			'{$foo}' => 'bar'
		)
	)
);
