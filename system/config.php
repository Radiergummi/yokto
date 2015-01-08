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
LOG = true;
 
// site name
define('SITENAME', 'Website name');

// template folder path
define('TDIR', 'templates' . DS);

// available routes for this site.
// every route has its own array which is extendable (like the functionally unecessary comments-enabled)
$routes = array(
	'' => array(
		'name' => 'Start',
		'template' => 'page_start',
		'comments-enabled' => false
	),
	'foo-bar' => array(
		'name' => 'foo bar!',
		'template' => 'page',
		'comments-enabled' => true
	),
	'portfolio' => array(
		'name' => 'Example Page',
		'template' => 'page_portfolio',
		'comments-enabled' => false
	)
);

// available error messages
$errors = array(
	'http_404' => 'Error: The requested page could not be found.',
	'template_not_found' => 'Error: The requested template could not be found.'
);

// available content variables to replace
$template_vars = array(
	'{$title}' => SITENAME,
	'{$siteurl}' => URL,
	'{$assetdir}' => 'assets',
	'{$name}' => '',
	'{$file}' => '',
	'{$description}' => '',
);
